<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentByCheckoutDataExpander;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentByCheckoutDataExpanderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByCheckoutDataExpander;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByCheckoutDataExpanderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByShipmentExpander;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByShipmentExpanderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMapper;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapper;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodRestResponseBuilder;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodRestResponseBuilderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentRestResponseBuilder;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentRestResponseBuilderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorter;
use Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorterInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Validator\AddressSourceCheckoutDataValidator;
use Spryker\Glue\ShipmentsRestApi\Processor\Validator\AddressSourceCheckoutDataValidatorInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Validator\ShipmentCheckoutDataValidator;
use Spryker\Glue\ShipmentsRestApi\Processor\Validator\ShipmentCheckoutDataValidatorInterface;

class ShipmentsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentByCheckoutDataExpanderInterface
     */
    public function createShipmentByCheckoutDataExpander(): ShipmentByCheckoutDataExpanderInterface
    {
        return new ShipmentByCheckoutDataExpander(
            $this->getShipmentService(),
            $this->createShipmentMapper(),
            $this->createShipmentRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByCheckoutDataExpanderInterface
     */
    public function createShipmentMethodByCheckoutDataExpander(): ShipmentMethodByCheckoutDataExpanderInterface
    {
        return new ShipmentMethodByCheckoutDataExpander(
            $this->createShipmentMethodRestResponseBuilder(),
            $this->createShipmentMethodMapper(),
            $this->createShipmentMethodSorter()
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByShipmentExpanderInterface
     */
    public function createShipmentMethodByShipmentExpander(): ShipmentMethodByShipmentExpanderInterface
    {
        return new ShipmentMethodByShipmentExpander(
            $this->createShipmentMethodRestResponseBuilder(),
            $this->createShipmentMethodMapper(),
            $this->createShipmentMethodSorter()
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMapperInterface
     */
    public function createShipmentMapper(): ShipmentMapperInterface
    {
        return new ShipmentMapper();
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface
     */
    public function createShipmentMethodMapper(): ShipmentMethodMapperInterface
    {
        return new ShipmentMethodMapper();
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentRestResponseBuilderInterface
     */
    public function createShipmentRestResponseBuilder(): ShipmentRestResponseBuilderInterface
    {
        return new ShipmentRestResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodRestResponseBuilderInterface
     */
    public function createShipmentMethodRestResponseBuilder(): ShipmentMethodRestResponseBuilderInterface
    {
        return new ShipmentMethodRestResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorterInterface
     */
    public function createShipmentMethodSorter(): ShipmentMethodSorterInterface
    {
        return new ShipmentMethodSorter();
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Validator\ShipmentCheckoutDataValidatorInterface
     */
    public function createShipmentCheckoutDataValidator(): ShipmentCheckoutDataValidatorInterface
    {
        return new ShipmentCheckoutDataValidator();
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Validator\AddressSourceCheckoutDataValidatorInterface
     */
    public function createAddressSourceCheckoutDataValidator(): AddressSourceCheckoutDataValidatorInterface
    {
        return new AddressSourceCheckoutDataValidator($this->getAddressSourceProviderPlugins());
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\AddressSourceProviderPluginInterface[]
     */
    public function getAddressSourceProviderPlugins(): array
    {
        return $this->getProvidedDependency(ShipmentsRestApiDependencyProvider::PLUGINS_ADDRESS_SOURCE_PROVIDER);
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface
     */
    public function getShipmentService(): ShipmentsRestApiToShipmentServiceInterface
    {
        return $this->getProvidedDependency(ShipmentsRestApiDependencyProvider::SERVICE_SHIPMENT);
    }
}
