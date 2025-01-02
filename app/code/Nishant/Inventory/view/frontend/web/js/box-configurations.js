define(['uiComponent', 'ko', 'Nishant_Inventory/js/model/box-configurations', 'Nishant_Inventory/js/model/sku', 'jquery', ''],
    function (Component, ko, boxConfigurationsModel, skuModel, $) {
        'use strict'

        return Component.extend({
            defaults: {
                boxConfigurationsModel: boxConfigurationsModel,
                skuModel: skuModel
            },
            initialize() {
                this._super()
                console.log('Box configurations are loaded')

                skuModel.isSuccess.subscribe((value) => {
                    // console.log("Value old: ", value);
                });

                skuModel.isSuccess.subscribe((value) => {
                    // console.log("Value new: ", value);
                }, null, 'beforeChange');
            },
            handleAdd() {
                boxConfigurationsModel.add();
            },
            handleDelete(index) {
                boxConfigurationsModel.delete(index);
            },
            handleSubmit() {
                $('.box-configurations form input').removeAttr('aria-invalid');

                if ($('.box-configurations form').valid()) {
                    boxConfigurationsModel.isSuccess(true);
                }
                else {
                    boxConfigurationsModel.isSuccess(false);
                }
            }
        });
    });