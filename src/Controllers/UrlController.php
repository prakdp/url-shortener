<?php

namespace App\Controllers;

use App\Controller;
use App\Repositories\ShortUrlRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class UrlController extends Controller
{

    public function postAction()
    {
        $url = $this->request->get('url');

        $result = [
            'success' => false
        ];

        $errors = [];

        if (empty($url)) {
            $errors[] = 'URL cannot be blank';
        } elseif (!preg_match('/^(https?):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i', $url)) {
            $errors[] = 'URL is not a valid URL';
        }

        if (empty($errors)) {
            $repository = new ShortUrlRepository($this->app);
            $shortUrl = $repository->createByUrl($url);
            $result['success'] = true;
            $result['url']     = $this->request->getSchemeAndHttpHost() . '/' . $shortUrl->getShortUrl();
        } else {
            $result['errors'] = $errors;
        }

        return new JsonResponse($result);
    }

    public function redirectAction()
    {
        $url = trim($this->request->getPathInfo(), '/');

        if (empty($url)) {
            return new RedirectResponse('/');
        }

        $repository = new ShortUrlRepository($this->app);
        $shortUrl = $repository->findByShortUrl($url);

        if (empty($shortUrl)) {
            return new Response('Url not found', 404);
        }

        return new RedirectResponse($shortUrl->getUrl());
    }
}
