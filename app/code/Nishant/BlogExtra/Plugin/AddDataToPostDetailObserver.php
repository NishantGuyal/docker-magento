<?php declare (strict_types = 1);

namespace Nishant\BlogExtra\Plugin;

use Magento\Framework\Event\Observer;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Nishant\Blog\Observer\LogPostDetailView;

class AddDataToPostDetailObserver
{
    public function __construct(
        private TimezoneInterface $timezone,
    ) {}

    public function beforeExecute(
        LogPostDetailView $subject,
        Observer $observer
    ) {
        // Access the request object
        $request = $observer->getData('request');

        // Add the current datetime to the request
        $request->setParam('datetime', $this->timezone->date());

        // Return the modified observer
        return [$observer];
    }
}
