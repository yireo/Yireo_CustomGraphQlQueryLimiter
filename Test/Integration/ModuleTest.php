<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Test\Integration;

use Magento\Framework\GraphQl\Query\QueryComplexityLimiter;
use Magento\Framework\GraphQl\Query\QueryProcessor;
use Yireo\CustomGraphQlQueryLimiter\GraphQl\Query\CustomQueryComplexityLimiter;
use Yireo\CustomGraphQlQueryLimiter\Plugin\QueryProcessorPlugin;
use Yireo\IntegrationTestHelper\Test\Integration\AbstractTestCase;

class ModuleTest extends AbstractTestCase
{
    public function testIfModuleIsWorking()
    {
        $this->assertModuleIsRegistered('Yireo_CustomGraphQlQueryLimiter');
        $this->assertModuleIsEnabled('Yireo_CustomGraphQlQueryLimiter');
    }

    public function testIfPreferenceWorks()
    {
        $this->assertPreferenceOf(CustomQueryComplexityLimiter::class, QueryComplexityLimiter::class);
    }

    public function testIfPluginWorks()
    {
        $this->assertInterceptorPluginIsRegistered(
            QueryProcessor::class,
            QueryProcessorPlugin::class,
            'Yireo_CustomGraphQlQueryLimiter_QueryProcessorPlugin'
        );
    }
}
