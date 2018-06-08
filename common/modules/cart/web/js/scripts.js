if (typeof shop == "undefined" || !shop) {
    var shop = {};
}

/** @global shop */
shop.cart = {
    init: function () {

        cartElementsCount = '[data-role=cart-element-count]';
        buyElementButton = '[data-role=cart-buy-button]';
        deleteElementButton = '[data-role=cart-delete-button]';
        truncateCartButton = '[data-role=truncate-cart-button]';

        shop.cart.csrf = jQuery('meta[name=csrf-token]').attr("content");
        shop.cart.csrf_param = jQuery('meta[name=csrf-param]').attr("content");

        jQuery(document).on('change', cartElementsCount, function () {

            var self = this,
                url = jQuery(self).data('href');

            if (jQuery(self).val() < 0) {
                jQuery(self).val('0');
                return false;
            }

            cartElementId = jQuery(self).data('id');
            cartElementCount = jQuery(self).val();

            jQuery('.shop-cart-element-cost' + cartElementId).css({'opacity': '0.3'});
            $(document).one('shopCartChanged', function(event, data){
                jQuery('.shop-cart-element-cost' + data.elementId).css({'opacity': '1'});
                jQuery('.shop-cart-element-cost' + data.elementId).html(data.element_cost);
            });
            shop.cart.changeElementCount(cartElementId, cartElementCount, url);
            // shop.cart.changeElementCost(cartElementId, cartElementCount, url);
        });

        jQuery(document).on('click', buyElementButton, function () {

            var self = this,
                url = jQuery(self).data('url'),
                itemModelName = jQuery(self).data('model'),
                itemId = jQuery(self).data('id'),
                itemCount = jQuery(self).data('count'),
                itemPrice = jQuery(self).data('price'),
                itemOptions = jQuery(self).data('options');

            shop.cart.addElement(itemModelName, itemId, itemCount, itemPrice, itemOptions, url);

            return false;
        });

        jQuery(document).on('click', truncateCartButton, function () {

            var self = this,
                url = jQuery(self).data('url');

            shop.cart.truncate(url);
            
            return false;
        });

        jQuery(document).on('click', deleteElementButton, function (e) {

            e.preventDefault();

            var self = this,
                url = jQuery(self).data('url'),
                elementId = jQuery(self).data('id');

            shop.cart.deleteElement(elementId, url);
            
            $('[data-role=cart-delete-button][data-id=' + elementId + ']').each(function(){
                self = this;
                if (lineSelector = jQuery(self).data('line-selector')) {
                    jQuery(self).parents(lineSelector).last().hide('slow');
                }
            });

            return false;
        });
        
        jQuery(document).on('click', '.shop-arr', this.changeInputValue);
        jQuery(document).on('change', '.shop-cart-element-before-count', this.changeBeforeElementCount);
        jQuery(document).on('change', '.shop-option-values-before', this.changeBeforeElementOptions);
        jQuery(document).on('change', '.shop-option-values', this.changeElementOptions);

        return true;
    },
    elementsListWidgetParams: [],
    jsonResult: null,
    csrf: null,
    csrf_param: null,
    changeElementOptions: function () {
        jQuery(document).trigger("changeCartElementOptions", this);

        var id = jQuery(this).data('id');

        var options = {};

        if (jQuery(this).is('select')) {
            var els = jQuery('.shop-cart-option' + id);
        }
        else {
            var els = jQuery('.shop-cart-option' + id + ':checked');
            console.log('radio');
        }

        jQuery(els).each(function () {
            var name = jQuery(this).data('id');

            options[id] = jQuery(this).val();
        });

        var data = {};
        data.CartElement = {};
        data.CartElement.id = id;
        data.CartElement.options = JSON.stringify(options);

        shop.cart.sendData(data, jQuery(this).data('href'));

        return false;
    },
    changeBeforeElementOptions: function () {
        var id = jQuery(this).data('id');
        var filter_id = jQuery(this).data('filter-id');
        var buyButton = jQuery('.shop-cart-buy-button' + id);

        var options = jQuery(buyButton).data('options');
        if (!options) {
            options = {};
        }

        options[filter_id] = jQuery(this).val();

        jQuery(buyButton).data('options', options);
        jQuery(buyButton).attr('data-options', options);

        jQuery(document).trigger("beforeChangeCartElementOptions", id);

        return true;
    },
    deleteElement: function (elementId, url) {

        shop.cart.sendData({elementId: elementId}, url);

        return false;
    },
    changeInputValue: function () {
        var val = parseInt(jQuery(this).siblings('input').val());
        var input = jQuery(this).siblings('input');

        if (jQuery(this).hasClass('shop-downArr')) {
            if (val <= 0) {
                return false;
            }
            jQuery(input).val(val - 1);
        }
        else {
            jQuery(input).val(val + 1);
        }

        jQuery(input).change();

        return false;
    },
    changeBeforeElementCount: function () {
        if (jQuery(this).val() <= 0) {
            jQuery(this).val('0');
        }

        var id = jQuery(this).data('id');
        var buyButton = jQuery('.shop-cart-buy-button' + id);
        jQuery(buyButton).data('count', jQuery(this).val());
        jQuery(buyButton).attr('data-count', jQuery(this).val());

        return true;
    },
    changeElementCost: function(cartElementId, cartElementCount) {
        var newCost = jQuery('.shop-cart-element-price'+cartElementId).html() * cartElementCount;
        jQuery('.shop-cart-element-cost'+cartElementId).html(newCost);
    },
    changeElementCount: function (cartElementId, cartElementCount, url) {

        var data = {};
        data.CartElement = {};
        data.CartElement.id = cartElementId;
        data.CartElement.count = cartElementCount;

        shop.cart.sendData(data, url);

        return false;
    },
    addElement: function (itemModelName, itemId, itemCount, itemPrice, itemOptions, url) {

        var data = {};
        data.CartElement = {};
        data.CartElement.model = itemModelName;
        data.CartElement.item_id = itemId;
        data.CartElement.count = itemCount;
        data.CartElement.price = itemPrice;
        data.CartElement.options = itemOptions;

        shop.cart.sendData(data, url);

        return false;
    },
    truncate: function (url) {
        shop.cart.sendData({}, url);
        return false;
    },
    sendData: function (data, link) {
        if (!link) {
            link = '/cart/element/create';
        }

        jQuery(document).trigger("sendDataToCart", data);

        data.elementsListWidgetParams = shop.cart.elementsListWidgetParams;
        data[shop.cart.csrf_param] = shop.cart.csrf;

        jQuery('.shop-cart-block').css({'opacity': '0.3'});
        jQuery('.shop-cart-count').css({'opacity': '0.3'});
        jQuery('.shop-cart-price').css({'opacity': '0.3'});
        jQuery('.shop-order-total').css({'opacity': '0.3'});

        jQuery.post(link, data,
            function (json) {
                jQuery('.shop-cart-block').css({'opacity': '1'});
                jQuery('.shop-cart-count').css({'opacity': '1'});
                jQuery('.shop-cart-price').css({'opacity': '1'});
                jQuery('.shop-order-total').css({'opacity': '1'});

                if (json.result == 'fail') {
                    console.log(json.error);
                }
                else {
                    shop.cart.renderCart(json);
                    $(document).trigger('shopCartChanged', json);
                }

            }, "json");

        return false;
    },
    renderCart: function (json) {
        if (!json) {
            json = {};
            jQuery.post('/cart/default/info', {},
                function (answer) {
                    json = answer;
                }, "json");
        }

        jQuery('.shop-cart-block').replaceWith(json.elementsHTML);
        jQuery('.shop-cart-count').html(json.count);
        jQuery('.shop-cart-price').html(json.price);
        
        var deliveryPrice = json.clear_price + $('.shop-order-delivery').data('price');
        deliveryPrice += '';
        deliveryPrice = deliveryPrice.replace(/(\d{0,3}(?=\d{6}))?(\d{0,3}(?=\d{3}))?(\d{1,3})$/, '$1 $2 $3 ₽');
        jQuery('.shop-order-total').text(deliveryPrice);
        
        jQuery('.shop-cart-checkout-btn').toggle(!!json.count);
        
        if (json.action && json.elementName) {
            switch (json.action) {
                case 'delete':
                    message = 'Товар "' + json.elementName + '" удален из орзины';
                    break;
                case 'update':
                    message = 'Обновлено количество товара "' + json.elementName + '" в корзине';
                    break;
                case 'create':
                    message = 'Товар "' + json.elementName + '" добавлен в корзину';
                    break;
                default:
                    message = 'Изменение товаров в корзине';
                    break;
            }
            $.notify({"icon":"glyphicon glyphicon-fire","id":"w2","message":message},{"placement":{"from":"bottom","align":"right"},"mouse_over":"pause","type":"success"});
            $(document).trigger('addToCart');
        }

        jQuery(document).trigger("renderCart", json);

        return true;
    },
};

$(function() {
    shop.cart.init();
});

