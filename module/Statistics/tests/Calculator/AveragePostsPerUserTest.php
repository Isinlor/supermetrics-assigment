<?php

declare(strict_types=1);

namespace Tests\Statistics\Calculator;

use DateTime;
use PHPUnit\Framework\TestCase;
use SocialPost\Dto\SocialPostTo;
use Statistics\Calculator\AveragePostsPerUser;
use Statistics\Dto\ParamsTo;
use Statistics\Dto\StatisticsTo;
use Statistics\Enum\StatsEnum;

class AveragePostsPerUserTest extends TestCase
{
    public function testBasicCalculate(): void
    {
        $date = new DateTime('15-12-2022');
        $averagePostsPerUserCalculator = $this->getAveragePostsPerUserCalculator($date);

        $averagePostsPerUserCalculator->accumulateData(
            (new SocialPostTo())
                ->setAuthorId('author1')
                ->setDate($date)
        );

        $statisticsTo = $averagePostsPerUserCalculator->calculate();

        $this->assertEquals(1, $statisticsTo->getValue());
        $this->assertCommonStatisticsInfo($statisticsTo);
    }

    public function testFilteringPerMonth(): void
    {
        $date = new DateTime('15-12-2022');
        $averagePostsPerUserCalculator = $this->getAveragePostsPerUserCalculator($date);

        $averagePostsPerUserCalculator->accumulateData(
            (new SocialPostTo())
                ->setAuthorId('author1')
                ->setDate((clone $date)->modify('first day of next month'))
        );
        $averagePostsPerUserCalculator->accumulateData(
            (new SocialPostTo())
                ->setAuthorId('author1')
                ->setDate((clone $date)->modify('last day of previous month'))
        );
        $averagePostsPerUserCalculator->accumulateData(
            (new SocialPostTo())
                ->setAuthorId('author1')
                ->setDate((clone $date)->modify('first day of this month'))
        );
        $averagePostsPerUserCalculator->accumulateData(
            (new SocialPostTo())
                ->setAuthorId('author1')
                ->setDate((clone $date)->modify('last day of this month'))
        );

        $statisticsTo = $averagePostsPerUserCalculator->calculate();

        $this->assertEqualsWithDelta(2, $statisticsTo->getValue(), 0.001);
        $this->assertCommonStatisticsInfo($statisticsTo);
    }

    public function testAveragingPerUser(): void
    {
        $date = new DateTime('15-12-2022');
        $averagePostsPerUserCalculator = $this->getAveragePostsPerUserCalculator($date);

        $averagePostsPerUserCalculator->accumulateData(
            (new SocialPostTo())
                ->setAuthorId('author1')
                ->setDate((clone $date)->modify('first day of this month'))
        );
        $averagePostsPerUserCalculator->accumulateData(
            (new SocialPostTo())
                ->setAuthorId('author1')
                ->setDate((clone $date)->modify('last day of this month'))
        );
        $averagePostsPerUserCalculator->accumulateData(
            (new SocialPostTo())
                ->setAuthorId('author2')
                ->setDate((clone $date)->modify('first day of this month'))
        );

        // this one will not be counted due to date
        $averagePostsPerUserCalculator->accumulateData(
            (new SocialPostTo())
                ->setAuthorId('author2')
                ->setDate((clone $date)->modify('last day of previous month'))
        );

        $statisticsTo = $averagePostsPerUserCalculator->calculate();

        $this->assertEqualsWithDelta(1.5, $statisticsTo->getValue(), 0.001);
        $this->assertCommonStatisticsInfo($statisticsTo);
    }

    private function getAveragePostsPerUserCalculator(DateTime $date): AveragePostsPerUser
    {
        $startDate = (clone $date)->modify('first day of this month')->setTime(0, 0, 0);
        $endDate = (clone $date)->modify('last day of this month')->setTime(23, 59, 59);

        $averagePostsPerUserCalculator = new AveragePostsPerUser();

        $averagePostsPerUserCalculator->setParameters(
            (new ParamsTo())
                ->setStatName(StatsEnum::AVERAGE_POST_NUMBER_PER_USER)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
        );

        return $averagePostsPerUserCalculator;
    }

    private function assertCommonStatisticsInfo(StatisticsTo $statisticsTo): void
    {
        $this->assertEquals(StatsEnum::AVERAGE_POST_NUMBER_PER_USER, $statisticsTo->getName());
        $this->assertEquals('posts', $statisticsTo->getUnits());
    }
}
