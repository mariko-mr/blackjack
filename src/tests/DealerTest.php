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
        // テストA, A
        $dealer = new Dealer();
        $deckMock = $this->getMockBuilder(Deck::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deckMock->method('drawCards')
            ->willReturn([
                new Card('ハート', 'A', 11),
                new Card('クラブ', 'A', 11)
            ]);

        // プレイヤーの手札に引かれたカードが正しく追加されているか
        $dealerCards = $dealer->drawCards($deckMock, 2);
        $this->assertSame(2, count($dealerCards));
        // プレイヤーの合計得点が正しく更新されているか
        $this->assertSame(12, $dealer->getTotalScore());


        // テストA, K
        $dealer2 = new Dealer();
        $deckMock2 = $this->getMockBuilder(Deck::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deckMock2->method('drawCards')
            ->willReturn([
                new Card('ハート', 'A', 11),
                new Card('クラブ', 'K', 10),

            ]);

        // プレイヤーの手札に引かれたカードが正しく追加されているか
        $dealerCards2 = $dealer2->drawCards($deckMock2, 2);
        $this->assertSame(2, count($dealerCards2));
        // プレイヤーの合計得点が正しく更新されているか
        $this->assertSame(21, $dealer2->getTotalScore());


        // テスト2, A, A
        $dealer3 = new Dealer();
        $deckMock2 = $this->getMockBuilder(Deck::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deckMock2->method('drawCards')
            ->willReturn([
                new Card('スペード', '2', 2),
                new Card('ハート', 'A', 11),
                new Card('クラブ', 'A', 11),

            ]);

        // プレイヤーの手札に引かれたカードが正しく追加されているか
        $dealerCards3 = $dealer3->drawCards($deckMock2, 3);
        $this->assertSame(3, count($dealerCards3));
        // プレイヤーの合計得点が正しく更新されているか
        $this->assertSame(14, $dealer3->getTotalScore());


        // テストA, 2, 8, A
        $dealer4 = new Dealer();
        $deckMock4 = $this->getMockBuilder(Deck::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deckMock4->method('drawCards')
            ->willReturn([
                new Card('スペード', 'A', 11),
                new Card('スペード', '2', 2),
                new Card('ハート', '8', 8),
                new Card('クラブ', 'A', 11),
            ]);

        // プレイヤーの手札に引かれたカードが正しく追加されているか
        $dealerCards4 = $dealer4->drawCards($deckMock4, 4);
        $this->assertSame(4, count($dealerCards4));
        // プレイヤーの合計得点が正しく更新されているか
        $this->assertSame(12, $dealer4->getTotalScore());
    }
}
