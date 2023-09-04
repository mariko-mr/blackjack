<?php

namespace Blackjack\Participants;

use Blackjack\Deck;
use Blackjack\Card;

abstract class Participant
{
    /**
     * デッキからカードを引いて持ち札に加え、合計点を更新する
     *
     * @param  Deck $deck
     * @param  int  $drawNum
     * @return void
     */
    abstract public function drawCards(Deck $deck, int $drawNum);

    /**
     * プレイヤーのカードを取得
     *
     * @return Card[]
     */
    abstract public function getCards();

    /**
     * プレイヤーの合計点を取得
     *
     * @return int
     */
    abstract public function getTotalScore();

    /**
     * バーストしているか調べる
     *
     * @param  int $totalScore
     * @return bool
     */
    abstract public function isBust(int $totalScore): bool;

    /**
     * 合計点を更新
     *
     * @param  Card[] $cards
     * @return int
     */
    abstract protected function updateTotalScore(array $cards);
}
