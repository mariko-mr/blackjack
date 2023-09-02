<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;

use Blackjack\Rule\DealerRule;
use Blackjack\Rule\AceRule;
use Blackjack\Participants\Dealer;

require_once(__DIR__ . '/../../lib/Rule/DealerRule.php');

final class DealerRuleTest extends TestCase
{
    public function testIsBust(): void
    {
        $dealerRule = new DealerRule();

        $this->assertFalse($dealerRule->isBust(21));
        $this->assertTrue($dealerRule->isBust(22));
    }

        public function testShouldDrawCard(): void
    {
        $dealerRule = new DealerRule();
        $this->assertFalse($dealerRule->shouldDrawCard(17));
        $this->assertTrue($dealerRule->shouldDrawCard(16));
    }

}
