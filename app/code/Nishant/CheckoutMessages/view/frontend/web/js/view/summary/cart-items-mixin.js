define([], function () {
    'use strict';

    return function (Component) {
        return Component.extend({
            defaults: {
                template: 'Nishant_CheckoutMessages/summary/cart-items',
                exports: {
                    'totals.subtotal': 'checkout.sidebar.guarantee:subtotal'
                }
            },
            isItemsBlockExpanded: function () {
                // If you wish to execute parent method, be sure to call
                // this._super();
                return true;
            }
        });
    };
});
