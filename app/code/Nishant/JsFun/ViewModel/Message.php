<?php declare (strict_types = 1);

namespace Nishant\JsFun\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class Message implements ArgumentInterface
{
    public function getMessage()
    {
        return "Nishant Jadhav";
    }

}
