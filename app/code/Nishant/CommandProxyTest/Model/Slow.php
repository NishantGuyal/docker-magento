<?php declare (strict_types = 1);

namespace Nishant\CommandProxyTest\Model;

use Magento\Framework\Model\AbstractModel;

class Slow extends AbstractModel
{
    protected function _construct(): void
    {
        sleep(3);
    }

    public function getSomeData(): string
    {
        return 'some slow data';
    }
}
