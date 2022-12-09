<?php

declare(strict_types=1);

namespace Tests\App\Controller;

use App\Controller\ErrorController;
use App\Kernel\AppKernel;
use PHPUnit\Framework\TestCase;

class ErrorControllerTest extends TestCase
{

    private AppKernel $kernel;
    private ErrorController $controller;

    public function setUp(): void
    {
        $this->kernel = new AppKernel('config_test.yaml');
        $this->controller = $this->kernel->getContainer()->get(ErrorController::class);
    }

    public function testIndexAction(): void
    {
        $response = $this->controller->notFoundAction();

        $this->assertEquals(200, $response->getStatusCode());
    }

}
