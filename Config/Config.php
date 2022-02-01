<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Config;

use GraphQL\Error\Error;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return int
     * @throws Error
     */
    public function getQueryDepth(): int
    {
        $value = (int) $this->scopeConfig->getValue('graphql_query_limiter/settings/query_depth');
        if ($value < 1) {
            throw new Error('Empty value for "graphql_query_limiter/settings/query_depth"');
        }

        return $value;
    }

    /**
     * @return int
     * @throws Error
     */
    public function getQueryComplexity(): int
    {
        $value = (int) $this->scopeConfig->getValue('graphql_query_limiter/settings/query_complexity');
        if ($value < 1) {
            throw new Error('Empty value for "graphql_query_limiter/settings/query_complexity"');
        }

        return $value;
    }
}
