<?php

namespace Blackjack;

class Dealer
{
    /**
     * @var Card[] $dealerCards
     * @var int $dealerTotalScore
     */
    private array $dealerCards;
    private int $dealerTotalScore;

    public function __construct()
    {
        // 初期化処理
        $this->dealerCards = [];
        $this->dealerTotalScore = 0;
    }

    /**
     * デッキからカードを引いて合計点を更新し、持ち札に加える
     *
     * @param Deck $deck
     * @param int $drawNum
     * @return Card[]
     */
    public function drawCards(Deck $deck, int $drawNum): array
    {
        $drawnCards = $deck->drawCards($drawNum);

        // 合計点を更新する
        $this->dealerTotalScore = $this->updateTotalScore($drawnCards);

        // 引いたカードを持ち札に加える
        $this->dealerCards = array_merge($this->dealerCards, $drawnCards);

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
     * ここを修正
     * Aの得点を減算して調整するロジックを追加
     */
    /**
     * ディーラーの合計点を更新
     *
     * @param Card[] $drawnCards
     * @return int
     */
    private function updateTotalScore(array $drawnCards): int
    {
        foreach ($drawnCards as $drawnCard) {
            $this->dealerTotalScore += $drawnCard->getScore();
        }

        // 合計21を超える場合、Aの得点を減算して調整
        if ($this->dealerTotalScore > 21) {
            $this->reduceScoreWithAce($drawnCards);
        }

        return $this->dealerTotalScore;
    }

    /**
     * ここを追加
     * 合計21を超える場合、Aの得点を11から1へと減算する
     *
     * @param Card[] $drawnCards
     * @return void
     */
    private function reduceScoreWithAce(array $drawnCards): void
    {
        foreach ($drawnCards as $drawnCard) {
            if ($drawnCard->getNumber() === 'A' && $this->dealerTotalScore > 21) {
                $this->dealerTotalScore -= 10;
            }
        }
    }
}
