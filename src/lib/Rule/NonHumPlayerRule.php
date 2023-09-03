<?php

namespace Blackjack\Rule;

interface NonHumPlayerRule
{
    /** @var int カードの総得点 */
    public const TOTAL_SCORE_17 = 17;

    /**
     * カードを引くかを決める
     *
     * @param  int $totalScore
     * @return bool
     */
    public function shouldDrawCard(int $totalScore);
}
