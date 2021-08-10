<?php

namespace SilverStripe\Workflow\Trello;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Workflow\StepRelation;
use SilverStripe\Workflow\Trello\API\Cards;

/**
 * @property StepRelation|$this owner
 * @property string TrelloID
 * @property string TrelloURL
 */
class StepRelationExtension extends DataExtension
{
    private static array $db = [
        'TrelloID' => 'Varchar(255)',
        'TrelloURL' => 'Varchar(255)',
    ];

    public function onBeforeDelete(): void
    {
        Cards::delete($this->owner->TrelloID);
    }
}
