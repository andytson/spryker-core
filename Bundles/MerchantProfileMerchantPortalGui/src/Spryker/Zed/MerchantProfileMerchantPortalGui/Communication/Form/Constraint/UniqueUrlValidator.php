<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\MerchantProfileUrlCollection\MerchantProfileUrlCollectionFormType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\MerchantProfileMerchantPortalGuiCommunicationFactory getFactory()
 */
class UniqueUrlValidator extends AbstractConstraintValidator
{
    /**
     * Checks if the passed url is unique.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        $url = $value->getUrl();
        if (!$url) {
            return;
        }

        if (!$constraint instanceof UniqueUrl) {
            throw new UnexpectedTypeException($constraint, UniqueUrl::class);
        }

        if (!$this->isUrlChanged($value, $constraint)) {
            return;
        }

        if ($this->hasUrlCaseInsensitive($url)) {
            $this->context
                ->buildViolation(sprintf('Provided URL "%s" is already taken.', $value->getUrl()))
                ->atPath(MerchantProfileUrlCollectionFormType::FIELD_URL)
                ->addViolation();
        }
    }

    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    protected function findExistingUrl(string $url): ?UrlTransfer
    {
        $urlTransfer = $this->createUrlTransfer($url);

        return $this->getFactory()->getUrlFacade()->findUrlCaseInsensitive($urlTransfer);
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    protected function hasUrlCaseInsensitive(string $url): bool
    {
        $urlTransfer = $this->createUrlTransfer($url);

        return $this->getFactory()->getUrlFacade()->hasUrlCaseInsensitive($urlTransfer);
    }

    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer(string $url): UrlTransfer
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl($url);

        return $urlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Constraint\UniqueUrl $constraint
     *
     * @return bool
     */
    protected function isUrlChanged(UrlTransfer $urlTransfer, UniqueUrl $constraint): bool
    {
        /** @var string $url */
        $url = $urlTransfer->requireUrl()->getUrl();
        $existingUrlTransfer = $this->findExistingUrl($url);

        if (!$existingUrlTransfer) {
            return true;
        }

        $idMerchant = $existingUrlTransfer->getFkResourceMerchant();

        if (!$idMerchant) {
            return true;
        }

        return (int)$idMerchant !== (int)$urlTransfer->getFkResourceMerchant();
    }
}
