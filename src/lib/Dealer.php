<?php

namespace Blackjack;

class Dealer
{
    /**
     * @var Card[] ディーラーの持ち札
     * @var int    ディーラーの総得点
     */
    private array $dealerCards;
    private int   $dealerTotalScore;

    public function __construct(
        private DealerRule $dealerRule,
        private AceRule $aceRule
    ) {
        // 初期化処理
        $this->dealerCards = [];
        $this->dealerTotalScore = 0;
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
        $this->dealerCards = array_merge($this->dealerCards, $drawnCards);

        // 合計点を更新する
        $this->dealerTotalScore = $this->updateTotalScore($drawnCards);

        return $this->dealerCards;
    }

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
     * ここを修正
     *
     * Aルールによる減算をAceRuleクラスに委譲
     */
    /**
     * ディーラーの合計点を更新
     *
     * @param  Card[] $drawnCards
     * @return int
     */
    private function updateTotalScore(array $drawnCards): int
    {
        // 引いたカードをそれぞれ合計点に合算する
        foreach ($drawnCards as $drawnCard) {
            $this->dealerTotalScore += $drawnCard->getScore();
        }

        // Aルールによる減算分を取得
        $aceSubtraction = $this->aceRule->subtractAceScore($this->dealerRule, $this->dealerTotalScore, $this->dealerCards);

        return $this->dealerTotalScore - $aceSubtraction;
    }
}
