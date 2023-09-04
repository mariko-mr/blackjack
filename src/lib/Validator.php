<?php

namespace Blackjack;

class Validator
{
    /**
     * 入力値(y or N)のバリデーション処理
     *
     * @param  string  $answer
     * @param  Message $message
     * @return string  $answer validated 'y' or 'N'
     */
    public function validateYesNoAnswer(string $answer, Message $message): string
    {
        // 入力値のバリデーション処理
        while (!($answer === 'y' || $answer === 'N')) {
            $message->showValidateYesNoErrorMsg();
            $answer = trim(fgets(STDIN));
        }
        return $answer;
    }


    /**
     * 入力値(プレイヤーの人数)のバリデーション処理
     *
     * @param  string  $number
     * @param  Message $message
     * @return string  $number validated '1'~'3'
     */
    public function validateNumberAnswer(string $number, Message $message): string
    {
        $allowedNumbers  = ['1', '2', '3'];

        while (!(in_array($number, $allowedNumbers))) {
            $message->showValidateNumberErrorMsg();
            $number = trim(fgets(STDIN));
        }
        return $number;
    }
}
