<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\HumPlayer;
use Blackjack\Dealer;
use Blackjack\Deck;

require_once(__DIR__ . '/../lib/BlackjackGame.php');

final class BlackjackGameTest extends TestCase
{
    public function testStartGame(): void
    {
        $player = new HumPlayer();
        $dealer = new Dealer();
        $deck = new Deck();
        $playerCards = $player->drawCards($deck, 2);
        $dealerCards = $dealer->drawCards($deck, 2);
        $this->assertIsArray($playerCards);
        $this->assertIsArray($dealerCards);
    }
}
