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
        // 引いたカードを持ち札に加える
        $drawnCards = $deck->drawCards($drawNum);
        $this->playerCards = array_merge($this->playerCards, $drawnCards);

        // 合計点を更新する
        $this->playerTotalScore = $this->updateTotalScore($drawnCards);

        return $this->playerCards;
    }

    public function getTotalScore(): int
    {
        return $this->playerTotalScore;
    }

    private function updateTotalScore(array $drawnCards): int
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
