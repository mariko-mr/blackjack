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

    /**
     * ブラックジャックゲームを開始
     */
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

        // 例外エラー表示
        while ($stdin !== 'y' && $stdin !== 'N') {
            echo 'yまたはNを入力してください。';
            $stdin = trim(fgets(STDIN));
        }

        // プレイヤーがカードを引くターン
        while ($stdin === 'y') {
            $playerCards = $this->player->drawCards($deck, self::DRAW_ONE);
            $this->showPlayerMsg($playerCards, $stdin);
        }

        // ディーラーがカードを引くターン
        // 合計が17以上になるまで引き続ける
        if ($stdin === 'N') {
            $this->showDealerMsg($dealerCards);

            while ($this->dealer->getTotalScore() < 17) {
                $dealerCards = $this->dealer->drawCards($deck, self::DRAW_ONE);
                $this->showDealerDrawnMsg($dealerCards);
            }

            // 判定して終了する
            $this->showJudgementMsg();
        }
    }

    /**
     * 開始時のメッセージを表示
     *
     * @param Card[] $playerCards
     * @param Card[] $dealerCards
     */
    private function showStartMsg(array $playerCards, array $dealerCards): void
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
            $this->player->getTotalScore() .
            'です。カードを引きますか？（y/N）' . PHP_EOL;
    }

    /**
     * プレイヤーターンのメッセージを表示
     *
     * @param Card[] $playerCards
     */
    private function showPlayerMsg(array $playerCards, string &$stdin): void
    {
        $playerLastDrawnCard = $playerCards[array_key_last($playerCards)];
        $playerTotalScore = $this->player->getTotalScore();

        echo 'あなたの引いたカードは' .
            $playerLastDrawnCard->getSuit() . 'の' .
            $playerLastDrawnCard->getNumber() . 'です。' . PHP_EOL;

        if ($playerTotalScore <= 21) { // 合計が21以内の場合は続行
            echo 'あなたの現在の得点は' .
                $playerTotalScore .
                'です。カードを引きますか？（y/N）' . PHP_EOL;
            $stdin = trim(fgets(STDIN));

            while ($stdin !== 'y' && $stdin !== 'N') {
                echo 'yまたはNを入力してください。';
                $stdin = trim(fgets(STDIN));
            }
        } elseif ($playerTotalScore > 21) { // 合計が21を超えたら終了
            echo 'あなたの現在の得点は' .
                $playerTotalScore .
                'です。バーストしました。' . PHP_EOL . PHP_EOL .
                '残念！あなたの負けです。' . PHP_EOL;
            exit;
        }
    }

    /**
     * ディーラーターンのメッセージを表示
     *
     * @param Card[] $dealerCards
     */
    private function showDealerMsg(array $dealerCards): void
    {
        echo 'ディーラーの引いた2枚目のカードは' .
            $dealerCards[1]->getSuit() . 'の' .
            $dealerCards[1]->getNumber() . 'でした。' . PHP_EOL;
        echo 'ディーラーの現在の得点は' .
            $this->dealer->getTotalScore() . 'です。' . PHP_EOL . PHP_EOL;
    }

    /**
     * ディーラーがカードを引くメッセージを表示
     *
     * @param Card[] $dealerCards
     */
    private function showDealerDrawnMsg(array $dealerCards): void
    {
        $dealerLastDrawnCard = $dealerCards[array_key_last($dealerCards)];

        echo 'ディーラーがカードを引きます。' . PHP_EOL;
        echo 'ディーラーの引いたカードは' .
            $dealerLastDrawnCard->getSuit() . 'の' .
            $dealerLastDrawnCard->getNumber() . 'です。' . PHP_EOL . PHP_EOL;
    }

    /**
     * 判定ッセージを表示
     *
     * @return void
     */
    private function showJudgementMsg(): void
    {
        $playerTotalScore = $this->player->getTotalScore();
        $dealerTotalScore = $this->dealer->getTotalScore();

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
        } elseif ($playerTotalScore < $dealerTotalScore) {
            'ディーラーの勝ちです。残念！' . PHP_EOL . PHP_EOL;
        }

        // ゲームを終了する
        echo 'ブラックジャックを終了します。' . PHP_EOL;
        exit;
    }
}

/*
php lib/Main.php
*/
