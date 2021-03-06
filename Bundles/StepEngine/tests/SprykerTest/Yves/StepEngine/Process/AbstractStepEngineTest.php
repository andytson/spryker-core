<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\StepEngine\Process;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface;
use Spryker\Yves\StepEngine\Process\StepCollection;
use SprykerTest\Yves\StepEngine\Process\Fixtures\StepMock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group StepEngine
 * @group Process
 * @group AbstractStepEngineTest
 * Add your own group annotations below this line
 */
abstract class AbstractStepEngineTest extends Unit
{
    public const ERROR_ROUTE = 'error-route';
    public const ERROR_URL = '/error/url';

    public const ESCAPE_ROUTE = 'escape-route';
    public const ESCAPE_URL = '/escape/url';

    public const STEP_ROUTE_A = 'step-route-a';
    public const STEP_URL_A = '/step/url/a';

    public const STEP_ROUTE_B = 'step-route-b';
    public const STEP_URL_B = '/step/url/b';

    public const STEP_ROUTE_C = 'step-route-c';
    public const STEP_URL_C = '/step/url/c';

    public const STEP_ROUTE_D = 'step-route-d';
    public const STEP_URL_D = '/step/url/d';

    public const EXTERNAL_URL = 'http://external.de';

    /**
     * @return \Spryker\Yves\StepEngine\Process\StepCollection
     */
    protected function getStepCollection(): StepCollection
    {
        return new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected function getUrlGeneratorMock(): UrlGeneratorInterface
    {
        $urlGeneratorMock = $this->getMockBuilder(UrlGeneratorInterface::class)->getMock();
        $urlGeneratorMock->method('generate')->will($this->returnCallback([$this, 'urlGeneratorCallBack']));

        return $urlGeneratorMock;
    }

    /**
     * @param string $input
     *
     * @return string
     */
    public function urlGeneratorCallBack(string $input): string
    {
        $map = [
            self::ERROR_ROUTE => self::ERROR_URL,
            self::ESCAPE_ROUTE => self::ESCAPE_URL,
            self::STEP_ROUTE_A => self::STEP_URL_A,
            self::STEP_ROUTE_B => self::STEP_URL_B,
            self::STEP_ROUTE_C => self::STEP_URL_C,
        ];

        return $map[$input];
    }

    /**
     * @param bool $preCondition
     * @param bool $postCondition
     * @param bool $requireInput
     * @param string $stepRoute
     * @param string|null $escapeRoute
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    protected function getStepMock(
        bool $preCondition = true,
        bool $postCondition = true,
        bool $requireInput = true,
        string $stepRoute = '',
        ?string $escapeRoute = null
    ): StepInterface {
        return new StepMock($preCondition, $postCondition, $requireInput, $stepRoute, $escapeRoute);
    }

    /**
     * @param string $route
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(string $route = ''): Request
    {
        $request = Request::createFromGlobals();
        $request->request->set('_route', $route);

        return $request;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function getDataTransferMock(): AbstractTransfer
    {
        return $this->getMockBuilder(AbstractTransfer::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface
     */
    protected function getStepWithExternalRedirectUrl(): StepWithExternalRedirectInterface
    {
        $stepMock = $this->getMockBuilder(StepWithExternalRedirectInterface::class)->getMock();
        $stepMock->method('getExternalRedirectUrl')->willReturn(self::EXTERNAL_URL);

        return $stepMock;
    }
}
