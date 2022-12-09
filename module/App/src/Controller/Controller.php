<?php

namespace App\Controller;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Controller
 *
 * @package App\Controller
 */
abstract class Controller implements ControllerInterface
{

    public function render(string $template, array $vars, int $status = 200, array $headers = [], bool $useLayout = true): ResponseInterface
    {
        ob_start();
        $templateFile = sprintf(__DIR__ . '/../../view/%s.phtml', $template);
        if (!file_exists($templateFile)) {
            throw new \RuntimeException(sprintf('Template %s not found', $template));
        }

        extract($vars);

        $content = $templateFile;

        if (true === $useLayout) {
            include __DIR__ . '/../../view/layout.phtml';
        } else {
            include $content;
        }

        return new Response($status, $headers, ob_get_clean());
    }

    public function renderJson(array $vars, int $status = 200)
    {
        return $this->render('json', $vars, $status, ['Content-Type' => 'application/json'], false);
    }

}
