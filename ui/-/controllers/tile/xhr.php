<?php namespace ss\components\products\ui\controllers\tile;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function reload()
    {
        if ($product = \ss\models\Product::find($this->data('product_id'))) {
            $this->c('<:reload', ['product' => $product], 'pivot');
        }
    }
}
