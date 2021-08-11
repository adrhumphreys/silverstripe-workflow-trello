<?php

namespace SilverStripe\Workflow\Trello\API;

use InvalidArgumentException;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Workflow\Step;
use SilverStripe\Workflow\StepRelation;
use SilverStripe\Workflow\Trello\SiteConfigTrelloExtension;
use SilverStripe\Workflow\Trello\StepRelationExtension;

class Cards
{
    public static function create(
        string $title,
        string $stepTrelloID,
        ?string $editLink = null
    ): ?array {
        $response = Request::post(Client::CARDS_CREATE, [
            'idList' => $stepTrelloID,
            'name' => $title,
            'urlSource' => $editLink,
        ]);

        if (!$response || !array_key_exists('id', $response)) {
            return null;
        }

        return $response;
    }

    public static function update(
        string $cardTrelloId,
        string $stepTrelloId,
        ?string $editLink
    ): ?string {
        $card = Request::put(sprintf(Client::CARDS_UPDATE, $cardTrelloId), [
            'idList' => $stepTrelloId,
            'urlSource' => $editLink,
        ]);

        return $card['url'] ?? $card['shortUrl'] ?? null;
    }

    public static function delete($trelloID): void
    {
        Request::delete(sprintf(Client::CARDS_DELETE, $trelloID));
    }

    public static function sync(): void
    {
        /** @var SiteConfig|SiteConfigTrelloExtension $config */
        $config = SiteConfig::current_site_config();
        $board = $config->Board();

        if (!$board || !$board->TrelloID) {
            throw new InvalidArgumentException('No associated board found');
        }

        $cards = Request::get(sprintf(Client::CARDS, $board->TrelloID));

        $stepRelations = StepRelation::get()->exclude('TrelloID', null);
        $steps = Step::get()
            ->map('ID', 'TrelloID')
            ->toArray();
        $stepsInverse = Step::get()
            ->map('TrelloID', 'ID')
            ->toArray();

        /** @var StepRelation|StepRelationExtension $relation */
        foreach ($stepRelations as $relation) {
            $stepId = self::getStepID($relation->TrelloID, $cards);

            // The card has been removed
            if ($stepId === null) {
                $relation->delete();

                continue;
            }

            // The card has not been updated
            if ($stepId === $steps[$relation->StepID]) {
                continue;
            }

            // The card has changed
            $relation->StepID = $stepsInverse[$stepId] ?? 0;
            $relation->write();
        }
    }

    private static function getStepID(string $trelloID, array $cards): ?string
    {
        foreach ($cards as $card) {
            if ($card['id'] === $trelloID) {
                return $card['idList'];
            }
        }

        return null;
    }
}
