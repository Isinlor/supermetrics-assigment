<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface ControllerInterface
 *
 * @package App\Controller
 */
interface ControllerInterface
{
    public function render(string $template, array $vars, int $status = 200, array $headers = [], bool $useLayout = true): ResponseInterface;
}
