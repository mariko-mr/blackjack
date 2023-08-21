<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Player;
use Blackjack\Deck;
use Blackjack\Card;

require_once(__DIR__ . '/../lib/Player.php');

final class PlayerTest extends TestCase
{
    public function testDrawCardsCount(): void
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

    /**
     * ここを追加
     */
    public function testDrawCardsGetTotalScore(): void
    {
        // テストA, A
        $player = new Player();
        $deckMock = $this->getMockBuilder(Deck::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deckMock->method('drawCards')
            ->willReturn([
                new Card('ハート', 'A', 11),
                new Card('クラブ', 'A', 11)
            ]);

        // プレイヤーの手札に引かれたカードが正しく追加されているか
        $playerCards = $player->drawCards($deckMock, 2);
        $this->assertSame(2, count($playerCards));
        // プレイヤーの合計得点が正しく更新されているか
        $this->assertSame(12, $player->getTotalScore());


        // テストA, K
        $player2 = new Player();
        $deckMock2 = $this->getMockBuilder(Deck::class)
            ->disableOriginalConstructor()
            ->getMock();
        $deckMock2->method('drawCards')
            ->willReturn([
                new Card('ハート', 'A', 11),
                new Card('クラブ', 'K', 10),

            ]);

        // プレイヤーの手札に引かれたカードが正しく追加されているか
        $playerCards2 = $player2->drawCards($deckMock2, 2);
        $this->assertSame(2, count($playerCards2));
        // プレイヤーの合計得点が正しく更新されているか
        $this->assertSame(21, $player2->getTotalScore());


        // テスト2, A, A
        $player3 = new Player();
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
        $playerCards3 = $player3->drawCards($deckMock2, 3);
        $this->assertSame(3, count($playerCards3));
        // プレイヤーの合計得点が正しく更新されているか
        $this->assertSame(14, $player3->getTotalScore());


        // テストA, 2, 8, A
        $player4 = new Player();
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
        $playerCards4 = $player4->drawCards($deckMock4, 4);
        $this->assertSame(4, count($playerCards4));
        // プレイヤーの合計得点が正しく更新されているか
        $this->assertSame(12, $player4->getTotalScore());
    }
}
