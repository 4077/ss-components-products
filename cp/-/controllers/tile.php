<?php namespace ss\components\products\cp\controllers;

class Tile extends \Controller
{
    private $pivot;

    public function __create()
    {
        if ($this->pivot = $this->unpackModel('pivot')) {
            $this->instance_($this->pivot->id);
        } else {
            $this->lock();
        }
    }

    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $pivot = $this->pivot;
        $pivotXPack = xpack_model($pivot);

        $pivotData = _j($pivot->data);

        $tileData = ap($pivotData, 'tile');

        $quantify = ap($tileData, 'quantify');
        $cartbuttonDisplay = ap($tileData, 'cartbutton/display');

        $priceDisplay = ap($tileData, 'price_display');
        $priceRoundingEnabled = ap($tileData, 'price_rounding/enabled');

        $sellByAltUnits = ap($tileData, 'sell_by_alt_units');
        $otherUnitsDisplay = ap($tileData, 'other_units_display');

        $stockInfo = ap($tileData, 'stock_info');

        $inStockInfoDisplay = ap($stockInfo, 'in_stock/display');
        $notInStockInfoDisplay = ap($stockInfo, 'not_in_stock/display');

        $inUnderOrderInfoDisplay = ap($stockInfo, 'in_under_order/display');
        $notInUnderOrderInfoDisplay = ap($stockInfo, 'not_in_under_order/display');

        $stockRoundingEnabled = ap($stockInfo, 'common/rounding/enabled');

        $v->assign([
                       'NAME_PRIORITY_SWITCHER'                => $this->c('\std\ui\switcher~:view', [
                           'path'    => $this->_p('>xhr:setNamePriority'),
                           'data'    => [
                               'pivot' => $pivotXPack,
                           ],
                           'value'   => ap($tileData, 'name_priority'),
                           'class'   => 'name_priority_switcher',
                           'classes' => [

                           ],
                           'buttons' => [
                               [
                                   'value' => 'full',
                                   'label' => 'полное',
                                   'class' => 'full'
                               ],
                               [
                                   'value' => 'remote_full',
                                   'label' => '(ориг.)',
                                   'class' => 'remote_full'
                               ],
                               [
                                   'value' => 'short',
                                   'label' => 'короткое',
                                   'class' => 'short'
                               ],
                               [
                                   'value' => 'remote_short',
                                   'label' => '(ориг.)',
                                   'class' => 'remote_short'
                               ]
                           ]
                       ]),
                       'CARTBUTTON_TOGGLE'                     => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleCartbutton',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$cartbuttonDisplay
                           ],
                           'class'   => 'cartbutton_toggle ' . ($cartbuttonDisplay ? 'enabled' : ''),
                           'content' => $cartbuttonDisplay ? 'вкл.' : 'выкл.'
                       ]),
                       'CARTBUTTON_LABEL'                      => ap($tileData, 'cartbutton/label'),
                       'QUANTIFY_TOGGLE'                       => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleQuantify',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$quantify
                           ],
                           'class'   => 'quantify_toggle ' . ($quantify ? 'enabled' : ''),
                           'content' => $quantify ? 'вкл.' : 'выкл.'
                       ]),
                       'PRICE_TOGGLE'                          => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:togglePrice',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$priceDisplay
                           ],
                           'class'   => 'price_toggle ' . ($priceDisplay ? 'enabled' : ''),
                           'content' => $priceDisplay ? 'да' : 'нет'
                       ]),
                       'SELL_UNITS_TOGGLE'                     => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleSellByAltUnits',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$sellByAltUnits
                           ],
                           'class'   => 'sell_units_toggle ' . ($sellByAltUnits ? 'enabled' : ''),
                           'content' => $sellByAltUnits ? 'дополнительные' : 'основные'
                       ]),
                       'OTHER_UNITS_DISPLAY_TOGGLE'            => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleOtherUnitsDisplay',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$otherUnitsDisplay
                           ],
                           'class'   => 'other_units_display_toggle ' . ($otherUnitsDisplay ? 'enabled' : ''),
                           'content' => $otherUnitsDisplay ? 'да' : 'нет'
                       ]),
                       //
                       // stock
                       //
                       'IN_STOCK_DISPLAY_TOGGLE'               => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleInStockDisplay',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$inStockInfoDisplay
                           ],
                           'class'   => 'stock_display_toggle ' . ($inStockInfoDisplay ? 'enabled' : ''),
                           'content' => $inStockInfoDisplay ? 'вкл.' : 'выкл.'
                       ]),
                       'NOT_IN_STOCK_DISPLAY_TOGGLE'           => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleNotInStockDisplay',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$notInStockInfoDisplay
                           ],
                           'class'   => 'stock_display_toggle ' . ($notInStockInfoDisplay ? 'enabled' : ''),
                           'content' => $notInStockInfoDisplay ? 'вкл.' : 'выкл.'
                       ]),
                       'IN_STOCK_INFO_MODE_SWITCHER'           => $this->c('\std\ui\switcher~:view', [
                           'path'    => $this->_p('>xhr:setInStockMode'),
                           'data'    => [
                               'pivot' => $pivotXPack,
                           ],
                           'value'   => ap($stockInfo, 'in_stock/mode'),
                           'class'   => 'stock_info_mode_switcher',
                           'classes' => [

                           ],
                           'buttons' => [
                               [
                                   'value' => 'value',
                                   'label' => 'значение',
                                   'class' => 'value'
                               ],
                               [
                                   'value' => 'label',
                                   'label' => 'надпись',
                                   'class' => 'label'
                               ]
                           ]
                       ]),
                       'NOT_IN_STOCK_INFO_MODE_SWITCHER'       => $this->c('\std\ui\switcher~:view', [
                           'path'    => $this->_p('>xhr:setNotInStockMode'),
                           'data'    => [
                               'pivot' => $pivotXPack,
                           ],
                           'value'   => ap($stockInfo, 'not_in_stock/mode'),
                           'class'   => 'stock_info_mode_switcher',
                           'classes' => [

                           ],
                           'buttons' => [
                               [
                                   'value' => 'value',
                                   'label' => 'значение',
                                   'class' => 'value'
                               ],
                               [
                                   'value' => 'label',
                                   'label' => 'надпись',
                                   'class' => 'label'
                               ]
                           ]
                       ]),
                       'IN_STOCK_INFO_LABEL'                   => ap($stockInfo, 'in_stock/label'),
                       'NOT_IN_STOCK_INFO_LABEL'               => ap($stockInfo, 'not_in_stock/label'),
                       //
                       // under_order
                       //
                       'IN_UNDER_ORDER_DISPLAY_TOGGLE'         => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleInUnderOrderDisplay',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$inUnderOrderInfoDisplay
                           ],
                           'class'   => 'under_order_display_toggle ' . ($inUnderOrderInfoDisplay ? 'enabled' : ''),
                           'content' => $inUnderOrderInfoDisplay ? 'вкл.' : 'выкл.'
                       ]),
                       'NOT_IN_UNDER_ORDER_DISPLAY_TOGGLE'     => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleNotInUnderOrderDisplay',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$notInUnderOrderInfoDisplay
                           ],
                           'class'   => 'under_order_display_toggle ' . ($notInUnderOrderInfoDisplay ? 'enabled' : ''),
                           'content' => $notInUnderOrderInfoDisplay ? 'вкл.' : 'выкл.'
                       ]),
                       'IN_UNDER_ORDER_INFO_MODE_SWITCHER'     => $this->c('\std\ui\switcher~:view', [
                           'path'    => $this->_p('>xhr:setInUnderOrderMode'),
                           'data'    => [
                               'pivot' => $pivotXPack,
                           ],
                           'value'   => ap($stockInfo, 'in_under_order/mode'),
                           'class'   => 'under_order_info_mode_switcher',
                           'classes' => [

                           ],
                           'buttons' => [
                               [
                                   'value' => 'value',
                                   'label' => 'значение',
                                   'class' => 'value'
                               ],
                               [
                                   'value' => 'label',
                                   'label' => 'надпись',
                                   'class' => 'label'
                               ]
                           ]
                       ]),
                       'NOT_IN_UNDER_ORDER_INFO_MODE_SWITCHER' => $this->c('\std\ui\switcher~:view', [
                           'path'    => $this->_p('>xhr:setNotInUnderOrderMode'),
                           'data'    => [
                               'pivot' => $pivotXPack,
                           ],
                           'value'   => ap($stockInfo, 'not_in_under_order/mode'),
                           'class'   => 'under_order_info_mode_switcher',
                           'classes' => [

                           ],
                           'buttons' => [
                               [
                                   'value' => 'value',
                                   'label' => 'значение',
                                   'class' => 'value'
                               ],
                               [
                                   'value' => 'label',
                                   'label' => 'надпись',
                                   'class' => 'label'
                               ]
                           ]
                       ]),
                       'IN_UNDER_ORDER_INFO_LABEL'             => ap($stockInfo, 'in_under_order/label'),
                       'NOT_IN_UNDER_ORDER_INFO_LABEL'         => ap($stockInfo, 'not_in_under_order/label'),
                       //
                       //
                       //
                       'STOCK_ROUNDING_TOGGLE'                 => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleStockRounding',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$stockRoundingEnabled
                           ],
                           'class'   => 'stock_rounding_toggle ' . ($stockRoundingEnabled ? 'enabled' : ''),
                           'content' => $stockRoundingEnabled ? 'вкл.' : 'выкл.'
                       ]),
                       'STOCK_ROUNDING_MODE_SWITCHER'          => $this->c('\std\ui\switcher~:view', [
                           'path'    => $this->_p('>xhr:setStockRoundingMode'),
                           'data'    => [
                               'pivot' => $pivotXPack,
                           ],
                           'value'   => ap($stockInfo, 'common/rounding/mode'),
                           'class'   => 'stock_rounding_mode_switcher',
                           'classes' => [

                           ],
                           'buttons' => [
                               [
                                   'value' => 'floor',
                                   'label' => '<',
                                   'class' => 'value',
                                   'title' => 'К меньшему'
                               ],
                               [
                                   'value' => 'round',
                                   'label' => '|',
                                   'class' => 'label',
                                   'title' => 'К ближайшему'
                               ],
                               [
                                   'value' => 'ceil',
                                   'label' => '>',
                                   'class' => 'label',
                                   'title' => 'К большему'
                               ]
                           ]
                       ]),
                       'TEMPLATE_SELECTOR'                     => $this->templateSelectorView(),
                       'IMAGE_WIDTH'                           => ap($tileData, 'image/width'),
                       'IMAGE_HEIGHT'                          => ap($tileData, 'image/height'),
                       'IMAGE_RESIZE_MODE'                     => ap($tileData, 'image/height'),
                       'IMAGE_RESIZE_MODE_SWITCHER'            => $this->c('\std\ui\switcher~:view', [
                           'path'    => $this->_p('>xhr:setImageResizeMode'),
                           'data'    => [
                               'pivot' => $pivotXPack,
                           ],
                           'value'   => ap($tileData, 'image/resize_mode'),
                           'class'   => 'image_resize_mode_switcher',
                           'classes' => [

                           ],
                           'buttons' => [
                               [
                                   'value' => 'fill',
                                   'label' => 'заполнить',
                                   'class' => 'fill'
                               ],
                               [
                                   'value' => 'fit',
                                   'label' => 'вписать',
                                   'class' => 'fit'
                               ]
                           ]
                       ]),
                   ]);

        if ($cartbuttonDisplay) {
            $v->assign('cartbutton_enabled');
        }

        //

        if ($priceDisplay) {
            $v->assign('price_display');

            $priceRoundingMode = ap($tileData, 'price_rounding/mode');
            $zeropriceLabelEnabled = ap($tileData, 'zeroprice_label/enabled');
            $zeropriceLabelValue = ap($tileData, 'zeroprice_label/value');

            $v->assign([
                           'PRICE_ROUNDING_TOGGLE'        => $this->c('\std\ui button:view', [
                               'path'    => '>xhr:togglePriceRounding',
                               'data'    => [
                                   'pivot' => $pivotXPack,
                                   'value' => !$priceRoundingEnabled
                               ],
                               'class'   => 'price_rounding_toggle ' . ($priceRoundingEnabled ? 'enabled' : ''),
                               'content' => $priceRoundingEnabled ? 'вкл.' : 'выкл.'
                           ]),
                           'PRICE_ROUNDING_MODE_SWITCHER' => $this->c('\std\ui\switcher~:view', [
                               'path'    => $this->_p('>xhr:setPriceRoundingMode'),
                               'data'    => [
                                   'pivot' => $pivotXPack,
                               ],
                               'value'   => $priceRoundingMode,
                               'class'   => 'price_rounding_mode_switcher',
                               'classes' => [

                               ],
                               'buttons' => [
                                   [
                                       'value' => 'floor',
                                       'label' => '<',
                                       'class' => 'value',
                                       'title' => 'К меньшему'
                                   ],
                                   [
                                       'value' => 'round',
                                       'label' => '|',
                                       'class' => 'label',
                                       'title' => 'К ближайшему'
                                   ],
                                   [
                                       'value' => 'ceil',
                                       'label' => '>',
                                       'class' => 'label',
                                       'title' => 'К большему'
                                   ]
                               ]
                           ]),
                           'ZEROPRICE_LABEL_TOGGLE'       => $this->c('\std\ui button:view', [
                               'path'    => '>xhr:toggleZeropriceLabel',
                               'data'    => [
                                   'pivot' => $pivotXPack,
                                   'value' => !$zeropriceLabelEnabled
                               ],
                               'class'   => 'zeroprice_label_toggle ' . ($zeropriceLabelEnabled ? 'enabled' : ''),
                               'content' => $zeropriceLabelEnabled ? 'вкл.' : 'выкл.'
                           ]),
                           'ZEROPRICE_LABEL_VALUE'        => $zeropriceLabelValue
                       ]);

            if ($priceRoundingEnabled) {
                $v->assign('price_rounding_enabled');
            }

            if ($zeropriceLabelEnabled) {
                $v->assign('zeroprice_label');
            }
        }

        //

        $stockInfoHasValueMode = false;

        if ($inStockInfoDisplay) {
            $v->assign('in_stock_info_enabled');

            if (ap($stockInfo, 'in_stock/mode') == 'label') {
                $v->assign('in_stock_label_control');
            } else {
                $stockInfoHasValueMode = true;
            }
        }

        if ($notInStockInfoDisplay) {
            $v->assign('not_in_stock_info_enabled');

            if (ap($stockInfo, 'not_in_stock/mode') == 'label') {
                $v->assign('not_in_stock_label_control');
            } else {
                $stockInfoHasValueMode = true;
            }
        }

        if ($stockInfoHasValueMode) {
            $v->assign('stock_info_value_label', [
                'CONTENT' => ap($stockInfo, 'common/stock_value_label'),
            ]);
        }

        //

        $underOrderInfoHasValueMode = false;

        if ($inUnderOrderInfoDisplay) {
            $v->assign('in_under_order_info_enabled');

            if (ap($stockInfo, 'in_under_order/mode') == 'label') {
                $v->assign('in_under_order_label_control');
            } else {
                $underOrderInfoHasValueMode = true;
            }
        }

        if ($notInUnderOrderInfoDisplay) {
            $v->assign('not_in_under_order_info_enabled');

            if (ap($stockInfo, 'not_in_under_order/mode') == 'label') {
                $v->assign('not_in_under_order_label_control');
            } else {
                $underOrderInfoHasValueMode = true;
            }
        }

        if ($underOrderInfoHasValueMode) {
            $v->assign('under_order_info_value_label', [
                'CONTENT' => ap($stockInfo, 'common/under_order_value_label'),
            ]);
        }

        //

        if ($stockRoundingEnabled) {
            $v->assign('stock_rounding_enabled');
        }

        $this->css();

        $this->widget(':|', [
            '.payload' => [
                'pivot' => $pivotXPack
            ],
            '.r'       => [
                'updateImageDimension'           => $this->_p('>xhr:updateImageDimension'),
                'updateCartbuttonLabel'          => $this->_p('>xhr:updateCartbuttonLabel'),
                'updateNotInStockInfoLabel'      => $this->_p('>xhr:updateNotInStockInfoLabel'),
                'updateInStockInfoLabel'         => $this->_p('>xhr:updateInStockInfoLabel'),
                'updateStockValueLabel'          => $this->_p('>xhr:updateStockValueLabel'),
                'updateNotInUnderOrderInfoLabel' => $this->_p('>xhr:updateNotInUnderOrderInfoLabel'),
                'updateInUnderOrderInfoLabel'    => $this->_p('>xhr:updateInUnderOrderInfoLabel'),
                'updateUnderOrderValueLabel'     => $this->_p('>xhr:updateUnderOrderValueLabel'),
                'updateZeropriceLabel'           => $this->_p('>xhr:updateZeropriceLabel'),
                'reload'                         => $this->_p('>xhr:reload')
            ]
        ]);

        return $v;
    }

    public function templateSelectorView()
    {
        $pivot = $this->pivot;

        $templates = dataSets()->get('ss/components/products:tiles_templates');

        $items = [];
        foreach ($templates as $name => $data) {
            $items[$name] = $data['label'];
        }

        return $this->c('\std\ui select:view', [
            'path'     => '>xhr:selectTemplate',
            'data'     => [
                'pivot' => xpack_model($pivot)
            ],
            'items'    => $items,
            'selected' => ss()->cats->apComponentPivotData($pivot, 'tile/template')
        ]);
    }
}
