<?php

namespace Blackjack\Rule;

require_once(__DIR__ . '/../Rule/ParticipantRule.php');
require_once(__DIR__ . '/../Rule/NonHumPlayerRule.php');

class DealerRule implements ParticipantRule
{
    public function isBust(int $totalScore): bool
    {
        return $totalScore > ParticipantRule::TOTAL_SCORE_21;
    }

    public function shouldDrawCard(int $totalScore): bool
    {
        return $totalScore < NonHumPlayerRule::TOTAL_SCORE_17;
    }
}
