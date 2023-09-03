<?php

namespace Blackjack\Rule;
interface ParticipantRule
{
    /** @var int カードの総得点 */
    const TOTAL_SCORE_21 = 21;

    /**
     * バーストしているか調べる
     *
     * @param  int $totalScore
     * @return bool
     */
    public function isBust(int $totalScore);
}
