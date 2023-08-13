<?php

namespace Blackjack;

class Dealer
{
    private array $dealerCards;
    private int $dealerTotalScore;

    public function __construct()
    {
        // 初期化処理
        $this->dealerCards = [];
        $this->dealerTotalScore = 0;
    }

    public function drawCards(Deck $deck, int $drawNum): array
    {
        // 引いたカードを持ち札に加える
        $drawnCards = $deck->drawCards($drawNum);
        $this->dealerCards = array_merge($this->dealerCards, $drawnCards);

        // 合計点を更新する
        $this->dealerTotalScore = $this->updateTotalScore($drawnCards);

        return $this->dealerCards;
    }

    public function getTotalScore(): int
    {
        return $this->dealerTotalScore;
    }

    private function updateTotalScore(array $drawnCards): int
    {
        foreach ($drawnCards as $drawnCard) {
            $this->dealerTotalScore += $drawnCard->getScore();
        }

        return $this->dealerTotalScore;
    }
}

/*
$dealerCards = [
    new CARD(),
    new CARD(),
]
*/
