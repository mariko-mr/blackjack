<?php

namespace Blackjack;

class HumPlayer
{
    /**
     * @var Card[] プレイヤーの持ち札
     * @var int    プレイヤーの総得点
     */
    private array $playerCards;
    private int   $playerTotalScore;

    public function __construct(
        private HumPlayerRule $playerRule,
        private AceRule $aceRule
    ) {
        // 初期化処理
        $this->playerCards = [];
        $this->playerTotalScore = 0;
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
     * ここを修正
     *
     * Aルールによる減算をAceRuleクラスに委譲
     */
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

        // Aルールによる減算分を取得
        $aceSubtraction = $this->aceRule->subtractAceScore($this->playerRule, $this->playerTotalScore, $this->playerCards);

        return $this->playerTotalScore - $aceSubtraction;
    }
}
