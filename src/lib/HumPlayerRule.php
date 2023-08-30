<?php

namespace Blackjack;

class HumPlayerRule
{
    private const TOTAL_SCORE_17 = 17;
    private const TOTAL_SCORE_21 = 21;

    public static function shouldDrawCard(HumPlayer $player): bool
    {
        return $player->getTotalScore() < self::TOTAL_SCORE_17;
    }

    public static function isBust(int $playerTotalScore): bool
    {
        return $playerTotalScore > self::TOTAL_SCORE_21;
    }
}
