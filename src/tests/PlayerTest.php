<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Player;
use Blackjack\Deck;
use Blackjack\Card;

require_once(__DIR__ . '/../lib/Player.php');
require_once(__DIR__ . '/../lib/Deck.php');
require_once(__DIR__ . '/../lib/Card.php');

final class PlayerTest extends TestCase
{
    public function testDrawCardsReturnArray(): void
    {
        $deck = new Deck();
        $player = new Player();
        $cards = $player->drawCards($deck, 2);
        $this->assertIsArray($cards);
    }

    /**
     * ここを追加
     */
    public function testCalTotalScore(): void
    {
        $playerCards = [new Card('ハート', 'J', 10), new Card('ハート', 'A', 1)];
        $player = new Player();
        $totalScore = $player->calTotalScore($playerCards);
        $this->assertIsInt($totalScore);
    }
}
