<?php

namespace Blackjack\Participants;

require_once(__DIR__ . '/../Rule/CpuPlayerRule.php');
require_once(__DIR__ . '/../Rule/AceRule.php');

use Blackjack\Rule\CpuPlayerRule;
use Blackjack\Rule\AceRule;
use Blackjack\Deck;

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
     * @return void
     */
    public function drawCards(Deck $deck, int $drawNum): void
    {
        $drawnCards = $deck->drawCards($drawNum);

        // 引いたカードを持ち札に加える
        $this->cpuCards = array_merge($this->cpuCards, $drawnCards);

        // 合計点を更新する
        $this->cpuTotalScore = $this->updateTotalScore($this->cpuCards);
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
     * ここを追加
     *
     * @param  int $cpuTotalScore
     * @return bool
     */
    public function isBust(int $cpuTotalScore): bool
    {
        return $this->cpuPlayerRule->isBust($cpuTotalScore);
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
    private function updateTotalScore(array $cpuCards): int
    {
        $this->cpuTotalScore = 0;

        // カードを引くたび合計点をイチから再計算
        foreach ($cpuCards as $cpuCard) {
            $this->cpuTotalScore += $cpuCard->getScore();
        }

        // Aルールによる減算
        return $this->aceRule->subtractAceScore($this->cpuPlayerRule, $this->cpuTotalScore, $this->cpuCards);
    }
}
