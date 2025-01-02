<?php

declare(strict_types=1);

namespace Nishant\Blog\Api\Data;

/**
 * Summary of PostInterface
 * @api
 * @since 1.0.0
 */
interface PostInterface
{
    const ID = 'id';
    const TITLE = 'title';

    const CONTENT = 'content';

    const CREATED_AT = 'created_at';

    /**
     * Summary of getId
     * @return int
     */
    public function getId();

    /**
     * Summary of setId
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Summary of getTitle
     * @return string
     */
    public function getTitle();

    /**
     * Summary of setTitle
     * @param string $title
     * @return $this
     */
    public function setTitle($title);
    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * @return string
     */
    public function getCreatedAt();
}
