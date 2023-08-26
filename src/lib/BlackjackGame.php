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
        private HandJudger $handJudger,
        private HumPlayer $player,
        private Dealer $dealer,
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
        $validatedAnswer = $this->showStartMsg();

        // プレイヤーがカードを引くターン
        while ($validatedAnswer === 'y') {
            $this->playerTurn();

            // 入力値のバリデーション処理
            $inputAnswer = trim(fgets(STDIN));
            $validatedAnswer = $this->validateAnswer($inputAnswer);
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
        echo 'CPUプレイヤーの人数(0人~2人)を選択してください(0~2の数値を入力)' . PHP_EOL;

        // 入力値のバリデーション処理
        $validatedCpuNumber = $this->validateCpuNumber(trim(fgets(STDIN)));

        // CPUプレイヤーがいる場合、インスタンスを生成し2枚ずつカードを引く
        if ($validatedCpuNumber >= 1) {
            for ($i = 1; $i <= $validatedCpuNumber; $i++) {
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
     * 開始時のメッセージを表示
     *
     * @return string 'y' or 'N'
     */
    private function showStartMsg(): string
    {
        echo PHP_EOL .
            'ブラックジャックを開始します。' . PHP_EOL . PHP_EOL;

        // プレイヤーのカードを表示
        foreach ($this->player->getCards() as $card) {
            echo 'あなたの引いたカードは' .
                $card->getSuit() . 'の' .
                $card->getNumber() . 'です。' . PHP_EOL;
        }
        echo PHP_EOL;

        // CPUプレイヤーのカードを表示
        if (count($this->cpuPlayers) >= 1) {
            foreach ($this->cpuPlayers as $num => $cpuPlayer) {
                foreach ($cpuPlayer->getCards() as $card) {
                    echo 'CPUプレイヤー' . $num . 'の引いたカードは' .
                        $card->getSuit() . 'の' .
                        $card->getNumber() . 'です。' . PHP_EOL;
                }
                echo PHP_EOL;
            }
        }

        // ディーラーのカードを表示
        echo 'ディーラーの引いたカードは' .
            ($this->dealer->getCards())[0]->getSuit() . 'の' .
            ($this->dealer->getCards())[0]->getNumber() . 'です。' . PHP_EOL .
            'ディーラーの引いた2枚目のカードはわかりません。' . PHP_EOL . PHP_EOL;

        // プレイヤーの合計点
        echo 'あなたの現在の得点は' .
            $this->player->getTotalScore() .
            'です。カードを引きますか？（y/N）' . PHP_EOL;

        // 入力値のバリデーション処理
        $inputAnswer = trim(fgets(STDIN));
        return $this->validateAnswer($inputAnswer);
    }

    /**
     * プレイヤーのターン
     * TODO: if文の内容をPlayerクラスに移行
     */
    private function playerTurn(): void
    {
        // プレイヤーがカードを1枚引く
        $this->player->drawCards($this->deck, self::DRAW_ONE);

        $playerLastDrawnCard = $this->player->getCards()[array_key_last($this->player->getCards())];
        $playerTotalScore = $this->player->getTotalScore();

        echo PHP_EOL .
            'あなたの引いたカードは' .
            $playerLastDrawnCard->getSuit() . 'の' .
            $playerLastDrawnCard->getNumber() . 'です。' . PHP_EOL;

        if ($playerTotalScore <= 21) { // 合計が21以内の場合は続行
            echo 'あなたの現在の得点は' .
                $playerTotalScore .
                'です。カードを引きますか？（y/N）' . PHP_EOL;
        } elseif ($playerTotalScore > 21) { // 合計が21を超えたら終了
            echo 'あなたの現在の得点は' .
                $playerTotalScore .
                'です。バーストしました。' . PHP_EOL . PHP_EOL .
                '残念！あなたの負けです。' . PHP_EOL;
            exit;
        }
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
            $cpuPlayer->drawCards($this->deck, self::DRAW_ONE);
            $this->showCpuDrawnMsg($cpuPlayer, $num);
        }
    }

    /**
     * CPUがカードを引くメッセージを表示
     *
     * @param CpuPlayer $cpuPlayer
     * @param int       $num
     */
    private function showCpuDrawnMsg(CpuPlayer $cpuPlayer, int $num): void
    {
        $cpuLastDrawnCard = $cpuPlayer->getCards()[array_key_last($cpuPlayer->getCards())];

        echo PHP_EOL .
            'CPUプレイヤー' . $num . 'がカードを引きます。' . PHP_EOL .
            'CPUプレイヤー' . $num . 'の引いたカードは' .
            $cpuLastDrawnCard->getSuit() . 'の' .
            $cpuLastDrawnCard->getNumber() . 'です。' . PHP_EOL;
    }

    /**
     * ディーラーのターン
     * TODO: while文の内容をDealerクラスに移行
     *
     */
    private function dealerTurn(): void
    {
        // ディーラーが引いた2枚目のカードを表示
        $this->showDealerMsg();

        // 合計が17以上になるまでカードを引き続ける
        while ($this->dealer->getTotalScore() < 17) {
            $this->dealer->drawCards($this->deck, self::DRAW_ONE);
            $this->showDealerDrawnMsg();
        }
    }

    /**
     * ディーラーが引いた2枚目のカードを表示
     *
     */
    private function showDealerMsg(): void
    {
        echo PHP_EOL .
            'ディーラーの引いた2枚目のカードは' .
            ($this->dealer->getCards())[1]->getSuit() . 'の' .
            ($this->dealer->getCards())[1]->getNumber() . 'でした。' . PHP_EOL .
            'ディーラーの現在の得点は' .
            $this->dealer->getTotalScore() . 'です。' . PHP_EOL;
    }

    /**
     * ディーラーがカードを引くメッセージを表示
     *
     */
    private function showDealerDrawnMsg(): void
    {
        $dealerLastDrawnCard = $this->dealer->getCards()[array_key_last($this->dealer->getCards())];

        echo PHP_EOL .
            'ディーラーがカードを引きます。' . PHP_EOL .
            'ディーラーの引いたカードは' .
            $dealerLastDrawnCard->getSuit() . 'の' .
            $dealerLastDrawnCard->getNumber() . 'です。' . PHP_EOL;
    }

    /**
     * 判定ッセージを表示
     *
     * @param HandJudger $handJudger
     * @return void
     */
    private function showDown(HandJudger $handJudger): void
    {
        $participants = [
            0 => ['name' => 'あなた', 'total' => $this->player->getTotalScore()],
            3 => ['name' => 'ディーラー', 'total' => $this->dealer->getTotalScore()],
        ];

        foreach ($this->cpuPlayers as $num => $cpuPlayer) {
            $participants[$num] = ['name' => 'CPUプレイヤー' . $num, 'total' => $cpuPlayer->getTotalScore()];
        }

        // あなた-CPU1-CPU2-ディーラー の順になるように並び替え
        ksort($participants);

        // 得点発表
        echo  PHP_EOL .
            '判定に移ります。' . PHP_EOL . PHP_EOL .
            '-------- 判定結果 --------' . PHP_EOL;

        foreach ($participants as $participant) {
            echo $participant['name'] . 'の得点は' .
                $participant['total'] . 'です。' . PHP_EOL;
        }

        // 勝敗判定
        $winnerMsg = $handJudger->determineWinner($participants);
        echo '--------------------------' . PHP_EOL .
            $winnerMsg . PHP_EOL . PHP_EOL;

        // ゲームを終了する
        echo 'ブラックジャックを終了します。' . PHP_EOL;
        exit;
    }

    /**
     * 入力値('y' or 'N')のバリデーション処理
     *
     * @param string  $inputAnswer
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
     * 入力値(CPUの人数)のバリデーション処理
     *
     * @param string $inputNumber
     * @return int   $inputNumber validated 0~2
     */
    private function validateCpuNumber($inputNumber): int
    {
        $cpuNum = [0, 1, 2];

        while (!(in_array($inputNumber, $cpuNum))) {
            echo PHP_EOL .
                '0~2の数値を入力してください。' . PHP_EOL;
            $inputNumber = trim(fgets(STDIN));
        }
        return $inputNumber;
    }
}
