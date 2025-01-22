<?php

namespace Nishant\CustomEmailCheckout\Controller\Checkout;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Message\ManagerInterface;

class UpdateEmail extends Action
{
    protected $customerSession;
    protected $jsonFactory;
    protected $messageManager;

    public function __construct(
        Context $context,
        Session $customerSession,
        JsonFactory $jsonFactory,
        ManagerInterface $messageManager
    ) {
        $this->customerSession = $customerSession;
        $this->jsonFactory = $jsonFactory;
        $this->messageManager = $messageManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $response = ['success' => false];
        $newEmail = $this->getRequest()->getParam('email');

        if ($this->customerSession->isLoggedIn()) {
            $customer = $this->customerSession->getCustomer();
            if ($newEmail && $customer->getEmail() !== $newEmail) {
                try {
                    $customer->setEmail($newEmail);
                    $customer->save();
                    $response['success'] = true;
                    $this->messageManager->addSuccessMessage(__('Your email address has been updated.'));
                } catch (\Exception $e) {
                    $response['error'] = __('Error updating email address.');
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
        } else {
            $response['error'] = __('You must be logged in to update your email.');
        }

        return $this->jsonFactory->create()->setData($response);
    }
}
