<?php

namespace SilverStripe\Workflow\Trello\API;

use GuzzleHttp\Psr7\Request as BaseRequest;

class Request extends BaseRequest
{
    public static function get(string $url, ?array $queryParams = null): ?array
    {
        $request = new Request('GET', $url);
        $response = Client::create()->send($request, $queryParams);
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }

    public static function post(string $url, ?array $queryParams = null): ?array
    {
        $request = new Request('POST', $url);
        $response = Client::create()->send($request, $queryParams);
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }

    public static function put(string $url, ?array $queryParams = null): ?array
    {
        $request = new Request('PUT', $url);
        $response = Client::create()->send($request, $queryParams);
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }
}
