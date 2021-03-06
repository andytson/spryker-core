<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\ProductAlternativeDataSet;

interface ProductAlternativeDataSetInterface
{
    public const KEY_COLUMN_CONCRETE_SKU = 'concrete_sku';
    public const FK_PRODUCT = 'fkProduct';
    public const KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU = 'alternative_product_concrete_sku';
    public const FK_PRODUCT_CONCRETE_ALTERNATIVE = 'fkProductConcreteAlternative';
    public const KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU = 'alternative_product_abstract_sku';
    public const FK_PRODUCT_ABSTRACT_ALTERNATIVE = 'fkProductAbstractAlternative';
}
