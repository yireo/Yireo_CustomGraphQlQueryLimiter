<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Plugin;

use GraphQL\Language\AST\DocumentNode;
use Magento\Framework\GraphQl\Exception\ExceptionFormatter;
use Magento\Framework\GraphQl\Query\QueryComplexityLimiter;
use Magento\Framework\GraphQl\Query\QueryProcessor;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema;

class QueryProcessorPlugin
{
    public function __construct(
        private readonly QueryComplexityLimiter $queryComplexityLimiter,
        private readonly ExceptionFormatter $exceptionFormatter
    ) {
    }

    /**
     * @return null
     */
    public function beforeProcess(
        QueryProcessor $queryProcessor,
        Schema $schema,
        DocumentNode|string $source,
        ?ContextInterface $contextValue = null,
        ?array $variableValues = null,
        ?string $operationName = null
    ) {
        if ($this->exceptionFormatter->shouldShowDetail()) {
            $this->queryComplexityLimiter->execute();
        }

        return null;
    }
}
