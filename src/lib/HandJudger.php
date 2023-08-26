<?php

namespace Blackjack;

class HandJudger
{
    public function determineWinner(...$scores): void
    {
        $winner = "";
        $participantScores = [
            ['participant' => 'プレイヤー', 'score' => 20],
            ['participant' => 'ディーラー', 'score' => 20],
            ['participant' => 'CPU1', 'score' => 20],
            ['participant' => 'CPU2', 'score' => 20],
        ];

        // バーストした参加者を表示

        //


        // if ($dealerTotalScore > 21) {
        //     return 'ディーラーはバーストしました。あなたの勝ちです！';
        // }

        // if ($dealerTotalScore === $playerTotalScore) {
        //     return '同点でした。この勝負は引き分けとします。';
        // } elseif ($playerTotalScore > $dealerTotalScore) {
        //     return 'あなたの勝ちです！';
        // } elseif ($playerTotalScore < $dealerTotalScore) {
        //     return 'ディーラーの勝ちです。残念！';
        // }
    }
}
