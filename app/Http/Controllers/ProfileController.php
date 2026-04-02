<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Support\ListingAnalytics;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProfileController extends Controller
{
    private ListingAnalytics $listingAnalytics;

    public function __construct(ListingAnalytics $listingAnalytics)
    {
        $this->listingAnalytics = $listingAnalytics;
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $activeTab = $this->activeTab($request->query('tab'));

        return view('profile.edit', [
            'user' => $user,
            'activeTab' => $activeTab,
            'profileCompletionPercent' => $this->profileCompletionPercent($user),
            'profileFileUrls' => [
                'profile_photo' => $this->profileFileUrl($user, 'profile-photo'),
                'nid_front_image' => $this->profileFileUrl($user, 'nid-front'),
                'nid_back_image' => $this->profileFileUrl($user, 'nid-back'),
                'passport_image' => $this->profileFileUrl($user, 'passport'),
                'ownership_proof' => $this->profileFileUrl($user, 'ownership-proof'),
            ],
            'homeElevationFileUrls' => $this->homeElevationFileUrls($user),
            'verificationSummary' => [
                'identity_ready' => (bool) (($user->nid_front_image_path && $user->nid_back_image_path) || $user->passport_image_path),
                'ownership_ready' => (bool) $user->ownership_proof_path,
                'gallery_count' => count($user->home_elevation_image_paths ?? []),
            ],
            'propertyAnalytics' => $this->listingAnalytics->analyticsForUser($user),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->safe()->except([
            'profile_photo',
            'nid_front_image',
            'nid_back_image',
            'passport_image',
            'ownership_proof',
            'home_elevation_images',
            'remove_profile_photo',
            'remove_nid_front_image',
            'remove_nid_back_image',
            'remove_passport_image',
            'remove_ownership_proof',
            'reset_home_elevation_images',
        ]);

        if (empty($validated['country'])) {
            $validated['country'] = 'Bangladesh';
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $this->syncUploadedFile($request, $user, 'profile_photo', 'profile_photo_path', 'profile-photo', 'remove_profile_photo');
        $this->syncUploadedFile($request, $user, 'nid_front_image', 'nid_front_image_path', 'nid-front', 'remove_nid_front_image');
        $this->syncUploadedFile($request, $user, 'nid_back_image', 'nid_back_image_path', 'nid-back', 'remove_nid_back_image');
        $this->syncUploadedFile($request, $user, 'passport_image', 'passport_image_path', 'passport', 'remove_passport_image');
        $this->syncUploadedFile($request, $user, 'ownership_proof', 'ownership_proof_path', 'ownership-proof', 'remove_ownership_proof');

        if ($request->boolean('reset_home_elevation_images') || $request->hasFile('home_elevation_images')) {
            $this->deleteFiles($user->home_elevation_image_paths ?? []);
            $user->home_elevation_image_paths = null;
        }

        if ($request->hasFile('home_elevation_images')) {
            $paths = [];

            foreach ($request->file('home_elevation_images') as $image) {
                $paths[] = $image->store('users/'.$user->id.'/home-elevations', 'public');
            }

            $user->home_elevation_image_paths = $paths;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Stream a protected profile file for the authenticated user.
     */
    public function file(Request $request, string $type, ?int $index = null): BinaryFileResponse
    {
        $user = $request->user();
        $path = match ($type) {
            'profile-photo' => $user->profile_photo_path,
            'nid-front' => $user->nid_front_image_path,
            'nid-back' => $user->nid_back_image_path,
            'passport' => $user->passport_image_path,
            'ownership-proof' => $user->ownership_proof_path,
            'home-elevation' => ($user->home_elevation_image_paths ?? [])[$index ?? -1] ?? null,
            default => null,
        };

        abort_unless($path && Storage::disk('public')->exists($path), 404);

        return response()->file(Storage::disk('public')->path($path));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        $this->deleteUserFiles($user);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function syncUploadedFile(Request $request, User $user, string $input, string $attribute, string $folder, string $removeFlag): void
    {
        if ($request->boolean($removeFlag) && $user->{$attribute}) {
            $this->deleteFile($user->{$attribute});
            $user->{$attribute} = null;
        }

        if (! $request->hasFile($input)) {
            return;
        }

        if ($user->{$attribute}) {
            $this->deleteFile($user->{$attribute});
        }

        $user->{$attribute} = $request->file($input)->store('users/'.$user->id.'/'.$folder, 'public');
    }

    private function profileCompletionPercent(User $user): int
    {
        $checks = [
            $user->name,
            $user->email,
            $user->phone,
            $user->present_address,
            $user->district,
            $user->division,
            $user->profile_photo_path,
            $user->nid_number,
            $user->nid_front_image_path,
            $user->nid_back_image_path,
            $user->ownership_proof_path,
            count($user->home_elevation_image_paths ?? []) > 0,
        ];

        $completed = collect($checks)->filter(fn ($value) => ! empty($value))->count();

        return (int) round(($completed / count($checks)) * 100);
    }

    private function profileFileUrl(User $user, string $type): ?string
    {
        $attribute = match ($type) {
            'profile-photo' => $user->profile_photo_path,
            'nid-front' => $user->nid_front_image_path,
            'nid-back' => $user->nid_back_image_path,
            'passport' => $user->passport_image_path,
            'ownership-proof' => $user->ownership_proof_path,
            default => null,
        };

        if (! $attribute || ! Storage::disk('public')->exists($attribute)) {
            return null;
        }

        return route('profile.files.show', ['type' => $type, 'v' => optional($user->updated_at)->timestamp]);
    }

    private function homeElevationFileUrls(User $user): array
    {
        return collect($user->home_elevation_image_paths ?? [])
            ->filter(fn ($path) => $path && Storage::disk('public')->exists($path))
            ->values()
            ->map(fn ($path, $index) => route('profile.files.show', [
                'type' => 'home-elevation',
                'index' => $index,
                'v' => optional($user->updated_at)->timestamp,
            ]))
            ->all();
    }

    private function deleteUserFiles(User $user): void
    {
        $this->deleteFiles(array_filter([
            $user->profile_photo_path,
            $user->nid_front_image_path,
            $user->nid_back_image_path,
            $user->passport_image_path,
            $user->ownership_proof_path,
            ...($user->home_elevation_image_paths ?? []),
        ]));
    }

    private function deleteFiles(array $paths): void
    {
        foreach ($paths as $path) {
            $this->deleteFile($path);
        }
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function activeTab(?string $tab): string
    {
        $allowedTabs = [
            'dashboard',
            'my_property',
            'add_property',
            'profile',
            'verification',
            'home_info',
            'password_update',
            'account_security',
        ];

        return in_array($tab, $allowedTabs, true) ? $tab : 'profile';
    }
}
