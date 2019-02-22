<?php namespace ss\components\products\cp\controllers\tile;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    private function triggerUpdate($catId, $reload = true)
    {
        pusher()->trigger('ss/container/' . $catId . '/update_pivot');

        if ($reload) {
            $this->reload();
        }
    }

    public function reload()
    {
        $this->c('grid:reload', [], true);
    }

    public function updateStringValue()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            if ($path = _j64($this->data('path'))) {
                $value = $this->processStringValue($path);

                ss()->cats->apComponentPivotData($pivot, 'tile/' . $path, $value);

                $this->valueUpdateCallback($pivot, $path);

                $this->widget('<:|', 'savedHighlight', $this->data('path'));

                $this->triggerUpdate($pivot->cat_id, false);
            }
        }
    }

    private function processStringValue($path)
    {
        $value = $this->data('value');

        if (in($path, 'image/width, image/height')) {
            if (!is_numeric($value)) {
                return null;
            }

            if ($value > 1920) {
                $value = 1920;
            }

            return $value;
        }

        return $value;
    }

    private function valueUpdateCallback($pivot, $path)
    {
        if (in($path, 'image/width, image/height')) {
            ss()->cats->resetProductsImagesCache($pivot->cat);
        }
    }

    public function toggle()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            if ($path = _j64($this->data('path'))) {
                ss()->cats->invertComponentPivotData($pivot, 'tile/' . $path);

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    public function switch()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            if ($path = _j64($this->data('path'))) {
                if ($value = _j64($this->data('value'))) {
                    ss()->cats->apComponentPivotData($pivot, 'tile/' . $path, $value);

                    $this->triggerUpdate($pivot->cat_id);
                }
            }
        }
    }

//    public function assignStockInfoGroup()
//    {
//        if ($pivot = $this->unxpackModel('pivot')) {
//            if ($group = \ss\multisource\models\WarehouseGroup::find($this->data('value'))) {
//                $stockInfoGroupsData = ss()->cats->apComponentPivotData($pivot, 'tile/stock/groups');
//
//                if (!isset($stockInfoGroupsData[$group->id])) {
//                    $stockInfoGroupsData[$group->id] = \ss\components\products\DefaultData::stockInfoGroup();
//
//                    $stockInfoGroupsData[$group->id]['value_label'] = $group->name . ':';
//                }
//
//                $stockInfoGroupsData[$group->id]['enabled'] = true;
//
//                ss()->cats->apComponentPivotData($pivot, 'tile/stock/groups', $stockInfoGroupsData);
//
//                $this->triggerUpdate($pivot->cat_id);
//            }
//        }
//    }
//
//    public function disableWarehousesGroup()
//    {
//        $pivot = $this->unxpackModel('pivot');
//        $group = $this->unxpackModel('group');
//
//        if ($pivot && $group) {
//            ss()->cats->apComponentPivotData($pivot, 'tile/stock/groups/' . $group->id . '/enabled', false);
//
//            $this->triggerUpdate($pivot->cat_id);
//        }
//    }
//
//    public function toggleInStockDisplay()
//    {
//        $pivot = $this->unxpackModel('pivot');
//        $group = $this->unxpackModel('group');
//
//        if ($pivot && $group) {
//            ss()->cats->invertComponentPivotData($pivot, 'tile/stock/groups/' . $group->id . '/in_stock/display');
//
//            $this->triggerUpdate($pivot->cat_id);
//        }
//    }
//
//    public function toggleNotInStockDisplay()
//    {
//        $pivot = $this->unxpackModel('pivot');
//        $group = $this->unxpackModel('group');
//
//        if ($pivot && $group) {
//            ss()->cats->invertComponentPivotData($pivot, 'tile/stock/groups/' . $group->id . '/not_in_stock/display');
//
//            $this->triggerUpdate($pivot->cat_id);
//        }
//    }
//
//    public function setInStockMode()
//    {
//        $pivot = $this->unxpackModel('pivot');
//        $group = $this->unxpackModel('group');
//
//        if ($pivot && $group) {
//            $value = $this->data('value');
//
//            if (in($value, 'value, label')) {
//                ss()->cats->apComponentPivotData($pivot, 'tile/stock/groups/' . $group->id . '/in_stock/mode', $value);
//
//                $this->triggerUpdate($pivot->cat_id);
//            }
//        }
//    }
//
//    public function setNotInStockMode()
//    {
//        $pivot = $this->unxpackModel('pivot');
//        $group = $this->unxpackModel('group');
//
//        if ($pivot && $group) {
//            $value = $this->data('value');
//
//            if (in($value, 'value, label')) {
//                ss()->cats->apComponentPivotData($pivot, 'tile/stock/groups/' . $group->id . '/not_in_stock/mode', $value);
//
//                $this->triggerUpdate($pivot->cat_id);
//            }
//        }
//    }

    //
    // units
    //

//    public function toggleOtherUnitsDisplay()
//    {
//        if ($pivot = $this->unxpackModel('pivot')) {
//            ss()->cats->invertComponentPivotData($pivot, 'tile/units/other_units_display');
//
//            $this->triggerUpdate($pivot->cat_id);
//        }
//    }

    //
    // layout
    //

    public function selectTemplate()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            ss()->cats->apComponentPivotData($pivot, 'tile/template', $this->data('value'));

            $this->triggerUpdate($pivot->cat_id);
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
}
