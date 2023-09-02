<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;

use Blackjack\Participants\CpuPlayer;
use Blackjack\Rule\CpuPlayerRule;
use Blackjack\Rule\AceRule;
use Blackjack\Deck;
use Blackjack\Card;

require_once(__DIR__ . '/../../lib/Participants/CpuPlayer.php');

final class CpuPlayerTest extends TestCase
{
    public function testDrawCards(): void
    {
        $cpuPlayer = new CpuPlayer(new CpuPlayerRule, new AceRule);

        $deckStub1 = $this->createStub(Deck::class);
        $deckStub1->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11)]);

        $deckStub2 = $this->createStub(Deck::class);
        $deckStub2->method('drawCards')
            ->willReturn([new Card('スペード', 'J', 10)]);

        // 2枚引いた場合
        $cpuPlayer->drawCards($deckStub1, 2);
        $this->assertSame(2, count($cpuPlayer->getCards()));
        $this->assertSame(12, $cpuPlayer->getTotalScore());

        // もう1枚引いた場合
        $cpuPlayer->drawCards($deckStub2, 1);
        $this->assertSame(3, count($cpuPlayer->getCards()));
        $this->assertSame(12, $cpuPlayer->getTotalScore());
    }

    public function testGetCards(): void
    {
        $cpuPlayer = new CpuPlayer(new CpuPlayerRule, new AceRule);

        $deckStub1 = $this->createStub(Deck::class);
        $deckStub1->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11)]);

        $deckStub2 = $this->createStub(Deck::class);
        $deckStub2->method('drawCards')
            ->willReturn([new Card('スペード', 'J', 10)]);

        // 2枚引いた場合
        $cpuPlayer->drawCards($deckStub1, 2);
        $this->assertSame(2, count($cpuPlayer->getCards()));

        // もう1枚引いた場合
        $cpuPlayer->drawCards($deckStub2, 1);
        $this->assertSame(3, count($cpuPlayer->getCards()));
    }

    public function testGetTotalScore(): void
    {
        $cpuPlayer = new CpuPlayer(new CpuPlayerRule, new AceRule);

        $deckStub1 = $this->createStub(Deck::class);
        $deckStub1->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11)]);

        $deckStub2 = $this->createStub(Deck::class);
        $deckStub2->method('drawCards')
            ->willReturn([new Card('スペード', 'J', 10)]);

        // 2枚引いた場合
        $cpuPlayer->drawCards($deckStub1, 2);
        $this->assertSame(12, $cpuPlayer->getTotalScore());

        // もう1枚引いた場合
        $cpuPlayer->drawCards($deckStub2, 1);
        $this->assertSame(12, $cpuPlayer->getTotalScore());
    }

    public function testIsBust(): void
    {
        $cpuPlayer = new CpuPlayer(new CpuPlayerRule, new AceRule);

        $cpuPlayerTotalScore1 = 21;
        $this->assertSame(false, $cpuPlayer->isBust($cpuPlayerTotalScore1));

        $cpuPlayerTotalScore2 = 22;
        $this->assertSame(true, $cpuPlayer->isBust($cpuPlayerTotalScore2));
    }
}
