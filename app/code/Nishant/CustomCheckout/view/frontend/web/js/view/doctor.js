define([
    'uiComponent',
    'ko',
    'Magento_Checkout/js/model/step-navigator',
    'mage/translate',
], function (
    Component,
    ko,
    stepNavigator,
    $t,
) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Nishant_CustomCheckout/doctor',
            isVisible: ko.observable(false),
        },
        doctorName: ko.observable(''),
        doctorPhone: ko.observable(''),

        initialize: function () {
            this._super();

            // Register step in checkout step-navigator
            stepNavigator.registerStep(
                'doctor',                  // Step code
                null,                           // Alias (optional)
                $t('Doctor Information'),       // Step title
                this.isVisible,                 // Visibility condition
                _.bind(this.navigate, this),   // Navigation logic
                this.sortOrder                              // Sort order
            );
            return this;
        },

        navigate: function () {
            this.isVisible(true);
        },

        navigateToNextStep: function () {
            stepNavigator.next();
        }
    });
});
