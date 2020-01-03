<?php
use Baohan\Monolog\Logger\AppLogger;
use Monolog\Logger;

require('./vendor/autoload.php');

$extra = [
    'key1' => 'val1'
];

$log = AppLogger::getLogger('demo', $extra);
// or grab http request data as extra
// $extra = AppLogger::getExtraFromRequest($request);
// AppLogger::setExtra($log, $extra);
$log->pushHandler(AppLogger::getConsoleHandler(Logger::DEBUG));
$log->pushHandler(AppLogger::getStreamHandler('debug.log', Logger::DEBUG));
$log->pushHandler(AppLogger::getStreamHandler('error.log', Logger::ERROR));
$log->pushHandler(AppLogger::getBearychatHandler('YOUR_API_KEY', Logger::CRITICAL));

$context = [
    'page' => 'demo.php'
];
$log->debug('The first debug message', $context);