<?php
declare (strict_types = 1);

namespace Nishant\Blog\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Nishant\Blog\Api\Data\PostInterface;

interface PostRepositoryInterface
{
    /**
     * Summary of getById
     * @param int $id
     * @return \Nishant\Blog\Api\Data\PostInterface
     * @throws LocalizedException
     */
    public function getById(int $id): PostInterface;

    /**
     * Summary of save
     * @param \Nishant\Blog\Api\Data\PostInterface $post
     * @return \Nishant\Blog\Api\Data\PostInterface
     * @throws LocalizedException
     */
    public function save(PostInterface $post): PostInterface;

    /**
     * Summary of deleteById
     * @param int $id
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): bool;
}
