// head {
var __nodeId__ = "ss_components_products_ui__grid";
var __nodeNs__ = "ss_components_products_ui";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, $.ewma.node, {
        options: {},

        __create: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            w._bindTiles();
            w.bindEvents();
        },

        bindEvents: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            ///

            var cartProductChangeHandler = function (data) {
                var $tile = $("[product_id='" + data.productId + "']", $w);

                if ($tile.length) {
                    w.mr('reloadTile', {
                        product: $tile.attr("xpack")
                    });
                }
            };

            w.e('ss/cart/stage/update_product.' + o.catId, cartProductChangeHandler);
            w.e('ss/cart/add_product.' + o.catId, cartProductChangeHandler);
            w.e('ss/cart/delete_product.' + o.catId, cartProductChangeHandler);
            w.e('ss/cart/update_product.' + o.catId, cartProductChangeHandler);

            ///

            // todo ss/cat/components_update events либо оставить в качестве примера
            w.e('ss/cat/components_update.' + o.catId, function () {
                w.mr('reload');
            });

            w.e('ss/cat/update_cats.' + o.catId, function (data) {
                if (o.catId === data.id) {
                    w.mr('reload');
                }
            });

            w.e('ss/cat/update_products.' + o.catId, function (data) {
                if (o.catId === data.id) {
                    w.mr('reload');
                }
            });

            w.e('ss/container/update.' + o.catId, function (data) {
                if (o.catId === data.id) {
                    if (isset(data.name)) {
                        $("> .name", $w).html(data.name);
                    }

                    if (isset(data.description)) {
                        $("> .description", $w).html(data.description);
                    }
                }
            });

            w.e('ss/product/update.' + o.catId, function (data) {
                var $tile = $(".tile[item_id='" + data.id + "']", $w);

                if ($tile.length) {
                    if (isset(data.published)) {
                        $tile.find(".not_published_mark").toggle(!data.published);
                    }

                    if (isset(data.name)) {
                        $(".ss_components_products_ui__tile[instance='" + o.productId + "'] .name_container > .name", $w).html(data.name);
                    }

                    if (isset(data.images)) {
                        w.mr('reload');
                    }

                    if (isset(data.status)) {
                        w.mr('reload');
                    }
                }
            });
        },

        resetQuantity: function (productId) {
            this.options.products[productId].quantity = 1;
        },

        bindTile: function (productId) {
            this._bindTile($(".tile[item_id='" + productId + "']", this.element));
        },

        _bindTiles: function () {
            var w = this;
            var $w = w.element;

            $(".tile", $w).each(function () {
                w._bindTile($(this));
            });
        },

        _bindTile: function ($tile) {
            var w = this;
            var o = w.options;
            var $w = w.element;

            $(".not_published_mark", $w).click(function (e) {
                e.stopPropagation();
            });

            //
            // tmp {
            //
            // https://ru.stackoverflow.com/a/743917
            var number_format = function (number, decimals = 0, dec_point = '.', thousands_sep = ' ') {
                var sign = number < 0 ? '-' : '';

                var s_number = Math.abs(parseInt(number = (+number || 0).toFixed(decimals))) + "";
                var len = s_number.length;
                var tchunk = len > 3 ? len % 3 : 0;

                var ch_first = (tchunk ? s_number.substr(0, tchunk) + thousands_sep : '');
                var ch_rest = s_number.substr(tchunk).replace(/(\d\d\d)(?=\d)/g, '$1' + thousands_sep);
                var ch_last = decimals ?
                    dec_point + (Math.abs(number) - s_number)
                        .toFixed(decimals)
                        .slice(2) :
                    '';

                return sign + ch_first + ch_rest + ch_last;
            };
            //
            // }

//
            // carousel
            //

            $tile.click(function () {
                var productId = $tile.attr("item_id");

                if (w.w('carousel').show($(this).attr("n"))) {
                    window.history.replaceState(null, null, '/товары/' + productId + '/');

                    w.r('productOpen', {
                        product_id: productId
                    });
                }
            });

            //
            // quantify
            //

            var $quantify = $(".quantify", $tile);
            var $input = $("input", $quantify);
            var $totalCost = $(".total_cost > .value > span", $tile);

            $(".dec.button", $quantify).rebind("click", function (e) {
                var productId = $tile.attr("item_id");

                if (o.products[productId]['quantity'] > 1) {
                    deltaQuantity(-1, $tile);
                }

                e.stopPropagation();
            });

            $(".inc.button", $quantify).rebind("click", function (e) {
                deltaQuantity(1, $tile);

                e.stopPropagation();
            });

            $input.rebind("keyup", function (e) {
                var productId = $tile.attr("item_id");

                var value = $input.val();

                if (value === '') {
                    updateQuantity(productId, 0);
                } else {
                    if (parseInt(value) === parseInt(value)) {
                        updateQuantity(productId, parseInt(value));
                    } else {
                        $input.val(o.products[productId]['quantity']);
                    }
                }
            });

            $input.rebind("click", function (e) {
                e.stopPropagation();
            });

            function deltaQuantity(delta, $tile) {
                var productId = $tile.attr("item_id");

                updateQuantity(productId, o.products[productId]['quantity'] + delta);
            }

            var updateTimeout = 0;

            function updateQuantity(productId, quantity) {
                if (quantity < 0) {
                    quantity = 0;
                }

                o.products[productId]['quantity'] = quantity;

                var price = o.products[productId]['price'];
                var totalCost = price * quantity;

                $input.val(quantity);

                if (o.priceRounding.enabled) {
                    if (o.priceRounding.mode === 'floor') {
                        totalCost = Math.floor(totalCost);
                    }

                    if (o.priceRounding.mode === 'round') {
                        totalCost = Math.round(totalCost);
                    }

                    if (o.priceRounding.mode === 'ceil') {
                        totalCost = Math.ceil(totalCost);
                    }

                    totalCost = number_format(totalCost)
                } else {
                    totalCost = number_format(totalCost, 2);
                }

                $totalCost.html(totalCost);

                clearTimeout(updateTimeout);
                updateTimeout = setTimeout(function () {
                    w.mr('setQuantity', {
                        product: $tile.find("[xpack]").attr("xpack"),
                        cart:    o.cart,
                        value:   quantity
                    });
                }, 200);
            }

            //
            // add to cart
            //

            var $cartbutton = $(".add_to_cart_button", $tile);

            $cartbutton.rebind("click", function (e) {
                var productId = $tile.attr("item_id");

                w.r('addToCart', {
                    data:     $(this).attr("data"),
                    quantity: o.products[productId]['quantity']
                });

                e.stopPropagation();
            });
        }
    });
})(__nodeNs__, __nodeId__);
