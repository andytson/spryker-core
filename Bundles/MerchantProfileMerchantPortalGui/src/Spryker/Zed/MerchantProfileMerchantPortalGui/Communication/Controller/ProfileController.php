<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\MerchantProfileMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProfileController extends AbstractController
{
    protected const MESSAGE_MERCHANT_UPDATE_SUCCESS = 'The Profile has been changed successfully.';

    /**
     * @phpstan-return array<mixed>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        /** @var int $idMerchant */
        $idMerchant = $this->getFactory()
            ->getMerchantUserFacade()
            ->getCurrentMerchantUser()
            ->requireIdMerchant()
            ->getIdMerchant();

        $merchantProfileFormDataProvider = $this->getFactory()->createMerchantProfileFormDataProvider();
        $merchantTransfer = $merchantProfileFormDataProvider->findMerchantById($idMerchant);

        $merchantProfileForm = $this->getFactory()->createMerchantProfileForm($merchantTransfer);
        $merchantProfileForm->handleRequest($request);

        if ($merchantProfileForm->isSubmitted() && $merchantProfileForm->isValid()) {
            $this->updateMerchant($merchantProfileForm);
        }

        return $this->viewResponse([
            'form' => $merchantProfileForm->createView(),
        ]);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $merchantForm
     *
     * @param \Symfony\Component\Form\FormInterface $merchantForm
     *
     * @return void
     */
    protected function updateMerchant(FormInterface $merchantForm): void
    {
        $merchantTransfer = $merchantForm->getData();

        $merchantResponseTransfer = $this->getFactory()
            ->getMerchantFacade()
            ->updateMerchant($merchantTransfer);

        if ($merchantResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::MESSAGE_MERCHANT_UPDATE_SUCCESS);

            return;
        }

        foreach ($merchantResponseTransfer->getErrors() as $merchantErrorTransfer) {
            /** @var string $message */
            $message = $merchantErrorTransfer->requireMessage()->getMessage();
            $this->addErrorMessage($message);
        }
    }
}
