<?php

namespace SilverStripe\Workflow\Trello\Tasks;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Workflow\Trello\API\Steps;

class SyncSteps extends BuildTask
{
    private static string $segment = 'trello-sync-steps';

    protected $title = 'Sync Trello steps (columns)';

    protected $description = 'Sync trello steps with the site';

    /**
     * @param HTTPRequest|mixed $request
     */
    public function run($request): void
    {
        Steps::sync();
    }
}
