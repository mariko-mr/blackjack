<?php

namespace Blackjack\Rule;

require_once(__DIR__ . '/../Rule/ParticipantRule.php');

class HumPlayerRule implements ParticipantRule
{
    public function isBust(int $totalScore): bool
    {
        return $totalScore > ParticipantRule::TOTAL_SCORE_21;
    }
}
