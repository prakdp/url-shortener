<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract controller
 */
abstract class Controller
{

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Application $app
     */
    public function __construct(Application $app, Request $request)
    {
        $this->app     = $app;
        $this->request = $request;
    }

    /**
     * Render view file
     *
     * @param string $view View name
     * @param array $data View variables
     *
     * @return Response
     */
    public function render(string $view, array $data = []): Response
    {
        $file = $this->app->getProjectDir() . '/templates/' . $view . '.php';

        if (!file_exists($file)) {
            return new Response('View ' . $view . ' not found', 404);
        }

        $content = $this->renderFile($file, $data);

        return new Response($content);
    }

    /**
     * Render file
     *
     * @param string $file Filename
     * @param array $data File variables
     *
     * @return string Result
     *
     * @throws \Exception
     */
    public function renderFile(string $file, array $data = []): string
    {
        $obInitialLevel = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($data, EXTR_OVERWRITE);
        try {
            require($file);
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $obInitialLevel) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $obInitialLevel) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
}
