<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Plugin;

use GraphQL\Validator\DocumentValidator;
use GraphQL\Validator\Rules\QueryComplexity;
use GraphQL\Validator\Rules\QueryDepth;
use Magento\Framework\GraphQl\Query\QueryComplexityLimiter;
use Yireo\CustomGraphQlQueryLimiter\Config\Config;

class QueryComplexityLimiterPlugin
{
    public function __construct(
        private readonly Config $config
    ) {
    }

    public function afterExecute(QueryComplexityLimiter $queryComplexityLimiter, mixed $result): mixed
    {
        $queryDepth = $this->config->getQueryDepth();
        if ($queryDepth !== null) {
            DocumentValidator::addRule(new QueryDepth($queryDepth));
        }

        $queryComplexity = $this->config->getQueryComplexity();
        if ($queryComplexity !== null) {
            DocumentValidator::addRule(new QueryComplexity($queryComplexity));
        }

        return $result;
    }
}
