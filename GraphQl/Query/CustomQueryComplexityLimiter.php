<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\GraphQl\Query;

use GraphQL\Validator\DocumentValidator;
use GraphQL\Validator\Rules\DisableIntrospection;
use GraphQL\Validator\Rules\QueryComplexity;
use GraphQL\Validator\Rules\QueryDepth;
use Magento\Framework\GraphQl\Query\IntrospectionConfiguration;
use Magento\Framework\GraphQl\Query\QueryComplexityLimiter;
use Yireo\CustomGraphQlQueryLimiter\Config\Config as ModuleConfig;

class CustomQueryComplexityLimiter extends QueryComplexityLimiter
{
    /**
     * @var int
     */
    protected $queryDepth;

    /**
     * @var int
     */
    protected $queryComplexity;

    /**
     * @var ModuleConfig
     */
    protected $moduleConfig;

    /**
     * @var IntrospectionConfiguration
     */
    protected $introspectionConfig;

    /**
     * @param int $queryDepth
     * @param int $queryComplexity
     * @param IntrospectionConfiguration $introspectionConfig
     * @param ModuleConfig $moduleConfig
     */
    public function __construct(
        int $queryDepth,
        int $queryComplexity,
        IntrospectionConfiguration $introspectionConfig,
        ModuleConfig $moduleConfig
    ) {
        parent::__construct($queryDepth, $queryComplexity, $introspectionConfig);
        $this->moduleConfig = $moduleConfig;
        $this->introspectionConfig = $introspectionConfig;
    }

    public function execute(): void
    {
        $queryDepth = $this->moduleConfig->getQueryDepth() ?? $this->queryDepth;
        DocumentValidator::addRule(new QueryDepth($queryDepth));

        $queryComplexity = $this->moduleConfig->getQueryComplexity() ?? $this->queryComplexity;
        DocumentValidator::addRule(new QueryComplexity($queryComplexity));

        DocumentValidator::addRule(
            new DisableIntrospection((int)$this->introspectionConfig->isIntrospectionDisabled())
        );
    }
}
