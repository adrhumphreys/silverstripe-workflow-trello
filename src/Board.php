<?php

namespace SilverStripe\Workflow\Trello;

use SilverStripe\ORM\DataObject;
use SilverStripe\Workflow\Trello\API\Client;
use SilverStripe\Workflow\Trello\API\Request;

/**
 * @property string Title
 * @property string TrelloID
 * @property string TrelloURL
 */
class Board extends DataObject
{
    private static string $table_name = 'Workflow_Trello_Board';

    private static array $db = [
        'Title' => 'Varchar(255)',
        'TrelloID' => 'Varchar(255)',
        'TrelloURL' => 'Varchar(255)',
    ];

    public static function findOrCreate(string $id): Board
    {
        $board = static::get()->find('TrelloID', $id);

        if (!$board) {
            $board = Board::create();
        }

        return $board;
    }

    public static function sync()
    {
        $boards = Request::get(Client::BOARDS, [
            'fields' => 'name,url,id',
        ]);

        foreach ($boards as $board) {
            $boardDo = Board::findOrCreate($board['id']);
            $boardDo->Title = $board['name'];
            $boardDo->TrelloID = $board['id'];
            $boardDo->TrelloURL = $board['url'];
            $boardDo->write();
        }
    }
}
