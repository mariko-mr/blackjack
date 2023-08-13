<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Player;
use Blackjack\Deck;

require_once(__DIR__ . '/../lib/Player.php');
require_once(__DIR__ . '/../lib/Deck.php');

final class PlayerTest extends TestCase
{
    public function testDrawCards(): void
    {
        $player = new Player();
        $deck = new Deck();
        $playerCards = [];

        // 2枚引いた場合
        $playerCards = $player->drawCards($deck, 2);
        $this->assertSame(2, count($playerCards));

        // もう1枚引いた場合
        $playerCards = $player->drawCards($deck, 1);
        $this->assertSame(3, count($playerCards));
    }

    public function testGetTotalScore(): void
    {
        $player = new Player();
        $this->assertSame(0, $player->getTotalScore());
    }
}
