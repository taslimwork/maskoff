<?php

namespace App\Providers\CustomProvider;

use App\Interfaces\GroupManageInterface;
use App\Repositories\GroupManageRepository;
use Illuminate\Support\ServiceProvider;

class GroupManageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(GroupManageInterface::class, GroupManageRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
