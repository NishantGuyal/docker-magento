<?php declare (strict_types = 1);

namespace Nishant\BlogExtra\Plugin;

use Nishant\Blog\Observer\LogPostDetailView;

class PreventPostDetailLogger
{
    public function aroundExecute(LogPostDetailView $logger,
        callable $proceed, ) {
        // $proceed();
    }
}
