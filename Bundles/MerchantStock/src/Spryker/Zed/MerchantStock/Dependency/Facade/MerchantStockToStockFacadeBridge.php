<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Dependency\Facade;

use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;

class MerchantStockToStockFacadeBridge implements MerchantStockToStockFacadeInterface
{
    /**
     * @var \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @param \Spryker\Zed\Stock\Business\StockFacadeInterface $stockFacade
     */
    public function __construct($stockFacade)
    {
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function createStock(StockTransfer $stockTransfer): StockResponseTransfer
    {
        return $this->stockFacade->createStock($stockTransfer);
    }
}
