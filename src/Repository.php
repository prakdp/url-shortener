<?php

namespace App;

use PDO;

/**
 * Abstract repository class
 */
abstract class Repository
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get DB connector
     *
     * @return PDO
     */
    public function getDB(): PDO
    {
        return $this->app->getContainer()->get('db');
    }
}
