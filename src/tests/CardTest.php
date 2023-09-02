<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;

use Blackjack\Card;

require_once(__DIR__ . '/../lib/Card.php');

final class CardTest extends TestCase
{
    public function testGetSuit(): void
    {
        $card = new Card('ハート', 'A', 11);
        $suit = $card->getSuit();
        $this->assertSame('ハート', $suit);
    }

    public function testGetNumber(): void
    {
        $card = new Card('ハート', 'A', 11);
        $number = $card->getNumber();
        $this->assertSame('A', $number);
    }

    public function testGetScore(): void
    {
        $card = new Card('ハート', 'A', 11);
        $score = $card->getScore();
        $this->assertSame(11, $score);
    }
}
