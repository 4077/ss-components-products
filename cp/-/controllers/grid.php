<?php namespace ss\components\products\cp\controllers;

class Grid extends \Controller
{
    private $pivot;

    private $pivotXPack;

    private $pivotData;

    public function __create()
    {
        if ($this->pivot = $this->unpackModel('pivot')) {
            $this->instance_($this->pivot->id);

            $this->pivotXPack = xpack_model($this->pivot);
            $this->pivotData = _j($this->pivot->data);
        } else {
            $this->lock();
        }
    }

    public function gridData($path = false)
    {
        return ap($this->pivotData, 'grid/' . $path);
    }

    public function tileData($path = false)
    {
        return ap($this->pivotData, 'tile/' . $path);
    }

    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $this->assignNameDescCp($v);
        $this->assignStockMinimumCp($v);
        $this->assignStockFilterCp($v);
        $this->assignNotzeropriceFilterCp($v);

        $v->assign([
                       'TILE' => $this->c_('@tile:view')
                   ]);

        $this->css();

        $this->widget(':|', [
            '.payload' => [
                'pivot' => $this->pivotXPack
            ],
            '.r'       => [
                'updateStringValue' => $this->_p('>xhr:updateStringValue'),
                'reload'            => $this->_abs('>xhr:reload', ['pivot' => $this->pivotXPack])
            ]
        ]);

        return $v;
    }

    private function assignNameDescCp(\ewma\Views\View $v)
    {
        $v->assign([
                       'DISPLAY_NAME_TOGGLE'        => $this->toggleButton('name_display'),
                       'DISPLAY_DESCRIPTION_TOGGLE' => $this->toggleButton('description_display')
                   ]);
    }

    private function assignStockMinimumCp(\ewma\Views\View $v)
    {
        $v->assign([
                       'STOCK_MINIMUM_TOGGLE' => $this->toggleButton('stock_minimum/enabled', ['вкл.', 'выкл.']),
                   ]);

        if ($this->gridData('stock_minimum/enabled')) {
            $v->assign('stock_minimum', [
                'VALUE_INPUT' => $this->stringValueInput('stock_minimum/value')
            ]);
        }
    }

    private function assignStockFilterCp(\ewma\Views\View $v)
    {
        $enabled = $this->gridData('filters/stock/enabled');

        $v->assign([
                       'STOCK_FILTER_TOGGLE'        => $this->toggleButton('filters/stock/enabled', ['вкл.', 'выкл.']),
                       'STOCK_FILTER_MODE_SWITCHER' => $this->gridData('filters/stock/enabled')
                           ? $this->switcher('filters/stock/mode', [
                               'all'      => [
                                   'label' => 'по всем группам',
                                   'title' => 'Будут скрыты товары, которых нет в наличии ни в одной из групп'
                               ],
                               'selected' => [
                                   'label' => 'по выбранной группе',
                                   'title' => 'Будут скрыты товары, которых нет в наличии в выбранной группе. Если группа не выбрана, фильтрация будет производиться по всем группам'
                               ]
                           ])
                           : ''
                   ]);

        if ($enabled) {

            // assigned groups

//            $assignedStockWarehousesGroups = $this->tileData('stock/groups');
//            $assignedStockWarehousesGroupsIds = array_keys($assignedStockWarehousesGroups);
//
//            $groups = table_rows_by_id(\ss\multisource\models\WarehouseGroup::whereIn('id', $assignedStockWarehousesGroupsIds)->orderBy('position')->get());
//
//            foreach ($assignedStockWarehousesGroupsIds as $groupId) {
//                if (ap($assignedStockWarehousesGroups[$groupId], 'enabled')) {
//                    $group = $groups[$groupId];
//
//                    $groupFilteringEnabled = $this->gridData('filters/stock/groups/' . $groupId);
//
//                    $v->assign('stock_filter/warehouse_group', [
//                        'NAME'   => $group->name,
//                        'TOGGLE' => $this->c('\std\ui button:view', [
//                            'path'    => '>xhr:toggleStockFilterGroup',
//                            'data'    => [
//                                'pivot' => $this->pivotXPack,
//                                'group' => xpack_model($group)
//                            ],
//                            'class'   => 'toggle ' . ($groupFilteringEnabled ? 'enabled' : ''),
//                            'content' => $groupFilteringEnabled ? 'вкл.' : 'выкл.'
//                        ])
//                    ]);
//                }
//            }
        }
    }

    private function assignNotzeropriceFilterCp(\ewma\Views\View $v)
    {
        $v->assign([
                       'NOTZEROPRICE_FILTER_TOGGLE' => $this->toggleButton('filters/not_zeroprice/enabled', ['вкл.', 'выкл.'])
                   ]);
    }

    private function stringValueInput($path, $ra = [])
    {
        $attrs = [
            'path'  => j64_($path),
            'value' => $this->gridData($path)
        ];

        ra($attrs, $ra);

        return $this->c('\std\ui tag:view:input', [
            'attrs' => $attrs
        ]);
    }

    private function toggleButton($path, $labels = ['да', 'нет'], $class = 'toggle')
    {
        $value = $this->gridData($path);

        $buttonData = [
            'path'    => '>xhr:toggle',
            'data'    => [
                'pivot' => $this->pivotXPack,
                'path'  => j64_($path)
            ],
            'class'   => $class . ' ' . ($value ? 'enabled' : ''),
            'content' => $value ? $labels[0] : $labels[1]
        ];

        return $this->c('\std\ui button:view', $buttonData);
    }

    private function switcher($path, $buttons)
    {
        $selectedValue = $this->gridData($path);

        $switcherButtons = [];

        foreach ($buttons as $value => $button) {
            $switcherButtons[] = [
                'label' => $button['label'] ?? '',
                'value' => j64_($value),
                'class' => $button['class'] ?? '',
                'title' => $button['title'] ?? ''
            ];
        }

        return $this->c('\std\ui\switcher~:view', [
            'path'    => $this->_p('>xhr:switch'),
            'data'    => [
                'pivot' => $this->pivotXPack,
                'path'  => j64_($path)
            ],
            'value'   => j64_($selectedValue),
            'class'   => 'switcher',
            'classes' => [

            ],
            'buttons' => $switcherButtons
        ]);
    }
}
