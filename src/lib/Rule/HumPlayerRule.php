<?php

namespace Blackjack\Rule;

require_once(__DIR__ . '/../Rule/ParticipantRule.php');

class HumPlayerRule implements ParticipantRule
{
    /**
     * バーストしているか調べる
     *
     * @param  int $totalScore
     * @return bool
     */
    public function isBust(int $totalScore): bool
    {
        return $totalScore > ParticipantRule::TOTAL_SCORE_21;
    }
}
