define(['uiComponent', 'Magento_Customer/js/customer-data', 'underscore', 'knockout'], function (Component, customerData, _, ko) {
    'use strict';

    console.log('Free Shipping UI component has been loaded')

    return Component.extend({
        defaults: {
            freeShippingThreshold: 100,
            subtotal: ko.observable(0.00),
            template: 'Nishant_FreeShippingPromo/free-shipping-banner',
            tracks: {
                message: true,
            }
        },

        initialize: function () {
            this._super();
            var cart = customerData.get('cart');
            var self = this;

            customerData.getInitCustomerData().done(function () {
                if (cart() && cart().subtotalAmount) {
                    self.subtotal(parseFloat(cart().subtotalAmount));
                    console.log(cart());
                }
            });

            cart.subscribe(function (cartData) {
                if (cartData && cartData.subtotalAmount) {
                    self.subtotal(parseFloat(cartData.subtotalAmount));
                    console.log(cartData);
                }
            });

            self.message = ko.computed(function () {
                var subtotal = self.subtotal();
                if (_.isUndefined(subtotal) || subtotal === 0) {
                    return self.messageDefault.replace('{{freeShippingThreshold}}', self.freeShippingThreshold);

                }
                if (subtotal > 0 && subtotal < self.freeShippingThreshold) {
                    var subtotalRemaining = self.freeShippingThreshold - subtotal;
                    var formattedSubtotalRemaining = self.formatCurrency(subtotalRemaining);
                    return self.messageItemsInCart.replace('$XX.XX', formattedSubtotalRemaining);
                }
                if (subtotal >= self.freeShippingThreshold) {
                    return self.messageFreeShipping;
                }
            });

            console.log('Free Shipping Banner UI Component has been loaded');
        },

        formatCurrency: function (value) {
            return '$' + value.toFixed(2);
        }
    });
});
