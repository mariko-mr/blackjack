<?php

namespace Blackjack\Rule;

require_once(__DIR__ . '/../Rule/ParticipantRule.php');

use Blackjack\Participants\Dealer;

class DealerRule implements ParticipantRule
{
    private const TOTAL_SCORE_17 = 17;

    public static function shouldDrawCard(Dealer $dealer): bool
    {
        return $dealer->getTotalScore() < self::TOTAL_SCORE_17;
    }

    public function isBust(int $totalScore): bool
    {
        return $totalScore > ParticipantRule::TOTAL_SCORE_21;
    }
}
