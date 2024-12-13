<?php

declare (strict_types = 1);

namespace Nishant\BlogExtra\Controller\Post;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Details implements HttpGetActionInterface
{
    public function __construct(
        private Session $session,
        private PageFactory $pageFactory,
    ) {}
    public function execute(): Page
    {
        return $this->pageFactory->create();
    }
}
