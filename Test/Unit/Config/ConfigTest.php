<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Test\Unit\Config;

use GraphQL\Error\Error as GraphQlError;
use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\MockObject\MockClass;
use PHPUnit\Framework\TestCase;
use Yireo\CustomGraphQlQueryLimiter\Config\Config;

class ConfigTest extends TestCase
{
    public function testGetQueryDepth()
    {
        $scopeConfig = $this->getScopeConfig();
        $scopeConfig->method('getValue')->willReturn(42);

        $config = new Config($scopeConfig);
        $this->assertEquals(42, $config->getQueryDepth());
    }

    public function testGetQueryComplexity()
    {
        $scopeConfig = $this->getScopeConfig();
        $scopeConfig->method('getValue')->willReturn(42);

        $config = new Config($scopeConfig);
        $this->assertEquals(42, $config->getQueryComplexity());
    }

    /**
     * @return mixed
     */
    private function getScopeConfig()
    {
        return $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
