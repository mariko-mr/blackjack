<?php

namespace Blackjack;

class Dealer
{
    /**
     * @var Card[] $dealerCards
     * @var int    $dealerTotalScore
     * @var int    $aceReductionCount
     */
    private array $dealerCards;
    private int $dealerTotalScore;
    private int $aceReductionCount;

    public function __construct()
    {
        // 初期化処理
        $this->dealerCards = [];
        $this->dealerTotalScore = 0;
        $this->aceReductionCount = 0;
    }

    /**
     * デッキからカードを引いて持ち札に加え、合計点を更新する
     *
     * @param Deck $deck
     * @param int  $drawNum
     * @return Card[]
     */
    public function drawCards(Deck $deck, int $drawNum): array
    {
        $drawnCards = $deck->drawCards($drawNum);

        // 引いたカードを持ち札に加える
        $this->dealerCards = array_merge($this->dealerCards, $drawnCards);

        // 合計点を更新する
        $this->dealerTotalScore = $this->updateTotalScore($drawnCards);

        return $this->dealerCards;
    }

    /**
     * ここを追加
     */
    /**
     * ディーラーのカードを取得
     *
     * @return array
     */
    public function getCards(): array
    {
        return $this->dealerCards;
    }

    /**
     * ディーラーの合計点を取得
     *
     * @return int
     */
    public function getTotalScore(): int
    {
        return $this->dealerTotalScore;
    }

    /**
     * ディーラーの合計点を更新
     *
     * @param Card[] $drawnCards
     * @return int
     */
    private function updateTotalScore(array $drawnCards): int
    {
        // 引いたカードをそれぞれ合計点に合算する
        foreach ($drawnCards as $drawnCard) {
            $this->dealerTotalScore += $drawnCard->getScore();
        }

        // 合計21を超え、Aがあれば得点を減算して調整
        if ($this->dealerTotalScore > 21 && $this->hasAce()) {
            $this->reduceScoreWithAce();
        }

        return $this->dealerTotalScore;
    }

    /**
     * $dealerCardsにAが含まれるかを調べる
     *
     * @return bool
     */
    private function hasAce(): bool
    {
        foreach ($this->dealerCards as $dealerCard) {
            if ($dealerCard->getNumber() == 'A') {
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
        foreach ($this->dealerCards as $dealerCard) {
            if ($dealerCard->getNumber() == 'A') {
                $aceCount++;
            }
        }

        // Aの出現回数以上に減算しないように条件を設定
        while ($this->dealerTotalScore > 21 && $aceCount > $this->aceReductionCount) {
            // Aの得点分を減算
            $this->dealerTotalScore -= 10;

            // Aによる減算回数をカウント
            $this->aceReductionCount++;
        }
    }
}
