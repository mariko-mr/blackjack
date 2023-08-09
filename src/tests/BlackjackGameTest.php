<?php

declare(strict_types=1);

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\BlackjackGame;

require_once(__DIR__ . '/../lib/BlackjackGame.php');

final class BlackjackGameTest extends TestCase
{
    public function testBlackjackGameStart(): void
    {
        $game = new BlackjackGame();
        $result = $game->blackjackGameStart();
        $this->assertSame(1, $result);
    }
}
