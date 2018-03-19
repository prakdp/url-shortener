<?php

namespace App\Routing;

/**
 * Route
 */
class Route
{

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $methods;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $action;

    /**
     * Route constructor.
     * @param string $path
     * @param array $methods
     * @param string $controller
     * @param string $action
     */
    public function __construct(string $path, array $methods, string $controller, string $action)
    {
        $this->path       = $path;
        $this->methods    = $methods;
        $this->controller = $controller;
        $this->action     = $action;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
