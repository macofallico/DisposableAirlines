<?php

namespace Modules\DisposableAirlines\Providers;

use App\Services\ModuleService;
use Illuminate\Support\ServiceProvider;
use Route;

class AirlinesServiceProvider extends ServiceProvider
{
  protected $moduleSvc;

  /** Boot the application events */
  public function boot()
  {
    $this->moduleSvc = app(ModuleService::class);

    $this->registerRoutes();
    $this->registerTranslations();
    $this->registerConfig();
    $this->registerViews();
    $this->registerLinks();

    $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
  }

  /** Register The Service Provider **/
  public function register() { }

  /** Register Module Links **/
  public function registerLinks()
  {
    /** If You Want To Place The Links Automatically Then Enable below 3 lines **/
    /** DisposableTheme is pre-configure to show below links when you install this module  **/

    // $this->moduleSvc->addFrontendLink('Airlines', '/dairlines', 'fas fa-calendar-alt', $logged_in=true);
    // $this->moduleSvc->addFrontendLink('Fleet', '/dfleet', 'fas fa-plane-departure', $logged_in=true);
    // $this->moduleSvc->addFrontendLink('All PIREPs', '/dpireps', 'fas fa-upload', $logged_in=true);

    /** Do Not Remove or Disable Admin Link **/
    $this->moduleSvc->addAdminLink('Disposable Airlines', '/admin/disposableairlines');
  }

  /** Register Routes **/
  protected function registerRoutes()
  {
    /** Frontend Routes **/
    Route::group([
      'as'     => 'DisposableAirlines.',
      'prefix' => '',
      'middleware' => ['web'],
      'namespace'  => 'Modules\DisposableAirlines\Http\Controllers',
    ], function () {
      Route::group([
        'middleware' => ['auth'],
      ], function () {
        // Airline Contoller Routes
        Route::get('dairlines', 'AirlineController@aindex')->name('aindex');
        Route::get('dairlines/{icao}', 'AirlineController@ashow')->name('ashow');
        // Fleet Controller Routes
        Route::get('dfleet', 'FleetController@fleet')->name('dfleet');
        Route::get('dfleet/{type}', 'FleetController@subfleet')->name('dsubfleet');
        Route::get('daircraft/{reg}', 'FleetController@aircraft')->name('daircraft');
        // All Pireps Controller Routes
        Route::get('dpireps', 'PirepsController@allpireps')->name('dpireps');
      });
    });

    /** Admin Routes **/
    Route::group([
      'as'     => 'DisposableAirlines.',
      'prefix' => 'admin',
      'middleware' => ['web', 'role:admin'],
      'namespace'  => 'Modules\DisposableAirlines\Http\Controllers',
    ], function () {
      Route::group([],
      function () {
        Route::get('disposableairlines', 'AdminController@admin')->name('dairlinesadmin');
      });
    });
  }

  protected function registerConfig()
  {
    $this->publishes([ __DIR__.'/../Config/config.php' => config_path('DisposableAirlines.php'),], 'config');
    $this->mergeConfigFrom( __DIR__.'/../Config/config.php', 'DisposableAirlines');
  }

  public function registerTranslations()
  {
    $langPath = resource_path('lang/modules/DisposableAirlines');

    if (is_dir($langPath)) {
      $this->loadTranslationsFrom($langPath, 'DisposableAirlines');
    } else {
      $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'DisposableAirlines');
    }
  }

  public function registerViews()
  {
    $viewPath = resource_path('views/modules/DisposableAirlines');
    $sourcePath = __DIR__.'/../Resources/views';

    $this->publishes([$sourcePath => $viewPath,], 'views');

    $this->loadViewsFrom(array_merge(array_map(function ($path) {
      return $path . '/modules/DisposableAirlines';
    }, \Config::get('view.paths')), [$sourcePath]), 'DisposableAirlines');
  }

  public function provides(): array { return []; }
}
