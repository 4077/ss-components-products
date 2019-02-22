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

            var updateStringValue = function ($input) {
                w.r('updateStringValue', {
                    path:  $input.attr("path"),
                    value: $input.val()
                });
            };

            var $grid = $("> .grid", $w);

            $("input[path]", $grid).rebind("blur cut paste", function () {
                updateStringValue($(this));
            });

            $("input[path]", $grid).rebind("keyup", function (e) {
                if (e.which === 13) {
                    updateStringValue($(this));
                }
            });
        },

        savedHighlight: function (path) {
            var $field = $("input[path='" + path + "']", this.element);

            $field.removeClass("updating").addClass("saved");

            setTimeout(function () {
                $field.removeClass("saved");
            }, 1000);
        }
    });
})(__nodeNs__, __nodeId__);
