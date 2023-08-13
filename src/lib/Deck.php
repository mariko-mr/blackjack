<?php

namespace Blackjack;

require_once __DIR__ . ('/Card.php');

class Deck
{
    private const SUITS = ['ハート', 'ダイヤ', 'スペード', 'クラブ'];
    private const CARD_NUM_AND_SCORES = [
        ['num' => 'A',  'score' => 1],
        ['num' => '2',  'score' => 2],
        ['num' => '3',  'score' => 3],
        ['num' => '4',  'score' => 4],
        ['num' => '5',  'score' => 5],
        ['num' => '6',  'score' => 6],
        ['num' => '7',  'score' => 7],
        ['num' => '8',  'score' => 8],
        ['num' => '9',  'score' => 9],
        ['num' => '10', 'score' => 10],
        ['num' => 'J',  'score' => 10],
        ['num' => 'Q',  'score' => 10],
        ['num' => 'K',  'score' => 10],
    ];
    private array $cards;

    public function __construct()
    {
        // 52枚のカードを作りシャッフルする
        foreach (self::SUITS as $suit) {
            foreach (self::CARD_NUM_AND_SCORES as $card) {
                $this->cards[] = new Card($suit, $card['num'], $card['score']);
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
