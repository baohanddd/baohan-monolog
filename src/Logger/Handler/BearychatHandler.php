<?php
namespace Baohan\Monolog\Logger\Handler;

use GuzzleHttp\Client;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Class BearychatHandler
 * @package Baohan\Monolog\Logger\Handler
 */
class BearychatHandler extends AbstractProcessingHandler
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $id;

    /**
     * @param string $bearychatEndpoint
     * @param string $bearychatChannelId
     * @param int $level
     * @param bool $bubble
     */
    public function __construct($bearychatEndpoint = "", $bearychatChannelId = "", $level = Logger::DEBUG, $bubble = true)
    {
        $this->id = $bearychatChannelId;
        $this->client = $this->getHttpClient($bearychatEndpoint);
        parent::__construct($level, $bubble);
    }

    /**
     * @param array $record
     */
    protected function write(array $record): void
    {
        $json = [
            'text' => "[{$record['level_name']}] ".$record['message'],
            "markdown" => true,
            "attachments" => [
                [
                    "title"  => 'context',
                    "text"   => "```json\n".json_encode($record['context'], JSON_PRETTY_PRINT)."\n```",
                    "color"  => "#ffa500"
                ],
                [
                    "title"  => 'extra',
                    "text"   => "```json\n".json_encode($record['extra'], JSON_PRETTY_PRINT)."\n```",
                    "color"  => "#ffa500"
                ]
            ]
        ];

        $this->client->post($this->id, ['body' => \json_encode($json)]);
    }

    /**
     * @param string $uri
     * @return Client
     */
    protected function getHttpClient(string $uri): Client
    {
        if (!$this->client) {
            $this->client = new Client([
                'base_uri' => $uri,
                'headers' => ['Content-Type' => "application/json"],
                'timeout' => 10.0,
                'verify' => true,
                'debug' => false
            ]);
        }
        return $this->client;
    }
}