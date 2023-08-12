<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Card;

require_once(__DIR__ . '/../lib/Card.php');

/**
 * ここを修正
 * 引き数に$scoreを追加
 */
final class CardTest extends TestCase
{
    public function testGetSuit(): void
    {
        $card = new Card('ハート', '7', 7);
        $suit = $card->getSuit();
        $this->assertSame('ハート', $suit);
    }

/**
 * ここを修正
 * 引き数に$scoreを追加
 */
    public function testGetNumber(): void
    {
        $card = new Card('ハート', '7', 7);
        $number = $card->getNumber();
        $this->assertSame('7', $number);
    }

    /**
     * ここを追加
     */
    public function testGetScore(): void
    {
        $card = new Card('ハート', '7', 7);
        $score = $card->getScore();
        $this->assertSame(7, $score);
    }
}
