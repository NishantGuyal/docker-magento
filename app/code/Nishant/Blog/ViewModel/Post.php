<?php declare (strict_types = 1);

namespace Nishant\Blog\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Nishant\Blog\Api\Data\PostInterface;
use Nishant\Blog\Model\PostRepository;
use Nishant\Blog\Model\ResourceModel\Post\Collection;

class Post implements ArgumentInterface
{
    public function __construct(
        private Collection $collection,
        private PostRepository $postRepository,
        private RequestInterface $requestInterface,
    ) {}
    public function getList(): array
    {
        return $this->collection->getItems();
    }
    public function getCount(): int
    {
        return $this->collection->count();
    }
    public function getDetail(): PostInterface
    {
        $id = (int) $this->requestInterface->getParam('id');
        return $this->postRepository->getById($id);
    }
}
