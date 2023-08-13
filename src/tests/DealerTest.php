<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Dealer;
use Blackjack\Deck;

require_once(__DIR__ . '/../lib/Dealer.php');
require_once(__DIR__ . '/../lib/Deck.php');

final class DealerTest extends TestCase
{
    public function testDrawCards(): void
    {
        $dealer = new Dealer();
        $deck = new Deck();
        $dealerCards = [];

        // 2枚引いた場合
        $dealerCards = $dealer->drawCards($deck, 2);
        $this->assertSame(2, count($dealerCards));

        // もう1枚引いた場合
        $dealerCards = $dealer->drawCards($deck, 1);
        $this->assertSame(3, count($dealerCards));
    }

    public function testGetTotalScore(): void
    {
        $dealer = new Dealer();
        $this->assertSame(0, $dealer->getTotalScore());
    }
}
