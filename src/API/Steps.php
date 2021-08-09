<?php

namespace SilverStripe\Workflow\Trello\API;

use InvalidArgumentException;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Workflow\Step;
use SilverStripe\Workflow\StepRelation;
use SilverStripe\Workflow\Trello\SiteConfigTrelloExtension;
use SilverStripe\Workflow\Trello\StepExtension;

class Steps
{
    public static function sync()
    {
        /** @var SiteConfig|SiteConfigTrelloExtension $config */
        $config = SiteConfig::current_site_config();
        $board = $config->Board();

        if (!$board || !$board->TrelloID) {
            throw new InvalidArgumentException('No associated board found');
        }

        $steps = Request::get(sprintf(Client::COLUMNS, $board->TrelloID));

        $stepIdsProcessed = [];

        foreach ($steps as $step) {
            $stepId = $step['id'];
            $stepIdsProcessed[] = $stepId;
            $stepDo = StepExtension::findOrCreate($stepId);
            $stepDo->TrelloID = $stepId;
            $stepDo->Title = $step['name'];
            $stepDo->Sort = $step['pos'];
            $stepDo->BoardID = $board->ID;
            $stepDo->WorkflowID = $config->ID;
            $stepDo->write();
        }

        $oldSteps = Step::get()->exclude('TrelloID', $stepIdsProcessed);

        /** @var Step $step */
        foreach ($oldSteps as $step) {
            StepRelation::get()
                ->filter('StepID', $step->ID)
                ->removeAll();
            $step->delete();
        }
    }
}
