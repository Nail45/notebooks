<?php

namespace App\Providers;

use App\Services\Filters\FilterApplier;
use App\Services\Filters\FilterDefinitions;
use App\Services\Filters\NotebookFilterService;
use App\Services\Pagination\NotebookPaginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->singleton(FilterDefinitions::class);
    $this->app->singleton(FilterApplier::class);
    $this->app->singleton(NotebookFilterService::class);

    $this->app->bind(NotebookPaginator::class, function ($app) {
      return new NotebookPaginator(FilterDefinitions::PER_PAGE);
    });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    //
  }
}
