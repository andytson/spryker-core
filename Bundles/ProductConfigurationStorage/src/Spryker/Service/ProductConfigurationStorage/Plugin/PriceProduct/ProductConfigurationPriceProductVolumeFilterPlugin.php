<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfigurationStorage\Plugin\PriceProduct;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface;
use Spryker\Shared\ProductConfigurationStorage\ProductConfigurationStorageConfig;

/**
 * @method \Spryker\Service\ProductConfigurationStorage\ProductConfigurationStorageServiceInterface getService()()
 */
class ProductConfigurationPriceProductVolumeFilterPlugin extends AbstractPlugin implements PriceProductFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Compares singular item prices with volume prices.
     * - Finds corresponding volume price for provided quantity.
     * - Returns singular item prices if matching volume price can not be found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function filter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
        return $this->getService()->filterProductConfigurationVolumePrices($priceProductTransfers, $priceProductFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string
    {
        return ProductConfigurationStorageConfig::PRICE_DIMENSION_PRODUCT_CONFIGURATION;
    }
}
