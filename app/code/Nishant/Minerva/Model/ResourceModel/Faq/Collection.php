<?php declare (strict_types = 1);
namespace Nishant\Minerva\Model\ResourceModel\Faq;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Nishant\Minerva\Model\Faq;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Faq::class, \Nishant\Minerva\Model\ResourceModel\Faq::class);
    }
}
