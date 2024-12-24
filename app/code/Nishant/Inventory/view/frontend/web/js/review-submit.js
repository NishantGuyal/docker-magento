define(['uiComponent', 'ko', 'Nishant_Inventory/js/model/box-configurations', 'Nishant_Inventory/js/model/sku'], function (Component, ko, boxConfigurationsModel, skuModel) {
    'use strict'

    return Component.extend({
        defaults: {
            sku: skuModel.sku(),
            data: null,
            numberOfBoxes: ko.observable(0)
        },
        initialize() {
            this._super();
            this.data = boxConfigurationsModel.boxConfigurations()[0];
            this.data.numberOfBoxes.subscribe((newValue) => {
                console.log('Updated numberOfBoxes:', newValue);
                this.numberOfBoxes(newValue);
            });
            console.log('Review Submit is loaded');
        },
        getUpdatedNumberOfBoxes() {
            return this.numberOfBoxes();
        }
    })
})