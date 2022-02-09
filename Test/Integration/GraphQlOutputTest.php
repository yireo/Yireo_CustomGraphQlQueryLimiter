<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Test\Integration;

use Yireo\IntegrationTestHelper\Test\Integration\GraphQlTestCase;

class GraphQlOutputTest extends GraphQlTestCase
{
    /**
     * @return void
     * @magentoAppArea graphql
     */
    public function testIfQueryWorksAsUsualWhenConfigurationIsNotThere()
    {
        $queryData = $this->getGraphQlQueryData('query { __schema { types { kind }} }');
        $this->assertGraphQlDataHasData('__schema.types', $queryData);
    }

    /**
     * @return void
     * @magentoAppArea graphql
     * @magentoConfigFixture graphql_query_limiter/settings/query_depth 0
     * @magentoConfigFixture graphql_query_limiter/settings/query_complexity 1000
     */
    public function testIfQueryWorksAsUsualWhenQueryDepthIsEmpty()
    {
        $queryData = $this->getGraphQlQueryData('query { __schema { types { kind }} }');
        $this->assertGraphQlDataHasData('__schema.types', $queryData);
    }

    /**
     * @return void
     * @magentoAppArea graphql
     * @magentoConfigFixture graphql_query_limiter/settings/query_depth 1000
     * @magentoConfigFixture graphql_query_limiter/settings/query_complexity 10
     */
    public function testIfQueryIsDeniedWhenQueryComplexityIsTooMuch()
    {
        $this->assertStoreConfigValueEquals(1000, 'graphql_query_limiter/settings/query_depth');
        $this->assertStoreConfigValueEquals(10, 'graphql_query_limiter/settings/query_complexity');

        $graphQlQueryFile = file_get_contents(__DIR__ . '/fixtures/complex_query.graphql');
        $queryData = $this->getGraphQlQueryData($graphQlQueryFile);
        $this->assertGraphQlDataHasError('Max query complexity should be 10 but got', $queryData);
    }

    /**
     * @return void
     * @magentoAppArea graphql
     * @magentoConfigFixture graphql_query_limiter/settings/query_depth 1000
     * @magentoConfigFixture graphql_query_limiter/settings/query_complexity 100
     */
    public function testIfQueryIsAllowedWhenQueryComplexityIsAllowed()
    {
        $this->assertStoreConfigValueEquals(1000, 'graphql_query_limiter/settings/query_depth');
        $this->assertStoreConfigValueEquals(100, 'graphql_query_limiter/settings/query_complexity');

        $graphQlQueryFile = file_get_contents(__DIR__ . '/fixtures/complex_query.graphql');
        $queryData = $this->getGraphQlQueryData($graphQlQueryFile);
        $this->assertGraphQlDataHasData('products.page_info', $queryData);
    }

    /**
     * @return void
     * @magentoAppArea graphql
     * @magentoConfigFixture graphql_query_limiter/settings/query_depth 3
     * @magentoConfigFixture graphql_query_limiter/settings/query_complexity 1000
     */
    public function testIfQueryIsDeniedWhenQueryDepthIsTooMuch()
    {
        $this->assertStoreConfigValueEquals(3, 'graphql_query_limiter/settings/query_depth');
        $this->assertStoreConfigValueEquals(1000, 'graphql_query_limiter/settings/query_complexity');

        $graphQlQueryFile = file_get_contents(__DIR__ . '/fixtures/deep_query.graphql');
        $queryData = $this->getGraphQlQueryData($graphQlQueryFile);
        $this->assertGraphQlDataHasError('Max query depth should be 3 but got', $queryData);
    }
}
