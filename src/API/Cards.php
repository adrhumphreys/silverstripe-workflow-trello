<?php

namespace SilverStripe\Workflow\Trello\API;

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
}
