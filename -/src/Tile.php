<?php namespace ss\components\products;

class Tile
{
    public static $instances = [];

    public static function getInstance(\ss\models\Product $product, \ss\models\CatComponent $pivot)
    {
        if (!isset(static::$instances[$pivot->id][$product->id])) {
            static::$instances[$pivot->id][$product->id] = new self($product, $pivot);
        }

        return static::$instances[$pivot->id][$product->id];
    }

    private $grid;

    public $product;

    public $pivot;

    public $pivotData;

    public function __construct(\ss\models\Product $product, \ss\models\CatComponent $pivot)
    {
        $this->grid = grid($pivot);

        $this->product = $product;
        $this->pivot = $pivot;

        $this->pivotData = _j($this->pivot->data);

        $this->render();
    }

    public function data($path = false)
    {
        return ap($this->pivotData, 'tile/' . $path);
    }

    public $name;

    public $cartInstance = '';

    /**
     * @var \ss\cart\Svc
     */
    public $cart;

    public $stageQuantity;

    public $cartQuantity;

    public $cartbuttonQuantify;

    public $cartbuttonDisplay;

    public $cartbuttonLabel;

    public $stockInfoDisplay;

    public $stock;

    public $inStock;

    public $inStockOnOthers;

    public $units;

    public $notRoundedPrice;

    public $priceMin;

    public $priceMinFormatted;

    public $priceMax;

    public $priceMaxFormatted;

    public $price;

    public $priceIsRange;

    public $priceDisplay;

    public $zeropriceLabelEnabled;

    public $zeropriceLabelValue;

    public $discountApplied;

    public $discount;

    public $priceWithoutDiscount;

    public $priceWithoutDiscountFormatted;

    public $imageWidth = 225;

    public $imageHeight = 200;

    public $imageResizeMode = 'fill';

    public $imageQuery = '225 200 fill';

    public $imageHref;

    public $template;

    public $css = ':\css\std~';

    public $hiddenByStock;

    public $hiddenByZeroprice;

    public $stockMinimumValue;

    private function render()
    {
        $this->name = $this->renderProductName();

        $this->stockMinimumValue = $this->areAltUnitsUsed() ? $this->grid->stockMinimumValue * $this->product->unit_size : $this->grid->stockMinimumValue;

        $this->renderTemplate(); #1
        $this->renderCss(); #2
        $this->renderImage();

        $this->renderMultisourceData(); #1
        $this->renderPriceAndUnits(); #2
        $this->renderDiscount(); #3
        $this->roundPrices(); #4
        $this->formatPrices(); #5

        if ($this->zeropriceLabelEnabled = $this->data('price/zeroprice_label/enabled')) {
            $this->zeropriceLabelValue = $this->data('price/zeroprice_label/value');
        }

        $this->cartInstance = $this->data('cart_instance');
        $this->cartbuttonDisplay = $this->data('cartbutton/display');
        $this->cartbuttonLabel = $this->data('cartbutton/label');
        $this->cartbuttonQuantify = $this->data('cartbutton/quantify');

        $this->cart = cart($this->cartInstance);
        $this->stageQuantity = $this->cart->stage->getQuantity($this->product);
        $this->cartQuantity = $this->cart->getQuantity($this->product);

        $this->priceDisplay = $this->data('price/display');
    }

    private function renderTemplate()
    {
        $templates = dataSets()->get('ss/components/products:tiles_templates'); // todo modules/ss-components-products:tiles_templates

        $this->template = $templates[$this->data('template')]['path'] ?? '';
    }

    private function renderCss()
    {
        if ($this->template) {
            $this->css = $this->data('css') ?: $this->template;
        }
    }

    private function renderImage()
    {
        $this->imageWidth = $this->data('image/width') ?: $this->imageWidth;
        $this->imageHeight = $this->data('image/height') ?: $this->imageHeight;
        $this->imageResizeMode = $this->data('image/resize_mode') ?: $this->imageResizeMode;

        $this->imageQuery = $this->imageWidth . ' ' . $this->imageHeight . ' ' . $this->imageResizeMode;
    }

    public function isHidden()
    {
        return $this->hiddenByStock || $this->hiddenByZeroprice;
    }

    public function getTotalCost($quantity)
    {
        return $this->formatPrice($quantity * $this->price);
    }

    public $stockGroupsInfo = [];

    private $stockRoundingEnabled;

    private $stockRoundingMode;

    private function renderMultisourceData()
    {
        $product = $this->product;
        $grid = $this->grid;

        //

        if ($this->stockRoundingEnabled = $this->data('stock/rounding/enabled')) {
            $this->stockRoundingMode = $this->data('stock/rounding/mode');
        }

        //

        $selectedGroupId = $grid->selectedWarehousesGroup;

        $groups = ss()->multisource->getWarehousesGroups();
        $summary = table_rows_by($product->multisourceSummary, 'warehouses_group_id');

        $totalStock = 0;

        $price = false;
        $priceMin = PHP_INT_MAX;
        $priceMax = 0;

        $discount = PHP_INT_MAX;

        $stockMultiplier = $this->areAltUnitsUsed() ? 1 / $product->unit_size : 1;

        $useSelectedGroup = false;
        if ($selectedGroupId) {
            $group = $groups[$selectedGroupId];

            $groupWarehouses = ss()->multisource->getGroupWarehouses($group);

            $hasFilials = false;

            foreach ($groupWarehouses as $groupWarehouse) {
                $isSupplier = $groupWarehouse->division->supplier;

                if (!$isSupplier) {
                    $hasFilials = true;
                }
            }

            if ($hasFilials) {
                $useSelectedGroup = true;
            }
        }

        if ($useSelectedGroup) {

            //
            // selected group
            //

            $groupSummary = $summary[$selectedGroupId];

            $stockInSelected = $groupSummary->stock - $groupSummary->reserved; //
            $totalStock += $stockInSelected;

            $stockInSelectedRounded = $this->roundStock($stockInSelected);
            $inStock = $stockInSelectedRounded > 0 && $stockInSelectedRounded >= $this->stockMinimumValue;

            $this->inStock = $inStock;

            $selectedGroupSettings = $this->data('stock/selected_group');
            $settingsIndex = $inStock ? 'in_stock' : 'not_in_stock';

            if (
                ($inStock && $this->data('stock/selected_group/in_stock/display')) ||
                (!$inStock && $this->data('stock/selected_group/not_in_stock/display'))
            ) {
                $this->stockGroupsInfo[] = [
                    'type'        => 'selected',
                    'value'       => $this->roundStock($stockInSelected * $stockMultiplier),
                    'in_stock'    => $inStock,
                    'mode'        => $selectedGroupSettings[$settingsIndex]['mode'],
                    'label'       => $selectedGroupSettings[$settingsIndex]['label'],
                    'value_label' => $selectedGroupSettings['value_label']['group_name_if_possible'] ? $group->name . ':' : $selectedGroupSettings['value_label']['label']
                ];
            }

            list($priceMin, $priceMax) = $this->precisePrice($priceMin, $priceMax, $groupSummary);
            list($priceMin, $priceMax, $price, $priceIsRange) = $this->renderPriceVars($priceMin, $priceMax);

            $discount = min($discount, $groupSummary->min_discount);

            //
            // other groups
            //

            $getPriceFromAllGroups = $price === 0;

            if ($getPriceFromAllGroups) {
                $priceMin = PHP_INT_MAX;
                $priceMax = 0;
            }

            $groupsWithStockCount = 0;

            $stock = 0;
            foreach ($groups as $groupId => $group) {
                $groupSummary = $summary[$groupId];

                if ($groupId != $selectedGroupId) {
                    $groupStock = $groupSummary->stock - $groupSummary->reserved;

                    $stock += $groupStock;

                    if ($groupStock > 0) {
                        $groupsWithStockCount++;

                        $otherGroupName = $group->name;
                    }
                }

                if ($getPriceFromAllGroups) {
                    list($priceMin, $priceMax) = $this->precisePrice($priceMin, $priceMax, $groupSummary);
                }
            }

            if ($getPriceFromAllGroups) {
                list($priceMin, $priceMax, $price, $priceIsRange) = $this->renderPriceVars($priceMin, $priceMax);
            }

            $totalStock += $stock;

            $stockRounded = $this->roundStock($stock);
            $inStock = $stockRounded > 0 && $stockRounded >= $this->stockMinimumValue;

            $this->inStockOnOthers = $inStock;

            $otherGroupsSettings = $this->data('stock/other_groups');
            $settingsIndex = $inStock ? 'in_stock' : 'not_in_stock';

            if (
                ($inStock && $this->data('stock/other_groups/in_stock/display')) ||
                (!$inStock && $this->data('stock/other_groups/not_in_stock/display'))
            ) {
                $this->stockGroupsInfo[] = [
                    'type'        => 'other',
                    'value'       => $this->roundStock($stock * $stockMultiplier),
                    'in_stock'    => $inStock,
                    'mode'        => $otherGroupsSettings[$settingsIndex]['mode'],
                    'label'       => $otherGroupsSettings[$settingsIndex]['label'],
                    'value_label' => $otherGroupsSettings['value_label']['group_name_if_possible'] && $groupsWithStockCount == 1 ? $otherGroupName . ':' : $otherGroupsSettings['value_label']['label']
                ];
            }

            //
            // stock filter
            //

            if ($grid->stockFilterEnabled) {
                if ($grid->stockFilterMode == 'all') {
                    $this->hiddenByStock = $totalStock <= 0 || $totalStock < $this->stockMinimumValue;
                }

                if ($grid->stockFilterMode == 'selected') {
                    $this->hiddenByStock = $stockInSelectedRounded <= 0 || $stockInSelectedRounded < $this->stockMinimumValue;
                }
            }
        } else {

            //
            // all groups
            //

            $stock = 0;
            foreach ($groups as $groupId => $group) {
                $groupSummary = $summary[$groupId];

                $groupStock = $groupSummary->stock - $groupSummary->reserved;

                $stock += $groupStock;

                list($priceMin, $priceMax) = $this->precisePrice($priceMin, $priceMax, $groupSummary);

                $discount = min($discount, $groupSummary->min_discount);
            }

            list($priceMin, $priceMax, $price, $priceIsRange) = $this->renderPriceVars($priceMin, $priceMax);

            $totalStock += $stock;

            $stockRounded = $this->roundStock($stock);
            $inStock = $stockRounded > 0 && $stockRounded >= $this->stockMinimumValue;

            $this->inStock = $inStock;

            $selectedGroupSettings = $this->data('stock/selected_group');
            $settingsIndex = $inStock ? 'in_stock' : 'not_in_stock';

            if (
                ($inStock && $this->data('stock/selected_group/in_stock/display')) ||
                (!$inStock && $this->data('stock/selected_group/not_in_stock/display'))
            ) {
                $this->stockGroupsInfo[] = [
                    'type'        => 'other',
                    'value'       => $this->roundStock($stock * $stockMultiplier),
                    'in_stock'    => $inStock,
                    'mode'        => $selectedGroupSettings[$settingsIndex]['mode'],
                    'label'       => $selectedGroupSettings[$settingsIndex]['label'],
                    'value_label' => $selectedGroupSettings['value_label']['label']
                ];
            }

            //
            // stock filter
            //

            if ($grid->stockFilterEnabled) {
                $this->hiddenByStock = $totalStock <= 0 || $totalStock < $this->stockMinimumValue;
            }
        }

        //
        //
        //

        if ($discount == PHP_INT_MAX || $priceIsRange) {
            $discount = 0;
        }

        $this->price = $price;
        $this->priceMin = $priceMin;
        $this->priceMax = $priceMax;
        $this->priceIsRange = $priceIsRange;

        $this->discount = $discount;

        if (!$priceIsRange && $price == 0 && $grid->notZeropriceFilterEnabled) {
            $this->hiddenByZeroprice = true;
        }
    }

    private function precisePrice($min, $max, $groupSummary)
    {
        if ($groupSummary->not_suppliers_min_price > 0) {
            $min = min($min, $groupSummary->not_suppliers_min_price);
        }

        $max = max($max, $groupSummary->not_suppliers_max_price);

        return [$min, $max];
    }

    private function renderPriceVars($min, $max)
    {
        if ($min == PHP_INT_MAX) {
            $min = 0;
        }

        if ($min == $max) {
            $price = $min;
            $isRange = false;
        } else {
            if ($min > 0) {
                $price = false;
                $isRange = true;
            } else {
                $price = $max;
                $isRange = false;
            }
        }

        return [$min, $max, $price, $isRange];
    }

    private function roundStock($stock)
    {
        if ($this->stockRoundingEnabled) {
            if ($this->stockRoundingMode == 'floor') {
                return floor($stock);
            }

            if ($this->stockRoundingMode == 'round') {
                return round($stock);
            }

            if ($this->stockRoundingMode == 'ceil') {
                return ceil($stock);
            }
        }

        return $stock;
    }

    private function roundPrices()
    {
        $this->notRoundedPrice = $this->price;

        if ($this->data('price/rounding/enabled')) {
            $roundingMode = $this->data('price/rounding/mode');

            if ($this->priceIsRange) {
                if ($roundingMode == 'floor') {
                    $this->priceMin = floor($this->priceMin);
                    $this->priceMax = floor($this->priceMax);
                }

                if ($roundingMode == 'round') {
                    $this->priceMin = round($this->priceMin);
                    $this->priceMax = round($this->priceMax);
                }

                if ($roundingMode == 'ceil') {
                    $this->priceMin = ceil($this->priceMin);
                    $this->priceMax = ceil($this->priceMax);
                }
            } else {
                if ($roundingMode == 'floor') {
                    $this->price = floor($this->price);
                    $this->priceWithoutDiscount = floor($this->priceWithoutDiscount);
                }

                if ($roundingMode == 'round') {
                    $this->price = round($this->price);
                    $this->priceWithoutDiscount = round($this->priceWithoutDiscount);
                }

                if ($roundingMode == 'ceil') {
                    $this->price = ceil($this->price);
                    $this->priceWithoutDiscount = ceil($this->priceWithoutDiscount);
                }
            }
        }
    }

    private function formatPrices()
    {
        if ($this->priceIsRange) {
            $this->priceMinFormatted = $this->formatPrice($this->priceMin);
            $this->priceMaxFormatted = $this->formatPrice($this->priceMax);
        } else {
            $this->priceFormatted = $this->formatPrice($this->price);
            $this->priceWithoutDiscountFormatted = $this->formatPrice($this->priceWithoutDiscount);
        }
    }

    private function formatPrice($price)
    {
        $decimals = $price == (int)$price ? 0 : 2;

        return number_format__($price, $decimals);
    }

    private function renderDiscount()
    {
        if (!$this->priceIsRange) {
            if ($this->discount > 0 && $this->data('price/discount/display') && $this->price > 0 && $this->inStock) {
                $this->discountApplied = true;
                $this->priceWithoutDiscount = $this->price;
                $this->price -= $this->price * $this->discount / 100;
            }
        }
    }

    private function renderPriceAndUnits()
    {
        $product = $this->product;

        $this->units = $product->units;

        if ($this->areAltUnitsUsed()) {
            if ($this->priceIsRange) {
                $this->priceMin *= $product->unit_size;
                $this->priceMax *= $product->unit_size;
            } else {
                $this->price *= $product->unit_size;
            }

            $this->units = $product->alt_units;
        }
    }

    private $altUnitsUsed;

    private function areAltUnitsUsed()
    {
        if (null == $this->altUnitsUsed) {
            $product = $this->product;

            $used = $this->data('units/sell_by_alt_units');

            if ($this->data('units/try_force_units/enabled')) {
                $tryForceUnitsList = $this->data('units/try_force_units/list');

                $forceUnits = in($product->units, $tryForceUnitsList);
                $forceAltUnits = in($product->alt_units, $tryForceUnitsList);

                $this->altUnitsUsed = (!$forceUnits && $used) || $forceAltUnits;
            } else {
                $this->altUnitsUsed = $used;
            }
        }

        return $this->altUnitsUsed;
    }

    private function renderProductName()
    {
        $product = $this->product;

        $namePriority = $this->data('name/priority');

        if ($namePriority == 'full') {
            $name = $product->name ?: $product->short_name ?: $product->remote_name ?: $product->remote_short_name;
        }

        if ($namePriority == 'short') {
            $name = $product->short_name ?: $product->name ?: $product->remote_short_name ?: $product->remote_name;
        }

        if ($namePriority == 'remote_full') {
            $name = $product->remote_name ?: $product->remote_short_name ?: $product->name ?: $product->short_name;
        }

        if ($namePriority == 'remote_short') {
            $name = $product->remote_short_name ?: $product->remote_name ?: $product->short_name ?: $product->name;
        }

        return $name;
    }
}
