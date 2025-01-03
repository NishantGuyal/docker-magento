<?php declare (strict_types = 1);

namespace Nishant\Minerva\Controller\Adminhtml\Faq;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Nishant\Minerva\Model\Faq;
use Nishant\Minerva\Model\FaqFactory;
use Nishant\Minerva\Model\ResourceModel\Faq as FaqResource;

class Delete extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Nishant_Minerva::faq_delete';
    /** @var FaqFactory */
    protected $faqFactory;
    /** @var FaqResource */
    protected $faqResource;
    /**
     * Delete constructor.
     * @param Context $context
     * @param FaqFactory $faqFactory
     * @param FaqResource $faqResource
     */
    public function __construct(
        Context $context,
        FaqFactory $faqFactory,
        FaqResource $faqResource
    ) {
        $this->faqFactory = $faqFactory;
        $this->faqResource = $faqResource;
        parent::__construct($context);
    }
    public function execute(): Redirect
    {
        try {
            $id = $this->getRequest()->getParam('id');
            /** @var Faq $faq */
            $faq = $this->faqFactory->create();
            $this->faqResource->load($faq, $id);
            if ($faq->getId()) {
                $this->faqResource->delete($faq);
                $this->messageManager->addSuccessMessage(__('The record has been deleted.'));
            } else {
                $this->messageManager->addErrorMessage(__('The record does not exist.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $redirect->setPath('*/*');
    }
}
