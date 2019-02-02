<?php namespace ss\components\products\ui\controllers\grid;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    private function getCartInstance()
    {
        return _j64($this->data('cart'));
    }

    public function reload()
    {
        if ($cat = \ss\models\Cat::find($this->data('cat_id'))) {
            $this->c('<:reload', [
                'cat'         => $cat,
                'multisource' => _j64($this->data('multisource'))
            ], 'pivot');
        }
    }

    public function tileReload()
    {
        $product = $this->unxpackModel('product');
        $cat = \ss\models\Cat::find($this->data('cat_id'));

        if ($cat && $product) {
            $this->c('tile:reload', [
                'cat'         => $cat,
                'product'     => $product,
                'multisource' => _j64($this->data('multisource'))
            ], 'pivot');

            $this->c('product:reload', [
                'cat'         => $cat,
                'product'     => $product,
                'multisource' => _j64($this->data('multisource'))
            ], 'pivot');

            $this->widget('<:', 'bindTile', $product->id);
            $this->widget('<<carousel:', 'bindProduct', $product->id);
        }
    }

    public function productOpen()
    {
        if ($product = \ss\models\Product::find($this->data('product_id'))) {
            sstm()->events->trigger('cats/ui/productOpen', [
                'product' => $product
            ]);
        }
    }

    public function close()
    {
        sstm()->events->trigger('cats/ui/productClose');
    }

    public function productSlide()
    {
        if ($product = \ss\models\Product::find($this->data('product_id'))) {
            sstm()->events->trigger('cats/ui/productSlide', [
                'product' => $product
            ]);
        }
    }

    public function addToCart()
    {
        $product = $this->unxpackModel('product');
        $cartInstance = $this->getCartInstance();

        if ($product && null !== $cartInstance) {
            if ($cat = $product->cat) {
                $multisource = _j64($this->data('multisource'));

                $component = \ewma\components\models\Component::find(58); // hardcode

                $pivot = ss()->cats->getComponentPivot($cat, $component);

                /**
                 * @var $cTileApp \ss\components\products\ui\controllers\tile\App
                 */
                $cTileApp = $this->c('tile/app');

                $tileData = $cTileApp->renderTileData($product, $pivot, true, $multisource);

                cart($cartInstance)->add(ss()->products->getCartKey($product), [
                    'name'  => $product->name,
                    'price' => $tileData['price'],
                    'model' => pack_model($product)
                ]);
            }
        }
    }

//    public function addToCart()
//    {
//        $product = $this->unxpackModel('product');
//        $cartInstance = $this->getCartInstance();
//
//        if ($product && null !== $cartInstance) {
//            $sellByAltUnits = false;
//
//            if ($cat = $product->cat and $pivot = ss()->cats->getFirstEnabledComponentPivot($cat)) { // todo проверить что будет если компонент сетки не первый
//                $pivotData = _j($pivot->data);
//
//                $sellByAltUnits = ap($pivotData, 'tile/sell_by_alt_units');
//            }
//
//            if ($sellByAltUnits) {
//                $priceField = 'alt_price';
//            } else {
//                $priceField = 'price';
//            }
//
//            cart($cartInstance)->add(ss()->products->getCartKey($product), [
//                'name'  => $product->name,
//                'price' => $product->{$priceField},
//                'model' => pack_model($product)
//            ]);
//        }
//    }

    public function decQuantity()
    {
        $product = $this->unxpackModel('product');
        $cartInstance = $this->getCartInstance();

        if ($product && null !== $cartInstance) {
            cart($cartInstance)->stage->decQuantity(ss()->products->getCartKey($product));
        }
    }

    public function incQuantity()
    {
        $product = $this->unxpackModel('product');
        $cartInstance = $this->getCartInstance();

        if ($product && null !== $cartInstance) {
            cart($cartInstance)->stage->incQuantity(ss()->products->getCartKey($product));
        }
    }

    public function setQuantity()
    {
        $product = $this->unxpackModel('product');
        $cartInstance = $this->getCartInstance();

        if ($product && null !== $cartInstance) {
            cart($cartInstance)->stage->setQuantity(ss()->products->getCartKey($product), $this->data('value'));
        }
    }

    public function arrange()
    {
        if ($cat = $this->unxpackModel('cat') and $this->dataHas('sequence array')) {
            foreach ($this->data['sequence'] as $n => $nodeId) {
                if (is_numeric($n) && $node = \ss\models\Product::find($nodeId)) {
                    $node->update(['position' => ($n + 1) * 10]);
                }
            }

            pusher()->trigger('ss/cat/update_products', [
                'id' => $cat->id
            ]);

            pusher()->trigger('ss/cat/some_container_update_products', [
                'id' => $cat->parent_id
            ]);
        }
    }

    public function productDialog()
    {
        if ($product = $this->unxpackModel('product')) {
            if (ss()->products->isEditable($product)) {
                $this->c('\ss\cats\cp dialogs:product|ss/cats', [
                    'product' => $product
                ]);
            }
        }
    }
}
