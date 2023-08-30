<?php

namespace Blackjack;

require_once __DIR__ . ('/CpuPlayer.php');
require_once __DIR__ . ('/CpuPlayerRule.php');
require_once __DIR__ . ('/AceRule.php');

class BlackjackGame
{
    /**
     * ゲーム開始時にデッキから引くカードの枚数
     */
    private const DRAW_TWO = 2;

    /**
     * 各自のターンにデッキから引くカードの枚数
     */
    private const DRAW_ONE = 1;

    /**
     * CpuPlayerの設定人数による配列
     *
     * @var CpuPlayer[]
     */
    private array $cpuPlayers;

    public function __construct(
        private Deck $deck,
        private HumPlayer $player,
        private Dealer $dealer,
        private Message $message,
        private Validator $validator,
        private HandJudger $handJudger,
    ) {
        // 初期化処理
        $this->cpuPlayers = [];
    }

    /**
     * ブラックジャックゲームの一連の流れを管理
     *
     */
    public function playGame(): void
    {
        $this->setupGame();
        $this->startGame();
        $this->showDown($this->handJudger);
        $this->quitGame();
    }

    /**
     * ゲームをスタート
     *
     */
    private function startGame(): void
    {
        // スタート時のメッセージを表示
        $this->message->showStartMsg($this->player, $this->cpuPlayers, $this->dealer);
        $validatedAnswer = $this->validator->validateYesNoAnswer(trim(fgets(STDIN)), $this->message);

        // プレイヤーがカードを引くターン
        while ($validatedAnswer === 'y') {
            $validatedAnswer = $this->playerTurn();
        }

        if ($validatedAnswer === 'N') {
            // CPUプレイヤーがカードを引くターン
            foreach ($this->cpuPlayers as $num => $cpuPlayer) {
                $this->cpuTurn($cpuPlayer, $num);
            }
            // ディーラーがカードを引くターン
            $this->dealerTurn();
        }
    }

    /**
     * ここを修正
     *
     * CPUプレイヤーインスタンスに引き数を追加
     */
    /**
     * 開始時のゲーム設定
     *
     */
    private function setupGame(): void
    {
        // 設定メッセージを表示
        $this->message->showSetupMsg();
        $validatedNumber = $this->validator->validateNumberAnswer(trim(fgets(STDIN)), $this->message);

        // CPUプレイヤーがいる場合、インスタンスを生成し2枚ずつカードを引く
        if ($validatedNumber >= 2) {
            for ($i = 1; $i < $validatedNumber; $i++) {
                $this->cpuPlayers[$i] = new CpuPlayer(new CpuPlayerRule, new AceRule);
            }
            foreach ($this->cpuPlayers as $cpuPlayer) {
                $cpuPlayer->drawCards($this->deck, self::DRAW_TWO);
            }
        }

        // ディーラーとプレイヤーが2枚ずつカードを引く
        $this->dealer->drawCards($this->deck, self::DRAW_TWO);
        $this->player->drawCards($this->deck, self::DRAW_TWO);
    }

    /**
     * プレイヤーのターン
     *
     */
    private function playerTurn(): string
    {
        // プレイヤーがカードを1枚引く
        $this->player->drawCards($this->deck, self::DRAW_ONE);

        $playerLastDrawnCard = $this->player->getCards()[array_key_last($this->player->getCards())];
        $playerTotalScore = $this->player->getTotalScore();

        $this->message->showPlayerTurnMsg($playerLastDrawnCard, $playerTotalScore);

        // メッセージへの入力をバリデーション処理して返す(y or N)
        return $this->validator->validateYesNoAnswer(trim(fgets(STDIN)), $this->message);
    }

    /**
     * ここを修正
     *
     * カードを引き続ける条件をCpuPlayerRuleに委譲
     */
    /**
     * CPUのターン
     *
     * @param CpuPlayer $cpuPlayer
     * @param int       $num
     */
    private function cpuTurn(CpuPlayer $cpuPlayer, int $num): void
    {
        // 合計が17以上になるまでカードを引き続ける
        while (CpuPlayerRule::shouldDrawCard($cpuPlayer)) {

            // CPUプレイヤーがカードを1枚引く
            $cpuPlayer->drawCards($this->deck, self::DRAW_ONE);

            $cpuLastDrawnCard = $cpuPlayer->getCards()[array_key_last($cpuPlayer->getCards())];
            $this->message->showCpuDrawnMsg($num, $cpuLastDrawnCard);
        }
    }

    /**
     * ここを修正
     *
     * カードを引き続ける条件をDealerRuleに委譲
     */
    /**
     * ディーラーのターン
     *
     */
    private function dealerTurn(): void
    {
        // ディーラーが引いた2枚目のカードを表示
        $this->message->showDealerTurnMsg($this->dealer);

        // 合計が17以上になるまでカードを引き続ける
        while (DealerRule::shouldDrawCard($this->dealer)) {

            // ディーラーがカードを1枚引く
            $this->dealer->drawCards($this->deck, self::DRAW_ONE);

            $dealerLastDrawnCard = $this->dealer->getCards()[array_key_last($this->dealer->getCards())];
            $this->message->showDealerDrawnMsg($dealerLastDrawnCard);
        }
    }

    /**
     * ゲーム結果を判定
     *
     * @param  HandJudger $handJudger
     */
    private function showDown(HandJudger $handJudger): void
    {
        // 扱いやすいように参加者の配列を設定
        $participants = $this->createParticipantsArray();

        // 得点発表
        $this->message->showTotalScoreMsg($participants);

        // 勝敗判定
        $results = $handJudger->determineWinner($participants);
        $this->message->showJudgmentMsg($results);
    }

    /**
     * ゲームを終了
     *
     */
    private function quitGame(): void
    {
        $this->message->showExitMsg();
        exit;
    }

    /**
     * 参加者を扱いやすいように配列にまとめる
     *
     * @return array $participants
     */
    private function createParticipantsArray(): array
    {
        $participants = [
            'dealer' => ['name' => 'ディーラー', 'total' => $this->dealer->getTotalScore()],
            'you'    => ['name' => 'あなた',     'total' => $this->player->getTotalScore()],
        ];

        // CPUが一人の場合$numは1、CPUが二人の場合$numは1と2
        foreach ($this->cpuPlayers as $num => $cpuPlayer) {
            $participants[$num] = ['name' => 'CPUプレイヤー' . $num, 'total' => $cpuPlayer->getTotalScore()];
        }

        return $participants;
    }
}
