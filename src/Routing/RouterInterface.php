<?php

namespace App\Routing;

use Symfony\Component\HttpFoundation\Request;

interface RouterInterface
{
    /**
     * Match route by Request
     *
     * @param Request $request
     *
     * @return Match
     */
    public function match(Request $request): Match;
}
