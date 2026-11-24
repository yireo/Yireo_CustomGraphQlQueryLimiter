<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Test\Unit\Plugin;

use GraphQL\Validator\DocumentValidator;
use GraphQL\Validator\Rules\QueryComplexity;
use GraphQL\Validator\Rules\QueryDepth;
use Magento\Framework\GraphQl\Query\QueryComplexityLimiter;
use PHPUnit\Framework\TestCase;
use Yireo\CustomGraphQlQueryLimiter\Config\Config;
use Yireo\CustomGraphQlQueryLimiter\Plugin\QueryComplexityLimiterPlugin;

class QueryComplexityLimiterPluginTest extends TestCase
{
    protected function setUp(): void
    {
        DocumentValidator::addRule(new QueryDepth(20));
        DocumentValidator::addRule(new QueryComplexity(1000));
    }

    protected function tearDown(): void
    {
        DocumentValidator::removeRule(new QueryDepth(0));
        DocumentValidator::removeRule(new QueryComplexity(0));
    }

    public function testConfiguredValuesReplaceCoreRules(): void
    {
        $this->getPlugin(7, 50)->afterExecute($this->createMock(QueryComplexityLimiter::class), null);

        $this->assertSame(7, $this->getRule(QueryDepth::class)->getMaxQueryDepth());
        $this->assertSame(50, $this->getRule(QueryComplexity::class)->getMaxQueryComplexity());
    }

    public function testMissingValuesKeepCoreRules(): void
    {
        $this->getPlugin(null, null)->afterExecute($this->createMock(QueryComplexityLimiter::class), null);

        $this->assertSame(20, $this->getRule(QueryDepth::class)->getMaxQueryDepth());
        $this->assertSame(1000, $this->getRule(QueryComplexity::class)->getMaxQueryComplexity());
    }

    private function getPlugin(?int $queryDepth, ?int $queryComplexity): QueryComplexityLimiterPlugin
    {
        $config = $this->createMock(Config::class);
        $config->method('getQueryDepth')->willReturn($queryDepth);
        $config->method('getQueryComplexity')->willReturn($queryComplexity);
        return new QueryComplexityLimiterPlugin($config);
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     * @return T
     */
    private function getRule(string $className): object
    {
        $rule = DocumentValidator::getRule($className);
        $this->assertInstanceOf($className, $rule);
        return $rule;
    }
}
