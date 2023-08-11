<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Dealer;
use Blackjack\Deck;
use Blackjack\Card;

require_once(__DIR__ . '/../lib/Dealer.php');
require_once(__DIR__ . '/../lib/Deck.php');
require_once(__DIR__ . '/../lib/Card.php');

final class DealerTest extends TestCase
{
    public function testDrawCardsReturnArray(): void
    {
        $deck = new Deck();
        $dealer = new Dealer();
        $cards = $dealer->drawCards($deck, 2);
        $this->assertIsArray($cards);
    }
}
