<?php

namespace Blackjack;

class HandJudger
{
    public function determineWinner(int $playerTotalScore, int $dealerTotalScore): string
    {
        $winnerMsg = '';

        if ($dealerTotalScore > 21) {
            $winnerMsg = 'ディーラーはバーストしました。あなたの勝ちです！';
        }

        if ($dealerTotalScore === $playerTotalScore) {
            $winnerMsg = '同点でした。この勝負は引き分けとします。';
        } elseif ($playerTotalScore > $dealerTotalScore) {
            $winnerMsg = 'あなたの勝ちです！';
        } elseif ($playerTotalScore < $dealerTotalScore) {
            $winnerMsg = 'ディーラーの勝ちです。残念！';
        }

        return $winnerMsg;
    }
}
