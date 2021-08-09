<?php

namespace SilverStripe\Workflow\Trello;

use SilverStripe\Admin\ModelAdmin;

class TrelloAdmin extends ModelAdmin
{
    private static array $managed_models = [
        Board::class,
    ];

    private static string $menu_title = 'Trello';

    private static string $url_segment = 'trello';

    private static string $menu_icon_class = 'font-icon-list';
}
