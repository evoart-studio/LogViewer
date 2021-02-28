<?php

namespace Orchid\LogViewer;

use Orchid\LogViewer\MenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\Menu;

class LogViewServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     */
    public function boot(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;

        $this->loadRoutesFrom(realpath(__DIR__ . '/../routes/route.php'));
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'evoart/logs');

        View::composer('platform::systems', MenuComposer::class);

        $this->app->booted(function () {
            $this->dashboard->registerPermissions($this->registerPermissions());
        });

    }
    protected function registerPermissions(): ItemPermission
    {
        return ItemPermission::group(__('Systems'))
            ->addPermission('platform.systems.logs', __('Log View'));
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            \Arcanedev\LogViewer\LogViewerServiceProvider::class,
        ];
    }
}
