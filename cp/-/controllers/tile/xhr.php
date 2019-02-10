<?php namespace ss\components\products\cp\controllers\tile;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    private function triggerUpdate($catId)
    {
        pusher()->trigger('ss/container/' . $catId . '/update_pivot');
    }

    public function reload()
    {
        $this->c('<:reload', [], true);
    }

    public function selectTemplate()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/template', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    //

    public function setNamePriority()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            if (in($value, 'full, short, remote_full, remote_short')) {
                ss()->cats->apComponentPivotData($pivot, 'tile/name_priority', $value);

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    public function toggleCartbutton()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/cartbutton/display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function togglePrice()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/price_display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    //

    public function togglePriceRounding()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/price_rounding/enabled', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function setPriceRoundingMode()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            if (in($value, 'floor, round, ceil')) {
                ss()->cats->apComponentPivotData($pivot, 'tile/price_rounding/mode', $value);

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    //

    public function toggleZeropriceLabel()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/zeroprice_label/enabled', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function updateZeropriceLabel()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            ss()->cats->apComponentPivotData($pivot, 'tile/zeroprice_label/value', $value);

            $this->widget('<:|', 'savedHighlight', 'zeroprice_label');

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    //

    public function toggleInStockDisplay()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/in_stock/display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function toggleNotInStockDisplay()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/not_in_stock/display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function setInStockMode()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            if (in($value, 'value, label')) {
                ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/in_stock/mode', $value);

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    public function setNotInStockMode()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            if (in($value, 'value, label')) {
                ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/not_in_stock/mode', $value);

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    public function updateInStockInfoLabel()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/in_stock/label', $value);

            $this->widget('<:|', 'savedHighlight', 'in_stock_info_label');

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function updateNotInStockInfoLabel()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/not_in_stock/label', $value);

            $this->widget('<:|', 'savedHighlight', 'not_in_stock_info_label');

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function updateStockValueLabel()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/common/stock_value_label', $value);

            $this->widget('<:|', 'savedHighlight', 'stock_value_label');

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    //

    public function toggleInUnderOrderDisplay()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/in_under_order/display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function toggleNotInUnderOrderDisplay()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/not_in_under_order/display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function setInUnderOrderMode()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            if (in($value, 'value, label')) {
                ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/in_under_order/mode', $value);

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    public function setNotInUnderOrderMode()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            if (in($value, 'value, label')) {
                ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/not_in_under_order/mode', $value);

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    public function updateInUnderOrderInfoLabel()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/in_under_order/label', $value);

            $this->widget('<:|', 'savedHighlight', 'in_under_order_info_label');

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function updateNotInUnderOrderInfoLabel()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/not_in_under_order/label', $value);

            $this->widget('<:|', 'savedHighlight', 'not_in_under_order_info_label');

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function updateUnderOrderValueLabel()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/common/under_order_value_label', $value);

            $this->widget('<:|', 'savedHighlight', 'under_order_value_label');

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    //

    public function toggleStockRounding()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/common/rounding/enabled', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function setStockRoundingMode()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            if (in($value, 'floor, round, ceil')) {
                ss()->cats->apComponentPivotData($pivot, 'tile/stock_info/common/rounding/mode', $value);

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    //

    public function toggleQuantify()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/quantify', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function toggleSellByAltUnits()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/sell_by_alt_units', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function toggleOtherUnitsDisplay()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/other_units_display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function updateImageDimension()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $field = $this->data('field');
            $value = $this->data('value');

            if (in($field, 'width, height') && is_numeric($value)) {
                if ($value > 1920) {
                    $value = 1920;
                }

                ss()->cats->apComponentPivotData($pivot, path('tile/image', $field), $value);
                ss()->cats->resetProductsImagesCache($pivot->cat);

                ss()->cats->resetProductsImagesCache(\ss\models\Cat::find(26141)); // todo hardcode категория образцов плитки

                $this->e('ss/cats/containers/updateImageDimension')->trigger(['pivot' => $pivot]); /// todo

                $this->widget('<:|', 'savedHighlight', $field);

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    public function setImageResizeMode()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            if (in($value, 'fill, fit')) {
                ss()->cats->apComponentPivotData($pivot, 'tile/image/resize_mode', $value);

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    public function updateCartbuttonLabel()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            ss()->cats->apComponentPivotData($pivot, 'tile/cartbutton/label', $value);

            $this->widget('<:|', 'savedHighlight', 'cartbutton_label');

            $this->triggerUpdate($pivot->cat_id);
        }
    }
}
