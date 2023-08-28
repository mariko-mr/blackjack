<?php

namespace Blackjack;

class HumPlayer
{
    /**
     * @var Card[] $playerCards
     * @var int    $playerTotalScore
     * @var int    $aceReductionCount
     */
    private array $playerCards;
    private int   $playerTotalScore;
    private int   $aceReductionCount;

    public function __construct()
    {
        // 初期化処理
        $this->playerCards = [];
        $this->playerTotalScore = 0;
        $this->aceReductionCount = 0;
    }

    /**
     * デッキからカードを引いて持ち札に加え、合計点を更新する
     *
     * @param  Deck $deck
     * @param  int  $drawNum
     * @return Card[]
     */
    public function drawCards(Deck $deck, int $drawNum): array
    {
        $drawnCards = $deck->drawCards($drawNum);

        // 引いたカードを持ち札に加える
        $this->playerCards = array_merge($this->playerCards, $drawnCards);

        // 合計点を更新する
        $this->playerTotalScore = $this->updateTotalScore($drawnCards);

        return $this->playerCards;
    }

    /**
     * プレイヤーのカードを取得
     *
     * @return array
     */
    public function getCards(): array
    {
        return $this->playerCards;
    }

    /**
     * プレイヤーの合計点を取得
     *
     * @return int
     */
    public function getTotalScore(): int
    {
        return $this->playerTotalScore;
    }

    /**
     * プレイヤーの合計点を更新
     *
     * @param  Card[] $drawnCards
     * @return int
     */
    private function updateTotalScore(array $drawnCards): int
    {
        // 引いたカードをそれぞれ合計点に合算する
        foreach ($drawnCards as $drawnCard) {
            $this->playerTotalScore += $drawnCard->getScore();
        }

        // 合計21を超え、Aがあれば得点を減算して調整
        if ($this->playerTotalScore > 21 && $this->hasAce()) {
            $this->reduceScoreWithAce();
        }

        return $this->playerTotalScore;
    }

    /**
     * $playerCardsにAが含まれるかを調べる
     *
     * @return bool
     */
    private function hasAce(): bool
    {
        foreach ($this->playerCards as $playerCard) {
            if ($playerCard->getNumber() == 'A') {
                return true;
            }
        }

        return false;
    }

    /**
     * Aの得点を11から1へと減算する
     *
     * @return void
     */
    private function reduceScoreWithAce(): void
    {
        // Aの出現回数を計算
        $aceCount = 0;
        foreach ($this->playerCards as $playerCard) {
            if ($playerCard->getNumber() == 'A') {
                $aceCount++;
            }
        }

        // Aの出現回数以上に減算しないように条件を設定
        while ($this->playerTotalScore > 21 && $aceCount > $this->aceReductionCount) {
            // Aの得点分を減算
            $this->playerTotalScore -= 10;

            // Aによる減算回数をカウント
            $this->aceReductionCount++;
        }
    }
}
