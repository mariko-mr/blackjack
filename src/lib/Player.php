<?php

namespace Blackjack;

class Player
{
    private array $playerCards;

    public function __construct()
    {
        // 初期化処理
        $this->playerCards = [];
    }

    public function drawCards(Deck $deck, int $drawNum): array
    {
        $drawnCards = $deck->drawCards($drawNum);
        $this->playerCards = array_merge($this->playerCards, $drawnCards);

        return $this->playerCards;
    }

    public function calTotalScore(array $playerCards): int
    {
        $totalScore = 0;

        foreach ($playerCards as $playerCard) {
            $totalScore += $playerCard->getScore();
        }

        return $totalScore;
    }
}

/*
$playerCards = [
    new CARD(),
    new CARD(),
]
*/
