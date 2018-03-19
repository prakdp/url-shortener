<?php

namespace App\Routing\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Request;

class RouteNotFoundException extends Exception
{

    private $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct("No route matched");
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
