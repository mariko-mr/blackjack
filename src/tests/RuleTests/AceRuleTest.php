<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;

use Blackjack\Rule\AceRule;
use Blackjack\Rule\HumPlayerRule;
use Blackjack\Card;

require_once(__DIR__ . '/../../lib/Rule/AceRule.php');

final class AceRuleTest extends TestCase
{
    public function testSubtractAceScore(): void
    {
        $aceRule = new AceRule();
        $participantRule = new HumPlayerRule();

        // Aがありバーストしている // 減算は1回
        $totalScore1 = 22;
        $cards1 = [new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11)];
        $this->assertSame(12, $aceRule->subtractAceScore($participantRule, $totalScore1, $cards1));

        // Aがありバーストしている // 減算は4回
        $totalScore1 = 54;
        $cards1 = [new Card('ハート', 'A', 11), new Card('クラブ', 'A', 11), new Card('スペード', 'A', 11), new Card('ダイヤ', 'A', 11), new Card('クラブ', 'K', 10)];
        $this->assertSame(14, $aceRule->subtractAceScore($participantRule, $totalScore1, $cards1));

        // Aが無くバーストしている
        $totalScore2 = 25;
        $cards2 = [new Card('ハート', 'J', 10), new Card('クラブ', '10', 10), new Card('クラブ', '5', 5)];
        $this->assertSame(25, $aceRule->subtractAceScore($participantRule, $totalScore2, $cards2));

        // Aがありバーストしていない
        $totalScore3 = 16;
        $cards3 = [new Card('ハート', 'A', 11), new Card('クラブ', '5', 5)];
        $this->assertSame(16, $aceRule->subtractAceScore($participantRule, $totalScore3, $cards3));

        // Aが無くバーストしていない
        $totalScore4 = 15;
        $cards4 = [new Card('ハート', '5', 5), new Card('クラブ', 'K', 10)];
        $this->assertSame(15, $aceRule->subtractAceScore($participantRule, $totalScore4, $cards4));
    }
}
