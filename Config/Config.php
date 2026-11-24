<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function getQueryDepth(): ?int
    {
        return $this->getPositiveInteger('graphql_query_limiter/settings/query_depth');
    }

    public function getQueryComplexity(): ?int
    {
        return $this->getPositiveInteger('graphql_query_limiter/settings/query_complexity');
    }

    private function getPositiveInteger(string $path): ?int
    {
        $value = $this->scopeConfig->getValue($path);
        if (is_int($value)) {
            return $value > 0 ? $value : null;
        }

        $value = trim((string)$value, " \t\n\r\0\x0B");
        if (!ctype_digit($value)) {
            return null;
        }

        $value = (int)$value;
        return $value > 0 ? $value : null;
    }
}
