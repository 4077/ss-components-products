<?php namespace ss\components\products\cp\controllers;

class Grid extends \Controller
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

        $gridData = ap($pivotData, 'grid');

        $nameDisplay = ap($gridData, 'name_display');
        $descriptionDisplay = ap($gridData, 'description_display');
        $notInStockProductsDisplay = ap($gridData, 'not_in_stock_products_display');
        $notInUnderOrderProductsDisplay = ap($gridData, 'not_in_under_order_products_display');
        $stockMinimumEnabled = ap($gridData, 'stock_minimum/enabled');
        $underOrderMinimumEnabled = ap($gridData, 'under_order_minimum/enabled');
        $zeropriceProductsDisplay = ap($gridData, 'zeroprice_products_display');

        $v->assign([
                       'DISPLAY_NAME_TOGGLE'                => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleName',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$nameDisplay
                           ],
                           'class'   => 'toggle ' . ($nameDisplay ? 'enabled' : ''),
                           'content' => $nameDisplay ? 'да' : 'нет'
                       ]),
                       'DISPLAY_DESCRIPTION_TOGGLE'         => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleDescription',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$descriptionDisplay
                           ],
                           'class'   => 'toggle ' . ($descriptionDisplay ? 'enabled' : ''),
                           'content' => $descriptionDisplay ? 'да' : 'нет'
                       ]),
                       'NOT_IN_STOCK_PRODUCTS_TOGGLE'       => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleNotInStockProducts',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$notInStockProductsDisplay
                           ],
                           'class'   => 'toggle ' . ($notInStockProductsDisplay ? 'enabled' : ''),
                           'content' => $notInStockProductsDisplay ? 'да' : 'нет'
                       ]),
                       'STOCK_MINIMUM_TOGGLE'               => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleStockMinimum',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$stockMinimumEnabled
                           ],
                           'class'   => 'toggle ' . ($stockMinimumEnabled ? 'enabled' : ''),
                           'content' => $stockMinimumEnabled ? 'вкл.' : 'выкл.'
                       ]),
                       'NOT_IN_UNDER_ORDER_PRODUCTS_TOGGLE' => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleNotInUnderOrderProducts',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$notInUnderOrderProductsDisplay
                           ],
                           'class'   => 'toggle ' . ($notInUnderOrderProductsDisplay ? 'enabled' : ''),
                           'content' => $notInUnderOrderProductsDisplay ? 'да' : 'нет'
                       ]),
                       'UNDER_ORDER_MINIMUM_TOGGLE'         => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleUnderOrderMinimum',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$underOrderMinimumEnabled
                           ],
                           'class'   => 'toggle ' . ($underOrderMinimumEnabled ? 'enabled' : ''),
                           'content' => $underOrderMinimumEnabled ? 'вкл.' : 'выкл.'
                       ]),
                       'STOCK_MINIMUM_VALUE'                => ap($gridData, 'stock_minimum/value'),
                       'UNDER_ORDER_MINIMUM_VALUE'          => ap($gridData, 'under_order_minimum/value'),
                       'ZEROPRICE_PRODUCTS_DISPLAY_TOGGLE'  => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleZeropriceProductsDisplay',
                           'data'    => [
                               'pivot' => $pivotXPack,
                               'value' => !$zeropriceProductsDisplay
                           ],
                           'class'   => 'toggle ' . ($zeropriceProductsDisplay ? 'enabled' : ''),
                           'content' => $zeropriceProductsDisplay ? 'да' : 'нет'
                       ]),
                       'TILE'                               => $this->c_('@tile:view')
                   ]);

        if ($stockMinimumEnabled) {
            $v->assign('stock_minimum_enabled');
        }

        if ($underOrderMinimumEnabled) {
            $v->assign('under_order_minimum_enabled');
        }

        $this->css();

        $this->widget(':|', [
            '.e' => [
                'ss/container/' . $pivot->cat_id . '/update_pivot' => 'mr.reload'
            ],
            '.r' => [
                'updateStockMinimumValue'      => $this->_abs('>xhr:updateStockMinimumValue', ['pivot' => $pivotXPack]),
                'updateUnderOrderMinimumValue' => $this->_abs('>xhr:updateUnderOrderMinimumValue', ['pivot' => $pivotXPack]),
                'reload'                       => $this->_abs('>xhr:reload', ['pivot' => $pivotXPack])
            ]
        ]);

        return $v;
    }
}
