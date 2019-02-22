<?php namespace ss\components\products\ui\controllers;

class Grid extends \Controller
{
    private $cat;

    private $pivot;

    private $pivotData;

    private $globalEditable;

    private $catEditable;

    private $productsEditable;

    private $ss;

    /**
     * @var \ss\components\products\Grid
     */
    private $grid;

    public function __create()
    {
        $this->cat = $this->data('cat');
        $this->pivot = $this->unpackModel('pivot');
        $this->pivotData = _j($this->pivot->data);

        $this->ss = ss();
        $this->grid = \ss\components\products\grid($this->pivot);

        $this->globalEditable = $this->ss->globalEditable();
        $this->catEditable = $this->ss->cats->isEditable($this->cat);
        $this->productsEditable = $this->ss->cats->isProductsEditable($this->cat);

        $this->instance_($this->cat->id);
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

    private function getProducts()
    {
        if ($products = $this->data('products')) {
            return $products;
        } else {
            $builder = $this->cat->products()->with('multisourceSummary')->where('enabled', true);

            if (!($this->productsEditable && $this->globalEditable)) {
                $builder = $builder->where('published', true);
            }

            return $builder->orderBy('position')->get();
        }
    }

    public function view()
    {
        $v = $this->v('|');

        $cat = $this->cat;
        $pivot = $this->pivot;
        $grid = $this->grid;

        $disableSorting = $this->data('disable_sorting');

        $statuses = (new \ss\moderation\Main)->statuses;

        if ($grid->nameDisplay) {
            $v->assign('name', [
                'CONTENT' => ss()->cats->getName($cat)
            ]);
        }

        if ($grid->descriptionDisplay) {
            $v->assign('description', [
                'CONTENT' => $cat->description
            ]);
        }

        $products = $this->getProducts();

        $hasVisibleProducts = false;

        /**
         * @var $carouselSvc \ss\components\products\ui\controllers\Carousel
         */
        $carouselSvc = $this->c('@carousel');

        $productIds = [];
        $productsWidgetData = [];

        foreach ($products as $product) {
            $tile = \ss\components\products\tile($product, $pivot);

            if (!$tile->isHidden()) {
                $v->assign('tile', [
                    'N'          => $carouselSvc->n,
                    'PRODUCT_ID' => $product->id,
                    'CONTENT'    => $this->c('@tile:view', [
                        'product' => $product,
                        'pivot'   => $pivot
                    ])
                ]);

                if ($this->globalEditable) {
                    $v->assign('tile/cp');

                    if ($this->catEditable || $this->productsEditable) {
                        $v->assign('tile/status', [
                            'STATUS'            => $product->status,
                            'STATUS_ICON_CLASS' => 'fa ' . $statuses[$product->status]['icon'],
                        ]);
                    }

                    $v->assign('tile/not_published_mark', [
                        'HIDDEN_CLASS' => $product->published ? 'hidden' : ''
                    ]);

                    if ($this->productsEditable) {
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

                $carouselSvc->addTile($tile);

                $hasVisibleProducts = true;
                $productIds[] = $product->id;

                $productsWidgetData[$product->id] = [
                    'price'    => $tile->price,
                    'quantity' => $tile->stageQuantity
                ];
            }
        }

        if (!$disableSorting && $this->globalEditable && $this->catEditable) {
            $this->c('\std\ui sortable:bind', [
                'selector'       => $this->_selector('|') . ' > .tiles',
                'path'           => '>xhr:arrange',
                'items_id_attr'  => 'item_id',
                'data'           => ['cat' => xpack_model($cat)],
                'plugin_options' => ['distance' => 15]
            ]);
        }

        if ($hasVisibleProducts) {
            $this->css();

            $widgetData = [
                '.r'             => [
                    'incQuantity' => $this->_p('>xhr:incQuantity'),
                    'decQuantity' => $this->_p('>xhr:decQuantity'),
                    'setQuantity' => $this->_p('>xhr:setQuantity'),
                    'addToCart'   => $this->_abs('>xhr:addToCart'),
                    'reload'      => $this->_abs('>xhr:reload', [
                        'cat_id' => $cat->id,
                        'pivot'  => xpack_model($pivot)
                    ]),
                    'reloadTile'  => $this->_abs('>xhr:tileReload', [
                        'cat_id' => $cat->id,
                        'pivot'  => xpack_model($pivot)
                    ]),
                    'productOpen' => $this->_p('>xhr:productOpen')
                ],
                '.w'             => [
                    'carousel' => $this->_w('@carousel:')
                ],
                'products'       => $productsWidgetData,
                'priceRounding' => $this->tileData('price/rounding'),
                'cart'           => j64_($this->tileData('cart_instance')),
                'catId'          => $cat->id,
                'productsIds'    => $productIds,
                'pivot'          => xpack_model($pivot)
            ];

            $this->widget(':|', $widgetData);

            return $v;
        }
    }
}
