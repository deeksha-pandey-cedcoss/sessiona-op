<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Config\ConfigFactory;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Http\Response\Cookies;
use time\Time;


$config = new Config([]);

require_once "../vendor/autoload.php";

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
        APP_PATH . "/etc/",
    ]
);

$loader->registerNamespaces(
    [
        'time' => APP_PATH ."/assets/",
    ]
);
$loader->register();

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$container->setShared(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
    [
        'savePath' => '/tmp',
    ]
);

$session->setAdapter($files)->start();
return $session;
    }

);
$container->set('cookies', function () {
    $cookies = new Cookies();

    $cookies->useEncryption(false);

    return $cookies;
});
$container->set(
    'time',
    function () {
       return new Time();
    }
);

$container->set(
    'mongo',
    function () {
        $mongo = new MongoDB\Client(
            'mongodb+srv://deekshapandey:Deeksha123@cluster0.whrrrpj.mongodb.net/?retryWrites=true&w=majority'
        );

        return $mongo->phalcon;
    },
    true
);

$application = new Application($container);
try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
