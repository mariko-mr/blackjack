<?php

namespace Blackjack\Rule;
interface ParticipantRule
{
    const TOTAL_SCORE_21 = 21;

    public function isBust(int $totalScore);
}
