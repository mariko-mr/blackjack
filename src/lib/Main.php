<?php

namespace Blackjack;

require_once __DIR__ . ('/Deck.php');
require_once __DIR__ . ('/HumPlayer.php');
require_once __DIR__ . ('/HumPlayerRule.php');
require_once __DIR__ . ('/Dealer.php');
require_once __DIR__ . ('/DealerRule.php');
require_once __DIR__ . ('/AceRule.php');
require_once __DIR__ . ('/Message.php');
require_once __DIR__ . ('/Validator.php');
require_once __DIR__ . ('/HandJudger.php');
require_once __DIR__ . ('/BlackjackGame.php');

/**
 * ここを修正
 *
 * プレイヤーインスタンスに引き数を追加
 * ディーラーインスタンスに引き数を追加
 */
$deck = new Deck();
$player = new HumPlayer(new HumPlayerRule, new AceRule);
$dealer = new Dealer(new DealerRule, new AceRule);
$message = new Message();
$validator = new Validator();
$handJudger = new HandJudger();

$game = new BlackjackGame($deck, $player, $dealer, $message, $validator, $handJudger);
$game->playGame();
