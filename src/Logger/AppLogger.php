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
    public static function getInstance(string $name, array $extra): Logger
    {
        $log = new Logger($name);
//        $log->pushHandler(new BearychatHandler(
//            'https://hook.bearychat.com/=bwBVl/incoming/',
//            '1383d56537283b18ec1d21cf6d5730ec',
//            Logger::CRITICAL));
//        $log->pushHandler(new StreamHandler('/data/logs/fu_cli_debug.log', Logger::DEBUG));
//        $log->pushHandler(new StreamHandler('/data/logs/fu_cli_error.log', Logger::ERROR));
//        $handler = new StreamHandler('php://stdout', Logger::DEBUG);
//        $handler->setFormatter(new ColoredLineFormatter());
//        $log->pushHandler($handler);
//        $log->pushProcessor(function ($record) use ($extra) {
//            $record['extra'] = $extra;
//            return $record;
//        });
        return $log;
    }

    /**
     * @param string $name
     * @param array $extra
     * @return Logger
     */
    public static function getLogger(string $name, array $extra): Logger
    {
        $log = new Logger($name);
        $log->pushProcessor(function ($record) use ($extra) {
            $record['extra'] = $extra;
            return $record;
        });
        return $log;
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