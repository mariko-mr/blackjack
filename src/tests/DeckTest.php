<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;

use Blackjack\Deck;
use Blackjack\Card;

require_once(__DIR__ . '/../lib/Deck.php');

final class DeckTest extends TestCase
{
    public function testDrawCardsCount()
    {
        $deck = new Deck();
        $cards = $deck->drawCards(2);
        $this->assertCount(2, $cards);
    }

    public function testDrawCardsAreInstancesOfCardClass()
    {
        $deck = new Deck();
        $cards = $deck->drawCards(2);
        foreach ($cards as $card) {
            $this->assertInstanceOf(Card::class, $card);
        }
    }
}
