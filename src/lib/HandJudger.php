<?php

namespace Blackjack;

class HandJudger
{
    public function determineWinner(int $playerTotalScore, int $dealerTotalScore): string
    {
        if ($dealerTotalScore > 21) {
            return 'ディーラーはバーストしました。あなたの勝ちです！';
        }

        if ($dealerTotalScore === $playerTotalScore) {
            return '同点でした。この勝負は引き分けとします。';
        } elseif ($playerTotalScore > $dealerTotalScore) {
            return 'あなたの勝ちです！';
        } elseif ($playerTotalScore < $dealerTotalScore) {
            return 'ディーラーの勝ちです。残念！';
        }
    }
}
