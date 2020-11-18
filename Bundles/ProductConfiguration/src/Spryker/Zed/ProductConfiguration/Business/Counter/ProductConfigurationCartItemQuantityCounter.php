<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business\Counter;

use ArrayObject;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface;

class ProductConfigurationCartItemQuantityCounter implements ProductConfigurationCartItemQuantityCounterInterface
{
    protected const DEFAULT_ITEM_QUANTITY = 0;

    /**
     * @var \Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface
     */
    protected $productConfigurationService;

    /**
     * @param \Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface $productConfigurationService
     */
    public function __construct(ProductConfigurationServiceInterface $productConfigurationService)
    {
        $this->productConfigurationService = $productConfigurationService;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countCartItemQuantity(
        ArrayObject $itemsInCart,
        ItemTransfer $itemTransfer
    ): CartItemQuantityTransfer {
        $currentItemQuantity = static::DEFAULT_ITEM_QUANTITY;

        foreach ($itemsInCart as $itemInCartTransfer) {
            if (!$this->isSameItem($itemInCartTransfer, $itemTransfer)) {
                continue;
            }

            $currentItemQuantity += $itemInCartTransfer->getQuantity() ?? static::DEFAULT_ITEM_QUANTITY;
        }

        return (new CartItemQuantityTransfer())->setQuantity($currentItemQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemInCartTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isSameItem(
        ItemTransfer $itemInCartTransfer,
        ItemTransfer $itemTransfer
    ): bool {
        return $itemInCartTransfer->getSku() === $itemTransfer->getSku()
            && $this->isSameProductConfigurationItem($itemInCartTransfer, $itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemInCartTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isSameProductConfigurationItem(ItemTransfer $itemInCartTransfer, ItemTransfer $itemTransfer): bool
    {
        $itemInCartProductConfigurationInstanceTransfer = $itemInCartTransfer->getProductConfigurationInstance();
        $itemProductConfigurationInstanceTransfer = $itemTransfer->getProductConfigurationInstance();

        return ($itemInCartProductConfigurationInstanceTransfer === null && $itemProductConfigurationInstanceTransfer === null)
            || $this->isProductConfigurationInstanceHashEquals(
                $itemInCartProductConfigurationInstanceTransfer,
                $itemProductConfigurationInstanceTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $itemInCartProductConfigurationInstanceTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $itemProductConfigurationInstanceTransfer
     *
     * @return bool
     */
    protected function isProductConfigurationInstanceHashEquals(
        ?ProductConfigurationInstanceTransfer $itemInCartProductConfigurationInstanceTransfer,
        ?ProductConfigurationInstanceTransfer $itemProductConfigurationInstanceTransfer
    ): bool {
        if ($itemInCartProductConfigurationInstanceTransfer === null || $itemProductConfigurationInstanceTransfer === null) {
            return false;
        }

        return $this->productConfigurationService->getProductConfigurationInstanceHash($itemInCartProductConfigurationInstanceTransfer)
            === $this->productConfigurationService->getProductConfigurationInstanceHash($itemProductConfigurationInstanceTransfer);
    }
}
