<?php

declare(strict_types=1);

namespace Tests\App\Controller;

use App\Controller\StatisticsController;
use App\Kernel\AppKernel;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class StatisticsControllerTest extends TestCase
{

    private AppKernel $kernel;
    private StatisticsController $controller;

    public function setUp(): void
    {
        $this->kernel = new AppKernel('config_test.yaml');
        $this->controller = $this->kernel->getContainer()->get(StatisticsController::class);
    }

    public function testIndexAction(): void
    {
        /** @var MockHandler $mockHandler */
        $mockHandler = $this->kernel->getContainer()->get(MockHandler::class);

        for ($i = 0; $i < 10; $i++) {
            $mockHandler->append(
                new Response(200, [], file_get_contents(__DIR__ . "/../data/auth-token-response.json")),
                new Response(200, [], file_get_contents(__DIR__ . "/../data/social-posts-response.json")),
            );
        }

        $response = $this->controller->indexAction(['month' => 'August, 2018']);

        $this->assertEquals(200, $response->getStatusCode());

        $responseArray = json_decode($response->getBody()->getContents(), true);
        $statsChildren = $responseArray['stats']['children'];
        $this->assertCount(4, $statsChildren);

        $this->assertEquals("average-posts-per-user", $statsChildren[3]['name']);
        $this->assertEquals(10, $statsChildren[3]['value']);

    }

}
