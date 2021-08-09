<?php

namespace SilverStripe\Workflow\Trello\Tasks;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Workflow\Trello\API\Boards;

class SyncBoards extends BuildTask
{
    private static string $segment = 'trello-sync-boards';

    protected $title = 'Sync Trello boards';

    protected $description = 'Sync trello boards with the site';

    /**
     * @param HTTPRequest|mixed $request
     */
    public function run($request): void
    {
        Boards::sync();
    }
}
