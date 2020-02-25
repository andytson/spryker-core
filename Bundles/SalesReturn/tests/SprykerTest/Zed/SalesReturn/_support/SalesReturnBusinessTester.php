<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnReasonTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnReason;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\SalesReturn\Business\SalesReturnFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesReturnBusinessTester extends Actor
{
    use _generated\SalesReturnBusinessTesterActions;

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderWithFakeRemuneration(): OrderTransfer
    {
        $itemTransfers = [
            (new ItemTransfer())
                ->setRemunerationAmount(100),
            (new ItemTransfer())
                ->setRemunerationAmount(200),
            (new ItemTransfer())
                ->setRemunerationAmount(300),
        ];

        return (new OrderTransfer())
            ->setItems(new ArrayObject($itemTransfers))
            ->setTotals(new TotalsTransfer());
    }

    /**
     * @param string[] $glossaryKeyReasons
     *
     * @return \Generated\Shared\Transfer\ReturnReasonTransfer[]
     */
    public function createReturnReasons(array $glossaryKeyReasons): array
    {
        $returnReasonTransfers = [];

        foreach ($glossaryKeyReasons as $glossaryKeyReason) {
            $salesReturnReasonEntity = (new SpySalesReturnReason())
                ->setGlossaryKeyReason($glossaryKeyReason);

            $salesReturnReasonEntity->save();

            $returnReasonTransfers[] = (new ReturnReasonTransfer())->fromArray($salesReturnReasonEntity->toArray(), true);
        }

        return $returnReasonTransfers;
    }

    /**
     * @return void
     */
    public function ensureReturnReasonTablesIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesReturnReasonQuery());
    }

    /**
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery
     */
    protected function getSalesReturnReasonQuery(): SpySalesReturnReasonQuery
    {
        return SpySalesReturnReasonQuery::create();
    }
}
