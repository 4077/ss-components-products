<?php namespace ss\components\products\cp\controllers\grid;

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

    public function toggleName()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'grid/name_display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function toggleDescription()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'grid/description_display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function toggleNotInStockProducts()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'grid/not_in_stock_products_display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function toggleNotInUnderOrderProducts()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'grid/not_in_under_order_products_display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function toggleStockMinimum()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'grid/stock_minimum/enabled', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function toggleUnderOrderMinimum()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'grid/under_order_minimum/enabled', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }

    public function updateStockMinimumValue()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            $value = \ss\support\Support::parseDecimal($value);

            if (is_numeric($value)) {
                ss()->cats->apComponentPivotData($pivot, 'grid/stock_minimum/value', $value);

                $this->widget('<:|', 'savedHighlight', 'stock_minimum_value');

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    public function updateUnderOrderMinimumValue()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            $value = $this->data('value');

            $value = \ss\support\Support::parseDecimal($value);

            if (is_numeric($value)) {
                ss()->cats->apComponentPivotData($pivot, 'grid/under_order_minimum/value', $value);

                $this->widget('<:|', 'savedHighlight', 'under_order_minimum_value');

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    public function toggleZeropriceProductsDisplay()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'grid/zeroprice_products_display', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
        }
    }
}
