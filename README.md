# Workflow Trello:
Sync your workflow with a Trello board to allow you to assign people to tasks, set date reminders, manage and overview the state of your content

## Setup
Extends upon the [Workflow module](https://github.com/adrhumphreys/silverstripe-workflow)

```
composer require silverstripe/workflow-trello
```

## Setting up Trello:
What you'll want to do is create a service account (an account created just to manage the Trello integration). The account should have access just to the one board you're integrating. You'll then (while being logged into the account) want to get your API key which can be found at: [trello.com/app-key](https://trello.com/app-key/).

You'll want to store that as the env var `TRELLO_KEY`. We then need to authorise the account with your app key so that it then has access to your account. The link is on the same page as the app key, or you can go through:
```
https://trello.com/1/authorize?expiration=never&scope=read,write,account&response_type=token&name=Server%20Token&key=YOUR_APP_KEY
```

This will give you a token which you will then want to store in the env var `TRELLO_TOKEN`

### Syncing boards:
You can run this automatically via a cron, it will find or create all the boards the service account has access to and then sync the `URL` and `Title`.

If those two attributes on the board are not going to change, then you should be able to run it once and forget about it (or run it again when they do change)

The task can be run from: `/dev/tasks/trello-sync-boards`. You can also call `Board::sync()` from elsewhere if you wanted to do this in another part of the application

### Syncing columns:
This depends on your situation. If you and your team are not going to change columns often/at all then you could just run the task as a one off. Otherwise you can sync them periodically. Columns are created/deleted/sorted in Trello. You can however edit them in the workflow how you please.

If a column is deleted in Trello then it will also be removed in the CMS (including all the relations to that column)

The task can be run from: `/dev/tasks/trello-sync-steps`. You can also call `Steps::sync()` from elsewhere if you wanted to do this in another part of the application

### Syncing cards:
This ideally is run frequently to reflect changes in Trello. This will update the pages/elements that have been updated in Trello to have the correct state (it'll remove the workflow if the cards be deleted, or update the workflow if the card has been put into a different column)

The task can be run from: `/dev/tasks/trello-sync-cards`. You can also call `Cards::sync()` from elsewhere if you wanted to do this in another part of the application

## License
See [License](license.md)

## Maintainers
 * Adrian Humphreys <adrhumphreys@gmail.com>

## Bugtracker
Bugs are tracked in the issues section of this repository. Before submitting an issue please read over existing issues to ensure yours is unique.

If the issue does look like a new bug:

 - Create a new issue
 - Describe the steps required to reproduce your issue, and the expected outcome. Unit tests, screenshots
 and screencasts can help here.
 - Describe your environment as detailed as possible: SilverStripe version, Browser, PHP version,
 Operating System, any installed SilverStripe modules.

Please report security issues to the module maintainers directly. Please don't file security issues in the bugtracker.

## Development and contribution
If you would like to make contributions to the module please ensure you raise a pull request and discuss with the module maintainers.
