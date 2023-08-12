<?php

namespace Blackjack;

class Card
{
    public function __construct(private string $suit, private string $number, private int $score)
    {
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * ここを追加
     */
    public function getScore(): int
    {
        return $this->score;
    }
}
