<?php namespace ss\components\products\ui\controllers;

class Carousel extends \Controller
{
    public $singleton = true;

    private $products = [];

    public $n = 0;

    public function addProduct($productId, $v)
    {
        $this->products[$productId] = $v;
        $this->n++;
    }

    /**
     * @var \ss\components\products\Tile[]
     */
    private $tiles = [];

    public function addTile(\ss\components\products\Tile $tile)
    {
        $this->tiles[] = $tile;
        $this->n++;
    }

    public function render($data = [])
    {
        $v = $this->v('|');

        $productsWidgetData = [];

        foreach ($this->tiles as $tile) {
            $product = $tile->product;

            $v->assign('item', [
                'PRODUCT_ID' => $product->id,
                'CONTENT'    => $this->c('product:view', [
                    'product' => $tile->product,
                    'pivot'   => $tile->pivot
                ])
            ]);

            $productsWidgetData[$product->id] = [
                'price'         => $tile->price,
                'quantity'      => $tile->stageQuantity,
                'priceRounding' => $tile->data('price/rounding')
            ];
        }

        $this->css();

        # 1

        $this->app->html->replaceContainer($this->_nodeId(), $v->render());

        # 2

        $this->c('\plugins\owlCarousel~:bind', [
            'selector' => $this->_selector('|') . ' .items',
            'options'  => [
                'items'      => 1,
                'autoHeight' => true,
                'dots'       => false,
                'loop'       => true,
                'smartSpeed' => 0
            ]
        ]);

        $this->widget(':|', [
            '.r'            => [
                'incQuantity'  => $this->_p('grid/xhr:incQuantity'),
                'decQuantity'  => $this->_p('grid/xhr:decQuantity'),
                'setQuantity'  => $this->_p('grid/xhr:setQuantity'),
                'addToCart'    => $this->_p('grid/xhr:addToCart'),
                'productSlide' => $this->_p('grid/xhr:productSlide'),
                'close'        => $this->_p('grid/xhr:close'),
            ],
            'products'      => $productsWidgetData,
            'route'         => $data['back_route'] ?? $this->data['cat']->route_cache ?? '',
            'openProductId' => $this->data('product_id')
        ]);
    }
}
