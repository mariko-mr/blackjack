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

    public function startGame(): void
    {
        // デッキを新規作成する
        $deck = new Deck();

        // プレイヤーとディーラーが始めに2枚ずつカードを引く
        $playerCards = $this->player->drawCards($deck, self::DRAW_TWO);
        $dealerCards = $this->dealer->drawCards($deck, self::DRAW_TWO);

        // スタート時のメッセージを表示
        $this->showStartMsg($playerCards, $dealerCards);
        $stdin = trim(fgets(STDIN));

        // プレイヤーがカードを引くターン
        while ($stdin === 'y') {
            $playerCards = $this->player->drawCards($deck, self::DRAW_ONE);
            $this->showPlayerMsg($playerCards, $stdin);
        }

        // ディーラーがカードを引くターン
        // 合計が17以上になるまで引き続ける
        if ($stdin === 'N') {
            $this->showDealerMsg($dealerCards);

            while ($this->dealer->getTotalScore($dealerCards) < 17) {
                $dealerCards = $this->dealer->drawCards($deck, self::DRAW_ONE);
                $this->showDealerDrawnMsg($dealerCards);
            }

            // 判定して終了する
            $this->showJudgementMsg($playerCards, $dealerCards);
        }

        echo 'y/N を入力してください。';
        exit;
    }

    private function showStartMsg($playerCards, $dealerCards): void
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

    /**
     * ここを修正
     * $playerTotalScoreに置き換え
     */
    private function showPlayerMsg($playerCards, &$stdin): void
    {
        $playerLastDrawnCard = $playerCards[array_key_last($playerCards)];
        $playerTotalScore = $this->player->getTotalScore($playerCards);

        echo 'あなたの引いたカードは' .
            $playerLastDrawnCard->getSuit() . 'の' .
            $playerLastDrawnCard->getNumber() . 'です。' . PHP_EOL;

        if ($playerTotalScore <= 21) { // 合計が21以内の場合は続行
            echo 'あなたの現在の得点は' .
                $playerTotalScore .
                'です。カードを引きますか？（y/N）' . PHP_EOL;
            $stdin = trim(fgets(STDIN));
        } elseif ($playerTotalScore > 21) { // 合計が21を超えたら終了
            echo 'あなたの現在の得点は' .
                $playerTotalScore .
                'です。バーストしました。' . PHP_EOL . PHP_EOL .
                '残念！あなたの負けです。' . PHP_EOL;
            exit;
        }
    }

    private function showDealerMsg($dealerCards): void
    {
        echo 'ディーラーの引いた2枚目のカードは' .
            $dealerCards[1]->getSuit() . 'の' .
            $dealerCards[1]->getNumber() . 'でした。' . PHP_EOL;
        echo 'ディーラーの現在の得点は' .
            $this->dealer->getTotalScore($dealerCards) . 'です。' . PHP_EOL . PHP_EOL;
    }

    private function showDealerDrawnMsg($dealerCards): void
    {
        $dealerLastDrawnCard = $dealerCards[array_key_last($dealerCards)];

        echo 'ディーラーがカードを引きます。' . PHP_EOL;
        echo 'ディーラーの引いたカードは' .
            $dealerLastDrawnCard->getSuit() . 'の' .
            $dealerLastDrawnCard->getNumber() . 'です。' . PHP_EOL . PHP_EOL;
    }

    /**
     * ここを修正
     * $playerTotalScoreに置き換え
     * $dealerTotalScoreに置き換え
     */
    private function showJudgementMsg($playerCards, $dealerCards): void
    {
        $playerTotalScore = $this->player->getTotalScore($playerCards);
        $dealerTotalScore = $this->dealer->getTotalScore($dealerCards);

        // 得点発表
        echo '判定に移ります。' . PHP_EOL . PHP_EOL;

        echo '----- 判定結果 -----' . PHP_EOL;
        echo 'あなたの得点は' .
            $playerTotalScore . 'です。' . PHP_EOL;
        echo 'ディーラーの得点は' .
            $dealerTotalScore . 'です。' . PHP_EOL . PHP_EOL;

        // 勝敗判定
        if ($dealerTotalScore > 21) {
            echo 'ディーラーはバーストしました。あなたの勝ちです！' . PHP_EOL . PHP_EOL;
            echo 'ブラックジャックを終了します。' . PHP_EOL;
            exit;
        }

        if ($dealerTotalScore === $playerTotalScore) {
            echo '同点でした。この勝負は引き分けとします。' . PHP_EOL . PHP_EOL;
            echo 'ブラックジャックを終了します。' . PHP_EOL;
            exit;
        }

        if ($playerTotalScore > $dealerTotalScore) {
            echo 'あなたの勝ちです！' . PHP_EOL . PHP_EOL;
        } else {
            echo 'ディーラーの勝ちです。残念！' . PHP_EOL . PHP_EOL;
        }

        // ゲームを終了する
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
