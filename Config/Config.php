<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Config;

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
     */
    public function getQueryDepth(): int
    {
        return (int) $this->scopeConfig->getValue('graphql_query_limiter/settings/query_depth');
    }

    /**
     * @return int
     */
    public function getQueryComplexity(): int
    {
        return (int) $this->scopeConfig->getValue('graphql_query_limiter/settings/query_complexity');
    }
}
