<?php

namespace Blackjack;

require_once __DIR__ . ('/BlackjackGame.php');
require_once __DIR__ . ('/Deck.php');
require_once __DIR__ . ('/Player.php');
require_once __DIR__ . ('/Dealer.php');
require_once __DIR__ . ('/HandJudger.php');

/**
 * ここを修正
 * 引き数にDeckインスタンス, HandJudgerインスタンスを追加
 */
$deck = new Deck();
$player = new Player();
$dealer = new Dealer();
$handJudger = new HandJudger();
$game = new BlackjackGame($deck, $player, $dealer, $handJudger);

$game->startGame();
