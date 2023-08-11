<?php

namespace Blackjack;

class Dealer
{
    private array $dealerCards;

    public function __construct()
    {
        // 初期化処理
        $this->dealerCards = [];
    }

    public function drawCards(Deck $deck, int $drawNum): array
    {
        $drawnCards = $deck->drawCards($drawNum);
        $this->dealerCards = array_merge($this->dealerCards, $drawnCards);

        return $this->dealerCards;
    }
}

/*
$dealerCards = [
    new CARD(),
    new CARD(),
]
*/
