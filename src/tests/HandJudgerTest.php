<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\HandJudger;

require_once(__DIR__ . '/../lib/HandJudger.php');

final class HandJudgerTest extends TestCase
{
    public function testDetermineWinner(): void
    {
        $handJudger = new HandJudger();
        $winnerMsg = $handJudger->determineWinner(17, 22);
        $this->assertSame('ディーラーはバーストしました。あなたの勝ちです！', $winnerMsg);

        $handJudger2 = new HandJudger();
        $winnerMsg2 = $handJudger2->determineWinner(17, 17);
        $this->assertSame('同点でした。この勝負は引き分けとします。', $winnerMsg2);

        $handJudger3 = new HandJudger();
        $winnerMsg3 = $handJudger3->determineWinner(17, 15);
        $this->assertSame('あなたの勝ちです！', $winnerMsg3);

        $handJudger4 = new HandJudger();
        $winnerMsg4 = $handJudger4->determineWinner(18, 21);
        $this->assertSame('ディーラーの勝ちです。残念！', $winnerMsg4);
    }
}
