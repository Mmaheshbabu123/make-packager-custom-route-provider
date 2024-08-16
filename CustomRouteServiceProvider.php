<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

abstract class CustomRouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace;
    protected $moduleName;
    /**
     * Middleware to apply to web routes.
     *
     * @var array
     */
    protected $webMiddleware = ['web'];

    /**
     * Middleware to apply to API routes.
     *
     * @var array
     */
    protected $apiMiddleware = ['api'];

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern-based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->routes(function () {
            $this->map();
        });
    }

    /**
     * Define the routes for the package.
     *
     * @return void
     */
    protected function map()
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Define the "web" routes for the package.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        foreach ($this->getWebRoutesPaths() as $path) {
            Route::middleware($this->webMiddleware)
                ->namespace($this->moduleNamespace)
                ->group($path);
        }
    }

    /**
     * Define the "api" routes for the package.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        foreach ($this->getApiRoutesPaths() as $path) {
          $prefix = $this->getPrefixFromPath($path);
            Route::prefix($prefix)
                ->middleware($this->apiMiddleware)
                ->namespace($this->moduleNamespace)
                ->group($path);
        }
    }

    /**
     * Determine the prefix based on the route file name.
     *
     * @param string $path
     * @return string
     */
    protected function getPrefixFromPath($path)
    {
        $filename = basename($path, '.php'); // Get the filename without extension

        // Determine prefix based on filename
        if ($filename === 'api') {
            return 'api';
        }

        // Default to filename if it's not 'api'
        return $filename;
    }

    /**
     * Get the paths to the web routes files.
     *
     * @return array
     */
    protected function getWebRoutesPaths()
    {
        return [
            module_path($this->moduleName, '/src/routes/web.php'),
            //module_path('Roles', '/src/routes/extra_web.php'), // Example of an additional route file
        ];
    }

    /**
     * Get the paths to the api routes files.
     *
     * @return array
     */
    protected function getApiRoutesPaths()
    {
        return [
            module_path($this->moduleName, '/src/routes/V1.php'),
          
        ];
    }

    // /**
    //  * Get the paths to the web routes files.
    //  *
    //  * @return array
    //  */
    // abstract protected function getWebRoutesPaths();
    //
    // /**
    //  * Get the paths to the api routes files.
    //  *
    //  * @return array
    //  */
    // abstract protected function getApiRoutesPaths();
}
