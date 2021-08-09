<?php

namespace SilverStripe\Workflow\Trello;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * @property SiteConfig|$this owner
 * @property int BoardID
 * @method Board Board
 */
class SiteConfigTrelloExtension extends DataExtension
{
    private static array $has_one = [
        'Board' => Board::class,
    ];

    public function updateCMSFields(FieldList $fields): void
    {
        $field = DropdownField::create(
            'BoardID',
            'Board',
            Board::get(),
        )->setEmptyString('Select a board to sync with');
        $fields->addFieldToTab('Root.Workflow', $field);
    }
}
