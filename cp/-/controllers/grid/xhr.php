<?php namespace ss\components\products\cp\controllers\grid;

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

                ss()->cats->apComponentPivotData($pivot, 'grid/' . $path, $value);

                $this->valueUpdateCallback($pivot, $path);

                $this->widget('<:|', 'savedHighlight', $this->data('path'));

                $this->triggerUpdate($pivot->cat_id, false);
            }
        }
    }

    private function processStringValue($path)
    {
        $value = $this->data('value');

        if ($path == 'filters/stock/minimum/value') {
            $value = (int)$value;
        }

        return $value;
    }

    private function valueUpdateCallback($pivot, $path)
    {

    }

    public function toggle()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            if ($path = _j64($this->data('path'))) {
                ss()->cats->invertComponentPivotData($pivot, 'grid/' . $path);

                $this->triggerUpdate($pivot->cat_id);
            }
        }
    }

    public function switch()
    {
        if ($pivot = $this->unxpackModel('pivot')) {
            if ($path = _j64($this->data('path'))) {
                if ($value = _j64($this->data('value'))) {
                    ss()->cats->apComponentPivotData($pivot, 'grid/' . $path, $value);

                    $this->triggerUpdate($pivot->cat_id);
                }
            }
        }
    }

    // stock filter

//    public function toggleStockFilterGroup()
//    {
//        $pivot = $this->unxpackModel('pivot');
//        $group = $this->unxpackModel('group');
//
//        if ($pivot && $group) {
//            ss()->cats->invertComponentPivotData($pivot, 'grid/filters/stock/groups/' . $group->id);
//
//            $this->triggerUpdate($pivot->cat_id);
//        }
//    }
}
