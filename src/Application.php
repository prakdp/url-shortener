<?php

namespace App;

use App\Routing\Exception\RouteNotFoundException;
use App\Routing\Router;
use PDO;
use Pimple\Container as Container;
use Pimple\Psr11\Container as Psr11Container;
use ReflectionObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Application class
 */
class Application
{

    /**
     * Container
     *
     * @var Psr11Container
     */
    protected $container;

    /**
     * Project Dir
     * @var string
     */
    protected $projectDir;

    public function __construct()
    {

    }

    /**
     * Bootstrap
     */
    public function bootstrap()
    {
        $this->initializeContainer();
    }

    /**
     * Initializes the service container
     */
    protected function initializeContainer()
    {
        $container = $this->buildContainer();

        $this->container = new Psr11Container($container);
    }

    /**
     * Gets a new Container instance used to build the service container
     *
     * @return Container
     */
    protected function buildContainer(): Container
    {
        $container = new Container();

        $this->configureContainer($container);

        return $container;
    }

    /**
     * Gets the application root dir (path of the project's composer file)
     *
     * @return string The project root dir
     */
    public function getProjectDir()
    {
        if (null === $this->projectDir) {
            $r       = new ReflectionObject($this);
            $dir     = $rootDir = dirname($r->getFileName());
            while (!file_exists($dir . '/composer.json')) {
                if ($dir === dirname($dir)) {
                    return $this->projectDir = $rootDir;
                }
                $dir = dirname($dir);
            }
            $this->projectDir = $dir;
        }

        return $this->projectDir;
    }

    /**
     * Configurate Container instance
     *
     * @param Container $container Container instance
     */
    public function configureContainer(Container $container)
    {
        $container->offsetSet('db', function() {return $this->dbConnect();});
        $container->offsetSet('router', function() {return $this->loadRoutes();});
    }

    /**
     * Load Routes
     *
     * @return Router
     */
    public function loadRoutes(): Router
    {
        return new Router(require_once $this->getProjectDir() . '/config/routes.php');
    }

    /**
     * DB Connecting
     *
     * @return PDO
     */
    public function dbConnect(): PDO
    {
        $dbConfig = require_once $this->getProjectDir() . '/config/db.php';
        $opt      = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        if (!isset($dbConfig['dsn'])) {
            $adapter = isset($dbConfig['adapter']) ? $dbConfig['adapter'] : '';
            $host    = isset($dbConfig['host']) ? $dbConfig['host'] : '';
            $db      = isset($dbConfig['db']) ? $dbConfig['db'] : '';
            $dsn     = "$adapter:host=$host;dbname=$db";
        } else {
            $dsn = $dbConfig['dsn'];
        }

        $user     = isset($dbConfig['user']) ? $dbConfig['user'] : '';
        $password = isset($dbConfig['password']) ? $dbConfig['password'] : '';
        $pdo      = new PDO($dsn, $user, $password, $opt);
        return $pdo;
    }

    /**
     * Handles a Request to convert it to a Response
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handleRequest(Request $request): Response
    {
        $this->bootstrap();

        /* @var $router Router */
        $router = $this->container->get('router');

        try {
            $match = $router->match($request);
        } catch (RouteNotFoundException $ex) {
            return new Response('Route ' . $ex->getRequest()->getRequestUri() . ' not found', 404);
        }


        $controllerClass = '\App\Controllers\\' . ucfirst($match->getController()) . 'Controller';

        if (!class_exists($controllerClass)) {
            return new Response('Controller ' . $match->getController() . ' not found', 404);
        }
        $controller = new $controllerClass($this, $request);

        $action = $match->getAction() . 'Action';

        if (!method_exists($controller, $action)) {
            return new Response('Action ' . $match->getAction() . ' not found', 404);
        }

        $result = $controller->$action();

        if (!($result instanceof Response)) {
            $result = new Response($result);
        }

        return $result;
    }

    /**
     * Get Container
     *
     * @return Psr11Container
     */
    public function getContainer(): Psr11Container
    {
        return $this->container;
    }


}
