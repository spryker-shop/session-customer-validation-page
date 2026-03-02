<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

class SessionCustomerValidationPageToCustomerClientBridge implements SessionCustomerValidationPageToCustomerClientInterface
{
    /**
     * @var \Spryker\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     */
    public function __construct($customerClient)
    {
        $this->customerClient = $customerClient;
    }

    public function getCustomer(): ?CustomerTransfer
    {
        return $this->customerClient->getCustomer();
    }

    public function getCustomerByEmail(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        return $this->customerClient->getCustomerByEmail($customerTransfer);
    }

    public function logout(): void
    {
        $this->customerClient->logout();
    }
}
