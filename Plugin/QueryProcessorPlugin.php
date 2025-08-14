<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Plugin;

use GraphQL\Error\Error;
use GraphQL\Language\AST\DocumentNode;
use GraphQL\Validator\DocumentValidator;
use GraphQL\Validator\Rules\QueryComplexity;
use GraphQL\Validator\Rules\QueryDepth;
use Magento\Framework\GraphQl\Query\QueryProcessor;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema;
use Yireo\CustomGraphQlQueryLimiter\Config\Config;

class QueryProcessorPlugin
{
    /**
     * @var Config
     */
    private $config;

    /**
     * QueryComplexityLimiterPlugin constructor.
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @param QueryProcessor $queryProcessor
     * @param Schema $schema
     * @param string $source
     * @param ContextInterface|null $contextValue
     * @param array|null $variableValues
     * @param string|null $operationName
     * @return array
     * @throws Error
     */
    public function beforeProcess(
        QueryProcessor $queryProcessor,
        Schema $schema,
        DocumentNode|string $source,
        ?ContextInterface $contextValue = null,
        ?array $variableValues = null,
        ?string $operationName = null
    ) {
        DocumentValidator::addRule(new QueryComplexity($this->config->getQueryComplexity()));
        DocumentValidator::addRule(new QueryDepth($this->config->getQueryDepth()));

        return [$schema, $source, $contextValue, $variableValues, $operationName];
    }
}
