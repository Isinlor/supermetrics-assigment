<?php

declare(strict_types=1);

namespace Tests\App\Controller;

use PHPUnit\Framework\TestCase;

class StatisticsControllerTest extends TestCase
{
    public function testIndexAction(): void
    {
        $this->markTestIncomplete(
            "The application should be refactored to use Dependency Injection Container. " .
            "Implementing integration test would require mocking HTTP client, but current factory based architecture is quite rigid."
        );
    }
}
