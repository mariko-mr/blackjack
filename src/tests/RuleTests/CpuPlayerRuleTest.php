<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;

use Blackjack\Rule\CpuPlayerRule;
use Blackjack\Rule\AceRule;
use Blackjack\Participants\CpuPlayer;

require_once(__DIR__ . '/../../lib/Rule/CpuPlayerRule.php');

final class CpuPlayerRuleTest extends TestCase
{
    public function testShouldDrawCard(): void
    {
        $cpuPlayer = new CpuPlayer(new CpuPlayerRule, new AceRule);
        $this->assertTrue(CpuPlayerRule::shouldDrawCard($cpuPlayer));
    }

    public function testIsBust(): void
    {
        $cpuPlayerRule = new CpuPlayerRule();

        $this->assertFalse($cpuPlayerRule->isBust(21));
        $this->assertTrue($cpuPlayerRule->isBust(22));
    }
}
