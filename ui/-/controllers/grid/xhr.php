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
                'cat' => $cat
            ], 'pivot');
        }
    }

    public function tileReload()
    {
        $product = $this->unxpackModel('product');
        $cat = \ss\models\Cat::find($this->data('cat_id'));

        if ($cat && $product) {
            $this->c('tile:reload', [
                'cat'     => $cat,
                'product' => $product
            ], 'pivot');

            $this->c('product:reload', [
                'cat'     => $cat,
                'product' => $product
            ], 'pivot');

            $this->widget('<:|' . $cat->id, 'resetQuantity', $product->id);
            $this->widget('<:|' . $cat->id, 'bindTile', $product->id);
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
        $data = _j64($this->data('data'));

        if ($product = unpack_model($data['product'])) {
            $cart = cart($data['cart_instance']);

            $quantity = $this->data('quantity');
            if ($quantity != $cart->stage->getQuantity($product)) {
                $cart->stage->setQuantity($product, $quantity);
            }

            $cart->add($product, map($data, 'pivot, name, price, price_without_discount, discount, units, price_display'));
        }
    }

    public function decQuantity()
    {
        $product = $this->unxpackModel('product');
        $cartInstance = $this->getCartInstance();

        if ($product && null !== $cartInstance) {
            cart($cartInstance)->stage->decQuantity($product);
        }
    }

    public function incQuantity()
    {
        $product = $this->unxpackModel('product');
        $cartInstance = $this->getCartInstance();

        if ($product && null !== $cartInstance) {
            cart($cartInstance)->stage->incQuantity($product);
        }
    }

    public function setQuantity()
    {
        $product = $this->unxpackModel('product');
        $cartInstance = $this->getCartInstance();

        if ($product && null !== $cartInstance) {
            cart($cartInstance)->stage->setQuantity($product, $this->data('value'));
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
