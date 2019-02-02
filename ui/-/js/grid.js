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

            var cartItemChangeHandler = function (data) {
                var $tile = $("[item_key='" + data.itemKey + "']", $w);

                if ($tile.length) {
                    w.mr('reloadTile', {
                        product: $tile.attr("xpack")
                    });
                }
            };

            w.e('ss/cart/stage/update_item.' + o.catId, cartItemChangeHandler);
            w.e('ss/cart/add_item.' + o.catId, cartItemChangeHandler);
            w.e('ss/cart/delete_item.' + o.catId, cartItemChangeHandler);
            w.e('ss/cart/update_item.' + o.catId, cartItemChangeHandler);

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
                p(data);

                var $tile = $(".tile[product_id='" + data.id + "']", $w);

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

        bindTile: function (productId) {
            this._bindTile($(".tile[product_id='" + productId + "']", this.element));
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
            // carousel
            //

            $tile.click(function () {
                var productId = $(this).attr("product_id");

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

            $(".dec.button", $quantify).rebind("click", function (e) {
                delta('decQuantity');

                e.stopPropagation();
            });

            $(".inc.button", $quantify).rebind("click", function (e) {
                delta('incQuantity');

                e.stopPropagation();
            });

            $input.rebind("click", function (e) {
                e.stopPropagation();
            });

            $input.rebind("keyup", function (e) {
                if (e.which === 13) {
                    setQuantity();
                }
            });

            $input.rebind("blur", function () {
                setQuantity();
            });

            var updateTimeout = 0;

            function setQuantity() {
                clearTimeout(updateTimeout);
                updateTimeout = setTimeout(function () {
                    w.r('setQuantity', {
                        product: $quantify.closest("[xpack]").attr("xpack"),
                        cart:    o.cart,
                        value:   $input.val()
                    });
                }, 200);
            }

            function delta(path) {
                w.r(path, {
                    product: $quantify.closest("[xpack]").attr("xpack"),
                    cart:    o.cart
                })
            }

            //
            // add to cart
            //

            var $cartbutton = $(".add_to_cart_button", $tile);

            $cartbutton.rebind("click", function (e) {
                w.r('addToCart', {
                    product: $cartbutton.closest("[xpack]").attr("xpack"),
                    cart:    o.cart
                });

                e.stopPropagation();
            });
        }
    });
})(__nodeNs__, __nodeId__);
