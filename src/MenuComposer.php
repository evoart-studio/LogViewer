<?php

namespace Orchid\LogViewer;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\Menu;

class MenuComposer
{
    /**
     * MenuComposer constructor.
     *
     * @param Dashboard $dashboard
     */
    public function __construct(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    /**
     *
     */
    public function compose()
    {
        $this->dashboard->menu
            ->add(Menu::SYSTEMS,
                ItemMenu::label('Log View')
                    ->icon('bug')
                    ->permission('platform.systems.logs')
                    ->sort(510)
                    ->Route('dashboard.systems.logs.index')
            )
            ->add('settings',
                ItemMenu::Label(__('Просмотр логов'))
                    ->Icon('bug')
                    ->title('Просмотр всех логов, удаление, скачивание')
                    ->Route('dashboard.systems.logs.index')
                    ->Permission('platform.systems.logs')
                    ->Sort(9)
            );
    }
}
