<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Test\Unit\Model\Config\Backend;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Model\AbstractModel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Yireo\CustomGraphQlQueryLimiter\Model\Config\Backend\PositiveInteger;

class PositiveIntegerTest extends TestCase
{
    #[DataProvider('getValidValues')]
    public function testValidValueIsSaved(mixed $value, string $expected): void
    {
        $backendModel = $this->getBackendModel($value);
        $backendModel->beforeSave();
        $this->assertSame($expected, $backendModel->getValue());
    }

    public static function getValidValues(): array
    {
        return [
            'empty' => ['', ''],
            'null' => [null, ''],
            'zero' => ['0', '0'],
            'number' => ['25', '25'],
            'padded' => [' 25 ', '25'],
        ];
    }

    #[DataProvider('getInvalidValues')]
    public function testInvalidValueIsRejected(string $value): void
    {
        $this->expectException(ValidatorException::class);
        $this->getBackendModel($value)->beforeSave();
    }

    public static function getInvalidValues(): array
    {
        return [
            'negative' => ['-1'],
            'text' => ['abc'],
            'float' => ['2.5'],
            'mixed' => ['25abc'],
        ];
    }

    private function getBackendModel(mixed $value): PositiveInteger
    {
        $backendModel = (new \ReflectionClass(PositiveInteger::class))->newInstanceWithoutConstructor();
        $eventManager = new \ReflectionProperty(AbstractModel::class, '_eventManager');
        $eventManager->setValue($backendModel, $this->createMock(ManagerInterface::class));
        $backendModel->setValue($value);
        return $backendModel;
    }
}
