<?php

namespace Blackjack;

/**
 * ここを修正
 * コンストラクタに HandJudger の依存関係を注入
 *
 */
class BlackjackGame
{
    /**
     * @var array $cpuPlayer
     */
    private const DRAW_TWO = 2;
    private const DRAW_ONE = 1;

    public function __construct(
        private Deck $deck,
        private HumPlayer $player,
        private Dealer $dealer,
        private Message $message,
        private HandJudger $handJudger,
    ) {
    }

    /**
     * ブラックジャックゲームを開始
     *
     */
    public function startGame(): void
    {
        // プレイヤーとディーラーが始めに2枚ずつカードを引く
        $playerCards = $this->player->drawCards($this->deck, self::DRAW_TWO);
        $dealerCards = $this->dealer->drawCards($this->deck, self::DRAW_TWO);

        // CPUプレイヤーもカードを引く


        // スタート時のメッセージを表示
        $this->message->showStartMsg($this->player, $this->cpuPlayers, $this->dealer);
        // $validatedAnswer = $this->validator->validateAnswer(trim(fgets(STDIN)));
        // 入力値のバリデーション処理 :TODO: Validatorクラスにうつす
        $inputAnswer = trim(fgets(STDIN));
        $validatedAnswer = $this->validateAnswer($inputAnswer);

        // プレイヤーがカードを引くターン
        while ($validatedAnswer === 'y') {
            $validatedAnswer = $this->playerTurn();
        }


        // ディーラーがカードを引くターン
        if ($validatedStdin === 'N') {
            $this->dealerTurn($dealerCards);
        }

        // 判定して終了する
        $this->showDown($this->handJudger);
    }

    /**
     * 開始時のゲーム設定
     *
     */
    private function setupGame(): void
    {
        $this->message->showSetupMsg();

        // 入力値のバリデーション処理 :TODO: Validatorクラスにうつす
        $validatedNumber = $this->validateNumber(trim(fgets(STDIN)));

        // CPUプレイヤーがいる場合、インスタンスを生成し2枚ずつカードを引く
        if ($validatedNumber >= 2) {
            for ($i = 1; $i < $validatedNumber; $i++) {
                $this->cpuPlayers[$i] = new CpuPlayer();
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
        $playerCards = $this->player->drawCards($this->deck, self::DRAW_ONE);
        $playerLastDrawnCard = $playerCards[array_key_last($playerCards)];
        $playerTotalScore = $this->player->getTotalScore();

        $this->message->showPlayerTurnMsg($playerLastDrawnCard, $playerTotalScore);

        // 入力値のバリデーション処理 :TODO: Validatorクラスにうつす
        // return = $this->validator->validateAnswer(trim(fgets(STDIN)));
        $inputAnswer = trim(fgets(STDIN));
        return $this->validateAnswer($inputAnswer);
    }

    /**
     * CPUのターン
     *
     * @param CpuPlayer $cpuPlayer
     * @param int       $num
     */
    private function cpuTurn(CpuPlayer $cpuPlayer, int $num): void
    {
        // 合計が17以上になるまでカードを引き続ける
        while ($cpuPlayer->getTotalScore() < 17) {

            // CPUプレイヤーがカードを1枚引く
            $cpuPlayer->drawCards($this->deck, self::DRAW_ONE);

            $cpuLastDrawnCard = $cpuPlayer->getCards()[array_key_last($cpuPlayer->getCards())];
            $this->message->showCpuDrawnMsg($num, $cpuLastDrawnCard);
        }
    }

    /**
     * ディーラーのターン
     *
     * @param Card[] $dealerCards
     */
    private function dealerTurn(array $dealerCards): void
    {
        // ディーラーが引いた2枚目のカードを表示
        $this->message->showDealerTurnMsg($this->dealer);

        // 合計が17以上になるまでカードを引き続ける
        while ($this->dealer->getTotalScore() < 17) {

            // ディーラーがカードを1枚引く
            $this->dealer->drawCards($this->deck, self::DRAW_ONE);

            $dealerLastDrawnCard = $this->dealer->getCards()[array_key_last($this->dealer->getCards())];
            $this->message->showDealerDrawnMsg($dealerLastDrawnCard);
        }
    }

    /**
     * 判定ッセージを表示
     *
     * @param  HandJudger $handJudger
     * @return void
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

        // ゲームを終了する
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

    /**
     * 入力値('y' or 'N')のバリデーション処理
     *
     * @param  string $inputAnswer
     * @return string $inputAnswer validated 'y' or 'N'
     */
    private function validateInput(string $stdin): string
    {
        while (!($stdin === 'y' || $stdin === 'N')) {
            echo PHP_EOL .
                'yまたはNを入力してください。' . PHP_EOL;
        }
    }

    /**
     * 入力値(プレイヤーの人数)のバリデーション処理
     *
     * @param  string $inputNumber
     * @return int    $inputNumber validated 1~3
     */
    private function validateNumber($inputNumber): int
    {
        $num = [1, 2, 3];

        while (!(in_array($inputNumber, $num))) {
            echo PHP_EOL .
                '1~3の数値を入力してください。' . PHP_EOL;
            $inputNumber = trim(fgets(STDIN));
        }
        return $inputNumber;
    }
}
