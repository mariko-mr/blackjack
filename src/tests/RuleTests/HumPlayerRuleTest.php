<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;

use Blackjack\Rule\HumPlayerRule;

require_once(__DIR__ . '/../../lib/Rule/HumPlayerRule.php');

final class HumPlayerRuleTest extends TestCase
{
    public function testIsBust(): void
    {
        $humPlayerRule = new HumPlayerRule();

        $this->assertFalse($humPlayerRule->isBust(21));
        $this->assertTrue($humPlayerRule->isBust(22));
    }
}
