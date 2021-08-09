<?php

namespace SilverStripe\Workflow\Trello;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\HasManyList;
use SilverStripe\Workflow\Step;

/**
 * @property string Title
 * @property string TrelloID
 * @property string TrelloURL
 * @method Step[]|HasManyList Steps
 */
class Board extends DataObject
{
    private static string $table_name = 'Workflow_Trello_Board';

    private static array $db = [
        'Title' => 'Varchar(255)',
        'TrelloID' => 'Varchar(255)',
        'TrelloURL' => 'Varchar(255)',
    ];

    private static array $has_many = [
        'Steps' => Step::class,
    ];

    public static function findOrCreate(string $id): Board
    {
        $board = static::get()->find('TrelloID', $id);

        if (!$board) {
            $board = Board::create();
        }

        return $board;
    }
}
