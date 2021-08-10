<?php

namespace SilverStripe\Workflow\Trello;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Extension;
use SilverStripe\View\ArrayData;
use SilverStripe\Workflow\StepRelation;
use SilverStripe\Workflow\WorkflowWidget;

/**
 * @property WorkflowWidget|$this owner
 */
class WorkflowWidgetExtension extends Extension
{
    public function updateProps(ArrayData $props): void
    {
        $item = $this->owner->getItem();
        $idField = $item instanceof SiteTree
            ? 'PageID'
            : 'ElementID';
        /** @var StepRelation|StepRelationExtension $selectedStep */
        $selectedStep = StepRelation::get()->find($idField, $item->ID);

        if (!$selectedStep) {
            return;
        }

        $props->setField('initialTrelloUrl', $selectedStep->TrelloURL);
    }
}
