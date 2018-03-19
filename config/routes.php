<?php


return [
    '/^\/$/' => ['home', 'index', ['GET']],
    '/^\/url\/$/' => ['url', 'post', ['POST']],
    '/^\/\w{10}$/' => ['url', 'redirect', ['GET']],
];