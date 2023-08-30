<?php

namespace Blackjack;

class AceRule
{
    /**
     * @var int Aで減算した回数をカウント
     */
    private int $aceReductionCount;

    public function __construct() {
        // 初期化処理
        $this->aceReductionCount = 0;
    }

    /**
     * Aの得点を11から1へと減算する
     *
     * @return int
     */
    public function subtractAceScore($participantRule, int $totalScore, array $cards): int
    {
        $aceSubtraction = 0;

        // バーストし、かつAがあれば得点を減算して調整
        if ($participantRule::isBust($totalScore) && $this->hasAce($cards)) {

            // Aの出現回数を計算
            $aceCount = $this->countAce($cards);

            // Aの出現回数以上に減算しないように条件を設定
            while ($participantRule::isBust($totalScore) && $aceCount > $this->aceReductionCount) {

                // Aの得点分を減算
                $aceSubtraction -= 10;

                // Aによる減算回数をカウント
                $this->aceReductionCount++;
            }
        }

        return $aceSubtraction;
    }


    private function hasAce(array $cards): bool
    {
        foreach ($cards as $card) {
            if ($card->getNumber() == 'A') {
                return true;
            }
        }

        return false;
    }

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
