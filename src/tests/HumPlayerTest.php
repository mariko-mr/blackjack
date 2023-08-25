<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\HumPlayer;
use Blackjack\Deck;
use Blackjack\Card;

require_once(__DIR__ . '/../lib/HumPlayer.php');

final class HumPlayerTest extends TestCase
{
    public function testDrawCardsCount(): void
    {
        $player = new HumPlayer();
        $deck = new Deck();
        $playerCards = [];

        // 2枚引いた場合
        $playerCards = $player->drawCards($deck, 2);
        $this->assertSame(2, count($playerCards));

        // もう1枚引いた場合
        $playerCards = $player->drawCards($deck, 1);
        $this->assertSame(3, count($playerCards));
    }

    public function testDrawCardsGetTotalScore(): void
    {
        // テスト1 [A, A]
        $player1 = new HumPlayer();

        $deckMock1 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock1->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11)]);

        // プレイヤーの合計得点が正しく更新されているか
        $player1->drawCards($deckMock1, 2);
        $this->assertSame(12, $player1->getTotalScore());


        // テスト2 [A, K]
        $player2 = new HumPlayer();

        $deckMock2 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock2->method('drawCards')
            ->willReturn([new Card('ハート', 'A', 11), new Card('クラブ', 'K', 10)]);

        // プレイヤーの合計得点が正しく更新されているか
        $player2->drawCards($deckMock2, 2);
        $this->assertSame(21, $player2->getTotalScore());


        // テスト3 [2, A, A]
        $player3 = new HumPlayer();

        $deckMock3_1 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock3_1->method('drawCards')
            ->willReturn([new Card('スペード', '2', 2), new Card('ハート', 'A', 11)]);

        $deckMock3_2 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock3_2->method('drawCards')
            ->willReturn([new Card('クラブ', 'A', 11)]);

        // プレイヤーの合計得点が正しく更新されているか
        $player3->drawCards($deckMock3_1, 2);
        $player3->drawCards($deckMock3_2, 1);
        $this->assertSame(14, $player3->getTotalScore());


        // テスト4 [A, 2, 8, A]
        $player4 = new HumPlayer();

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

        // プレイヤーの合計得点が正しく更新されているか
        $player4->drawCards($deckMock4_1, 2);
        $player4->drawCards($deckMock4_2, 1);
        $player4->drawCards($deckMock4_3, 1);
        $this->assertSame(12, $player4->getTotalScore());


        // テスト5 [A, K, A, J]
        $player5 = new HumPlayer();

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

        // プレイヤーの合計得点が正しく更新されているか
        $player5->drawCards($deckMock5_1, 2);
        $player5->drawCards($deckMock5_2, 1);
        $player5->drawCards($deckMock5_3, 1);
        $this->assertSame(22, $player5->getTotalScore());


        // テスト6 [A, A, A, A, 8, K]
        $player6 = new HumPlayer();

        $deckMock6_1 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock6_1->method('drawCards')
            ->willReturn([new Card('スペード', 'A', 11), new Card('ハート', 'A', 11)]);

        $deckMock6_2 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock6_2->method('drawCards')
            ->willReturn([
                new Card('クラブ', 'A', 11)
            ]);

        $deckMock6_3 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock6_3->method('drawCards')
            ->willReturn([
                new Card('ダイヤ', 'A', 11)
            ]);

        $deckMock6_4 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock6_4->method('drawCards')
            ->willReturn([
                new Card('ダイヤ', '8', 8)
            ]);

        $deckMock6_5 = $this->getMockBuilder(Deck::class)
            ->getMock();
        $deckMock6_5->method('drawCards')
            ->willReturn([
                new Card('クラブ', 'K', 10)
            ]);

        // プレイヤーの合計得点が正しく更新されているか
        $player6->drawCards($deckMock6_1, 2);
        $player6->drawCards($deckMock6_2, 1);
        $player6->drawCards($deckMock6_3, 1);
        $player6->drawCards($deckMock6_4, 1);
        $player6->drawCards($deckMock6_5, 1);
        $this->assertSame(22, $player6->getTotalScore());
    }
}
