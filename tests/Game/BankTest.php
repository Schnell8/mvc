<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Bank.
 */
class BankTest extends TestCase
{
    /**
     * Test create Bank object and verify that it's done correctly
     */
    public function testCreateBankObject(): void
    {
        $bank = new Bank();
        $this->assertInstanceOf(Bank::class, $bank);
    }

    /**
     * Test add card to Bank's hand
     */
    public function testAddCardToBankHand(): void
    {
        $bank = new Bank();
        $deck = new Deck();

        $card = $deck->drawCard();
        $bank->addCard($card);

        $this->assertCount(1, $bank->getHand());
        $this->assertSame($card, $bank->getHand()[0]);
    }
}