<?php

declare(strict_types=1);

namespace Nishant\Blog\Controller\Post;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class ListAction implements HttpGetActionInterface
{
    public function __construct(
        private PageFactory $pageFactory
    ) {}

    public function execute(): Page
    {
        // Optional: Remove the die statement, as it halts execution
        // die("Nishant Blog Post List");

        // Return the Page result
        return $this->pageFactory->create();
    }
}
