<?php

namespace Blackjack;

class Card
{
    public function __construct(private string $suit, private string $number, private int $score)
    {
    }

    /**
     * カードのスートを取得
     *
     * @return string
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * カードの数を取得
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * カードの得点を取得
     *
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }
}
