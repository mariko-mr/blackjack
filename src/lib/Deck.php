<?php

namespace Blackjack;

require_once __DIR__ . ('/Card.php');

class Deck
{
    private const SUITS = ['ハート', 'ダイヤ', 'スペード', 'クラブ'];
    private const CARD_NUMBER = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
    private array $cards;

    public function __construct()
    {
        // 52枚のカードを作りシャッフルする
        foreach (self::SUITS as $suit) {
            foreach (self::CARD_NUMBER as $number) {
                $this->cards[] = new Card($suit, $number);
            }
        }

        shuffle($this->cards);
    }

    public function drawCards(int $drawNum): array
    {
        // カードを引いたら配列から要素を削除する
        return array_splice($this->cards, 0, $drawNum);
    }
}
