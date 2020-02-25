<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Business\SalesReturnFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturn
 * @group Business
 * @group SalesReturnFacade
 * @group GetReturnReasonsTest
 * Add your own group annotations below this line
 */
class GetReturnReasonsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureReturnReasonTablesIsEmpty();
    }

    /**
     * @return void
     */
    public function testGetReturnReasonsRetrievesReturnReasons(): void
    {
        // Arrange
        $returnReasonTransfers = $this->tester->createReturnReasons([
            'return.return_reasons.fake_reason_1.name',
            'return.return_reasons.fake_reason_2.name',
        ]);

        $returnReasonFilterTransfer = new ReturnReasonFilterTransfer();

        // Act
        $returnReasonCollectionTransfer = $this->tester
            ->getFacade()
            ->getReturnReasons($returnReasonFilterTransfer);

        // Assert
        $this->assertNotEmpty($returnReasonCollectionTransfer->getReturnReasons());
        $this->assertEquals(
            $returnReasonTransfers,
            $returnReasonCollectionTransfer->getReturnReasons()->getArrayCopy()
        );
    }

    /**
     * @return void
     */
    public function testGetReturnReasonsInCaseEmptyTable(): void
    {
        // Arrange
        $returnReasonFilterTransfer = new ReturnReasonFilterTransfer();

        // Act
        $returnReasonCollectionTransfer = $this->tester
            ->getFacade()
            ->getReturnReasons($returnReasonFilterTransfer);

        // Assert
        $this->assertEmpty($returnReasonCollectionTransfer->getReturnReasons());
    }
}
