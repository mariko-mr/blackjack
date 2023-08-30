<?php

namespace Blackjack;

class CpuPlayer
{
    /**
     * @var Card[] CPUプレイヤーの持ち札
     * @var int    CPUプレイヤーの総得点
     */
    private array $cpuCards;
    private int   $cpuTotalScore;

    public function __construct(
        private CpuPlayerRule $cpuPlayerRule,
        private AceRule $aceRule
    ) {
        // 初期化処理
        $this->cpuCards = [];
        $this->cpuTotalScore = 0;
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
        $this->cpuCards = array_merge($this->cpuCards, $drawnCards);

        // 合計点を更新する
        $this->cpuTotalScore = $this->updateTotalScore($drawnCards);

        return $this->cpuCards;
    }

    /**
     * CPUプレイヤーのカードを取得
     *
     * @return array
     */
    public function getCards(): array
    {
        return $this->cpuCards;
    }

    /**
     * CPUプレイヤーの合計点を取得
     *
     * @return int
     */
    public function getTotalScore(): int
    {
        return $this->cpuTotalScore;
    }

    /**
     * ここを修正
     *
     * Aルールによる減算をAceRuleクラスに委譲
     */
    /**
     * CPUプレイヤーの合計点を更新
     *
     * @param  Card[] $drawnCards
     * @return int
     */
    private function updateTotalScore(array $drawnCards): int
    {
        // 引いたカードをそれぞれ合計点に合算する
        foreach ($drawnCards as $drawnCard) {
            $this->cpuTotalScore += $drawnCard->getScore();
        }

        // Aルールによる減算分を取得
        $aceSubtraction = $this->aceRule->subtractAceScore($this->cpuPlayerRule, $this->cpuTotalScore, $this->cpuCards);

        return $this->cpuTotalScore - $aceSubtraction;
    }
}
