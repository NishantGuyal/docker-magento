define(['uiComponent', 'ko', 'Nishant_Inventory/js/model/box-configurations', 'Nishant_Inventory/js/model/sku', 'mage/url'],
    function (Component, ko, boxConfigurationsModel, skuModel, url) {
        'use strict'

        return Component.extend({
            defaults: {
                data: null,
                // numberOfBoxes: ko.observable(0),
                // totalWeight: ko.observable(0),
                // billedWeight: ko.observable(0),
                boxConfigIsSuccess: boxConfigurationsModel.isSuccess,
                numberOfBoxes: boxConfigurationsModel.numberOfBoxes(),
                totalWeight: boxConfigurationsModel.totalWeight(),
                billedWeight: boxConfigurationsModel.billedWeight(),
                isTermsAgreed: ko.observable(false),
                boxConfigAgreed: ko.observable(false),
                skuConfigAgreed: ko.observable(false),
                buttonUpdate: ko.observable(false),
                boxConfigSuccess: null,
                skuConfigSuccess: null,
                boxConfigurations: boxConfigurationsModel.boxConfigurations,
                sku: skuModel.sku
            },
            initialize() {
                this._super();
                this.boxConfigSuccess = boxConfigurationsModel;

                this.data = boxConfigurationsModel.boxConfigurations()[0];

                // this.data.numberOfBoxes.subscribe((newValue) => {
                //     console.log('Updated numberOfBoxes: ', newValue);
                //     this.numberOfBoxes(newValue);
                // });

                // this.data.totalWeight.subscribe((newValue) => {
                //     console.log('Updated totalWeight: ', newValue);
                //     this.totalWeight(newValue);
                // });

                // this.data.billedWeight.subscribe((newValue) => {
                //     console.log('Updated billedWeight: ', newValue);
                //     this.billedWeight(newValue);
                // });

                this.isTermsAgreed.subscribe((newValue) => {
                    console.log('isTermsAgreed updated to: ', newValue);
                    this.updateSubmitButton();
                });

                this.boxConfigSuccess.isSuccess.subscribe((newValue) => {
                    console.log('Updated Box Success: ', newValue);
                    this.boxConfigAgreed(newValue);
                    this.updateSubmitButton();
                });

                skuModel.isSuccess.subscribe((value) => {
                    console.log("Sku Status: ", value);
                    this.skuConfigAgreed(value);
                    this.updateSubmitButton();
                });

                console.log('Review Submit is loaded');
            },

            // Method to update the submit button state
            updateSubmitButton() {
                if (this.isTermsAgreed() && this.boxConfigAgreed() && this.skuConfigAgreed()) {
                    this.buttonUpdate(true); // Enable the button if both conditions are met
                } else {
                    this.buttonUpdate(false); // Disable the button if either condition is false
                }
            },

            getNumberOfBoxes() {
                return this.numberOfBoxes();
            },
            getTotalWeight() {
                return this.totalWeight();
            },
            getBilledWeight() {
                return this.billedWeight();
            },
            getButtonUpdate() {
                return this.buttonUpdate();
            },
            getUrl() {
                return url.build('inventory/index/post');
            },
            handleSubmit() {
                if (this.canSubmit()) {
                    console.log('The Review Submit form has been submitted.');

                    storage
                        .post(this.getUrl(), {
                            'sku': skuModel.sku(),
                            'boxConfigurations': ko.toJSON(boxConfigurationsModel.boxConfigurations)
                        })
                        .done(response => console.log('Response', response))
                        .fail(err => console.log('Error', err));
                } else {
                    console.log('The Review Submit form has an error.');
                }
            },
        })
    });
