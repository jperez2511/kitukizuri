<?php

namespace Icebearsoft\Kitukizuri\App\Database;

use GuzzleHttp\Client;

class TrinoConnection
{
    protected $client;

    public function __construct()
    {

        $trinoHost = env('TRINO_HOST', 'trino');
        $trinoPort = env('TRINO_PORT', '8080');
        $uri       = 'http://'.$trinoHost.':'.$trinoPort;

        $this->client = new Client([
            'base_uri' => $uri,
        ]);
    }

    public function execute($sql)
    {
        $response = $this->client->post('/v1/statement', [
            'body' => $sql,
        ]);

        $body = (string) $response->getBody();
        return json_decode($body, true);
    }
}
