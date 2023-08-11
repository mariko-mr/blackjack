<?php

namespace Blackjack;

class Card
{
    public function __construct(private string $suit, private string $number)
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
}
