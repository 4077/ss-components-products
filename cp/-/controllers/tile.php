<?php namespace ss\components\products\cp\controllers;

class Tile extends \Controller
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

        $this->assignNameCp($v);
        $this->assignLayoutCp($v);
        $this->assignStockInfoCp($v);
        $this->assignPriceCp($v);
        $this->assignCartbuttonCp($v);
        $this->assignUnitsCp($v);

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

    private function assignLayoutCp(\ewma\Views\View $v)
    {
        $v->assign([
                       'TEMPLATE_SELECTOR'          => $this->templateSelectorView(),
                       'IMAGE_WIDTH_INPUT'          => $this->stringValueInput('image/width', ['class' => 'image_dimension']),
                       'IMAGE_HEIGHT_INPUT'         => $this->stringValueInput('image/height', ['class' => 'image_dimension']),
                       'IMAGE_RESIZE_MODE'          => $this->tileData('image/height'),
                       'IMAGE_RESIZE_MODE_SWITCHER' => $this->c('\std\ui\switcher~:view', [
                           'path'    => $this->_p('>xhr:setImageResizeMode'),
                           'data'    => [
                               'pivot' => $this->pivotXPack,
                           ],
                           'value'   => $this->tileData('image/resize_mode'),
                           'class'   => 'switcher',
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
                       ])
                   ]);
    }

    private function templateSelectorView()
    {
        $templates = dataSets()->get('ss/components/products:tiles_templates');

        $items = [];
        foreach ($templates as $name => $data) {
            $items[$name] = $data['label'];
        }

        return $this->c('\std\ui select:view', [
            'path'     => '>xhr:selectTemplate',
            'data'     => [
                'pivot' => $this->pivotXPack
            ],
            'items'    => $items,
            'selected' => $this->tileData('template')
        ]);
    }

    private function stringValueInput($path, $ra = [])
    {
        $attrs = [
            'path'  => j64_($path),
            'value' => $this->tileData($path)
        ];

        if ($ra) {
            ra($attrs, $ra);
        }

        return $this->c('\std\ui tag:view:input', [
            'attrs' => $attrs
        ]);
    }

    private function toggleButton($path, $labels = ['да', 'нет'], $class = 'toggle')
    {
        $value = $this->tileData($path);

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
        $selectedValue = $this->tileData($path);

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

    private function assignNameCp(\ewma\Views\View $v)
    {
        $v->assign([
                       'NAME_PRIORITY_SWITCHER' => $this->switcher('name/priority', [
                           'full'         => [
                               'label' => 'полное'
                           ],
                           'remote_full'  => [
                               'label' => '(ориг.)'
                           ],
                           'short'        => [
                               'label' => 'короткое'
                           ],
                           'remote_short' => [
                               'label' => '(ориг.)'
                           ],
                       ])
                   ]);
    }

    private function assignCartbuttonCp(\ewma\Views\View $v)
    {
        $cartbuttonDisplay = $this->tileData('cartbutton/display');

        $v->assign([
                       'CARTBUTTON_TOGGLE' => $this->toggleButton('cartbutton/display', ['вкл.', 'выкл.'])
                   ]);

        if ($cartbuttonDisplay) {
            $v->assign('cartbutton', [
                'LABEL_INPUT'     => $this->stringValueInput('cartbutton/label'),
                'QUANTIFY_TOGGLE' => $this->toggleButton('cartbutton/quantify', ['вкл.', 'выкл.'])
            ]);
        }
    }

    private function assignUnitsCp(\ewma\Views\View $v)
    {
        $sellByAltUnits = $this->tileData('units/sell_by_alt_units');
        $tryForceUnitsEnabled = $this->tileData('units/try_force_units/enabled');
//        $otherUnitsDisplay = $this->tileData('units/other_units_display');

        $v->assign([
                       'SELL_UNITS_TOGGLE'      => $this->toggleButton('units/sell_by_alt_units', ['дополнительные', 'основные'], 'sell_units_toggle'),
                       //                       'OTHER_UNITS_DISPLAY_TOGGLE' => $this->c('\std\ui button:view', [
                       //                           'path'    => '>xhr:toggleOtherUnitsDisplay',
                       //                           'data'    => [
                       //                               'pivot' => $this->pivotXPack
                       //                           ],
                       //                           'class'   => 'toggle ' . ($otherUnitsDisplay ? 'enabled' : ''),
                       //                           'content' => $otherUnitsDisplay ? 'да' : 'нет'
                       //                       ]),
                       'TRY_FORCE_UNITS_TOGGLE' => $this->toggleButton('units/try_force_units/enabled')
                   ]);

        if ($tryForceUnitsEnabled) {
            $v->assign('try_force_units', [
                'LIST_INPUT' => $this->stringValueInput('units/try_force_units/list')
            ]);
        }
    }

    private function assignPriceCp(\ewma\Views\View $v)
    {
        $priceDisplay = $this->tileData('price/display');

        $v->assign([
                       'PRICE_TOGGLE' => $this->toggleButton('price/display')
                   ]);

        if ($priceDisplay) {
            $roundingEnabled = $this->tileData('price/rounding/enabled');
            $zeropriceLabelEnabled = $this->tileData('price/zeroprice_label/enabled');

            $v->assign('price', [
                'ROUNDING_TOGGLE'        => $this->toggleButton('price/rounding/enabled', ['вкл.', 'выкл.']),
                'ROUNDING_MODE_SWITCHER' => $roundingEnabled
                    ? $this->switcher('price/rounding/mode', [
                        'floor' => [
                            'label' => '<',
                            'title' => 'К меньшему'
                        ],
                        'round' => [
                            'label' => '|',
                            'title' => 'К ближайшему'
                        ],
                        'ceil'  => [
                            'label' => '>',
                            'title' => 'К большему'
                        ]
                    ])
                    : '',
                'ZEROPRICE_LABEL_TOGGLE' => $this->toggleButton('price/zeroprice_label/enabled', ['вкл.', 'выкл.']),
                'DISCOUNT_TOGGLE'        => $this->toggleButton('price/discount/display'),
            ]);

            if ($zeropriceLabelEnabled) {
                $v->assign('price/zeroprice_label', [
                    'LABEL_INPUT' => $this->stringValueInput('price/zeroprice_label/value')
                ]);
            }
        }
    }

    private function assignStockInfoCp(\ewma\Views\View $v)
    {
        $stockDisplay = $this->tileData('stock/display');

        $v->assign([
                       'STOCK_TOGGLE' => $this->toggleButton('stock/display', ['вкл.', 'выкл.'])
                   ]);

        if ($stockDisplay) {
            $stockRoundingEnabled = $this->tileData('stock/rounding/enabled');

            $v->assign('stock', [
                'ROUNDING_TOGGLE'        => $this->toggleButton('stock/rounding/enabled', ['вкл.', 'выкл.']),
                'ROUNDING_MODE_SWITCHER' => $stockRoundingEnabled
                    ? $this->switcher('stock/rounding/mode', [
                        'floor' => [
                            'label' => '<',
                            'title' => 'К меньшему'
                        ],
                        'round' => [
                            'label' => '|',
                            'title' => 'К ближайшему'
                        ],
                        'ceil'  => [
                            'label' => '>',
                            'title' => 'К большему'
                        ]
                    ])
                    : ''
            ]);

            // selected group

            $v->append('stock', [
                'SELECTED_GROUP_IN_STOCK_DISPLAY_TOGGLE'     => $this->toggleButton('stock/selected_group/in_stock/display', ['вкл.', 'выкл.']),
                'SELECTED_GROUP_IN_STOCK_MODE_SWITCHER'      => $this->tileData('stock/selected_group/in_stock/display')
                    ? $this->switcher('stock/selected_group/in_stock/mode', [
                        'value' => [
                            'label' => 'значение'
                        ],
                        'label' => [
                            'label' => 'надпись'
                        ]
                    ])
                    : '',
                'SELECTED_GROUP_NOT_IN_STOCK_DISPLAY_TOGGLE' => $this->toggleButton('stock/selected_group/not_in_stock/display', ['вкл.', 'выкл.']),
                'SELECTED_GROUP_NOT_IN_STOCK_MODE_SWITCHER'  => $this->tileData('stock/selected_group/not_in_stock/display')
                    ? $this->switcher('stock/selected_group/not_in_stock/mode', [
                        'value' => [
                            'label' => 'значение'
                        ],
                        'label' => [
                            'label' => 'надпись'
                        ]
                    ])
                    : '',
            ]);

            $selectedGroupInStockDisplay = $this->tileData('stock/selected_group/in_stock/display');
            $selectedGroupInStockMode = $this->tileData('stock/selected_group/in_stock/mode');

            if ($selectedGroupInStockDisplay && $selectedGroupInStockMode == 'label') {
                $v->assign('stock/selected_group_in_stock_label', [
                    'LABEL_INPUT' => $this->stringValueInput('stock/selected_group/in_stock/label')
                ]);
            }

            $selectedGroupNotInStockDisplay = $this->tileData('stock/selected_group/not_in_stock/display');
            $selectedGroupNotInStockMode = $this->tileData('stock/selected_group/not_in_stock/mode');

            if ($selectedGroupNotInStockDisplay && $selectedGroupNotInStockMode == 'label') {
                $v->assign('stock/selected_group_not_in_stock_label', [
                    'LABEL_INPUT' => $this->stringValueInput('stock/selected_group/not_in_stock/label')
                ]);
            }

            if (
                ($selectedGroupInStockDisplay && $selectedGroupInStockMode == 'value') ||
                ($selectedGroupNotInStockDisplay && $selectedGroupNotInStockMode == 'value')
            ) {
                $v->assign('stock/selected_group_value_label', [
                    'LABEL_INPUT'                   => $this->stringValueInput('stock/selected_group/value_label/label'),
                    'GROUP_NAME_IF_POSSIBLE_TOGGLE' => $this->toggleButton('stock/selected_group/value_label/group_name_if_possible')
                ]);
            }

            // other groups

            $v->append('stock', [
                'OTHER_GROUPS_IN_STOCK_DISPLAY_TOGGLE'     => $this->toggleButton('stock/other_groups/in_stock/display', ['вкл.', 'выкл.']),
                'OTHER_GROUPS_IN_STOCK_MODE_SWITCHER'      => $this->tileData('stock/other_groups/in_stock/display')
                    ? $this->switcher('stock/other_groups/in_stock/mode', [
                        'value' => [
                            'label' => 'значение'
                        ],
                        'label' => [
                            'label' => 'надпись'
                        ]
                    ])
                    : '',
                'OTHER_GROUPS_NOT_IN_STOCK_DISPLAY_TOGGLE' => $this->toggleButton('stock/other_groups/not_in_stock/display', ['вкл.', 'выкл.']),
                'OTHER_GROUPS_NOT_IN_STOCK_MODE_SWITCHER'  => $this->tileData('stock/other_groups/not_in_stock/display')
                    ? $this->switcher('stock/other_groups/not_in_stock/mode', [
                        'value' => [
                            'label' => 'значение'
                        ],
                        'label' => [
                            'label' => 'надпись'
                        ]
                    ])
                    : '',
            ]);

            $otherGroupsInStockDisplay = $this->tileData('stock/other_groups/in_stock/display');
            $otherGroupsInStockMode = $this->tileData('stock/other_groups/in_stock/mode');

            if ($otherGroupsInStockDisplay && $otherGroupsInStockMode == 'label') {
                $v->assign('stock/other_groups_in_stock_label', [
                    'LABEL_INPUT' => $this->stringValueInput('stock/other_groups/in_stock/label')
                ]);
            }

            $otherGroupsNotInStockDisplay = $this->tileData('stock/other_groups/not_in_stock/display');
            $otherGroupsNotInStockMode = $this->tileData('stock/other_groups/not_in_stock/mode');

            if ($otherGroupsNotInStockDisplay && $otherGroupsNotInStockMode == 'label') {
                $v->assign('stock/other_groups_not_in_stock_label', [
                    'LABEL_INPUT' => $this->stringValueInput('stock/other_groups/not_in_stock/label')
                ]);
            }

            if (
                ($otherGroupsInStockDisplay && $otherGroupsInStockMode == 'value') ||
                ($otherGroupsNotInStockDisplay && $otherGroupsNotInStockMode == 'value')
            ) {
                $v->assign('stock/other_groups_value_label', [
                    'LABEL_INPUT'                   => $this->stringValueInput('stock/other_groups/value_label/label'),
                    'GROUP_NAME_IF_POSSIBLE_TOGGLE' => $this->toggleButton('stock/other_groups/value_label/group_name_if_possible')
                ]);
            }

            //
            // assigned groups
            //

//            $groups = \ss\multisource\models\WarehouseGroup::orderBy('position')->get();
//
//            $assignedStockWarehousesGroups = $this->tileData('stock/groups');
//            $assignedStockWarehousesGroupsIds = array_keys($assignedStockWarehousesGroups);
//
//            $items = [0 => ''];
//
//            foreach ($groups as $group) {
//                $groupInfo = ap($assignedStockWarehousesGroups, $group->id);
//
//                if (!in_array($group->id, $assignedStockWarehousesGroupsIds) || !$groupInfo['enabled']) {
//                    $items[$group->id] = $group->name;
//                }
//            }
//
//            $v->assign([
//                           'STOCK_WAREHOUSE_GROUP_SELECTOR' => $this->c('\std\ui select:view', [
//                               'path'  => '>xhr:assignStockInfoGroup',
//                               'data'  => [
//                                   'pivot' => $this->pivotXPack
//                               ],
//                               'items' => $items
//                           ])
//                       ]);
//
//            // groups
//
//            foreach ($groups as $group) {
//                $groupInfo = ap($assignedStockWarehousesGroups, $group->id);
//
//                if (!in_array($group->id, $assignedStockWarehousesGroupsIds) || !ap($groupInfo, 'enabled')) {
//                    continue;
//                }
//
//                $groupXPack = xpack_model($group);
//
//                $inStockInfo = ap($groupInfo, 'in_stock');
//                $notInStockInfo = ap($groupInfo, 'not_in_stock');
//
//                $inStockInfoDisplay = ap($inStockInfo, 'display');
//                $notInStockInfoDisplay = ap($notInStockInfo, 'display');
//
//                $v->assign('stock/warehouse_group', [
//                    'GROUP_NAME'                  => $group->name,
//                    'DISABLE_BUTTON'              => $this->c('\std\ui button:view', [
//                        'path'  => '>xhr:disableWarehousesGroup',
//                        'data'  => [
//                            'pivot' => $this->pivotXPack,
//                            'group' => $groupXPack
//                        ],
//                        'class' => 'disable_button',
//                        'icon'  => 'fa fa-close'
//                    ]),
//                    'IN_STOCK_DISPLAY_TOGGLE'     => $this->c('\std\ui button:view', [
//                        'path'    => '>xhr:toggleInStockDisplay',
//                        'data'    => [
//                            'pivot' => $this->pivotXPack,
//                            'group' => $groupXPack
//                        ],
//                        'class'   => 'toggle ' . ($inStockInfoDisplay ? 'enabled' : ''),
//                        'content' => $inStockInfoDisplay ? 'вкл.' : 'выкл.'
//                    ]),
//                    'NOT_IN_STOCK_DISPLAY_TOGGLE' => $this->c('\std\ui button:view', [
//                        'path'    => '>xhr:toggleNotInStockDisplay',
//                        'data'    => [
//                            'pivot' => $this->pivotXPack,
//                            'group' => $groupXPack
//                        ],
//                        'class'   => 'toggle ' . ($notInStockInfoDisplay ? 'enabled' : ''),
//                        'content' => $notInStockInfoDisplay ? 'вкл.' : 'выкл.'
//                    ])
//                ]);
//
//                $hasValueMode = false;
//
//                if ($inStockInfoDisplay) {
//                    $v->append('stock/warehouse_group', [
//                        'IN_STOCK_MODE_SWITCHER' => $this->c('\std\ui\switcher~:view', [
//                            'path'    => $this->_p('>xhr:setInStockMode'),
//                            'data'    => [
//                                'pivot' => $this->pivotXPack,
//                                'group' => $groupXPack,
//                            ],
//                            'value'   => ap($inStockInfo, 'mode'),
//                            'class'   => 'switcher',
//                            'classes' => [
//
//                            ],
//                            'buttons' => [
//                                [
//                                    'value' => 'value',
//                                    'label' => 'значение',
//                                    'class' => 'value'
//                                ],
//                                [
//                                    'value' => 'label',
//                                    'label' => 'надпись',
//                                    'class' => 'label'
//                                ]
//                            ]
//                        ])
//                    ]);
//
//                    if (ap($inStockInfo, 'mode') == 'label') {
//                        $v->assign('stock/warehouse_group/in_stock_label', [
//                            'LABEL_INPUT' => $this->stringValueInput('stock/groups/' . $group->id . '/in_stock/label'),
//                        ]);
//                    } else {
//                        $hasValueMode = true;
//                    }
//                }
//
//                if ($notInStockInfoDisplay) {
//                    $v->append('stock/warehouse_group', [
//                        'NOT_IN_STOCK_MODE_SWITCHER' => $this->c('\std\ui\switcher~:view', [
//                            'path'    => $this->_p('>xhr:setNotInStockMode'),
//                            'data'    => [
//                                'pivot' => $this->pivotXPack,
//                                'group' => $groupXPack,
//                            ],
//                            'value'   => ap($notInStockInfo, 'mode'),
//                            'class'   => 'switcher',
//                            'classes' => [
//
//                            ],
//                            'buttons' => [
//                                [
//                                    'value' => 'value',
//                                    'label' => 'значение',
//                                    'class' => 'value'
//                                ],
//                                [
//                                    'value' => 'label',
//                                    'label' => 'надпись',
//                                    'class' => 'label'
//                                ]
//                            ]
//                        ])
//                    ]);
//
//                    if (ap($notInStockInfo, 'mode') == 'label') {
//                        $v->assign('stock/warehouse_group/not_in_stock_label', [
//                            'LABEL_INPUT' => $this->stringValueInput('stock/groups/' . $group->id . '/not_in_stock/label'),
//                        ]);
//                    } else {
//                        $hasValueMode = true;
//                    }
//                }
//
//                if ($hasValueMode) {
//                    $v->assign('stock/warehouse_group/value_label', [
//                        'LABEL_INPUT' => $this->stringValueInput('stock/groups/' . $group->id . '/value_label'),
//                    ]);
//                }
//            }
        }
    }

    public function stockWarehouseGroupSelector()
    {
        $groups = \ss\multisource\models\WarehouseGroup::orderBy('position')->get();

        $assignedStockWarehousesGroups = $this->tileData('stock/groups');
        $assignedStockWarehousesGroupsIds = array_keys($assignedStockWarehousesGroups);

        $items = [0 => ''];

        foreach ($groups as $group) {
            $groupInfo = ap($assignedStockWarehousesGroups, $group->id);

            if (!in_array($group->id, $assignedStockWarehousesGroupsIds) || !$groupInfo['enabled']) {
                $items[$group->id] = $group->name;
            }
        }

        return $this->c('\std\ui select:view', [
            'path'  => '>xhr:assignStockInfoGroup',
            'data'  => [
                'pivot' => $this->pivotXPack
            ],
            'items' => $items
        ]);
    }
}
