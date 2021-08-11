<?php

namespace SilverStripe\Workflow\Trello;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_Base;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldPageCount;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\LiteralField;
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
        $board = $this->owner->Board();

        $content = sprintf(
            '<p class="message good">You are currently using the board ' .
            '"<a href="%s" target="_blank">%s</a>"</p>',
            $board->TrelloURL,
            $board->Title
        );

        $fields->addFieldToTab('Root.Workflow', LiteralField::create(
            'BoardDetails',
            $content
        ), 'Steps');

        $field = DropdownField::create(
            'BoardID',
            'Board',
            Board::get(),
        )->setEmptyString('Select a board to sync with');
        $fields->addFieldToTab('Root.Workflow', $field, 'Steps');

        // We want to prevent users from adding/deleting the steps now as they
        // are managed in Trello
        /** @var GridField $steps */
        $steps = $fields->fieldByName('Root.Workflow.Steps');
        $config = GridFieldConfig_Base::create()
            ->removeComponentsByType(GridFieldFilterHeader::class)
            ->removeComponentsByType(GridFieldPaginator::class)
            ->removeComponentsByType(GridFieldPageCount::class)
            ->addComponent(new GridFieldEditButton())
            ->addComponent(new GridFieldDetailForm(null, false, false));
        $steps->setConfig($config);
    }
}
