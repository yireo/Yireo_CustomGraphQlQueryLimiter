<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Test\Integration;

use Magento\Framework\GraphQl\Query\QueryComplexityLimiter;
use Magento\Framework\GraphQl\Query\QueryProcessor;
use Yireo\CustomGraphQlQueryLimiter\Plugin\QueryComplexityLimiterPlugin;
use Yireo\CustomGraphQlQueryLimiter\Plugin\QueryProcessorPlugin;
use Yireo\IntegrationTestHelper\Test\Integration\AbstractTestCase;

class ModuleTest extends AbstractTestCase
{
    public function testIfModuleIsWorking()
    {
        $this->assertModuleIsRegistered('Yireo_CustomGraphQlQueryLimiter');
        $this->assertModuleIsEnabled('Yireo_CustomGraphQlQueryLimiter');
    }

    /**
     * @magentoAppArea graphql
     */
    public function testIfPluginsAreRegistered()
    {
        $this->assertInterceptorPluginIsRegistered(
            QueryComplexityLimiter::class,
            QueryComplexityLimiterPlugin::class,
            'Yireo_CustomGraphQlQueryLimiter_QueryComplexityLimiterPlugin'
        );

        $this->assertInterceptorPluginIsRegistered(
            QueryProcessor::class,
            QueryProcessorPlugin::class,
            'Yireo_CustomGraphQlQueryLimiter_QueryProcessorPlugin'
        );
    }
}
