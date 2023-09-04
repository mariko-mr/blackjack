<?php

namespace Blackjack\Participants;

require_once(__DIR__ . '/../Participants/Participant.php');
require_once(__DIR__ . '/../Rule/DealerRule.php');
require_once(__DIR__ . '/../Rule/AceRule.php');

use Blackjack\Rule\DealerRule;
use Blackjack\Rule\AceRule;
use Blackjack\Deck;
use Blackjack\Card;

class Dealer extends Participant
{
    /** @var Card[] ディーラーの持ち札 */
    private array $dealerCards;

    /** @var int    ディーラーの総得点 */
    private int $dealerTotalScore;

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
     * @return void
     */
    public function drawCards(Deck $deck, int $drawNum): void
    {
        $drawnCards = $deck->drawCards($drawNum);

        // 引いたカードを持ち札に加える
        $this->dealerCards = array_merge($this->dealerCards, $drawnCards);

        // 合計点を更新する
        $this->dealerTotalScore = $this->updateTotalScore($this->dealerCards);
    }

    /**
     * ディーラーのカードを取得
     *
     * @return Card[]
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
     * バーストしているか調べる
     *
     * @param  int $dealerTotalScore
     * @return bool
     */
    public function isBust(int $dealerTotalScore): bool
    {
        return $this->dealerRule->isBust($dealerTotalScore);
    }

    /**
     * 続けてカードを引くか決める
     *
     * @param  int $dealerTotalScore
     * @return bool
     */
    public function shouldDrawCard(int $dealerTotalScore): bool
    {
        return $this->dealerRule->shouldDrawCard($dealerTotalScore);
    }

    /**
     * ディーラーの合計点を更新
     *
     * @param  Card[] $dealerCards
     * @return int
     */
    protected function updateTotalScore(array $dealerCards): int
    {
        $this->dealerTotalScore = 0;

        // カードを引くたび合計点をイチから再計算
        foreach ($dealerCards as $dealerCard) {
            $this->dealerTotalScore += $dealerCard->getScore();
        }

        // Aルールによる減算
        return $this->aceRule->subtractAceScore($this->dealerRule, $this->dealerTotalScore, $this->dealerCards);
    }
}
