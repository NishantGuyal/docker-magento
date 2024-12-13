<?php declare (strict_types = 1);

namespace Nishant\Blog\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Nishant\Blog\Api\PostRepositoryInterface;
use Nishant\Blog\Model\PostFactory;

class PopulateBlogPost implements DataPatchInterface
{
    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup,
        private PostFactory $postFactory,
        private PostRepositoryInterface $postRepository,

    ) {}
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $post = $this->postFactory->create();
        $post->setData(['title' => 'UEFA CL Night', 'content' => 'It is UCL night in Camp Nou, FC Barcelona vs FC Bayern.']);
        $this->postRepository->save($post);

        $this->moduleDataSetup->endSetup();
    }
    public function getAliases(): array
    {
        return [];
    }
    public static function getDependencies(): array
    {
        return [];
    }
}
