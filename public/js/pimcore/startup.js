pimcore.registerNS("pimcore.plugin.StarfruitShopBundle");

pimcore.plugin.StarfruitShopBundle = Class.create({

    initialize: function () {
        document.addEventListener(pimcore.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function (e) {
        // alert("StarfruitShopBundle ready!");
    }
});

var StarfruitShopBundlePlugin = new pimcore.plugin.StarfruitShopBundle();
