<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsProductReservationTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityPersistenceFactory getFactory()
 */
class AvailabilityQueryContainer extends AbstractQueryContainer implements AvailabilityQueryContainerInterface
{

    const SUM_QUANTITY = 'sumQuantity';
    const ABSTRACT_SKU = 'abstractSku';
    const AVAILABILITY_QUANTITY = 'availabilityQuantity';
    const STOCK_QUANTITY = 'stockQuantity';
    const RESERVATION_QUANTITY = 'reservationQuantity';
    const PRODUCT_NAME = 'productName';
    const CONCRETE_SKU = 'concreteSku';
    const CONCRETE_AVAILABILITY = 'concreteAvailability';
    const CONCRETE_NAME = 'concreteName';
    const ID_PRODUCT = 'idProduct';
    const GROUP_CONCAT = "GROUP_CONCAT";
    const CONCAT = "CONCAT";

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery
     */
    public function querySpyAvailabilityBySku($sku)
    {
        return $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterBySku($sku);
    }

    /**
     * @api
     *
     * @param string $abstractSku
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function querySpyAvailabilityAbstractByAbstractSku($abstractSku)
    {
        return $this->getFactory()
            ->createSpyAvailabilityAbstractQuery()
            ->filterByAbstractSku($abstractSku);
    }

    /**
     * @api
     *
     * @param int $idAvailabilityAbstract
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract)
    {
        return $this->getFactory()
            ->createSpyAvailabilityAbstractQuery()
            ->filterByIdAvailabilityAbstract($idAvailabilityAbstract);
    }

    /**
     * @api
     *
     * @param int $idAvailabilityAbstract
     *
     * @return \Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery
     */
    public function querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract)
    {
        return $this->getFactory()
            ->createSpyAvailabilityQuery()
            ->filterByFkAvailabilityAbstract($idAvailabilityAbstract)
            ->withColumn('SUM(' . SpyAvailabilityTableMap::COL_QUANTITY . ')', self::SUM_QUANTITY)
            ->select([self::SUM_QUANTITY]);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function querySpyProductBySku($sku)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductConcreteBySku($sku)
            ->innerJoinSpyProductAbstract()
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, self::ABSTRACT_SKU);
    }

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithStockByIdLocale($idLocale)
    {
        return $this->querySpyProductAbstractAvailabilityWithStockByIdLocale($idLocale)
            ->withColumn('SUM(' . SpyStockProductTableMap::COL_QUANTITY . ')', self::STOCK_QUANTITY)
            ->withColumn("" . self::GROUP_CONCAT . "(" . self::CONCAT . "(" . SpyProductTableMap::COL_ID_PRODUCT . ",':'," . SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY . "))", self::RESERVATION_QUANTITY)
            ->groupBy(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdLocale($idLocale)
    {
        return $this->querySpyProductAbstractAvailabilityWithStockByIdLocale($idLocale)
            ->addJoin(SpyProductTableMap::COL_ID_PRODUCT, SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT, Criteria::INNER_JOIN)
            ->addJoin(SpyProductTableMap::COL_SKU, SpyAvailabilityTableMap::COL_SKU,Criteria::INNER_JOIN)
            ->addAnd(SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE, $idLocale);
    }

    /**
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function querySpyProductAbstractAvailabilityWithStockByIdLocale($idLocale)
    {
        return $this->querySpyProductAbstractAvailabilityWithStock()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, self::PRODUCT_NAME)
            ->withColumn(SpyAvailabilityAbstractTableMap::COL_QUANTITY, self::AVAILABILITY_QUANTITY);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function querySpyProductAbstractAvailabilityWithStock()
    {
        return $this->querySpyProductAbstractAvailability()
            ->addJoin(SpyProductTableMap::COL_ID_PRODUCT, SpyStockProductTableMap::COL_FK_PRODUCT, Criteria::INNER_JOIN)
            ->addJoin(SpyProductTableMap::COL_SKU, SpyOmsProductReservationTableMap::COL_SKU, Criteria::LEFT_JOIN);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function querySpyProductAbstractAvailability()
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->addJoin(SpyProductAbstractTableMap::COL_SKU, SpyAvailabilityAbstractTableMap::COL_ABSTRACT_SKU ,Criteria::INNER_JOIN)
            ->addJoin(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, Criteria::LEFT_JOIN);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale)
    {
        return $this->queryAvailabilityAbstractWithStockByIdLocale($idLocale)
            ->filterByIdProductAbstract($idProductAbstract);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale)
    {
        return $this->queryAvailabilityWithStockByIdLocale($idLocale)
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, self::ID_PRODUCT)
            ->withColumn(SpyProductTableMap::COL_SKU, self::CONCRETE_SKU)
            ->withColumn(SpyAvailabilityTableMap::COL_QUANTITY, self::CONCRETE_AVAILABILITY)
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, self::CONCRETE_NAME)
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE)
            ->withColumn('SUM(' . SpyStockProductTableMap::COL_QUANTITY . ')', self::STOCK_QUANTITY)
            ->withColumn(SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY, self::RESERVATION_QUANTITY)
            ->filterByIdProductAbstract($idProductAbstract)
            ->select([self::CONCRETE_SKU])
            ->groupBy(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);
    }

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockByIdProduct($idProduct)
    {
        return $this->getFactory()
            ->getStockQueryContainer()
            ->queryStockByProducts($idProduct)
            ->useStockQuery()
                ->withColumn(SpyStockTableMap::COL_NAME, 'stockType')
            ->endUse()
            ->useSpyProductQuery()
                ->withColumn(SpyProductTableMap::COL_SKU, 'sku')
            ->endUse();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    public function queryAllStockType()
    {
        return $this->getFactory()
            ->getStockQueryContainer()
            ->queryAllStockTypes();
    }

}
