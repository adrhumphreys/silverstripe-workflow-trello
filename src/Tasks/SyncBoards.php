<?php

namespace SilverStripe\Workflow\Trello\Tasks;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Workflow\Trello\Board;

class SyncBoards extends BuildTask
{
    private const BOARDS = '/1/members/me/boards';

    private static string $segment = 'trello-sync-boards';

    protected $title = 'Sync Trello boards';

    protected $description = 'Sync trello boards with the site';

    /**
     * @param HTTPRequest|mixed $request
     */
    public function run($request): void
    {
        Board::sync();
    }
}
