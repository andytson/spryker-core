<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountMerchantSalesOrder\Business;

use Spryker\Zed\DiscountMerchantSalesOrder\Business\Filter\MerchantOrderDiscountFilter;
use Spryker\Zed\DiscountMerchantSalesOrder\Business\Filter\MerchantOrderDiscountFilterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DiscountMerchantSalesOrder\DiscountMerchantSalesOrderConfig getConfig()
 */
class DiscountMerchantSalesOrderBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DiscountMerchantSalesOrder\Business\Filter\MerchantOrderDiscountFilterInterface
     */
    public function createMerchantOrderDiscountFilter(): MerchantOrderDiscountFilterInterface
    {
        return new MerchantOrderDiscountFilter();
    }
}
