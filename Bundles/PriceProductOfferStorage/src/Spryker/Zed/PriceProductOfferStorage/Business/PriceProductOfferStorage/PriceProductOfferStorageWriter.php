<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Business\PriceProductOfferStorage;

use Generated\Shared\Transfer\PriceProductOfferStorageTransfer;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProductOffer\Persistence\Map\SpyPriceProductOfferTableMap;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery;
use Orm\Zed\PriceProductOfferStorage\Persistence\SpyProductConcreteProductOfferPriceStorageQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventFacadeInterface;

class PriceProductOfferStorageWriter implements PriceProductOfferStorageWriterInterface
{
    protected const ID_PRICE_PRODUCT_OFFER = 'id_price_product_offer';
    protected const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    protected const COL_ID_PRODUCT_NAME = 'IdProduct';
    protected const COL_SKU_NAME = 'Sku';

    /**
     * @var \Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\PriceProductOfferStorage\Dependency\Facade\PriceProductOfferStorageToEventFacadeInterface $eventFacade
     */
    public function __construct(PriceProductOfferStorageToEventFacadeInterface $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param int[] $priceProductOfferIds
     *
     * @return void
     */
    public function publish(array $priceProductOfferIds): void
    {
        $productSkus = $this->getProductSkusByPriceProductOfferIds($priceProductOfferIds);
        $priceProductOffers = $this->getProductOfferDataByProductSkus($productSkus);
        $productSkuToIdMap = $this->getActiveProductIdToSkuMapBySkus($productSkus);

        $this->savePriceProductOfferStorage($priceProductOffers, $productSkuToIdMap);
    }

    /**
     * @param int[] $priceProductOfferIdsWithOfferIds
     *
     * @return void
     */
    public function unpublish(array $priceProductOfferIdsWithOfferIds): void
    {
        $priceProductOfferIds = array_keys($priceProductOfferIdsWithOfferIds);
        $productOfferIds = array_values($priceProductOfferIdsWithOfferIds);
        $productIds = $this->getProductIdsByProductOfferIds($productOfferIds);
        $productConcreteProductOfferPriceStorageEntities = $this->getProductConcreteProductOfferPriceStorageEntities($productIds);

        foreach ($productConcreteProductOfferPriceStorageEntities as $productConcreteProductOfferPriceStorageEntity) {
            $offerPrices = $productConcreteProductOfferPriceStorageEntity->getData();
            foreach ($offerPrices as $key => $offerPrice) {
                if (in_array($offerPrice[static::ID_PRICE_PRODUCT_OFFER], $priceProductOfferIds)) {
                    unset($offerPrices[$key]);
                }
            }
            if (empty($offerPrices)) {
                $productConcreteProductOfferPriceStorageEntity->delete();

                continue;
            }
            $productConcreteProductOfferPriceStorageEntity->setData(array_values($offerPrices));
            $productConcreteProductOfferPriceStorageEntity->save();
        }
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishByProductIds(array $productIds): void
    {
        $productEntities = $this->getProductEntitiesByProductIds($productIds);
        $publishData = [];
        $unpublishData = [];
        foreach ($productEntities as $productEntity) {
            if ($productEntity->isActive()) {
                $publishData[$productEntity->getSku()] = $productEntity->getIdProduct();

                continue;
            }
            $unpublishData[$productEntity->getSku()] = $productEntity->getIdProduct();
        }
        if ($unpublishData) {
            $this->unpublishByProductIds($unpublishData);
        }
        if ($publishData) {
            $priceProductOffers = $this->getProductOfferDataByProductSkus(array_keys($publishData));
            $this->savePriceProductOfferStorage($priceProductOffers, $publishData);
        }
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    public function unpublishByProductIds(array $productIds): void
    {
        $priceProductOfferStorageEntities = SpyProductConcreteProductOfferPriceStorageQuery::create()
            ->filterByFkProduct_In($productIds)
            ->find();

        foreach ($priceProductOfferStorageEntities as $priceProductOfferStorageEntity) {
            $priceProductOfferStorageEntity->delete();
        }
    }

    /**
     * @param int[] $priceProductOfferIds
     *
     * @return array
     */
    protected function getProductSkusByPriceProductOfferIds(array $priceProductOfferIds): array
    {
        $productSkus = SpyPriceProductOfferQuery::create()
            ->filterByIdPriceProductOffer_In($priceProductOfferIds)
            ->joinWithSpyProductOffer()
            ->select([SpyProductOfferTableMap::COL_CONCRETE_SKU])
            ->distinct()
            ->find()
            ->toArray();

        return $productSkus;
    }

    /**
     * @param string[] $productSkus
     *
     * @return array
     */
    protected function getProductOfferDataByProductSkus(array $productSkus): array
    {
        $priceProductOffers = SpyProductOfferQuery::create()
            ->useSpyPriceProductOfferQuery()
                ->useSpyPriceProductStoreQuery()
                    ->joinWithCurrency()
                    ->joinWithStore()
                    ->usePriceProductQuery()
                        ->joinWithPriceType()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->filterByConcreteSku_In($productSkus)
            ->select([
                SpyPriceProductOfferTableMap::COL_ID_PRICE_PRODUCT_OFFER,
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
                SpyCurrencyTableMap::COL_CODE,
                SpyStoreTableMap::COL_NAME,
                SpyPriceTypeTableMap::COL_NAME,
                SpyPriceProductStoreTableMap::COL_GROSS_PRICE,
                SpyPriceProductStoreTableMap::COL_NET_PRICE,
                SpyPriceProductStoreTableMap::COL_PRICE_DATA,
            ])
            ->find()
            ->toArray();

        return $priceProductOffers;
    }

    /**
     * @param string[] $productSkus
     *
     * @return array
     */
    protected function getActiveProductIdToSkuMapBySkus(array $productSkus): array
    {
        $productSkuToIdMap = SpyProductQuery::create()
            ->filterBySku_In($productSkus)
            ->filterByIsActive(true)
            ->find()
            ->toKeyValue(static::COL_SKU_NAME, static::COL_ID_PRODUCT_NAME);

        return $productSkuToIdMap;
    }

    /**
     * @param int[] $productIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getProductEntitiesByProductIds(array $productIds): ObjectCollection
    {
        $productEntities = SpyProductQuery::create()
            ->filterByIdProduct_In($productIds)
            ->find();

        return $productEntities;
    }

    /**
     * @param array $priceProductOffers
     * @param array $productSkuToIdMap
     *
     * @return void
     */
    protected function savePriceProductOfferStorage(array $priceProductOffers, array $productSkuToIdMap): void
    {
        $groupedProductOffersByStoreAndProductSku = [];
        foreach ($priceProductOffers as $productOffer) {
            $priceProductOfferStorageTransfer = $this->createPriceProductOfferStorageTransfer($productOffer);
            $groupedProductOffersByStoreAndProductSku[$productOffer[SpyStoreTableMap::COL_NAME]][$productOffer[SpyProductOfferTableMap::COL_CONCRETE_SKU]][] = $priceProductOfferStorageTransfer->modifiedToArray();
        }

        foreach ($groupedProductOffersByStoreAndProductSku as $storeName => $groupedProductOffersByProductSku) {
            foreach ($groupedProductOffersByProductSku as $productSku => $priceProductOffers) {
                if (isset($productSkuToIdMap[$productSku])) {
                    $productConcreteProductOfferPriceStorageEntity = SpyProductConcreteProductOfferPriceStorageQuery::create()
                        ->filterByFkProduct($productSkuToIdMap[$productSku])
                        ->filterByStore($storeName)
                        ->findOneOrCreate();
                    $productConcreteProductOfferPriceStorageEntity->setData($priceProductOffers);

                    $productConcreteProductOfferPriceStorageEntity->save();
                }
            }
        }
    }

    /**
     * @param array $productOffer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferStorageTransfer
     */
    protected function createPriceProductOfferStorageTransfer(array $productOffer): PriceProductOfferStorageTransfer
    {
        $priceProductOfferStorageTransfer = new PriceProductOfferStorageTransfer();
        $priceProductOfferStorageTransfer->setIdPriceProductOffer($productOffer[SpyPriceProductOfferTableMap::COL_ID_PRICE_PRODUCT_OFFER]);
        $priceProductOfferStorageTransfer->setProductOfferReference($productOffer[SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE]);
        $priceProductOfferStorageTransfer->setPriceType($productOffer[SpyPriceTypeTableMap::COL_NAME]);
        $priceProductOfferStorageTransfer->setCurrency($productOffer[SpyCurrencyTableMap::COL_CODE]);
        $priceProductOfferStorageTransfer->setNetPrice($productOffer[SpyPriceProductStoreTableMap::COL_NET_PRICE]);
        $priceProductOfferStorageTransfer->setGrossPrice($productOffer[SpyPriceProductStoreTableMap::COL_GROSS_PRICE]);
        $priceProductOfferStorageTransfer->setPriceData($productOffer[SpyPriceProductStoreTableMap::COL_PRICE_DATA]);

        return $priceProductOfferStorageTransfer;
    }

    /**
     * @param int[] $productIds
     *
     * @return \Orm\Zed\PriceProductOfferStorage\Persistence\SpyProductConcreteProductOfferPriceStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getProductConcreteProductOfferPriceStorageEntities(array $productIds): ObjectCollection
    {
        return SpyProductConcreteProductOfferPriceStorageQuery::create()
            ->filterByFkProduct_In($productIds)
            ->find();
    }

    /**
     * @param int[] $productOfferIds
     *
     * @return int[]
     */
    protected function getProductIdsByProductOfferIds(array $productOfferIds): array
    {
        $productSkus = SpyProductOfferQuery::create()
            ->filterByIdProductOffer_In($productOfferIds)
            ->select(SpyProductOfferTableMap::COL_CONCRETE_SKU)
            ->distinct()
            ->find()
            ->toArray();

        return SpyProductQuery::create()
            ->filterBySku_In($productSkus)
            ->select(SpyProductTableMap::COL_ID_PRODUCT)
            ->find()
            ->toArray();
    }
}
