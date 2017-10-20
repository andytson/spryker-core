<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Transfer;

use Symfony\Component\Form\FormInterface;

interface AttributeTranslationFormTransferMapperInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $translationForm
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function createTransfer(FormInterface $translationForm);
}
