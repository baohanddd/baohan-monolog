<?php
use Baohan\Monolog\Logger\AppLogger;
use Monolog\Logger;

require('./vendor/autoload.php');

$extra = [
    'key1' => 'val1'
];
$log = AppLogger::getLogger('demo', $extra);
$log->pushHandler(AppLogger::getConsoleHandler(Logger::DEBUG));
$log->pushHandler(AppLogger::getStreamHandler('debug.log', Logger::DEBUG));
$log->pushHandler(AppLogger::getStreamHandler('error.log', Logger::ERROR));
$log->pushHandler(AppLogger::getBearychatHandler('1383d56537283b18ec1d21cf6d5730ec', Logger::CRITICAL));

$context = [
    'page' => 'demo.php'
];
$log->debug('The first debug message', $context);