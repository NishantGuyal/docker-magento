define([
    'jquery',
    'Magento_Ui/js/form/form',
    'Magento_Customer/js/model/customer',
    'Magento_Ui/js/model/messageList', // Import message container
], function ($, Component, customer, messageContainer) {
    'use strict';

    var checkoutConfig = window.checkoutConfig;

    return Component.extend({
        welcomeMessage: '',  // Initialize as an empty string
        defaults: {
            template: 'Nishant_CheckoutMessage/authmessage'
        },

        initialize: function () {
            this._super();

            if (customer.isLoggedIn()) {
                this.showAuthenticationMessage();
            }

            return this;
        },

        /**
         * Display an authentication message for logged-in users.
         */
        showAuthenticationMessage: function () {
            this.welcomeMessage = 'Welcome ' + checkoutConfig.customerData.firstname + '! Need help? Call us at 8928755993.';
            console.log(this.welcomeMessage);
            messageContainer.addSuccessMessage({ message: this.welcomeMessage });
        },
    });
});
