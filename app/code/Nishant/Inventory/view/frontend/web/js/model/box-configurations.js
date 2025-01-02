define(['ko', 'Nishant_Inventory/js/ko/extenders/numeric', 'mage/url'], function (ko, url) {
    const boxConfigurations = () => {
        const divisor = 139;
        const data = {
            lenght: ko.observable().extend({ numeric: true }),
            width: ko.observable().extend({ numeric: true }),
            height: ko.observable().extend({ numeric: true }),
            weight: ko.observable().extend({ numeric: true }),
            unitsPerBox: ko.observable().extend({ numeric: true }),
            numberOfBoxes: ko.observable().extend({ numeric: true }),
        };

        data.totalWeight = ko.computed(() => {
            const total = data.numberOfBoxes() * data.weight();
            // console.log("Total Weight: ", total);
            return total;
        });

        data.dimensionalWeight = ko.computed(() => {
            const result = (data.lenght() * data.width() * data.height()) / divisor;
            // console.log("Dimensional Weight: ", Math.round(result * data.numberOfBoxes()));
            return Math.round(result * data.numberOfBoxes());
        });

        data.billedWeight = ko.computed(() => {
            return Math.max(data.dimensionalWeight(), data.totalWeight());
        })
        return data;
    };
    return {
        boxConfigurations: ko.observableArray([boxConfigurations()]),
        isSuccess: ko.observable(false),
        add: function () {
            this.boxConfigurations.push(boxConfigurations());
        },
        delete: function (index) {
            this.boxConfigurations.splice(index, 1);
        },
        numberOfBoxes: function () {
            return ko.computed(() => {
                return this.boxConfigurations().reduce(function (runningTotal, boxConfigurations) {
                    return runningTotal + (boxConfigurations.numberOfBoxes() || 0);
                }, 0);
            });
        },
        totalWeight: function () {
            return ko.computed(() => {
                return this.boxConfigurations().reduce(function (runningTotal, boxConfigurations) {
                    return runningTotal + (boxConfigurations.totalWeight() || 0);
                }, 0);
            });
        },
        billedWeight: function () {
            return ko.computed(() => {
                return this.boxConfigurations().reduce(function (runningTotal, boxConfigurations) {
                    return runningTotal + (boxConfigurations.billedWeight() || 0);
                }, 0);
            });
        }, getUrl() {
            return url.build('inventory/index/post');
        }
    }
});