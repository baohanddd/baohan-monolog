<?php
namespace Baohan\Monolog\Logger\Handler;

use GuzzleLogMiddleware\Handler\HandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\TransferStats;
use Psr\Log\LoggerInterface;

/**
 * Class GuzzleLogHandler
 * @package Baohan\Monolog\Logger\Handler
 */
final class GuzzleLogHandler implements HandlerInterface
{
    public function log(
        LoggerInterface $logger,
        RequestInterface $request,
        ?ResponseInterface $response,
        ?\Exception $exception,
        ?TransferStats $stats,
        array $options
    ): void
    {
        $this->withException($logger, $request, $exception);
        $this->withResponse($logger, $request, $response);
        return;
    }

    /**
     * @param LoggerInterface $logger
     * @param RequestInterface $request
     * @param \Exception|null $exception
     */
    protected function withException(LoggerInterface $logger, RequestInterface $request, ?\Exception $exception):void
    {
        if ($exception) {
            $logger->critical('FAIL CONNECT: ', [
                'request' => $this->getRequest($request),
                'exception' => [
                    'code'    => $exception->getCode(),
                    'message' => $exception->getMessage()
                ]
            ]);
        }
    }

    /**
     * @param LoggerInterface $logger
     * @param RequestInterface $request
     * @param ResponseInterface|null $response
     */
    protected function withResponse(LoggerInterface $logger, RequestInterface $request, ?ResponseInterface $response):void
    {
        if ($response) {
            $status = $response->getStatusCode();
            if ($status > 299) {
                $logger->error('INVALID RESPONSE: '.$status, [
                    'request'  => $this->getRequest($request),
                    'response' => $this->getResponse($response)
                ]);
            }
        }
    }

    /**
     * @param RequestInterface $request
     * @return array
     */
    protected function getRequest(RequestInterface $request): array
    {
        return [
            'headers' => $request->getHeaders(),
            'uri' => $request->getRequestTarget(),
            'method' => $request->getMethod(),
            'version' => $request->getProtocolVersion(),
            'body' => $this->getBody((string) $request->getBody()),
        ];
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    protected function getResponse(ResponseInterface $response): array
    {
        return [
            'headers' => $response->getHeaders(),
            'status_code' => $response->getStatusCode(),
            'version' => $response->getProtocolVersion(),
            'message' => $response->getReasonPhrase(),
            'body' => $this->getBody((string) $response->getBody()),
        ];
    }

    /**
     * @param string $body
     * @return mixed|string
     */
    protected function getBody(string $body)
    {
        $json = json_decode($body, true);
        if (json_last_error()) {
            return $body;
        }
        return $json;
    }
}