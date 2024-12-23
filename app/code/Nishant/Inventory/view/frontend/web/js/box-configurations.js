define(['uiComponent', 'ko'], function (Component, ko) {
    'use strict'
    const boxConfigurations = () => {
        return {
            lenght: ko.observable(),
            width: ko.observable(),
            height: ko.observable(),
            weight: ko.observable(),
            unitsPerBox: ko.observable(),
            numberOfBoxes: ko.observable(),
        }
    }
    return Component.extend({
        defaults: {
            boxConfigurations: ko.observableArray([boxConfigurations()])
        },
        initialize() {
            this._super()
            console.log('Box configurations are loaded')
        },
        handleAdd() {
            this.boxConfigurations.push(boxConfigurations());
        },
        handleDelete(index) {
            console.log('parent: ', parent);
            console.log('index: ', index);
            this.boxConfigurations.splice(index, 1);
        },
        handleSubmit() {
            console.log("Submit box configs")
        }
    });
});