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

    public function render($data = [])
    {
        $v = $this->v('|');

        foreach ($this->products as $productId => $product) {
            $v->assign('item', [
                'PRODUCT_ID' => $productId,
                'CONTENT'    => $product->render()
            ]);
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
            '.payload'      => [
                'multisource' => j64_($this->data('multisource'))
            ],
            '.r'            => [
                'incQuantity'  => $this->_p('grid/xhr:incQuantity'),
                'decQuantity'  => $this->_p('grid/xhr:decQuantity'),
                'setQuantity'  => $this->_p('grid/xhr:setQuantity'),
                'addToCart'    => $this->_p('grid/xhr:addToCart'),
                'productSlide' => $this->_p('grid/xhr:productSlide'),
                'close'        => $this->_p('grid/xhr:close'),
            ],
            'route'         => $data['back_route'] ?? $this->data['cat']->route_cache ?? '',
            'openProductId' => $this->data('product_id')
        ]);
    }
}
