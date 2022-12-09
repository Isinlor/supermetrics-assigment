<?php

declare(strict_types=1);

namespace Tests\App\Controller;

use App\Controller\IndexController;
use App\Kernel\AppKernel;
use PHPUnit\Framework\TestCase;

class IndexControllerTest extends TestCase
{

    private AppKernel $kernel;
    private IndexController $controller;

    public function setUp(): void
    {
        $this->kernel = new AppKernel('config_test.yaml');
        $this->controller = $this->kernel->getContainer()->get(IndexController::class);
    }

    public function testIndexAction(): void
    {
        $response = $this->controller->indexAction();

        $this->assertEquals(200, $response->getStatusCode());
    }

}
