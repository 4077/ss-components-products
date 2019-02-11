<?php namespace ss\components\products\ui\controllers;

class Grid extends \Controller
{
    private $cat;

    private $pivot;

    public function __create()
    {
        $this->cat = $this->data('cat');
        $this->pivot = $this->unpackModel('pivot');

        $this->instance_($this->cat->id);
    }

    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $ss = ss();

        $cat = $this->cat;
        $pivot = $this->pivot;

        $globalEditable = $ss->globalEditable();
        $catEditable = $ss->cats->isEditable($cat);
        $disableSorting = $this->data('disable_sorting');

        $statuses = (new \ss\moderation\Main)->statuses;

        $pivotData = _j($pivot->data);

        if (ap($pivotData, 'grid/name_display')) {
            $v->assign('name', [
                'CONTENT' => ss()->cats->getName($cat)
            ]);
        }

        if (ap($pivotData, 'grid/description_display')) {
            $v->assign('description', [
                'CONTENT' => $cat->description
            ]);
        }

        $notInStockProductsDisplay = ap($pivotData, 'grid/not_in_stock_products_display');
        $notInUnderOrderProductsDisplay = ap($pivotData, 'grid/not_in_under_order_products_display');
        $stockMinimumEnabled = ap($pivotData, 'grid/stock_minimum/enabled');
        $stockMinimumValue = ap($pivotData, 'grid/stock_minimum/value');
        $underOrderMinimumEnabled = ap($pivotData, 'grid/under_order_minimum/enabled');
        $underOrderMinimumValue = ap($pivotData, 'grid/under_order_minimum/value');

        $zeropriceProductsDisplay = ap($pivotData, 'grid/zeroprice_products_display');

        $products = $this->data('products') ?: $cat->products()->orderBy('position')->get();

        $stockWarehouses = $this->data('multisource/stock_warehouses');
        $underOrderWarehouses = $this->data('multisource/under_order_warehouses');
        $multisourceInstance = ss()->products->getMultisourceInstance($stockWarehouses, $underOrderWarehouses);
        $productsMultisourceSummary = table_rows_by(\ss\models\ProductsMultisourceSummary::whereIn('product_id', table_ids($products))->where('instance', $multisourceInstance)->get(), 'product_id');

        $hasVisibleProducts = false;

        /**
         * @var $carouselSvc \ss\components\products\ui\controllers\Carousel
         */
        $carouselSvc = $this->c('@carousel', [], 'multisource');

        /**
         * @var $cTileApp \ss\components\products\ui\controllers\tile\App
         */
        $cTileApp = $this->c('@tile/app');

        $productIds = [];

        foreach ($products as $product) {
            $productEditable = $ss->products->isEditable($product);

            $productVisible = $product->enabled && ($product->published || $globalEditable);

            if ($productVisible) {
                if (!isset($productsMultisourceSummary[$product->id])) {
                    $productsMultisourceSummary[$product->id] = $ss->products->updateMultisourceSummary($product, $stockWarehouses, $underOrderWarehouses);
                }

                $productMultisourceSummary = $productsMultisourceSummary[$product->id];

                $stock = $productMultisourceSummary->stock;
                $underOrder = $productMultisourceSummary->under_order;

                $stockMinimum = $stockMinimumEnabled ? $stockMinimumValue : 0;
                $underOrderMinimum = $underOrderMinimumEnabled ? $underOrderMinimumValue : 0;

                $inStock = $stock > $stockMinimum;
                $inUnderOrder = $underOrder > $underOrderMinimum;

                $hiddenByStock = !$notInStockProductsDisplay && !$inStock;
                $hiddenByUnderOrder = !$notInUnderOrderProductsDisplay && !$inUnderOrder;

                if (!$hiddenByStock && !$hiddenByUnderOrder) {
                    $tileData = $cTileApp->renderTileData($product, $pivot);

                    $tileData['stock'] = $stock;
                    $tileData['in_stock'] = $inStock;

                    $tileData['under_order'] = $underOrder;
                    $tileData['in_under_order'] = $inUnderOrder;

                    if ($inStock) {
                        $price = max($productMultisourceSummary->stock_min_price, $productMultisourceSummary->stock_max_price);
                    } else {
                        $price = max($productMultisourceSummary->under_order_min_price, $productMultisourceSummary->under_order_max_price);
                    }

                    $hiddenByZeroprice = $price == 0 && !$zeropriceProductsDisplay;

                    if (!$hiddenByZeroprice) {
                        $tileData['price'] = $price;

                        $v->assign('tile', [
                            'N'          => $carouselSvc->n,
                            'PRODUCT_ID' => $product->id,
                            'CONTENT'    => $this->c('@tile:view', $tileData)
                        ]);

                        if ($globalEditable) {
                            $v->assign('tile/cp');

                            if ($catEditable || $productEditable) {
                                $v->assign('tile/status', [
                                    'STATUS'            => $product->status,
                                    'STATUS_ICON_CLASS' => 'fa ' . $statuses[$product->status]['icon'],
                                ]);
                            }

                            $v->assign('tile/not_published_mark', [
                                'HIDDEN_CLASS' => $product->published ? 'hidden' : ''
                            ]);

                            if ($productEditable) {
                                $v->append('tile', [
                                    'PRODUCT_DIALOG_BUTTON' => $this->c('\std\ui button:view', [
                                        'path'  => '>xhr:productDialog',
                                        'data'  => [
                                            'product' => xpack_model($product)
                                        ],
                                        'class' => 'product_dialog button',
                                        'icon'  => 'fa fa-cog'
                                    ])
                                ]);
                            }
                        }

                        $carouselSvc->addProduct($product->id, $this->c('@product:view', $tileData));

                        $hasVisibleProducts = true;

                        $productIds[] = $product->id;
                    }
                }
            }
        }

        if (!$disableSorting && $globalEditable && $catEditable) {
            $this->c('\std\ui sortable:bind', [
                'selector'       => $this->_selector('|') . ' > .tiles',
                'path'           => '>xhr:arrange',
                'items_id_attr'  => 'product_id',
                'data'           => ['cat' => xpack_model($cat)],
                'plugin_options' => ['distance' => 15]
            ]);
        }

        if ($hasVisibleProducts) {
            $this->css();

            $widgetData = [
                '.r'          => [
                    'incQuantity' => $this->_p('>xhr:incQuantity'),
                    'decQuantity' => $this->_p('>xhr:decQuantity'),
                    'setQuantity' => $this->_p('>xhr:setQuantity'),
                    'addToCart'   => $this->_abs('>xhr:addToCart', [
                        'multisource' => j64_($this->data('multisource'))
                    ]),
                    'reload'      => $this->_abs('>xhr:reload', [
                        'cat_id'      => $cat->id,
                        'pivot'       => xpack_model($pivot),
                        'multisource' => j64_($this->data('multisource'))
                    ]),
                    'reloadTile'  => $this->_abs('>xhr:tileReload', [
                        'cat_id'      => $cat->id,
                        'pivot'       => xpack_model($pivot),
                        'multisource' => j64_($this->data('multisource'))
                    ]),
                    'productOpen' => $this->_p('>xhr:productOpen')
                ],
                '.w'          => [
                    'carousel' => $this->_w('@carousel:')
                ],
                'cart'        => j64_(ap($pivotData, 'tile/cart_instance')),
                'catId'       => $cat->id,
                'productsIds' => $productIds,
                'pivot'       => xpack_model($pivot)
            ];

            $this->widget(':|', $widgetData);

            return $v;
        }
    }
}
