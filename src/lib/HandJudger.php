<?php

namespace Blackjack;

class HandJudger
{
    /**
     * 勝者を決定する
     *
     * @param  array  $participants
     * @return string $winner
     */
    public function determineWinner(...$participants): string
    {
        $winner = "";

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

        return 'winner';
    }
}
