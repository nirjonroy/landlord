<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('users', 'is_admin')) {
            $admins = DB::table('users')
                ->where('is_admin', true)
                ->get(['name', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at']);

            foreach ($admins as $admin) {
                DB::table('admins')->updateOrInsert(
                    ['email' => $admin->email],
                    [
                        'name' => $admin->name,
                        'email_verified_at' => $admin->email_verified_at,
                        'password' => $admin->password,
                        'remember_token' => $admin->remember_token,
                        'created_at' => $admin->created_at ?? now(),
                        'updated_at' => $admin->updated_at ?? now(),
                    ]
                );
            }

            DB::table('users')->where('is_admin', true)->delete();

            if (DB::getDriverName() !== 'sqlite') {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn('is_admin');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email_verified_at');
        });
    }
};
