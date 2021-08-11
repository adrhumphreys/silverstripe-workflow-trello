<?php

namespace SilverStripe\Workflow\Trello;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Workflow\Trello\API\Cards;

class SyncCards extends BuildTask
{
    private static string $segment = 'trello-sync-cards';

    protected $title = 'Sync Trello cards (step relations)';

    protected $description = 'Sync trello cards with the site';

    /**
     * @param HTTPRequest|mixed $request
     */
    public function run($request): void
    {
        Cards::sync();
    }
}
