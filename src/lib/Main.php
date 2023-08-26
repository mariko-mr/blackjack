<?php

namespace Blackjack;

require_once __DIR__ . ('/Deck.php');
require_once __DIR__ . ('/HumPlayer.php');
require_once __DIR__ . ('/Dealer.php');
require_once __DIR__ . ('/HandJudger.php');
require_once __DIR__ . ('/BlackjackGame.php');

$deck = new Deck();
$handJudger = new HandJudger();
$player = new HumPlayer();
$dealer = new Dealer();
$game = new BlackjackGame($deck, $handJudger, $player, $dealer);

$game->startGame();
