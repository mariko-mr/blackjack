<?php

namespace Blackjack;

class HandJudger
{
    /**
     * 勝者を決定する
     *
     * @param  array<string, array<string, mixed>> $participants
     * @return array<string, string> $results ['participant' => 'result', ...]
     */
    public function determineWinner($participants): array
    {
        $results = [];

        // totalが21を超えた参加者はバースト
        foreach ($participants as $key => $participant) {
            if ($participant['obj']->isBust($participant['total'])) {
                $results[$participant['name']] = 'バースト';

                // バーストした人を$participantsから除く
                unset($participants[$key]);
            }
        }

        // ディーラーがバーストしている場合、残った参加者は勝ち
        if (!isset($participants['dealer'])) {
            foreach ($participants as $key => $participant) {
                $results[$participant['name']] = '勝ち';
            }
            return $results;
        }

        // ディーラーがバーストしてない場合
        foreach ($participants as $key => $participant) {
            if ($this->isTie($participants, $participant)) {
                $results[$participant['name']] = '引き分け';
            } elseif ($this->isHigherScore($participants, $participant)) {
                $results[$participant['name']] = '勝ち';
            } else {
                $results[$participant['name']] = '負け';
            }
        }
        return $results;
    }

    /**
     * ディーラーと同点か調べる
     *
     * @param  array<string, array<string, mixed>> $participants
     * @param  array<string, string> $participant
     * @return bool
     */
    private function isTie(array $participants, array $participant): bool
    {
        return $participant['total'] === $participants['dealer']['total'];
    }

    /**
     * ディーラーより高得点か調べる
     *
     * @param  array<string, array<string, mixed>> $participants
     * @param  array<string, string> $participant
     * @return bool
     */
    private function isHigherScore(array $participants, array $participant): bool
    {
        return $participant['total'] > $participants['dealer']['total'];
    }
}
