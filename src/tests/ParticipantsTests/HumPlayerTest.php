<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;

use Blackjack\Participants\HumPlayer;
use Blackjack\Rule\HumPlayerRule;
use Blackjack\Rule\AceRule;
use Blackjack\Deck;
use Blackjack\Card;

require_once(__DIR__ . '/../../lib/Participants/HumPlayer.php');

final class HumPlayerTest extends TestCase
{
    public function testDrawCards(): void
    {
        $player = new HumPlayer(new HumPlayerRule, new AceRule);

        $deckStub1 = $this->createStub(Deck::class);
        $deckStub1->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11)]);

        $deckStub2 = $this->createStub(Deck::class);
        $deckStub2->method('drawCards')
            ->willReturn([new Card('スペード', 'J', 10)]);

        // 2枚引いた場合
        $player->drawCards($deckStub1, 2);
        $this->assertSame(2, count($player->getCards()));
        $this->assertSame(12, $player->getTotalScore());

        // もう1枚引いた場合
        $player->drawCards($deckStub2, 1);
        $this->assertSame(3, count($player->getCards()));
        $this->assertSame(12, $player->getTotalScore());
    }

    public function testGetCards(): void
    {
        $player = new HumPlayer(new HumPlayerRule, new AceRule);

        $deckStub1 = $this->createStub(Deck::class);
        $deckStub1->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11)]);

        $deckStub2 = $this->createStub(Deck::class);
        $deckStub2->method('drawCards')
            ->willReturn([new Card('スペード', 'J', 10)]);

        // 2枚引いた場合
        $player->drawCards($deckStub1, 2);
        $this->assertSame(2, count($player->getCards()));

        // もう1枚引いた場合
        $player->drawCards($deckStub2, 1);
        $this->assertSame(3, count($player->getCards()));
    }

    public function testGetTotalScore(): void
    {
        $player = new HumPlayer(new HumPlayerRule, new AceRule);

        $deckStub1 = $this->createStub(Deck::class);
        $deckStub1->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11)]);

        $deckStub2 = $this->createStub(Deck::class);
        $deckStub2->method('drawCards')
            ->willReturn([new Card('スペード', 'J', 10)]);

        // 2枚引いた場合
        $player->drawCards($deckStub1, 2);
        $this->assertSame(12, $player->getTotalScore());

        // もう1枚引いた場合
        $player->drawCards($deckStub2, 1);
        $this->assertSame(12, $player->getTotalScore());
    }

    public function testIsBust(): void
    {
        $player = new HumPlayer(new HumPlayerRule, new AceRule);

        $playerTotalScore1 = 21;
        $this->assertSame(false, $player->isBust($playerTotalScore1));

        $playerTotalScore2 = 22;
        $this->assertSame(true, $player->isBust($playerTotalScore2));
    }
}
