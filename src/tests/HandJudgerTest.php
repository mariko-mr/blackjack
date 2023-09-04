<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;

use Blackjack\HandJudger;
use Blackjack\Participants\HumPlayer;
use Blackjack\Participants\Dealer;
use Blackjack\Participants\CpuPlayer;
use Blackjack\Rule\HumPlayerRule;
use Blackjack\Rule\DealerRule;
use Blackjack\Rule\CpuPlayerRule;
use Blackjack\Rule\AceRule;

require_once(__DIR__ . '/../lib/HandJudger.php');

final class HandJudgerTest extends TestCase
{
    public function testDetermineWinner(): void
    {
        $handJudger = new HandJudger();
        $player = new HumPlayer(new HumPlayerRule, new AceRule);
        $dealer = new Dealer(new DealerRule, new AceRule);
        $cpuPlayer1 = new CpuPlayer(new CpuPlayerRule, new AceRule);
        $cpuPlayer2 = new CpuPlayer(new CpuPlayerRule, new AceRule);

        $participants1 = [
            'dealer' => ['name' => 'ディーラー',     'obj' => $dealer,     'total' => 22],
            'player' => ['name' => 'あなた',         'obj' => $player,     'total' => 23],
            '1'      => ['name' => 'CPUプレイヤー1', 'obj' => $cpuPlayer1, 'total' => 24],
            '2'      => ['name' => 'CPUプレイヤー2', 'obj' => $cpuPlayer2, 'total' => 25],
        ];
        $results1 = [
            'ディーラー'     => 'バースト',
            'あなた'         => 'バースト',
            'CPUプレイヤー1' => 'バースト',
            'CPUプレイヤー2' => 'バースト',
        ];
        $this->assertSame($results1, $handJudger->determineWinner($participants1));


        $participants2 = [
            'dealer' => ['name' => 'ディーラー',     'obj' => $dealer,     'total' => 22],
            'player' => ['name' => 'あなた',         'obj' => $player,     'total' => 10],
            '1'      => ['name' => 'CPUプレイヤー1', 'obj' => $cpuPlayer1, 'total' => 20],
            '2'      => ['name' => 'CPUプレイヤー2', 'obj' => $cpuPlayer2, 'total' => 21],
        ];
        $results2 = [
            'ディーラー'     => 'バースト',
            'あなた'         => '勝ち',
            'CPUプレイヤー1' => '勝ち',
            'CPUプレイヤー2' => '勝ち',
        ];
        $this->assertSame($results2, $handJudger->determineWinner($participants2));


        $participants3 = [
            'dealer' => ['name' => 'ディーラー',     'obj' => $dealer,     'total' => 20],
            'player' => ['name' => 'あなた',         'obj' => $player,     'total' => 20],
            '1'      => ['name' => 'CPUプレイヤー1', 'obj' => $cpuPlayer1, 'total' => 19],
            '2'      => ['name' => 'CPUプレイヤー2', 'obj' => $cpuPlayer2, 'total' => 21],
        ];
        $results3 = [
            'ディーラー'     => '引き分け',
            'あなた'         => '引き分け',
            'CPUプレイヤー1' => '負け',
            'CPUプレイヤー2' => '勝ち',
        ];
        $this->assertSame($results3, $handJudger->determineWinner($participants3));
    }
}
