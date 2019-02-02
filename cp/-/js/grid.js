// head {
var __nodeId__ = "ss_components_products_cp__grid";
var __nodeNs__ = "ss_components_products_cp";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, $.ewma.node, {
        options: {},

        __create: function () {
            var w = this;
            var $w = w.element;

            $("input.stock_minimum_value", $w).rebind("blur cut paste keyup", function (e) {
                if (e.which != 9) {
                    var value = $(this).val();

                    w.r('updateStockMinimumValue', {
                        value: value
                    });

                    $(this).addClass("updating");
                }
            });

            $("input.under_order_minimum_value", $w).rebind("blur cut paste keyup", function (e) {
                if (e.which != 9) {
                    var value = $(this).val();

                    w.r('updateUnderOrderMinimumValue', {
                        value: value
                    });

                    $(this).addClass("updating");
                }
            });
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
