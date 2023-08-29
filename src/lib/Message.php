<?php

namespace Blackjack;

class Message
{
    /**
     * 設定メッセージを表示
     *
     */
    public function showSetupMsg(): void
    {
        echo 'プレイヤーの人数(1人~3人)を選択してください(1~3の数値を入力)' . PHP_EOL;
    }

    /**
     * 開始時のメッセージを表示
     *
     * @param HumPlayer $player
     * @param array     $cpuPlayers
     * @param Dealer    $dealer
     */
    public function showStartMsg(HumPlayer $player, array $cpuPlayers, Dealer $dealer): void
    {
        echo PHP_EOL .
            'ブラックジャックを開始します。' . PHP_EOL . PHP_EOL;

        // プレイヤーのカードを表示
        foreach ($player->getCards() as $card) {
            echo 'あなたの引いたカードは' .
                $card->getSuit() . 'の' .
                $card->getNumber() . 'です。' . PHP_EOL;
        }
        echo PHP_EOL;

        // CPUプレイヤーのカードを表示
        if (count($cpuPlayers) >= 1) {
            foreach ($cpuPlayers as $num => $cpuPlayer) {
                foreach ($cpuPlayer->getCards() as $card) {
                    echo 'CPUプレイヤー' . $num . 'の引いたカードは' .
                        $card->getSuit() . 'の' .
                        $card->getNumber() . 'です。' . PHP_EOL;
                }
                echo PHP_EOL;
            }
        }

        // ディーラーのカードを表示
        echo 'ディーラーの引いたカードは' .
            ($dealer->getCards())[0]->getSuit() . 'の' .
            ($dealer->getCards())[0]->getNumber() . 'です。' . PHP_EOL .
            'ディーラーの引いた2枚目のカードはわかりません。' . PHP_EOL . PHP_EOL;

        // プレイヤーの合計点
        echo 'あなたの現在の得点は' .
            $player->getTotalScore() .
            'です。カードを引きますか？（y/N）' . PHP_EOL;
    }

    /**
     * プレイヤーターンのメッセージを表示
     *
     * @param Card $playerLastDrawnCard
     * @param int  $playerTotalScore
     */
    public function showPlayerTurnMsg(Card $playerLastDrawnCard, int $playerTotalScore): void
    {
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
     * CPUがカードを引くメッセージを表示
     *
     * @param int  $num
     * @param Card $cpuLastDrawnCard
     */
    public function showCpuDrawnMsg(int $num, Card $cpuLastDrawnCard): void
    {
        echo PHP_EOL .
            'CPUプレイヤー' . $num . 'がカードを引きます。' . PHP_EOL .
            'CPUプレイヤー' . $num . 'の引いたカードは' .
            $cpuLastDrawnCard->getSuit() . 'の' .
            $cpuLastDrawnCard->getNumber() . 'です。' . PHP_EOL;
    }

    /**
     * ディーラーが引いた2枚目のカードを表示
     *
     * @param Dealer $dealer
     */
    public function showDealerTurnMsg(Dealer $dealer): void
    {
        echo PHP_EOL .
            'ディーラーの引いた2枚目のカードは' .
            ($dealer->getCards())[1]->getSuit() . 'の' .
            ($dealer->getCards())[1]->getNumber() . 'でした。' . PHP_EOL .
            'ディーラーの現在の得点は' .
            $dealer->getTotalScore() . 'です。' . PHP_EOL;
    }

    /**
     * ディーラーがカードを引くメッセージを表示
     *
     * @param Card $dealerLastDrawnCard
     */
    public function showDealerDrawnMsg(Card $dealerLastDrawnCard): void
    {
        echo PHP_EOL .
            'ディーラーがカードを引きます。' . PHP_EOL .
            'ディーラーの引いたカードは' .
            $dealerLastDrawnCard->getSuit() . 'の' .
            $dealerLastDrawnCard->getNumber() . 'です。' . PHP_EOL;
    }

    /**
     * 得点発表メッセージを表示
     *
     * @param array $participants
     */
    public function showTotalScoreMsg(array $participants): void
    {
        echo  PHP_EOL .
            '判定に移ります。' . PHP_EOL . PHP_EOL .
            '---------- 得点発表 ----------' . PHP_EOL;

        foreach ($participants as $participant) {
            echo $participant['name'] . 'の得点は' .
                $participant['total'] . 'です。' . PHP_EOL;
        }

        echo '-----------------------------' . PHP_EOL . PHP_EOL;
    }

    /**
     * 勝敗判定メッセージを表示
     *
     * @param array $results
     */
    public function showJudgmentMsg(array $results): void
    {
        echo '---------- 結果発表 ----------' . PHP_EOL;

        // バーストした参加者を表示
        foreach ($results as $key => $result) {
            if ($result === 'バースト') {
                echo $key . 'はバーストしました。' . PHP_EOL;

                // バーストした人を$resultsから除く
                unset($results[$key]);
            }
        }

        // ディーラー以外の結果を表示
        foreach ($results as $key => $result) {
            if ($key !== 'ディーラー') {
                if ($result === '引き分け') {
                    echo $key . 'は引き分けです。' . PHP_EOL;
                } else {
                    echo $key . 'の' . $result . 'です。' . PHP_EOL;
                }
            }
        }

        echo '------------------------------' . PHP_EOL . PHP_EOL;
    }

    /**
     * 終了メッセージを表示
     *
     */
    public function showExitMsg(): void
    {
        echo 'ブラックジャックを終了します。' . PHP_EOL;
    }

    /**
     * yes or NO 入力時のメッセージを表示
     *
     */
    public function showValidateYesNoErrorMsg(): void
    {
        echo PHP_EOL .
            'yまたはNを入力してください。' . PHP_EOL;
    }

    /**
     * CPUプレイヤー人数入力時のメッセージを表示
     *
     */
    public function showValidateNumberErrorMsg(): void
    {
        echo PHP_EOL .
            '1~3の数値を入力してください。' . PHP_EOL;
    }
}
