<?php

namespace Onixcat\Bundle\ViatecParserBundle\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

/**
 * Class AdminMenuListener
 *
 * Add new menu item for parser
 */
class AdminMenuListener
{

    /**
     * Add parser menu item
     *
     * @param MenuBuilderEvent $event
     */
    public function addItems(MenuBuilderEvent $event)
    {
        $menu = $event->getMenu();
        $subMenu = $menu->addChild('parser')->setLabel('onixcat.ui.parser');
        $subMenu->addChild('parser_viatec', ['route' => 'ult_admin_viatec_parser_command'])->setLabel('onixcat.ui.viatec_parser');
        $subMenu->addChild('parser_viatec_settings', ['route' => 'ult_admin_viatec_parser_edit_settings'])->setLabel('onixcat.ui.viatec_parser_settings');
        $subMenu->addChild('parser_viatec_files', ['route' => 'ult_admin_viatec_parser_files_list'])->setLabel('onixcat.ui.viatec_parser_files');
    }
}
