<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;

use Blackjack\BlackjackGame;
use Blackjack\Deck;
use Blackjack\Message;
use Blackjack\Validator;
use Blackjack\HandJudger;
use Blackjack\Participants\HumPlayer;
use Blackjack\Participants\Dealer;
use Blackjack\Rule\HumPlayerRule;
use Blackjack\Rule\DealerRule;
use Blackjack\Rule\AceRule;

require_once(__DIR__ . '/../lib/BlackjackGame.php');
require_once(__DIR__ . '/../lib/Message.php');
require_once(__DIR__ . '/../lib/Validator.php');
require_once(__DIR__ . '/../lib/HandJudger.php');

final class BlackjackGameTest extends TestCase
{
    // public function testPlayGame(): void
    // {
    //     $deck = new Deck();
    //     $player = new HumPlayer(new HumPlayerRule, new AceRule);
    //     $dealer = new Dealer(new DealerRule, new AceRule);
    //     $message = new Message();
    //     $validator = new Validator();
    //     $handJudger = new HandJudger();

    //     $game = new BlackjackGame($deck, $player, $dealer, $message, $validator, $handJudger);
    //     $game->playGame();
    //     $output = trim(fgets(STDOUT));

    //     $this->assertSame(
    //         'プレイヤーの人数(1人~3人)を選択してください(1~3の数値を入力)',
    //     $output);
    // }
}
