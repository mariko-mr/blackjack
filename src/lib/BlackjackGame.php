<?php

namespace Blackjack;

require_once __DIR__ . ('/Deck.php');

class BlackjackGame
{
    private const START_DRAW_NUM = 2;

    public function __construct(private Player $player, private Dealer $dealer)
    {
    }

    public function startGame()
    {
        $deck = new Deck();

        // プレイヤーがカードを引く
        $playerCards = $this->player->drawCards($deck, self::START_DRAW_NUM);
        // ディーラーがカードを引く
        $dealerCards = $this->dealer->drawCards($deck, self::START_DRAW_NUM);

        $this->getStartMessage($playerCards, $dealerCards);
    }

    private function getStartMessage($playerCards, $dealerCards)
    {
        echo 'ブラックジャックを開始します。' . PHP_EOL;

        // プレイヤーのカードを表示
        foreach ($playerCards as $card) {
            echo 'あなたの引いたカードは' . $card->getSuit() . 'の' . $card->getNumber() . 'です。' . PHP_EOL;
        }

        // ディーラーのカードを表示
        echo 'ディーラーの引いたカードは' . $dealerCards[0]->getSuit() . 'の' . $dealerCards[0]->getNumber() . 'です。' . PHP_EOL;
        echo 'ディーラーの引いた2枚目のカードはわかりません。' . PHP_EOL;

        // 合計点
        echo 'あなたの現在の得点は' . $this->player->calTotalScore($playerCards) . 'です。カードを引きますか？（Y/N）' . PHP_EOL;
    }
}
