<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Test\Unit\Acl;

use PHPUnit\Framework\TestCase;

class AclResourceTest extends TestCase
{
    public function testSystemConfigSectionResourceIsDefinedInAcl()
    {
        $moduleDir = dirname(__DIR__, 3);

        $system = simplexml_load_file($moduleDir . '/etc/adminhtml/system.xml');
        $sectionResources = array_map('strval', $system->xpath('//section/resource'));
        $this->assertNotEmpty($sectionResources);

        $acl = simplexml_load_file($moduleDir . '/etc/acl.xml');
        $aclResourceIds = array_map('strval', $acl->xpath('//resource/@id'));

        foreach ($sectionResources as $sectionResource) {
            $this->assertContains($sectionResource, $aclResourceIds);
        }
    }

    public function testConfigResourceIsGrantableUnderStoresConfiguration()
    {
        $acl = simplexml_load_file(dirname(__DIR__, 3) . '/etc/acl.xml');
        $parents = $acl->xpath(
            '//resource[@id="Magento_Config::config"]/resource[@id="Yireo_CustomGraphQlQueryLimiter::config"]'
        );

        $this->assertCount(1, $parents);
    }
}
