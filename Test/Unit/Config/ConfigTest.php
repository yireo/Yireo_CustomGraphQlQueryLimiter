<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Test\Unit\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Yireo\CustomGraphQlQueryLimiter\Config\Config;

class ConfigTest extends TestCase
{
    #[DataProvider('getValues')]
    public function testGetQueryDepth(mixed $value, ?int $expected): void
    {
        $config = new Config($this->getScopeConfig('graphql_query_limiter/settings/query_depth', $value));
        $this->assertSame($expected, $config->getQueryDepth());
    }

    #[DataProvider('getValues')]
    public function testGetQueryComplexity(mixed $value, ?int $expected): void
    {
        $config = new Config($this->getScopeConfig('graphql_query_limiter/settings/query_complexity', $value));
        $this->assertSame($expected, $config->getQueryComplexity());
    }

    public static function getValues(): array
    {
        return [
            'null' => [null, null],
            'empty' => ['', null],
            'whitespace' => ['  ', null],
            'text' => ['abc', null],
            'zero' => ['0', null],
            'negative' => ['-5', null],
            'negative int' => [-5, null],
            'float' => ['2.5', null],
            'mixed' => ['25abc', null],
            'string' => ['25', 25],
            'padded' => [' 25 ', 25],
            'int' => [42, 42],
        ];
    }

    private function getScopeConfig(string $path, mixed $value): ScopeConfigInterface
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->with($path)->willReturn($value);
        return $scopeConfig;
    }
}
