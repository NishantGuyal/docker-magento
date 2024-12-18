var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/view/summary/cart-items': {
                'Nishant_CheckoutMessages/js/view/summary/cart-items-mixin': true
            }
        }
    },
    map: {
        '*': {
            'Magento_Checkout/template/sidebar': 'Nishant_CheckoutMessages/template/sidebar'
        }
    }
};
