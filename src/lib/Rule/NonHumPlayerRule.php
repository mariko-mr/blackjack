<?php

namespace Blackjack\Rule;

interface NonHumPlayerRule
{
    const TOTAL_SCORE_17 = 17;

    public function shouldDrawCard(int $totalScore);
}
