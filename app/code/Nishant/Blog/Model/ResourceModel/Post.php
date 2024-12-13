<?php

declare(strict_types=1);

namespace Nishant\Blog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Post extends AbstractDb
{
    const TABLE_NAME = 'nishant_blog_posts';
    const ID = 'post_id';
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::ID);
    }
}
