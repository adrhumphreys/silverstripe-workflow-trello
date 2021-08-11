<?php

namespace SilverStripe\Workflow\Trello;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
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

        $links = self::createTrelloLinks($selectedStep->TrelloURL);

        $props->setField('links', $links);
    }

    public static function createTrelloLinks(string $url): array
    {
        $iconResource = ModuleResourceLoader::singleton()
            ->resolveResource('silverstripe/workflow-trello: client/assets/trello.svg');

        return [
            [
                'url' => $url,
                'title' => 'View card in Trello',
                'icon' => $iconResource->getURL(),
            ],
        ];
    }
}
