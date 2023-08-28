<?php

namespace Blackjack;

class HandJudger
{
    /**
     * 勝者を決定する
     *
     * @param  array  $participants
     * @return string $results
     */
    public function determineWinner($participants): array
    {
        $results = [];

        // totalが21を超えた参加者はバースト
        foreach ($participants as $key => $participant) {
            if ($participant['total'] > 21) {
                $results[$participant['name']] = 'burst';

                // バーストした人を$participantsから除く
                unset($participants[$key]);
            }
        }

        // ディーラーがバーストしている場合、残った参加者は勝ち
        if (!isset($participants['dealer'])) {
            foreach ($participants as $key => $participant) {
                $results[$participant['name']] = 'win';
            }
            return $results;
        }

        // ディーラーがバーストしてない場合
        foreach ($participants as $key => $participant) {
            if ($this->isTie($participants, $participant)) {   // ディーラーと同点なら引き分け
                $results[$participant['name']] = 'tie';
            } elseif ($this->isHigherScore($participants, $participant)) { // ディーラーより高得点なら勝ち
                $results[$participant['name']] = 'win';
            } else {
                $results[$participant['name']] = 'lose';
            }
        }
        return $results;
    }

    private function isTie(array $participants, array $participant): bool
    {
        return $participant['total'] === $participants['dealer']['total'];
    }

    private function isHigherScore(array $participants, array $participant): bool
    {
        return $participant['total'] > $participants['dealer']['total'];
    }
}
