<?php

declare(strict_types=1);

namespace Nishant\Blog\Controller\Post;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ManagerInterface as EventManager ;
use Magento\Framework\View\Result\Page; 
use Magento\Framework\View\Result\PageFactory;

class Details implements HttpGetActionInterface
{
    public function __construct(
        private Session $session,
        private RequestInterface $request,
        private PageFactory $pageFactory,
        private EventManager $eventManager,
    ) {}
    public function execute(): Page
    {
        $this->eventManager->dispatch('nishant_blog_post_detail_view', [
            'request' => $this->request,
        ]);
        return $this->pageFactory->create();
    }
}
