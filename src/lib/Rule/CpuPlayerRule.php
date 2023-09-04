<?php

namespace Blackjack\Rule;

require_once(__DIR__ . '/../Rule/ParticipantRule.php');
require_once(__DIR__ . '/../Rule/NonHumPlayerRule.php');

class CpuPlayerRule implements ParticipantRule, NonHumPlayerRule
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

    /**
     * カードを引くかを決める
     *
     * @param  int $totalScore
     * @return bool
     */
    public function shouldDrawCard(int $totalScore): bool
    {
        return $totalScore < NonHumPlayerRule::TOTAL_SCORE_17;
    }
}
