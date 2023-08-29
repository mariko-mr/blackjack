<?php

namespace Blackjack;

require_once __DIR__ . ('/BlackjackGame.php');
require_once __DIR__ . ('/Deck.php');
require_once __DIR__ . ('/Player.php');
require_once __DIR__ . ('/Dealer.php');
require_once __DIR__ . ('/Message.php');
require_once __DIR__ . ('/Validator.php');
require_once __DIR__ . ('/HandJudger.php');

/**
 * ここを修正
 * 引き数にDeckインスタンス, HandJudgerインスタンスを追加
 */
$deck = new Deck();
$player = new HumPlayer();
$dealer = new Dealer();
$message = new Message();
$validator = new Validator();
$handJudger = new HandJudger();

$game = new BlackjackGame($deck, $player, $dealer, $message, $validator, $handJudger);
$game->startGame();
