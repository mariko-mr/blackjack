<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\BlackjackGame;
use Blackjack\Deck;
use Blackjack\Player;
use Blackjack\Dealer;

require_once(__DIR__ . '/../lib/BlackjackGame.php');
require_once(__DIR__ . '/../lib/Deck.php');
require_once(__DIR__ . '/../lib/Player.php');
require_once(__DIR__ . '/../lib/Dealer.php');

final class BlackjackGameTest extends TestCase
{
    public function testStartGame(): void
    {
        $game = new BlackjackGame(new Player(), new Dealer());
        $result = $game->startGame();
        $this->assertSame(1, $result);
    }
}
