<?php

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

/**
 * Computes average number of posts per user.
 *
 * @package Statistics\Calculator
 */
class AveragePostsPerUser extends AbstractCalculator
{

    protected const UNITS = 'posts';

    /**
     * @var int[]
     */
    private $postCountsPerUser = [];

    /**
     * @param SocialPostTo $postTo
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $authorKey = $postTo->getAuthorId();
        $this->postCountsPerUser[$authorKey] = ($this->postCountsPerUser[$authorKey] ?? 0) + 1;
    }

    /**
     * @return StatisticsTo
     */
    protected function doCalculate(): StatisticsTo
    {
        $value = array_sum($this->postCountsPerUser) / count($this->postCountsPerUser);

        // TODO: precision seems like a display consideration - why precision 2 is expected?
        return (new StatisticsTo())->setValue(round($value,2));
    }
}
