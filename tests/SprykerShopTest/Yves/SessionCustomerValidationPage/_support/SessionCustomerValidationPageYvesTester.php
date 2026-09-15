<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionCustomerValidationPage;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerShopTest\Yves\SessionCustomerValidationPage\PHPMD)
 */
class SessionCustomerValidationPageYvesTester extends Actor
{
    use _generated\SessionCustomerValidationPageYvesTesterActions;

    /**
     * @uses \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig::SESSION_ENTITY_TYPE
     *
     * @var string
     */
    protected const SESSION_ENTITY_TYPE = 'customer';

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface
     */
    public function createCustomerClientMock(?CustomerTransfer $customerTransfer = null): SessionCustomerValidationPageToCustomerClientInterface
    {
        return Stub::makeEmpty(
            SessionCustomerValidationPageToCustomerClientInterface::class,
            [
                'getCustomer' => $customerTransfer,
                'getCustomerByEmail' => $customerTransfer ?? new CustomerTransfer(),
            ],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface
     */
    public function createCustomerSessionSaverPluginMock(): CustomerSessionSaverPluginInterface
    {
        return Stub::makeEmpty(CustomerSessionSaverPluginInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface
     */
    public function createCustomerSessionValidatorPluginMock(bool $isSessionValid = false): CustomerSessionValidatorPluginInterface
    {
        return Stub::makeEmpty(
            CustomerSessionValidatorPluginInterface::class,
            [
                'validate' => (new SessionEntityResponseTransfer())->setIsSuccessfull($isSessionValid),
            ],
        );
    }

    public function createConfigMock(): SessionCustomerValidationPageConfig
    {
        return Stub::makeEmpty(
            SessionCustomerValidationPageConfig::class,
            [
                'getSessionEntityType' => static::SESSION_ENTITY_TYPE,
            ],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    public function createAuthenticationTokenMock(bool $withUser = false): TokenInterface
    {
        $userMock = null;
        if ($withUser) {
            $userMock = Stub::makeEmpty(UserInterface::class);
        }

        return Stub::makeEmpty(
            TokenInterface::class,
            [
                'getUser' => $userMock,
            ],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    public function createAuthenticationTokenMockWithCustomerUser(CustomerTransfer $customerTransfer): TokenInterface
    {
        return Stub::makeEmpty(
            TokenInterface::class,
            [
                'getUser' => $this->createCustomerUserMock($customerTransfer),
            ],
        );
    }

    public function createCustomerUserMock(CustomerTransfer $customerTransfer): UserInterface
    {
        return new class ($customerTransfer) implements UserInterface {
            public function __construct(protected CustomerTransfer $customerTransfer)
            {
            }

            public function getCustomerTransfer(): CustomerTransfer
            {
                return $this->customerTransfer;
            }

            public function getRoles(): array
            {
                return [];
            }

            public function eraseCredentials(): void
            {
            }

            public function getUserIdentifier(): string
            {
                return 'test@spryker.com';
            }
        };
    }
}
