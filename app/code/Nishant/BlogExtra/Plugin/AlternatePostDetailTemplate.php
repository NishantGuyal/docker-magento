<?php declare (strict_types = 1);

namespace Nishant\BlogExtra\Plugin;

use Magento\Framework\App\RequestInterface;
use Nishant\Blog\Controller\Post\Details;

class AlternatePostDetailTemplate
{
    public function __construct(
        private RequestInterface $request,
    ) {}
    public function afterExecute(Details $details, $result)
    {
        if ($this->request->getParam('alternate')) {
            $result->getLayout()
                ->getBlock('blog.post.details')
                ->setTemplate('Nishant_BlogExtra::post/details.phtml');
        }
        
        return $result;
    }
}
