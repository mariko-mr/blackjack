<?php

namespace Blackjack;

require_once __DIR__ . ('/Deck.php');
require_once __DIR__ . ('/HumPlayer.php');
require_once __DIR__ . ('/Dealer.php');
require_once __DIR__ . ('/Message.php');
require_once __DIR__ . ('/HandJudger.php');
require_once __DIR__ . ('/BlackjackGame.php');

$deck = new Deck();
$player = new HumPlayer();
$dealer = new Dealer();
$message = new Message();
$handJudger = new HandJudger();

$game = new BlackjackGame($deck, $player, $dealer, $message, $handJudger);
$game->startGame();
