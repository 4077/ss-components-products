// head {
var __nodeId__ = "ss_components_products_cp__tile";
var __nodeNs__ = "ss_components_products_cp";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, $.ewma.node, {
        options: {},

        __create: function () {
            var w = this;
            var $w = w.element;

            $("input.cartbutton_label", $w).rebind("blur keyup", function (e) {
                if (e.type === 'blur' || e.which === 13) {
                    var value = $(this).val();

                    w.r('updateCartbuttonLabel', {value: value});
                }
            });

            //

            $("input.zeroprice_label", $w).rebind("blur keyup", function (e) {
                if (e.type === 'blur' || e.which === 13) {
                    var value = $(this).val();

                    w.r('updateZeropriceLabel', {value: value});
                }
            });

            //

            $("input.in_stock_info_label", $w).rebind("blur keyup", function (e) {
                if (e.type === 'blur' || e.which === 13) {
                    var value = $(this).val();

                    w.r('updateInStockInfoLabel', {value: value});
                }
            });

            $("input.not_in_stock_info_label", $w).rebind("blur keyup", function (e) {
                if (e.type === 'blur' || e.which === 13) {
                    var value = $(this).val();

                    w.r('updateNotInStockInfoLabel', {value: value});
                }
            });

            $("input.stock_value_label", $w).rebind("blur keyup", function (e) {
                if (e.type === 'blur' || e.which === 13) {
                    var value = $(this).val();

                    w.r('updateStockValueLabel', {value: value});
                }
            });

            //

            $("input.in_under_order_info_label", $w).rebind("blur keyup", function (e) {
                if (e.type === 'blur' || e.which === 13) {
                    var value = $(this).val();

                    w.r('updateInUnderOrderInfoLabel', {value: value});
                }
            });

            $("input.not_in_under_order_info_label", $w).rebind("blur keyup", function (e) {
                if (e.type === 'blur' || e.which === 13) {
                    var value = $(this).val();

                    w.r('updateNotInUnderOrderInfoLabel', {value: value});
                }
            });

            $("input.under_order_value_label", $w).rebind("blur keyup", function (e) {
                if (e.type === 'blur' || e.which === 13) {
                    var value = $(this).val();

                    w.r('updateUnderOrderValueLabel', {value: value});
                }
            });

            //

            $("input.image_dimension[field]", $w).rebind("blur cut paste", function (e) {
                if (e.which !== 9) {
                    updateImageDimension($(this));
                }
            });

            $("input.image_dimension[field]", $w).rebind("keyup", function (e) {
                if (e.which === 13) {
                    var field = $(this).attr("field");
                    var value = $(this).val();

                    w.r('updateImageDimension', {
                        field: field,
                        value: value
                    });

                    $(this).addClass("updating");
                }
            });

            var updateTimeout;

            function updateImageDimension($field) {
                var field = $field.attr("field");
                var value = $field.val();

                clearTimeout(updateTimeout);
                updateTimeout = setTimeout(function () {
                    w.r('updateImageDimension', {
                        field: field,
                        value: value
                    });

                    $field.addClass("updating");
                }, 400);
            }
        },

        savedHighlight: function (field) {
            var $field = $("input[field='" + field + "']", this.element);

            $field.removeClass("updating").addClass("saved");

            setTimeout(function () {
                $field.removeClass("saved");
            }, 1000);
        }
    });
})(__nodeNs__, __nodeId__);
