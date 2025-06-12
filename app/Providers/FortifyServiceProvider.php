<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $username = (string) $request->username;
            return Limit::perMinute(5)->by($username.$request->ip());
        });

        // Redirect to posts after login
        Fortify::authenticateUsing(function (Request $request) {
            $user = config('fortify.model')::where('username', $request->username)->first();

            if ($user && app('hash')->check($request->password, $user->password)) {
                return $user;
            }
        });

        // Redirect to posts after registration
        Fortify::registerView(function () {
            return inertia('auth/register');
        });

        Fortify::loginView(function () {
            return inertia('auth/login');
        });
    }
} 