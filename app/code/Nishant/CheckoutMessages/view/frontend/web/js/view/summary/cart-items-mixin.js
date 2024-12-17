define([], function () {
    'use strict';
    return function (Component) {
        return Component.extend({
            initialize: function () {
                this._super();
                console.log('Cart initialized initialized');
            },
            defaults: {
                template: 'Nishant_CheckoutMessages/summary/cart-items',
                exports: {
                    'totals.subtotal': 'checkout.sidebar.guarantee:subtotal'
                }
            },
            isItemsBlockExpanded: function () {
                // this._super();
                return true;
            }
        });
    };
});