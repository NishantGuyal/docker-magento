<?php
declare (strict_types = 1);

namespace Nishant\CustomCheckout\Setup\Patch\Data;

use MarkShust\SimpleData\Setup\Patch\SimpleDataPatch;

class BlockFulfillmentStatusCreate extends SimpleDataPatch
{
    public function apply(): self
    {
        $this->block->save([
            'identifier' => 'fulfillment_status',
            'title'      => 'Fulfillment Status',
            'content'    => <<<CONTENT
<style>#html-body [data-pb-style=GP5MUWI]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}</style><div data-content-type="row" data-appearance="contained" data-element="main"><div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="GP5MUWI"><div data-content-type="text" data-appearance="default" data-element="main"><p><strong>Fulfillment Status</strong>: Shipped within 48 hours.</p></div></div></div>
CONTENT,
        ]);
    }
}
