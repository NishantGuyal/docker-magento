<?php

declare(strict_types=1);

namespace Nishant\Blog\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Nishant\Blog\Model\Post;
use Nishant\Blog\Model\ResourceModel\Post as ResourceModelPost;

class Collection extends AbstractCollection
{

    protected function _construct()
    {
        $this->_init(Post::class, ResourceModelPost::class);
    }
}
