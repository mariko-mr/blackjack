<?php

namespace Blackjack;

class Player
{
    /**
     * @var Card[] $playerCards
     * @var int $playerTotalScore
     */
    private array $playerCards;
    private int $playerTotalScore;

    public function __construct()
    {
        // 初期化処理
        $this->playerCards = [];
        $this->playerTotalScore = 0;
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
        $this->playerTotalScore = $this->updateTotalScore($drawnCards);

        // 引いたカードを持ち札に加える
        $this->playerCards = array_merge($this->playerCards, $drawnCards);

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
     * Aの得点を減算して調整するロジックを追加
     */
    /**
     * プレイヤーの合計点を更新
     *
     * @param Card[] $drawnCards
     * @return int
     */
    private function updateTotalScore(array $drawnCards): int
    {
        foreach ($drawnCards as $drawnCard) {
            $this->playerTotalScore += $drawnCard->getScore();
        }

        // 合計21を超える場合、Aの得点を減算して調整
        if ($this->playerTotalScore > 21) {
            $this->reduceScoreWithAce($drawnCards);
        }

        return $this->playerTotalScore;
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
            if ($drawnCard->getNumber() === 'A' && $this->playerTotalScore > 21) {
                $this->playerTotalScore -= 10;
            }
        }
    }
}
