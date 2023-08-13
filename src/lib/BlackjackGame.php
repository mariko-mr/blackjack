<?php

namespace Blackjack;

require_once __DIR__ . ('/Deck.php');

class BlackjackGame
{
    private const DRAW_TWO = 2;
    private const DRAW_ONE = 1;

    public function __construct(private Player $player, private Dealer $dealer)
    {
    }

    public function startGame()
    {
        // デッキを新規作成する
        $deck = new Deck();

        // プレイヤーとディーラーが始めに2枚ずつカードを引く
        $playerCards = $this->player->drawCards($deck, self::DRAW_TWO);
        $dealerCards = $this->dealer->drawCards($deck, self::DRAW_TWO);

        // スタート時のメッセージを表示
        $this->showStartMessage($playerCards, $dealerCards);
        $stdin = trim(fgets(STDIN));

        // プレイヤーがカードを引くターン
        while ($stdin === 'y') {
            $playerCards = $this->player->drawCards($deck, self::DRAW_ONE);
            $this->showPlayerMessage($playerCards, $stdin);
        }

        // ディーラーがカードを引くターン
        // 合計が17以上になるまで引き続ける
        $this->showDealerMessage($dealerCards);

        while ($this->dealer->getTotalScore($dealerCards) < 17) {
            $dealerCards = $this->dealer->drawCards($deck, self::DRAW_ONE);
            $this->showDealerDrawnMessage($dealerCards);
        }

        // 判定して終了する
        $this->showJudgementMessage($playerCards, $dealerCards);
    }

    private function showStartMessage($playerCards, $dealerCards)
    {
        echo 'ブラックジャックを開始します。' . PHP_EOL . PHP_EOL;

        // プレイヤーのカードを表示
        foreach ($playerCards as $card) {
            echo 'あなたの引いたカードは' .
                $card->getSuit() . 'の' .
                $card->getNumber() . 'です。' . PHP_EOL;
        }

        // ディーラーのカードを表示
        echo 'ディーラーの引いたカードは' .
            $dealerCards[0]->getSuit() . 'の' .
            $dealerCards[0]->getNumber() . 'です。' . PHP_EOL;
        echo 'ディーラーの引いた2枚目のカードはわかりません。' . PHP_EOL . PHP_EOL;

        // プレイヤーの合計点
        echo 'あなたの現在の得点は' .
            $this->player->getTotalScore($playerCards) .
            'です。カードを引きますか？（y/N）' . PHP_EOL;
    }

    private function showPlayerMessage($playerCards, &$stdin)
    {
        echo 'あなたの引いたカードは' .
            $playerCards[array_key_last($playerCards)]->getSuit() . 'の' .
            $playerCards[array_key_last($playerCards)]->getNumber() . 'です。' . PHP_EOL;

        if ($this->player->getTotalScore($playerCards) <= 21) { // 合計が21以内の場合
            echo 'あなたの現在の得点は' .
                $this->player->getTotalScore($playerCards) .
                'です。カードを引きますか？（y/N）' . PHP_EOL;
            $stdin = trim(fgets(STDIN));
        } elseif ($this->player->getTotalScore($playerCards) > 21) { // 合計が21を超えたら終了
            echo 'あなたの現在の得点は' .
                $this->player->getTotalScore($playerCards) .
                'です。残念！あなたの負けです。' . PHP_EOL;
            exit;
        }
    }

    private function showDealerMessage($dealerCards)
    {
        echo 'ディーラーの引いた2枚目のカードは' .
            $dealerCards[1]->getSuit() . 'の' .
            $dealerCards[1]->getNumber() . 'でした。' . PHP_EOL;
        echo 'ディーラーの現在の得点は' .
            $this->dealer->getTotalScore($dealerCards) . 'です。' . PHP_EOL . PHP_EOL;
    }

    private function showDealerDrawnMessage($dealerCards)
    {
        echo 'ディーラーの引いたカードは' .
            $dealerCards[array_key_last($dealerCards)]->getSuit() . 'の' .
            $dealerCards[array_key_last($dealerCards)]->getNumber() . 'です。' . PHP_EOL;
    }

    private function showJudgementMessage($playerCards, $dealerCards)
    {
        echo '判定に移ります。' . PHP_EOL . PHP_EOL;

        echo '----- 判定結果 -----' . PHP_EOL;
        echo 'あなたの得点は' .
            $this->player->getTotalScore($playerCards) . 'です。' . PHP_EOL;
        echo 'ディーラーの得点は' .
            $this->dealer->getTotalScore($dealerCards) . 'です。' . PHP_EOL . PHP_EOL;

        // 判定
        if ($this->dealer->getTotalScore($dealerCards) > 21) {
            echo 'あなたの勝ちです！' . PHP_EOL . PHP_EOL;
            echo 'ブラックジャックを終了します。' . PHP_EOL;
            exit;
        }

        if ($this->dealer->getTotalScore($dealerCards) === $this->player->getTotalScore($playerCards)) {
            echo '同点でした。この勝負は引き分けとします。' . PHP_EOL . PHP_EOL;
            echo 'ブラックジャックを終了します。' . PHP_EOL;
            exit;
        }

        if ($this->player->getTotalScore($playerCards) > $this->dealer->getTotalScore($dealerCards)) {
            echo 'あなたの勝ちです！' . PHP_EOL . PHP_EOL;
        } else {
            echo 'ディーラーの勝ちです。残念！' . PHP_EOL . PHP_EOL;
        }

        echo 'ブラックジャックを終了します。' . PHP_EOL;
        exit;
    }
}

/*
php lib/Main.php

ブラックジャックを開始します。

あなたの引いたカードはハートの7です。
あなたの引いたカードはクラブの8です。
ディーラーの引いたカードはダイヤのQです。
ディーラーの引いた2枚目のカードはわかりません。

あなたの現在の得点は15です。カードを引きますか？（y/N）
Y
あなたの引いたカードはスペードの５です。
あなたの現在の得点は20です。カードを引きますか？（y/N）
N

ディーラーの引いた2枚目のカードはダイヤの2でした。
ディーラーの現在の得点は12です。

(合計17未満の場合)
ディーラーの引いたカードはハートのKです。

(合計17以上の場合)
判定に移ります。

----- 判定結果 -----
あなたの得点は20です。
ディーラーの得点は22です。
あなたの勝ちです！

ブラックジャックを終了します。
*/
