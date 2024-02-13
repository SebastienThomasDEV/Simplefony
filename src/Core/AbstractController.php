<?php

namespace Mvc\Framework\Core;

use Mvc\Framework\Core\Forge\View;
use Mvc\Framework\Core\Http\Response;

class AbstractController
{

    /**
     * Render a view
     *
     * @param string $view
     * @param array $vars
     * @return void
     */
    public function render(string $view, array $vars): void
    {
        $template = new View($view, $vars);
        $response = new Response($template->build());
        $response->send();
    }



    /**
     * Redirect to a URL
     *
     * @param string $url
     * @return void
     */
    public function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit();
    }


    /**
     * Generate an absolute URL
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function generateUrl(string $route, array $params = []): string
    {
        $url = $_ENV['BASE_URL'] . $route;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }



}
