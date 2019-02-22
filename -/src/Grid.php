<?php namespace ss\components\products;

class Grid
{
    public static $instances = [];

    public static function getInstance(\ss\models\CatComponent $pivot)
    {
        if (!isset(static::$instances[$pivot->id])) {
            static::$instances[$pivot->id] = new self($pivot);
        }

        return static::$instances[$pivot->id];
    }

    private $pivot;

    private $pivotData;

    public function __construct(\ss\models\CatComponent $pivot)
    {
        $this->pivot = $pivot;

        $this->pivotData = _j($this->pivot->data);

        $this->render();
    }

    public function data($path = false)
    {
        return ap($this->pivotData, 'grid/' . $path);
    }

    public $selectedWarehousesGroup;

    public $nameDisplay;

    public $descriptionDisplay;

    public $stockFilterEnabled;

    public $stockFilterMode;

    public $stockMinimumEnabled;

    public $stockMinimumValue = 0;

    public $notZeropriceFilterEnabled;

    public function render()
    {
        $this->selectedWarehousesGroup = ss()->cats->getSelectedWarehousesGroup($this->pivot->cat->tree_id);

        $this->nameDisplay = $this->data('name_display');
        $this->descriptionDisplay = $this->data('description_display');

        $this->renderFilters();
    }

    private function renderFilters()
    {
        if ($this->stockFilterEnabled = $this->data('filters/stock/enabled')) {
            $this->stockFilterMode = $this->data('filters/stock/mode');
        }

        if ($this->stockMinimumEnabled = $this->data('stock_minimum/enabled')) {
            $this->stockMinimumValue = $this->data('stock_minimum/value');
        }

        $this->notZeropriceFilterEnabled = $this->data('filters/not_zeroprice/enabled');
    }
}
