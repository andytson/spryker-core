<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface ManualOrderEntryFormPluginInterface
{
    /**
     * Specification:
     * - Provides form name.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Specification:
     * - Provides a form type.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, QuoteTransfer $quoteTransfer): FormInterface;

    /**
     * Specification:
     * - Handle data on form submit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleData(QuoteTransfer $quoteTransfer, &$form, Request $request): QuoteTransfer;

    /**
     * Specification:
     * - Checks if form has pre defined values.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isFormPreFilled(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Checks if form is skipped.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isFormSkipped(Request $request, QuoteTransfer $quoteTransfer): bool;
}
