A configured Monolog for out-of-box
====

Monolog is a powerful logger, but need some configures before to use for each projects, even if there are almost same config items. So it provides simplest way to reduce these works, that's why the project born.

### Installation

```
$ composer require "baohan/monolog: 1.*"
```

### Example

```php
use Baohan\Monolog\Logger\AppLogger;
use Monolog\Logger;

require('./vendor/autoload.php');

$extra = [
    'key1' => 'val1'
];
// or grab http request data as extra
// $extra = AppLogger::getExtraFromRequest($request);
$log = AppLogger::getLogger('demo', $extra);
$log->pushHandler(AppLogger::getConsoleHandler(Logger::DEBUG));
$log->pushHandler(AppLogger::getStreamHandler('debug.log', Logger::DEBUG));
$log->pushHandler(AppLogger::getBearychatHandler('YOUR_API_KEY', Logger::CRITICAL));
$log->pushHandler(AppLogger::getStreamHandler('error.log', Logger::ERROR));

$context = [
    'page' => 'demo.php'
];
$log->debug('The first debug message', $context);
```
It will output:
```
$ php demo.php
> [2020-01-02T07:30:02.602905+00:00] demo.DEBUG: The first debug message {"page":"demo.php"} {"key1":"val1"}
```

That's all.
