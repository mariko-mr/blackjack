<?php

namespace Blackjack;

class DealerRule
{
    private const TOTAL_SCORE_17 = 17;
    private const TOTAL_SCORE_21 = 21;

    public static function shouldDrawCard(Dealer $dealer): bool
    {
        return $dealer->getTotalScore() < self::TOTAL_SCORE_17;
    }

    public static function isBust(int $cpuTotalScore): bool
    {
        return $cpuTotalScore > self::TOTAL_SCORE_21;
    }
}
