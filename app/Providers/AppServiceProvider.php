<?php

namespace App\Providers;

use App\Models\User;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('viewApiDocs', function (User $user) {
            return $user->email == config('app.admin_email');
        });

        Scramble::configure()->withDocumentTransformers(function (OpenApi $document) {
            $document->info->title = 'Kairo API Documentation';
            $document->info->description = 'Generated API documentation for Kairo Habit Tracker app backend.';
            $document->secure(
                SecurityScheme::http('bearer')
            );
        });
    }
}
