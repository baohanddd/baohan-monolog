<?php
namespace Baohan\Monolog\Logger;

use Baohan\Monolog\Logger\Handler\BearychatHandler;
use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Phalcon\Http\RequestInterface;

class AppLogger
{
    /**
     * @param string $name
     * @param array $extra
     * @return Logger
     */
    public static function getLogger(string $name, array $extra): Logger
    {
        $log = new Logger($name);
        if ($extra) {
            static::setExtra($log, $extra);
        }
        return $log;
    }

    /**
     * @param Logger $log
     * @param array $extra
     */
    public static function setExtra(Logger $log, array $extra): void
    {
        $log->pushProcessor(function ($record) use ($extra) {
            $record['extra'] = $extra;
            return $record;
        });
    }

    /**
     * @param string $path
     * @param int $level
     * @return StreamHandler
     */
    public static function getStreamHandler(string $path, int $level): StreamHandler
    {
        return new StreamHandler($path, $level);
    }

    /**
     * @param int $level
     * @return StreamHandler
     */
    public static function getConsoleHandler(int $level): StreamHandler
    {
        $handler = new StreamHandler('php://stdout', $level);
        $handler->setFormatter(new ColoredLineFormatter());
        return $handler;
    }

    /**
     * @param string $channelId
     * @param int $level
     * @return BearychatHandler
     */
    public static function getBearychatHandler(string $channelId, int $level): BearychatHandler
    {
        return new BearychatHandler(
            'https://hook.bearychat.com/=bwBVl/incoming/',
            $channelId,
            $level);
    }

    /**
     * @param RequestInterface $req
     * @return array
     */
    public static function getExtraFromRequest(RequestInterface $req): array
    {
        return [
            'method' => $req->getMethod(),
            'params' => $req->get(),
            'authorization' => $req->getHeader('Authorization')
        ];
    }
}