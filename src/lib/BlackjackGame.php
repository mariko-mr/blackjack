<?php

namespace Blackjack;

require_once __DIR__ . ('/CpuPlayer.php');

class BlackjackGame
{
    private const DRAW_TWO = 2;
    private const DRAW_ONE = 1;
    private array $cpuPlayers;

    public function __construct(
        private Deck $deck,
        private HumPlayer $player,
        private Dealer $dealer,
        private Message $message,
        private HandJudger $handJudger,
    ) {
        // 初期化処理
        $this->cpuPlayers = [];
    }

    /**
     * ブラックジャックゲームを開始
     *
     */
    public function startGame(): void
    {
        // ゲームの設定をする
        $this->setupGame();

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

        // CPUプレイヤーとディーラーがカードを引くターン
        if ($validatedAnswer === 'N') {
            foreach ($this->cpuPlayers as $num => $cpuPlayer) {
                $this->cpuTurn($cpuPlayer, $num);
            }

            $this->dealerTurn();
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
        // プレイヤーがカードを1枚引く
        $this->player->drawCards($this->deck, self::DRAW_ONE);

        $playerLastDrawnCard = $this->player->getCards()[array_key_last($this->player->getCards())];
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
     */
    private function dealerTurn(): void
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
    private function validateAnswer(string $inputAnswer): string
    {
        while (!($inputAnswer === 'y' || $inputAnswer === 'N')) {
            echo PHP_EOL .
                'yまたはNを入力してください。' . PHP_EOL;
            $inputAnswer = trim(fgets(STDIN));
        }
        return $inputAnswer;
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
