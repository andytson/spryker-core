<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValue;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValuePersistenceFactory getFactory()
 */
class MerchantRelationshipMinimumOrderValueEntityManager extends AbstractEntityManager implements MerchantRelationshipMinimumOrderValueEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function setMerchantRelationshipThreshold(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $merchantRelationshipMinimumOrderValueTransfer
            ->requireMinimumOrderValueType()
            ->requireMerchantRelationship()
            ->requireStore()
            ->requireCurrency()
            ->requireValue();

        $minimumOrderValueTypeTransfer = $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValueType();
        $merchantRelationshipTransfer = $merchantRelationshipMinimumOrderValueTransfer->getMerchantRelationship();
        $storeTransfer = $merchantRelationshipMinimumOrderValueTransfer->getStore();
        $currencyTransfer = $merchantRelationshipMinimumOrderValueTransfer->getCurrency();

        $minimumOrderValueTypeTransfer->requireIdMinimumOrderValueType()->requireThresholdGroup();
        $merchantRelationshipTransfer->requireIdMerchantRelationship();
        $storeTransfer->requireIdStore();
        $currencyTransfer->requireIdCurrency();

        $merchantRelationshipMinimumOrderValueEntity = $this->getFactory()
            ->createMerchantRelationshipMinimumOrderValueQuery()
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterByFkCurrency($currencyTransfer->getIdCurrency())
            ->filterByFkMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationship())
            ->useMinimumOrderValueTypeQuery()
                ->filterByThresholdGroup($minimumOrderValueTypeTransfer->getThresholdGroup())
            ->endUse()
            ->findOne();

        if (!$merchantRelationshipMinimumOrderValueEntity) {
            $merchantRelationshipMinimumOrderValueEntity = (new SpyMerchantRelationshipMinimumOrderValue())
                ->setFkStore($storeTransfer->getIdStore())
                ->setFkCurrency($currencyTransfer->getIdCurrency());
        }

        $merchantRelationshipMinimumOrderValueEntity
            ->setValue($merchantRelationshipMinimumOrderValueTransfer->getValue())
            ->setFee($merchantRelationshipMinimumOrderValueTransfer->getFee())
            ->setFkMerchantRelationship(
                $merchantRelationshipTransfer->getIdMerchantRelationship()
            )->setFkMinOrderValueType(
                $minimumOrderValueTypeTransfer->getIdMinimumOrderValueType()
            )->save();

        $merchantRelationshipMinimumOrderValueTransfer = $this->getFactory()
            ->createMerchantRelationshipMinimumOrderValueMapper()
            ->mapMerchantRelationshipMinimumOrderValueEntityToTransfer(
                $merchantRelationshipMinimumOrderValueEntity,
                new MerchantRelationshipMinimumOrderValueTransfer()
            );

        return $merchantRelationshipMinimumOrderValueTransfer;
    }
}
