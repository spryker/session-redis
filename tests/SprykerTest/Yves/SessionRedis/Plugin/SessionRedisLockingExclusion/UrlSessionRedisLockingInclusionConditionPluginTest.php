<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Yves\SessionRedis\Plugin\SessionRedisLockingExclusion;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Yves\SessionRedis\Plugin\SessionRedisLockingExclusion\UrlSessionRedisLockingInclusionConditionPlugin;
use Spryker\Yves\SessionRedis\SessionRedisConfig;
use Spryker\Yves\SessionRedis\SessionRedisDependencyProvider;
use SprykerTest\Yves\SessionRedis\SessionRedisYvesTester;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group SessionRedis
 * @group Plugin
 * @group SessionRedisLockingExclusion
 * @group UrlSessionRedisLockingInclusionConditionPluginTest
 * Add your own group annotations below this line
 */
class UrlSessionRedisLockingInclusionConditionPluginTest extends Unit
{
    /**
     * @var \Spryker\Yves\SessionRedis\Plugin\SessionRedisLockingExclusion\UrlSessionRedisLockingInclusionConditionPlugin
     */
    protected UrlSessionRedisLockingInclusionConditionPlugin $plugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\SessionRedis\SessionRedisConfig
     */
    protected MockObject|SessionRedisConfig $configMock;

    /**
     * @var \SprykerTest\Yves\SessionRedis\SessionRedisYvesTester
     */
    protected SessionRedisYvesTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(SessionRedisDependencyProvider::REQUEST_STACK, new RequestStack());
        $this->configMock = $this->getMockBuilder(SessionRedisConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory = $this->tester->getFactory();
        $factory->setConfig($this->configMock);

        $this->plugin = new UrlSessionRedisLockingInclusionConditionPlugin();
        $this->plugin->setFactory($factory);
    }

    /**
     * @dataProvider includedUrlDataProvider
     *
     * @param list<string> $includedUrlPatterns
     * @param string $requestUri
     *
     * @return void
     */
    public function testCheckConditionReturnsFalseForMatchingUrls(
        array $includedUrlPatterns,
        string $requestUri
    ): void {
        // Arrange
        $this->configMock
            ->method('getSessionRedisLockingIncludedUrlPatterns')
            ->willReturn($includedUrlPatterns);

        $redisLockingSessionHandlerConditionTransfer = (new RedisLockingSessionHandlerConditionTransfer())
            ->setRequestUri($requestUri);

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertFalse($result, sprintf(
            'Expected session lock to remain active for URI "%s", but the checker bypassed it.',
            $requestUri,
        ));
    }

    /**
     * @dataProvider nonIncludedUrlDataProvider
     *
     * @param list<string> $includedUrlPatterns
     * @param string $requestUri
     *
     * @return void
     */
    public function testCheckConditionReturnsTrueForNonMatchingUrls(
        array $includedUrlPatterns,
        string $requestUri
    ): void {
        // Arrange
        $this->configMock
            ->method('getSessionRedisLockingIncludedUrlPatterns')
            ->willReturn($includedUrlPatterns);

        $redisLockingSessionHandlerConditionTransfer = (new RedisLockingSessionHandlerConditionTransfer())
            ->setRequestUri($requestUri);

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertTrue($result, sprintf(
            'Expected session lock to be bypassed for URI "%s", but the checker kept it active.',
            $requestUri,
        ));
    }

    public function testCheckConditionReturnsFalseWhenNoPatternsAreConfigured(): void
    {
        // Arrange
        $this->configMock
            ->method('getSessionRedisLockingIncludedUrlPatterns')
            ->willReturn([]);

        $redisLockingSessionHandlerConditionTransfer = (new RedisLockingSessionHandlerConditionTransfer())
            ->setRequestUri('/de/cart/add/1');

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertFalse($result);
    }

    public function testCheckConditionReturnsFalseWhenRequestUriIsNull(): void
    {
        // Arrange
        $this->configMock
            ->method('getSessionRedisLockingIncludedUrlPatterns')
            ->willReturn(['/^.*\/cart\/(add|remove)/']);

        $redisLockingSessionHandlerConditionTransfer = new RedisLockingSessionHandlerConditionTransfer();

        // Act
        $result = $this->plugin->checkCondition($redisLockingSessionHandlerConditionTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return array<string, array{list<string>, string}>
     */
    public function includedUrlDataProvider(): array
    {
        $patterns = $this->getInclusionPatterns();

        return [
            'cart root' => [$patterns, '/de/cart'],
            'cart root full' => [$patterns, '/DE/en/cart'],
            'cart add' => [$patterns, '/de/cart/add/1'],
            'cart add ajax' => [$patterns, '/en/cart/add-ajax/011_30775359'],
            'cart add ajax full' => [$patterns, '/DE/en/cart/add-ajax/011_30775359'],
            'cart remove' => [$patterns, '/en/cart/remove/abc'],
            'cart change quantity' => [$patterns, '/de/cart/change/SKU-1'],
            'cart update' => [$patterns, '/de/cart/update'],
            'cart get items' => [$patterns, '/DE/en/cart/get-cart-items'],
            'cart upselling widget' => [$patterns, '/DE/en/cart/get-upselling-widget'],
            'cart quick-add' => [$patterns, '/de/cart/quick-add'],
            'cart add-items' => [$patterns, '/de/cart/add-items'],
            'cart reset-lock' => [$patterns, '/de/cart/reset-lock'],
            'cart async add' => [$patterns, '/de/cart/async/add'],
            'cart async remove' => [$patterns, '/de/cart/async/remove'],
            'cart async remove full' => [$patterns, '/DE/en/cart/async/remove/136_24425591/136_24425591'],
            'cart async change-quantity' => [$patterns, '/de/cart/async/change-quantity'],
            'cart async change-quantity full' => [$patterns, '/DE/en/cart/async/change-quantity/134_29759322'],
            'cart async quick-add' => [$patterns, '/de/cart/async/quick-add'],
            'cart async update' => [$patterns, '/de/cart/async/update'],
            'cart code add' => [$patterns, '/de/cart-code/code/add'],
            'cart code remove' => [$patterns, '/de/cart-code/code/remove'],
            'cart code clear' => [$patterns, '/de/cart-code/code/clear'],
            'cart code async add' => [$patterns, '/de/cart-code/code-async/add'],
            'cart note' => [$patterns, '/de/cart-note/save'],
            'order custom reference' => [$patterns, '/order-custom-reference/async/save'],
            'order custom reference full' => [$patterns, '/DE/en/order-custom-reference/async/save'],
            'multi-cart create' => [$patterns, '/de/multi-cart/create'],
            'multi-cart update' => [$patterns, '/de/multi-cart/update'],
            'multi-cart delete' => [$patterns, '/de/multi-cart/delete'],
            'multi-cart clear' => [$patterns, '/de/multi-cart/clear'],
            'multi-cart duplicate' => [$patterns, '/de/multi-cart/duplicate'],
            'multi-cart set-default' => [$patterns, '/de/multi-cart/set-default'],
            'multi-cart async clear' => [$patterns, '/de/multi-cart-async/clear/'],
            'cart reorder' => [$patterns, '/de/cart-reorder/'],
            'shared cart share' => [$patterns, '/de/shared-cart/share'],
            'shared cart dismiss' => [$patterns, '/de/shared-cart/dismiss'],
            'checkout index' => [$patterns, '/de/checkout'],
            'checkout step address' => [$patterns, '/de/checkout/address'],
            'checkout step shipment' => [$patterns, '/de/checkout/shipment'],
            'checkout place order' => [$patterns, '/de/checkout/place-order'],
            'checkout summary' => [$patterns, '/de/checkout/summary'],
            'login' => [$patterns, '/de/login'],
            'logout' => [$patterns, '/de/logout'],
            'register' => [$patterns, '/de/register'],
            'customer profile' => [$patterns, '/de/customer/profile'],
            'customer address' => [$patterns, '/de/customer/address'],
            'customer newsletter' => [$patterns, '/de/customer/newsletter'],
            'customer delete' => [$patterns, '/de/customer/delete'],
        ];
    }

    /**
     * @return array<string, array{list<string>, string}>
     */
    public function nonIncludedUrlDataProvider(): array
    {
        $patterns = $this->getInclusionPatterns();

        return [
            'catalog search' => [$patterns, '/de/search'],
            'product detail page' => [$patterns, '/de/product/sku-123'],
            'homepage' => [$patterns, '/de/home'],
            'wishlist' => [$patterns, '/de/wishlist'],
            'order history' => [$patterns, '/de/order/history'],
            'company business unit' => [$patterns, '/de/company/business-unit'],
            'cms page' => [$patterns, '/de/cms/page/about-us'],
            'category listing' => [$patterns, '/de/category/computers'],
            'customer overview — not a write action' => [$patterns, '/de/customer/overview'],
        ];
    }

    /**
     * @return list<string>
     */
    protected function getInclusionPatterns(): array
    {
        return [
            '/^.*\/cart(\/|$)/',
            '/^.*\/cart-code\/code(|-async)\/(add|remove|clear)/',
            '/^.*\/cart-note\//',
            '/^.*\/order-custom-reference\//',
            '/^.*\/multi-cart\/(create|update|delete|clear|duplicate|set-default)/',
            '/^.*\/multi-cart-async\/clear\//',
            '/^.*\/cart-reorder\//',
            '/^.*\/shared-cart\/(share|dismiss)/',
            '/^.*\/checkout/',
            '/^.*\/(login|logout|register)($|\/)/',
            '/^.*\/customer\/(profile|address|newsletter|delete)/',
        ];
    }
}
