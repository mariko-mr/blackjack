<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Dealer;
use Blackjack\Deck;
use Blackjack\Card;

require_once(__DIR__ . '/../lib/Dealer.php');

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

    /**
     * ここを追加
     */
    public function testDrawCardsGetTotalScore(): void
    {
        // テスト1 [A, A]
        $dealer1 = new Dealer();

        $deckMock1 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock1->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11)]);

        // ディーラーの合計得点が正しく更新されているか
        $dealer1->drawCards($deckMock1, 2);
        $this->assertSame(12, $dealer1->getTotalScore());


        // テスト2 [A, K]
        $dealer2 = new Dealer();

        $deckMock2 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock2->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'K', 10)]);

        // ディーラーの合計得点が正しく更新されているか
        $dealer2->drawCards($deckMock2, 2);
        $this->assertSame(21, $dealer2->getTotalScore());


        // テスト3 [2, A, A]
        $dealer3 = new Dealer();

        $deckMock3_1 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock3_1->method('drawCards')
            ->willReturn([new Card('スペード', '2', 2), new Card('ハート', 'A', 11)]);

        $deckMock3_2 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock3_2->method('drawCards')
            ->willReturn([new Card('クラブ', 'A', 11)]);

        // ディーラーの合計得点が正しく更新されているか
        $dealer3->drawCards($deckMock3_1, 2);
        $dealer3->drawCards($deckMock3_2, 1);
        $this->assertSame(14, $dealer3->getTotalScore());


        // テスト4 [A, 2, 8, A]
        $dealer4 = new Dealer();

        $deckMock4_1 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock4_1->method('drawCards')
            ->willReturn([new Card('スペード', 'A', 11), new Card('スペード', '2', 2)]);

        $deckMock4_2 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock4_2->method('drawCards')
            ->willReturn([
                new Card('ハート', '8', 8)
            ]);

        $deckMock4_3 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock4_3->method('drawCards')
            ->willReturn([
                new Card('クラブ', 'A', 11)
            ]);

        // ディーラーの合計得点が正しく更新されているか
        $dealer4->drawCards($deckMock4_1, 2);
        $dealer4->drawCards($deckMock4_2, 1);
        $dealer4->drawCards($deckMock4_3, 1);
        $this->assertSame(12, $dealer4->getTotalScore());


        // テスト5 [A, K, A, J]
        $dealer5 = new Dealer();

        $deckMock5_1 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock5_1->method('drawCards')
            ->willReturn([new Card('スペード', 'A', 11), new Card('スペード', 'K', 10)]);

        $deckMock5_2 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock5_2->method('drawCards')
            ->willReturn([
                new Card('ハート', 'A', 11)
            ]);

        $deckMock5_3 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock5_3->method('drawCards')
            ->willReturn([
                new Card('クラブ', 'J', 10)
            ]);

        // ディーラーの合計得点が正しく更新されているか
        $dealer5->drawCards($deckMock5_1, 2);
        $dealer5->drawCards($deckMock5_2, 1);
        $dealer5->drawCards($deckMock5_3, 1);
        $this->assertSame(22, $dealer5->getTotalScore());
    }
}
