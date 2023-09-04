<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;

use Blackjack\Participants\Dealer;
use Blackjack\Rule\DealerRule;
use Blackjack\Rule\AceRule;
use Blackjack\Deck;
use Blackjack\Card;

require_once(__DIR__ . '/../../lib/Participants/Dealer.php');

final class DealerTest extends TestCase
{
    public function testDrawCards(): void
    {
        $dealer = new Dealer(new DealerRule, new AceRule);

        $deckStub1 = $this->createStub(Deck::class);
        $deckStub1->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11)]);

        $deckStub2 = $this->createStub(Deck::class);
        $deckStub2->method('drawCards')
            ->willReturn([new Card('スペード', 'J', 10)]);

        // 2枚引いた場合
        $dealer->drawCards($deckStub1, 2);
        $this->assertSame(2, count($dealer->getCards()));
        $this->assertSame(12, $dealer->getTotalScore());

        // もう1枚引いた場合
        $dealer->drawCards($deckStub2, 1);
        $this->assertSame(3, count($dealer->getCards()));
        $this->assertSame(12, $dealer->getTotalScore());
    }

    public function testGetCards(): void
    {
        $dealer = new Dealer(new DealerRule, new AceRule);

        $deckStub1 = $this->createStub(Deck::class);
        $deckStub1->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11)]);

        $deckStub2 = $this->createStub(Deck::class);
        $deckStub2->method('drawCards')
            ->willReturn([new Card('スペード', 'J', 10)]);

        // 2枚引いた場合
        $dealer->drawCards($deckStub1, 2);
        $this->assertSame(2, count($dealer->getCards()));

        // もう1枚引いた場合
        $dealer->drawCards($deckStub2, 1);
        $this->assertSame(3, count($dealer->getCards()));
    }

    public function testGetTotalScore(): void
    {
        $dealer = new Dealer(new DealerRule, new AceRule);

        $deckStub1 = $this->createStub(Deck::class);
        $deckStub1->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11)]);

        $deckStub2 = $this->createStub(Deck::class);
        $deckStub2->method('drawCards')
            ->willReturn([new Card('スペード', 'J', 10)]);

        // 2枚引いた場合
        $dealer->drawCards($deckStub1, 2);
        $this->assertSame(12, $dealer->getTotalScore());

        // もう1枚引いた場合
        $dealer->drawCards($deckStub2, 1);
        $this->assertSame(12, $dealer->getTotalScore());
    }

    public function testIsBust(): void
    {
        $dealer = new Dealer(new DealerRule, new AceRule);

        $dealerTotalScore1 = 21;
        $this->assertSame(false, $dealer->isBust($dealerTotalScore1));

        $dealerTotalScore2 = 22;
        $this->assertSame(true, $dealer->isBust($dealerTotalScore2));
    }
}
