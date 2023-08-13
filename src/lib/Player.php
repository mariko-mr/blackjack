<?php

namespace Blackjack;

class Player
{
    private array $playerCards;
    private int $playerTotalScore;

    public function __construct()
    {
        // 初期化処理
        $this->playerCards = [];
        $this->playerTotalScore = 0;
    }

    public function drawCards(Deck $deck, int $drawNum): array
    {
        // カードを引く
        $drawnCards = $deck->drawCards($drawNum);
        $this->playerCards = array_merge($this->playerCards, $drawnCards);

        // 合計点を更新する
        $this->playerTotalScore = $this->calTotalScore($drawnCards);

        return $this->playerCards;
    }

    public function getTotalScore(): int
    {
        return $this->playerTotalScore;
    }

    private function calTotalScore(array $drawnCards): int
    {
        foreach ($drawnCards as $drawnCard) {
            $this->playerTotalScore += $drawnCard->getScore();
        }

        return $this->playerTotalScore;
    }
}

/*
$playerCards = [
    new CARD(),
    new CARD(),
]
*/
