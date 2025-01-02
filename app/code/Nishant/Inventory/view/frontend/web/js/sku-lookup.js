define(['uiComponent', 'ko', 'mage/storage', 'jquery', 'mage/translate', 'Nishant_Inventory/js/model/sku'], function (Component, ko, storage, $, $t, skuModel) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Nishant_Inventory/sku-lookup',
            sku: ko.observable('24-MB01'),
            placeholder: $t('Example: %1'.replace('%1', '24-MB01')),
            messageResponse: ko.observable(''),
            isSuccess: skuModel.isSuccess,
            // skuModel: skuModel,
        },
        initialize() {
            this._super();
            console.log("skuLookup component has been loaded.");
        },
        handleSubmit() {
            $('body').trigger('processStart');
            this.messageResponse('');
            // this.isSuccess(false);
            skuModel.isSuccess(false);

            storage.get(`rest/V1/products/${this.sku()}`).done(response => {
                console.log(response);
                this.messageResponse('Product Found %1'.replace('%1', `<strong>${response.name}</strong>`));
                this.isSuccess(true);
            }).fail(() => {
                this.messageResponse($t('Product not found'));
                this.isSuccess(false);
            }).always(() => {
                $('body').trigger('processStop');
            })
        },
    });
});