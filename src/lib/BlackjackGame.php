<?php

namespace Blackjack;

require_once __DIR__ . ('/Deck.php');
require_once __DIR__ . ('/HandJudger.php');

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

        // 入力値のバリデーション処理
        $stdin = trim(fgets(STDIN));
        $validatedStdin = $this->validateInput($stdin);

        // プレイヤーがカードを引くターン
        while ($validatedStdin === 'y') {
            $this->playerTurn($deck);

            // 入力値のバリデーション処理
            $stdin = trim(fgets(STDIN));
            $validatedStdin = $this->validateInput($stdin);
        }

        // ディーラーがカードを引くターン
        if ($validatedStdin === 'N') {
            $this->DealerTurn($deck, $dealerCards);
        }

        // HandJudgerを新規作成する
        $handJudger = new HandJudger();

        // 判定して終了する
        $this->showDown($handJudger);
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
     * プレイヤーのターン
     *
     * @param Card[] $playerCards
     */
    private function playerTurn(Deck $deck): void
    {
        $playerCards = $this->player->drawCards($deck, self::DRAW_ONE);
        $playerLastDrawnCard = $playerCards[array_key_last($playerCards)];
        $playerTotalScore = $this->player->getTotalScore();

        echo PHP_EOL .
            'あなたの引いたカードは' .
            $playerLastDrawnCard->getSuit() . 'の' .
            $playerLastDrawnCard->getNumber() . 'です。' . PHP_EOL;

        if ($playerTotalScore <= 21) { // 合計が21以内の場合は続行
            echo 'あなたの現在の得点は' .
                $playerTotalScore .
                'です。カードを引きますか？（y/N）' . PHP_EOL;
        } elseif ($playerTotalScore > 21) { // 合計が21を超えたら終了
            echo 'あなたの現在の得点は' .
                $playerTotalScore .
                'です。バーストしました。' . PHP_EOL . PHP_EOL .
                '残念！あなたの負けです。' . PHP_EOL;
            exit;
        }
    }

    /**
     * ディーラーのターン
     *
     * @param Deck   $deck
     * @param Card[] $dealerCards
     */
    private function DealerTurn(Deck $deck, array $dealerCards): void
    {
        // ディーラーターンのメッセージを表示
        $this->showDealerMsg($dealerCards);

        // 合計が17以上になるまでカードを引き続ける
        while ($this->dealer->getTotalScore() < 17) {
            $dealerCards = $this->dealer->drawCards($deck, self::DRAW_ONE);
            $this->showDealerDrawnMsg($dealerCards);
        }
    }

    /**
     * ディーラーターンのメッセージを表示
     *
     * @param Card[] $dealerCards
     */
    private function showDealerMsg(array $dealerCards): void
    {
        echo PHP_EOL .
            'ディーラーの引いた2枚目のカードは' .
            $dealerCards[1]->getSuit() . 'の' .
            $dealerCards[1]->getNumber() . 'でした。' . PHP_EOL .
            'ディーラーの現在の得点は' .
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

        echo PHP_EOL .
            'ディーラーがカードを引きます。' . PHP_EOL .
            'ディーラーの引いたカードは' .
            $dealerLastDrawnCard->getSuit() . 'の' .
            $dealerLastDrawnCard->getNumber() . 'です。' . PHP_EOL . PHP_EOL;
    }

    /**
     * 判定ッセージを表示
     *
     * @param HandJudger $handJudger
     * @return void
     */
    private function showDown(HandJudger $handJudger): void
    {
        $playerTotalScore = $this->player->getTotalScore();
        $dealerTotalScore = $this->dealer->getTotalScore();

        // 得点発表
        echo '判定に移ります。' . PHP_EOL . PHP_EOL .
            '----- 判定結果 -----' . PHP_EOL .
            'あなたの得点は' .
            $playerTotalScore . 'です。' . PHP_EOL .
            'ディーラーの得点は' .
            $dealerTotalScore . 'です。' . PHP_EOL . PHP_EOL;

        // 勝敗判定
        $winnerMsg = $handJudger->determineWinner($playerTotalScore, $dealerTotalScore);
        echo $winnerMsg . PHP_EOL . PHP_EOL;

        // ゲームを終了する
        echo 'ブラックジャックを終了します。' . PHP_EOL;
        exit;
    }

    /**
     * 入力値のバリデーション処理
     *
     * @param string  $stdin
     * @return string $validatedStdin 'y' or 'N'
     */
    private function validateInput(string $stdin): string
    {
        while (!($stdin === 'y' || $stdin === 'N')) {
            echo PHP_EOL .
                'yまたはNを入力してください。' . PHP_EOL;

            $stdin = trim(fgets(STDIN));
        }
        return $stdin;
    }
}

/*
php lib/Main.php
*/
