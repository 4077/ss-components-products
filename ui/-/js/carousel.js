// head {
var __nodeId__ = "ss_components_products_ui__carousel";
var __nodeNs__ = "ss_components_products_ui";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, $.ewma.node, {
        options: {},

        __create: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            var owl = $(".items", $w);

            $(".prev", $w).rebind("click", function () {
                owl.trigger('prev.owl.carousel');
            });

            $(".next", $w).rebind("click", function () {
                owl.trigger('next.owl.carousel');
            });

            if (o.autoplay && o.autoplayHoverPause) {
                var container = $(".items", w.element);

                container.mouseenter(function () {
                    owl.trigger('stop.owl.autoplay');
                });

                container.mouseleave(function () {
                    owl.trigger('play.owl.autoplay');
                });
            }

            setTimeout(function () {
                w.wrapNavButtons(true);
            }, 1000);

            if (!o.loop) {
                var $arrows = $("." + __nodeId__ + "__arrows[instance='" + o.instance + "']");

                owl.on('changed.owl.carousel', function (e) {
                    var $item = $($(".owl-item", $w).get(e.item.index)).find("> .item");

                    var productId = $item.attr("product_id");

                    window.history.replaceState(null, null, '/товары/' + productId + '/');

                    w.mr('productSlide', {
                        product_id: productId
                    });

                    if (e.item.index === 0) {
                        $(".prev", $arrows).addClass("hidden");
                    } else {
                        $(".prev", $arrows).removeClass("hidden");
                    }

                    if (e.item.index >= e.item.count - o.items) {
                        $(".next", $arrows).addClass("hidden");
                    } else {
                        $(".next", $arrows).removeClass("hidden");
                    }

                    w.wrapNavButtons();
                });

                if (o.startPosition === 0) {
                    $(".prev", $arrows).addClass("hidden");
                }

                w.wrapNavButtons();
            }

            $(window).resize(function () {
                setTimeout(function () {
                    w.wrapNavButtons();
                }, 500);
            });

            $w.click(function () {
                $w.hide();

                w.revertPageRoute();

                w.r('close');
            });

            $(".carousel", $w).click(function (e) {
                e.stopPropagation();
            });

            $(".arrow", $w).click(function (e) {
                e.stopPropagation();
            });

            if (o.openProductId) {
                $(".tile[item_id='" + o.openProductId + "']").click();
            }

            w._bindProducts();
        },

        bindProduct: function (productId) {
            this._bindProduct($(".item[product_id='" + productId + "']", this.element));
        },

        _bindProducts: function () {
            var w = this;
            var $w = w.element;

            $(".item", $w).each(function () {
                w._bindProduct($(this));
            });
        },

        _bindProduct: function ($tile) {
            var w = this;
            var o = w.options;
            var $w = w.element;

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
                var productId = $tile.attr("product_id");

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
                var productId = $tile.attr("product_id");

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

                var priceRounding = o.products[productId]['priceRounding'];

                if (priceRounding.enabled) {
                    if (priceRounding.mode === 'floor') {
                        totalCost = Math.floor(totalCost);
                    }

                    if (priceRounding.mode === 'round') {
                        totalCost = Math.round(totalCost);
                    }

                    if (priceRounding.mode === 'ceil') {
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
                var productId = $tile.attr("product_id");

                w.r('addToCart', {
                    data:     $(this).attr("data"),
                    quantity: o.products[productId]['quantity']
                });

                e.stopPropagation();
            });
        },

        show: function (n) {
            var w = this;
            var $w = w.element;

            if ($(window).width() >= 919) {
                var owl = $(".items", $w);

                owl.trigger('to.owl.carousel', n);

                $w.css({
                    display: 'flex'
                });

                return true;
            }
        },

        wrapNavButtons: function () {
            var w = this;
            var $w = w.element;

            var $carousel = $(".carousel", $w);

            setTimeout(function () {
                $(".arrow", $w).height($carousel.height());
            }, 1000);
        },

        revertPageRoute: function () {
            var route = this.options.route ? this.options.route + '/' : '';

            window.history.replaceState(null, null, '/' + route);
        }
    });
})(__nodeNs__, __nodeId__);
