<?php

namespace SilverStripe\Workflow\Trello;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Workflow\Step;
use SilverStripe\Workflow\StepRelation;
use SilverStripe\Workflow\Trello\API\Cards;
use SilverStripe\Workflow\WorkflowController;

/**
 * @property WorkflowController|$this owner
 */
class WorkflowControllerExtension extends Extension
{
    /**
     * @param HTTPResponse $response
     * @param DataObject $record
     * @param StepRelation|StepRelationExtension $relation
     * @param Step|StepExtension|null $step
     * @throws ValidationException
     */
    public function updateResponse(
        HTTPResponse $response,
        DataObject $record,
        StepRelation $relation,
        ?Step $step
    ): void
    {
        // If there's no step then we're just removing the workflow from the item
        // therefore we'll just delete it
        if (!$step) {
            $relation->delete();

            return;
        }

        $editLink = null;

        if ($record instanceof SiteTree || $record instanceof BaseElement) {
            $editLink = $record->CMSEditLink();
            $editLink = Director::absoluteURL($editLink);
        }

        $url = null;

        // We don't have a card
        if (!$relation->TrelloID || !$relation->TrelloURL) {
            $url = $this->createCard($record, $step->TrelloID, $editLink, $relation);
        } else {
            $url = Cards::update($relation->TrelloID, $step->TrelloID, $editLink);
            $relation->TrelloURL = $url;
            $relation->write();
        }

        $response->setBody(json_encode([
            'success' => true,
            'links' => WorkflowWidgetExtension::createTrelloLinks($url),
        ]));
    }

    /**
     * @param array $data
     * @param StepRelation|StepRelationExtension|null $relation
     */
    public function updateGetSteps(array &$data, ?StepRelation $relation): void
    {
        if (!$relation) {
            return;
        }

        $data['links'] = array_merge(
            WorkflowWidgetExtension::createTrelloLinks($relation->TrelloURL),
            $data['links'] ?? []
        );
    }

    /**
     * @param DataObject $record
     * @param string $trelloID
     * @param string $editLink
     * @param StepRelation|StepRelationExtension $relation
     * @return string|null
     * @throws ValidationException
     */
    public function createCard(
        DataObject $record,
        string $trelloID,
        string $editLink,
        StepRelation $relation
    ): ?string
    {
        $card = Cards::create($record->Title, $trelloID, $editLink);
        $relation->TrelloID = $card['id'];
        $url = $card['url'] ?? $card['shortUrl'] ?? null;
        $relation->TrelloURL = $url;
        $relation->write();

        return $url;
    }
}
