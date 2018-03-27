<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Persistence;

interface ProductQuantityStorageRepositoryInterface
{
    /**
     * Specification:
     * - Retrieves product quantity storage entities by the provided related product IDs.
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityStorageEntityTransfer[]
     */
    public function getProductQuantityStorageEntitiesByProductIds(array $productIds);
}
