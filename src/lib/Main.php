<?php

namespace Blackjack;

require_once __DIR__ . ('/BlackjackGame.php');
require_once __DIR__ . ('/Player.php');
require_once __DIR__ . ('/Dealer.php');

$game = new BlackjackGame(new Player(), new Dealer());
$game->startGame();
