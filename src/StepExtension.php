<?php

namespace SilverStripe\Workflow\Trello;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Workflow\Step;

/**
 * @property Step|$this owner
 * @property string TrelloID
 * @property int BoardID
 * @method Board Board
 */
class StepExtension extends DataExtension
{
    private static array $db = [
        'TrelloID' => 'Varchar(255)',
    ];

    private static array $has_one = [
        'Board' => Board::class,
    ];

    /**
     * @param string $id
     * @return Step|StepExtension
     */
    public static function findOrCreate(string $id): Step
    {
        $step = Step::get()->find('TrelloID', $id);

        if (!$step) {
            $step = Step::create();
        }

        return $step;
    }

    public function updateCMSFields(FieldList $fields): void
    {
        $fields->removeByName([
            'BoardID',
            'TrelloID',
            'Title',
        ]);

        $fields->addFieldToTab(
            'Root.Main',
            ReadonlyField::create('Title'),
            'Icon'
        );
    }

    public function canDelete($member): bool
    {
        return false;
    }
}
