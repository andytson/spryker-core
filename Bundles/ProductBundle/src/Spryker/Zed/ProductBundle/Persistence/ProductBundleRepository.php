<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence;

use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundleQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundlePersistenceFactory getFactory()
 */
class ProductBundleRepository extends AbstractRepository implements ProductBundleRepositoryInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function findBundledProductsBySku(string $sku): array
    {
        $productBundleEntities = $this->getFactory()
            ->createProductBundleQuery()
            ->joinWithSpyProductRelatedByFkProduct()
            ->useSpyProductRelatedByFkProductQuery()
                ->filterBySku($sku)
            ->endUse()
            ->find();

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapProductBundleEntitiesToProductForBundleTransfers($productBundleEntities->getArrayCopy());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleCollectionTransfer
     */
    public function getProductBundleCollectionByCriteriaFilter(ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer): ProductBundleCollectionTransfer
    {
        $productBundleQuery = $this->getFactory()
            ->createProductBundleQuery()
            ->joinWithSpyProductRelatedByFkProduct();

        if ($productBundleCriteriaFilterTransfer->getIdBundledProduct()) {
            $productBundleQuery->filterByFkBundledProduct($productBundleCriteriaFilterTransfer->getIdBundledProduct());
        }

        $productBundleEntities = $productBundleQuery->find();

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapProductBundleEntitiesToProductBundleCollectionTransfer($productBundleEntities->getArrayCopy(), new ProductBundleCollectionTransfer());
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function getBundleItemsByIdProduct(int $idProductConcrete): array
    {
        $productBundleEntities = $this->getFactory()
            ->createProductBundleQuery()
            ->filterByFkProduct($idProductConcrete)
            ->joinWithSpyProductRelatedByFkBundledProduct()
            ->useSpyProductRelatedByFkBundledProductQuery()
            ->endUse()
            ->find()
            ->getData();

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapProductBundleEntitiesToProductForBundleTransfers($productBundleEntities);
    }

    /* @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer[]
     */

    public function getBundledProductByIdProduct(int $idProductConcrete): array
    {
        $productBundleEntities = $this->getFactory()
            ->createProductBundleQuery()
            ->filterByFkBundledProduct($idProductConcrete)
            ->find()
            ->getData();

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapProductBundleEntitiesToProductForBundleTransfers($productBundleEntities);
    }

    /**
     * @param \Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundleQuery $productBundleQuery
     * @param \Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
     *
     * @return \Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundleQuery
     */
    protected function setProductBundleQueryFilters(
        SpyProductBundleQuery $productBundleQuery,
        ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
    ): SpyProductBundleQuery {
        if ($productBundleCriteriaFilterTransfer->getIdBundledProduct()) {
            $productBundleQuery->filterByFkBundledProduct($productBundleCriteriaFilterTransfer->getIdBundledProduct());
        }

        return $productBundleQuery;
    }
}
