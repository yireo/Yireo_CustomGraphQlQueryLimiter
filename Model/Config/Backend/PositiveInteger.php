<?php declare(strict_types=1);

namespace Yireo\CustomGraphQlQueryLimiter\Model\Config\Backend;

use Magento\Framework\App\Config\Value;
use Magento\Framework\Exception\ValidatorException;

class PositiveInteger extends Value
{
    /**
     * @return $this
     * @throws ValidatorException
     */
    public function beforeSave()
    {
        $value = trim((string)$this->getValue(), " \t\n\r\0\x0B");
        if ($value !== '' && !ctype_digit($value)) {
            throw new ValidatorException(__('"%1" must be a whole number of 0 or higher.', $value));
        }

        $this->setValue($value);
        return parent::beforeSave();
    }
}
