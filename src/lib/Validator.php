<?php

namespace Blackjack;

class Validator
{
    /**
     * 入力値('y' or 'N')のバリデーション処理
     *
     * @param  string $answer
     * @return string $answer validated 'y' or 'N'
     */
    public function validateYesNoAnswer(string $answer): string
    {
        // 入力値のバリデーション処理
        while (!($answer === 'y' || $answer === 'N')) {
            echo PHP_EOL .
                'yまたはNを入力してください。' . PHP_EOL;
            $answer = trim(fgets(STDIN));
        }
        return $answer;
    }


    /**
     * 入力値(プレイヤーの人数)のバリデーション処理
     *
     * @param  string $number
     * @return int    $number validated 1~3
     */
    public function validateNumberAnswer($number): int
    {
        $allowedNumbers  = [1, 2, 3];

        while (!(in_array($number, $allowedNumbers))) {
            echo PHP_EOL .
                '1~3の数値を入力してください。' . PHP_EOL;
            $number = trim(fgets(STDIN));
        }
        return $number;
    }
}
