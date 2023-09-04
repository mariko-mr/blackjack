<?php

namespace Blackjack\Rule;

use Blackjack\Card;

class AceRule
{
    /**
     * Aの得点を11から1へと減算する
     *
     * @param  ParticipantRule $participantRule
     * @param  int    $totalScore
     * @param  Card[] $cards
     * @return int    $subtractedTotalScore Aによって減算された最終得点
     */
    public function subtractAceScore($participantRule, int $totalScore, array $cards): int
    {
        $subtractedTotalScore = $totalScore;
        $aceCount = $this->countAce($cards);

        // Aの出現回数内で減算分を計算
        for ($i = 0; $i < $aceCount; $i++) {
            if ($participantRule->isBust($subtractedTotalScore)) {
                $subtractedTotalScore -= 10;
            }
        }

        return $subtractedTotalScore;
    }

    /**
     * Aの出現回数を調べる
     *
     * @param  Card[] $cards
     * @return int    $aceCount Aの出現回数
     */
    private function countAce(array $cards): int
    {
        $aceCount = 0;
        foreach ($cards as $card) {
            if ($card->getNumber() == 'A') {
                $aceCount++;
            }
        }

        return $aceCount;
    }
}
