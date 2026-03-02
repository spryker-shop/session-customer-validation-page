<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionCustomerValidationPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToSessionClientInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\EventSubscriber\SaveCustomerSessionEventSubscriber;
use SprykerShop\Yves\SessionCustomerValidationPage\Extender\SessionCustomerValidationSecurityExtender;
use SprykerShop\Yves\SessionCustomerValidationPage\Extender\SessionCustomerValidationSecurityExtenderInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\FirewallListener\ValidateCustomerSessionListener;
use SprykerShop\Yves\SessionCustomerValidationPage\FirewallListener\ValidateCustomerSessionListenerInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\Updater\CustomerSessionUpdater;
use SprykerShop\Yves\SessionCustomerValidationPage\Updater\CustomerSessionUpdaterInterface;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @method \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig getConfig()
 */
class SessionCustomerValidationPageFactory extends AbstractFactory
{
    public function createSaveCustomerSessionEventSubscriber(): EventSubscriberInterface
    {
        return new SaveCustomerSessionEventSubscriber(
            $this->getCustomerClient(),
            $this->getCustomerSessionSaverPlugin(),
            $this->getConfig(),
        );
    }

    public function createCustomerSessionUpdater(): CustomerSessionUpdaterInterface
    {
        return new CustomerSessionUpdater(
            $this->getSessionClient(),
            $this->getCustomerSessionSaverPlugin(),
            $this->getConfig(),
        );
    }

    public function createValidateCustomerSessionListener(): ValidateCustomerSessionListenerInterface
    {
        return new ValidateCustomerSessionListener(
            $this->getCustomerClient(),
            $this->getCustomerSessionValidatorPlugin(),
            $this->getConfig(),
            $this->getSessionValidatorPlugins(),
        );
    }

    public function createSessionCustomerValidationSecurityExtender(): SessionCustomerValidationSecurityExtenderInterface
    {
        return new SessionCustomerValidationSecurityExtender(
            $this->createValidateCustomerSessionListener(),
            $this->getConfig(),
        );
    }

    public function getCustomerSessionSaverPlugin(): CustomerSessionSaverPluginInterface
    {
        return $this->getProvidedDependency(SessionCustomerValidationPageDependencyProvider::PLUGIN_CUSTOMER_SESSION_SAVER);
    }

    public function getCustomerSessionValidatorPlugin(): CustomerSessionValidatorPluginInterface
    {
        return $this->getProvidedDependency(SessionCustomerValidationPageDependencyProvider::PLUGIN_CUSTOMER_SESSION_VALIDATOR);
    }

    public function getCustomerClient(): SessionCustomerValidationPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(SessionCustomerValidationPageDependencyProvider::CLIENT_CUSTOMER);
    }

    public function getSessionClient(): SessionCustomerValidationPageToSessionClientInterface
    {
        return $this->getProvidedDependency(SessionCustomerValidationPageDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return array<\SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface>
     */
    public function getSessionValidatorPlugins(): array
    {
        return $this->getProvidedDependency(SessionCustomerValidationPageDependencyProvider::PLUGINS_CUSTOMER_SESSION_VALIDATOR);
    }
}
