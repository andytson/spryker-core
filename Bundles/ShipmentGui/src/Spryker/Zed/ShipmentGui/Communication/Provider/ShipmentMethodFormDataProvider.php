<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Provider;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToTaxFacadeInterface;

class ShipmentMethodFormDataProvider extends ViewShipmentMethodFormDataProvider
{
    public const OPTION_CARRIER_CHOICES = 'carrier_choices';
    public const OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST = 'availability_plugin_choice_list';
    public const OPTION_PRICE_PLUGIN_CHOICE_LIST = 'price_plugin_choice_list';
    public const OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST = 'delivery_time_plugin_choice_list';
    public const OPTION_MONEY_FACADE = 'money facade';
    public const OPTION_DELIVERY_KEY_DISABLED = 'option_delivery_key_disabled';

    protected const KEY_AVAILABILITY = 'AVAILABILITY_PLUGINS';
    protected const KEY_PRICE = 'PRICE_PLUGINS';
    protected const KEY_DELIVERY_TIME = 'DELIVERY_TIME_PLUGINS';

    /**
     * @var \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    protected $carrierQuery;

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface $shipmentFacade
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery $carrierQuery
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToTaxFacadeInterface $taxFacade
     */
    public function __construct(
        ShipmentGuiToShipmentFacadeInterface $shipmentFacade,
        SpyShipmentCarrierQuery $carrierQuery,
        ShipmentGuiToTaxFacadeInterface $taxFacade
    ) {
        parent::__construct($taxFacade);
        $this->carrierQuery = $carrierQuery;
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getData(ShipmentMethodTransfer $shipmentMethodTransfer): ShipmentMethodTransfer
    {
        return $shipmentMethodTransfer;
    }

    /**
     * @param bool $isDeliveryKeyDisabled
     *
     * @return array
     */
    public function getOptions(bool $isDeliveryKeyDisabled = false): array
    {
        $options = [
            static::OPTION_CARRIER_CHOICES => $this->getCarrierOptions(),
            static::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST => $this->getPluginOptions(static::KEY_AVAILABILITY),
            static::OPTION_PRICE_PLUGIN_CHOICE_LIST => $this->getPluginOptions(static::KEY_PRICE),
            static::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST => $this->getPluginOptions(static::KEY_DELIVERY_TIME),
        ];

        $options = array_merge(parent::getOptions(), $options);
        $options[static::OPTION_PRICES_DISABLED] = false;
        $options[static::OPTION_STORE_RELATION_DISABLED] = false;
        $options[static::OPTION_TAX_SET_DISABLED] = false;
        $options[static::OPTION_DELIVERY_KEY_DISABLED] = $isDeliveryKeyDisabled;

        return $options;
    }

    /**
     * @return string[]
     */
    protected function getCarrierOptions(): array
    {
        $carriers = $this->carrierQuery
            ->filterByIsActive(true)
            ->find();

        $result = [];

        foreach ($carriers as $carrier) {
            $result[$carrier->getIdShipmentCarrier()] = $carrier->getName();
        }

        return $result;
    }

    /**
     * @param string $pluginsType
     *
     * @return string[]
     */
    private function getPluginOptions($pluginsType): array
    {
        $plugins = array_keys($this->shipmentFacade->getShipmentMethodPlugins()[$pluginsType]);

        return array_combine($plugins, $plugins);
    }
}
