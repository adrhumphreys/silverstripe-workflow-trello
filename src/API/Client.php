<?php

namespace SilverStripe\Workflow\Trello\API;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Injector\Injectable;
use Throwable;

/**
 * Why do we have a connector between us and Guzzle?
 * - It's nice to have an easy send() method available.
 * - Allows us to easily mock API responses in tests.
 * - Allows a default base uri
 * - Allows for the key/tokens to be added in one place
 */
class Client
{
    public const BOARDS = '/1/members/me/boards';

    // First argument is the board ID
    public const COLUMNS = '/1/boards/%s/lists';

    public const CARDS_CREATE = '/1/cards';
    // First argument is the card ID
    public const CARDS_UPDATE = '/1/cards/%s';
    public const CARDS_DELETE = '/1/cards/%s';
    // First argument is the board ID
    public const CARDS = '/1/boards/%s/cards';

    public const API_TIMEOUT = 60;

    private const ENV_KEY = 'TRELLO_KEY';
    private const ENV_TOKEN = 'TRELLO_TOKEN';

    use Injectable;

    protected ?GuzzleClient $client;

    public function __construct(?GuzzleClient $client = null)
    {
        if ($client === null) {
            $client = new GuzzleClient([
                'base_uri' => 'https://api.trello.com/',
            ]);
        }

        $this->client = $client;
    }

    public function getClient(): GuzzleClient
    {
        return $this->client;
    }

    public function send(Request $request, ?array $queryParams = null): ResponseInterface
    {
        $key = Environment::getEnv(self::ENV_KEY);
        $token = Environment::getEnv(self::ENV_TOKEN);

        if (!$key || !$token) {
            throw new InvalidArgumentException('No Trello token/key provided');
        }

        try {
            $response = $this->getClient()->send($request, [
                RequestOptions::TIMEOUT => self::API_TIMEOUT,
                RequestOptions::QUERY => array_merge_recursive($queryParams ?? [], [
                    'key' => $queryParams['key'] ?? $key,
                    'token' => $queryParams['token'] ?? $token,
                ]),
            ]);
        } catch (Throwable $e) {
            if ($e instanceof RequestException
                && $e->hasResponse()
            ) {
                return $e->getResponse();
            }

            $message = strlen($e->getMessage()) > 0
                ? $e->getMessage()
                : 'Unknown error during API request';

            $body = [
                'message' => $message,
            ];

            $response = new Response(500, [], json_encode($body));
        }

        return $response;
    }
}
