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
                $(".tile[product_id='" + o.openProductId + "']").click();
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
                        cart:    $quantify.closest("[cart]").attr("cart"),
                        value:   $input.val()
                    });
                }, 200);
            }

            function delta(path) {
                w.r(path, {
                    product: $quantify.closest("[xpack]").attr("xpack"),
                    cart:    $quantify.closest("[cart]").attr("cart")
                })
            }

            //
            // add to cart
            //

            var $cartbutton = $(".add_to_cart_button", $tile);

            $cartbutton.rebind("click", function (e) {
                w.r('addToCart', {
                    product: $cartbutton.closest("[xpack]").attr("xpack"),
                    cart:    $cartbutton.closest("[cart]").attr("cart")
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
