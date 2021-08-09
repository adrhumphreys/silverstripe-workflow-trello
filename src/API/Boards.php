<?php

namespace SilverStripe\Workflow\Trello\API;

use SilverStripe\Workflow\Trello\Board;

class Boards
{
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
