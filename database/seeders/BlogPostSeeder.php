<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'category_slug' => 'property-tips',
                'title' => '5 Things Dhaka Renters Check Before Calling a Landlord',
                'slug' => '5-things-dhaka-renters-check-before-calling-a-landlord',
                'author_name' => 'Land Site Team',
                'excerpt' => 'Most serious tenants in Dhaka make a quick trust check before they call. These details help your listing feel more real and more worth the time.',
                'content' => "Many landlords lose good tenant leads before the first phone call because the listing does not answer the basic questions renters care about.\n\nA renter in Dhanmondi, Mirpur, or Bashundhara usually scans location clarity, rent amount, building condition, and whether the owner profile feels reliable. When those points are missing, even a good property can look risky.\n\nStart with the neighborhood, road number, and landmark. Then make the price, floor, bedroom count, and move-in readiness obvious. Clear building photos and a short owner introduction also help people decide faster.\n\nIf you are posting through a dashboard, keep the listing updated. Old rent amounts or missing contact details create doubt. Fresh information gets more serious responses than decorative copy ever will.",
                'quote' => 'Bangladesh renters usually do not want more marketing language. They want faster certainty about the property and the owner.',
                'featured_image_path' => 'frontend-assets/img/post_img_1.jpg',
                'featured_image_source' => 'asset',
                'meta_description' => 'Tips for Bangladesh landlords to make rent listings more trustworthy and easier for Dhaka tenants to act on.',
                'tags' => ['dhaka', 'rent', 'landlord tips', 'tenant trust'],
                'comments' => [
                    [
                        'name' => 'Sharmeen Akter',
                        'date_label' => 'April 2, 2026',
                        'body' => 'This is accurate. I usually skip listings that do not mention the exact area and building condition.',
                        'avatar_path' => 'frontend-assets/img/avatar_4.jpg',
                        'avatar_source' => 'asset',
                    ],
                    [
                        'name' => 'Mahin Hasan',
                        'date_label' => 'April 3, 2026',
                        'body' => 'The point about stale rent amounts is real. It wastes everyone’s time when the number is no longer valid.',
                        'avatar_path' => 'frontend-assets/img/avatar_5.jpg',
                        'avatar_source' => 'asset',
                    ],
                ],
                'published_at' => now()->subDays(7),
                'is_published' => true,
                'show_on_home' => true,
            ],
            [
                'category_slug' => 'landlord-management',
                'title' => 'How Bangladesh Landlords Can Prepare a Flat for Faster Approval',
                'slug' => 'how-bangladesh-landlords-can-prepare-a-flat-for-faster-approval',
                'author_name' => 'Land Site Editorial',
                'excerpt' => 'Approval delays usually come from missing information, weak photos, or unclear ownership signals. This checklist keeps the submission cleaner from the start.',
                'content' => "When a property enters review, the fastest approvals usually belong to owners who submit complete details the first time.\n\nBefore uploading a flat, confirm that the title, purpose, address, and pricing are consistent everywhere. Add a clear cover image, at least a few useful gallery photos, and make sure your profile already contains phone, address, and verification documents.\n\nFor sale listings, explain whether the asking price is negotiable and whether important documents are ready for buyer review. For rent listings, clarify service charge, advance amount, and family or bachelor preference if applicable.\n\nA clean submission is easier to review and easier to trust. That is why preparation often matters more than quantity.",
                'quote' => 'Approval becomes easier when the listing, the owner profile, and the uploaded evidence all tell the same story.',
                'featured_image_path' => 'frontend-assets/img/post_img_2.jpg',
                'featured_image_source' => 'asset',
                'meta_description' => 'A practical Bangladesh landlord checklist for faster property approval and clearer listing submissions.',
                'tags' => ['approval', 'landlord management', 'verification', 'flat listing'],
                'comments' => [
                    [
                        'name' => 'Sajib Ahmed',
                        'date_label' => 'April 1, 2026',
                        'body' => 'I had my first listing delayed because my profile was incomplete. This checklist would have saved time.',
                        'avatar_path' => 'frontend-assets/img/avatar_6.jpg',
                        'avatar_source' => 'asset',
                    ],
                ],
                'published_at' => now()->subDays(5),
                'is_published' => true,
                'show_on_home' => true,
            ],
            [
                'category_slug' => 'documentation',
                'title' => 'NID, Passport, or Ownership Proof: What Makes a Listing Feel Safer?',
                'slug' => 'nid-passport-or-ownership-proof-what-makes-a-listing-feel-safer',
                'author_name' => 'Land Site Team',
                'excerpt' => 'Not every document creates the same confidence. Here is how identity and ownership proof affect trust during property review.',
                'content' => "For a property platform, trust is not only about the building. It is also about whether the person posting looks connected to the property in a believable way.\n\nA profile photo and NID details help with identity confidence, while ownership proof helps connect the account to the property itself. Passport information can support identity, but ownership-related evidence usually matters more for sale or landlord-led listings.\n\nThis does not mean every document needs to be public. It means the review side must receive enough evidence to reduce the risk of fake or misleading submissions.\n\nIf you want your listing to feel safer, complete both identity and ownership sections where possible. The strongest profiles usually combine both.",
                'quote' => 'Identity shows who is posting. Ownership proof helps explain why that person should be trusted to post the property.',
                'featured_image_path' => 'frontend-assets/img/post_img_3.jpg',
                'featured_image_source' => 'asset',
                'meta_description' => 'A Bangladesh-focused explanation of how NID, passport, and ownership proof improve listing confidence.',
                'tags' => ['nid', 'passport', 'ownership proof', 'verification'],
                'comments' => [
                    [
                        'name' => 'Nusrat Jahan',
                        'date_label' => 'March 31, 2026',
                        'body' => 'This helps. Many owners do not understand why identity alone is not enough for a sale listing.',
                        'avatar_path' => 'frontend-assets/img/avatar_1.jpg',
                        'avatar_source' => 'asset',
                    ],
                ],
                'published_at' => now()->subDays(3),
                'is_published' => true,
                'show_on_home' => true,
            ],
            [
                'category_slug' => 'market-updates',
                'title' => 'Why Verified Area Names Matter in Dhaka, Chattogram, and Sylhet Searches',
                'slug' => 'why-verified-area-names-matter-in-dhaka-chattogram-and-sylhet-searches',
                'author_name' => 'Research Desk',
                'excerpt' => 'Area confusion damages search quality. Verified location naming makes matching faster for both buyers and tenants.',
                'content' => "Search quality drops quickly when the same place is described in different ways across listings.\n\nIn Bangladesh, users often search by familiar local names, block numbers, road numbers, or nearby landmarks. If those are inconsistent, renters and buyers miss relevant results even when the property is a strong match.\n\nThat is why standardized area naming matters. Dhanmondi, Uttara Sector 7, Agrabad, or Zindabazar should not be entered in five conflicting formats across the platform.\n\nBetter location consistency improves filtering, user confidence, and admin review. It also makes analytics more useful later because the data stays comparable.",
                'quote' => 'Location clarity is one of the cheapest ways to make a property listing more discoverable.',
                'featured_image_path' => 'frontend-assets/img/post_img_7.jpg',
                'featured_image_source' => 'asset',
                'meta_description' => 'Why consistent area naming improves Bangladesh property search results across major cities.',
                'tags' => ['dhaka', 'chattogram', 'sylhet', 'property search'],
                'comments' => [],
                'published_at' => now()->subDays(2),
                'is_published' => true,
                'show_on_home' => false,
            ],
            [
                'category_slug' => 'home-styling',
                'title' => 'Simple Photo Angles That Make Apartments Look More Honest and More Attractive',
                'slug' => 'simple-photo-angles-that-make-apartments-look-more-honest-and-more-attractive',
                'author_name' => 'Land Site Creative Desk',
                'excerpt' => 'Better listing photos are not always about expensive cameras. Honest framing and natural light do more work than heavy editing.',
                'content' => "Property photos fail when they hide the layout or exaggerate the size.\n\nA better approach is straightforward. Use daylight where possible, take one full shot from the doorway, one shot from the opposite corner, and one close frame for the strongest usable feature such as the balcony view or kitchen storage.\n\nFor exterior or elevation photos, include enough of the building to show the actual context. Avoid angles that crop out obvious condition issues if you expect serious buyers or tenants. Overly dramatic images create disappointment later.\n\nClear photos improve both click-through and trust. In property listings, honest presentation is part of the product.",
                'quote' => 'The best property photo is usually the one that helps the viewer understand the space fastest.',
                'featured_image_path' => 'frontend-assets/img/post_img_8.jpg',
                'featured_image_source' => 'asset',
                'meta_description' => 'Simple apartment photo ideas for Bangladesh property listings that need more trust and better first impressions.',
                'tags' => ['property photos', 'apartment', 'listing quality', 'home styling'],
                'comments' => [],
                'published_at' => now()->subDay(),
                'is_published' => true,
                'show_on_home' => false,
            ],
        ];

        foreach ($posts as $post) {
            $category = BlogCategory::query()->where('slug', $post['category_slug'])->first();

            BlogPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'blog_category_id' => $category?->id,
                    'title' => $post['title'],
                    'author_name' => $post['author_name'],
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'quote' => $post['quote'],
                    'featured_image_path' => $post['featured_image_path'],
                    'featured_image_source' => $post['featured_image_source'],
                    'meta_description' => $post['meta_description'],
                    'tags' => $post['tags'],
                    'comments' => $post['comments'],
                    'published_at' => $post['published_at'],
                    'is_published' => $post['is_published'],
                    'show_on_home' => $post['show_on_home'],
                ]
            );
        }
    }
}
