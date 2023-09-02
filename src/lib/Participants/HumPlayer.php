<?php

namespace Blackjack\Participants;

require_once(__DIR__ . '/../Rule/HumPlayerRule.php');
require_once(__DIR__ . '/../Rule/AceRule.php');

use Blackjack\Rule\HumPlayerRule;
use Blackjack\Rule\AceRule;
use Blackjack\Deck;

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
    public function drawCards(Deck $deck, int $drawNum): void
    {
        $drawnCards = $deck->drawCards($drawNum);

        // 引いたカードを持ち札に加える
        $this->playerCards = array_merge($this->playerCards, $drawnCards);

        // 合計点を更新する
        $this->playerTotalScore = $this->updateTotalScore($this->playerCards);
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
     * バーストしているか調べる
     *
     * @param  int $playerTotalScore
     * @return bool
     */
    public function isBust(int $playerTotalScore): bool
    {
        return $this->playerRule->isBust($playerTotalScore);
    }

    /**
     * プレイヤーの合計点を更新
     *
     * @param  Card[] $drawnCards
     * @return int
     */
    private function updateTotalScore(array $playerCards): int
    {
        $this->playerTotalScore = 0;

        // カードを引くたび合計点をイチから再計算
        foreach ($playerCards as $playerCard) {
            $this->playerTotalScore += $playerCard->getScore();
        }

        // Aルールによる減算
        return $this->aceRule->subtractAceScore($this->playerRule, $this->playerTotalScore, $this->playerCards);
    }
}
