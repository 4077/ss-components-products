<?php namespace ss\components\products\ui\controllers;

class Product extends \Controller
{
    private $product;

    private $productId;

    public function __create()
    {
        if ($this->product = $this->unpackModel('product')) {
            $this->productId = $this->product->id;

            $this->instance_($this->productId);
        } else {
            $this->lock();
        }
    }

    public function reload()
    {
        $product = $this->product;

        $pivot = $this->unpackModel('pivot');

        $tileData = $this->c('@tile/app')->renderTileData($product, $pivot, true, $this->data('multisource'));

        aa($this->data, $tileData);

        $this->jquery($this->_selector('|'))->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $product = $this->product;

        $cacheEnabled = $this->_env('local, remote/prod'); // todo МЕГАКОСТЫЛЬ
        $cacheFilePath = $this->getCacheFilePath();

        if ($cacheEnabled && $cache = aread($cacheFilePath)) {
            $v->setData($cache['v_data']);
        } else {
            $cacheData = [];

            $v->assign([
                           'XPACK' => xpack_model($product),
                           'CLASS' => $this->data('class'),
                           'NAME'  => $product->name
                       ]);

            $v = $this->assignCartCp($v);
            $v = $this->assignProps($v);
            $v = $this->assignImage($v);
            $v = $this->assignNameFontSize($v);
            $v = $this->assignStockInfo($v);
            $v = $this->assignUnderOrderInfo($v);

//            $eventData = pack_models(map($this->data, 'product, pivot, in_stock'));
//            $this->se('minicart/stage/update_quantity/' . jmd5([$this->productId]))->rebind(':reload', $eventData);

            $cache = [
                'data'   => $cacheData,
                'v_data' => $v->getData()
            ];

            if ($cacheEnabled) {
                awrite($cacheFilePath, $cache);
            }
        }

        $this->css();

        return $v;
    }

    private function getCacheFilePath()
    {
        $data = $this->data;

        $cartInstance = $this->data['cart_instance'] ?? '';

        $cart = cart($cartInstance);

        $cartItemKey = jmd5([$this->productId]);

        $quantity = $cart->stage->getQuantity($cartItemKey);
        $inCartCount = $cart->getQuantity($cartItemKey);

        $productArray = $this->product->toArray();

        $cacheKey = jmd5([unmap($data, 'pivot, product'), $quantity, $inCartCount]);

        $cacheFilePath = abs_path(
            'cache/ss/views',
            'tree_' . $productArray['tree_id'],
            'cat_' . $productArray['cat_id'],
            'product_' . $productArray['id'],
            $this->_nodeId(),
            $cacheKey . '.php'
        );

        return $cacheFilePath;
    }

//    private function getPriceAndUnits()
//    {
//        if ($this->data('sell_by_alt_units')) {
//            $priceField = 'alt_price';
//            $unitsField = 'alt_units';
//            $otherPriceField = 'price';
//            $otherUnitsField = 'units';
//        } else {
//            $priceField = 'price';
//            $unitsField = 'units';
//            $otherPriceField = 'alt_price';
//            $otherUnitsField = 'alt_units';
//        }
//
//        return [
////            $this->product->{$priceField},
//$this->data('price'),
//$this->product->{$unitsField},
//$this->product->{$otherPriceField},
//$this->product->{$otherUnitsField}
//        ];
//    }

    private function assignCartCp(\ewma\Views\View $v)
    {
        $cartInstance = $this->data('cart_instance') ?? '';

        $cart = cart($cartInstance);

        $cartItemKey = jmd5([$this->product->id]);

        $stageQuantity = $cart->stage->getQuantity($cartItemKey);
        $quantity = $cart->getQuantity($cartItemKey);

        $quantify = $this->data('quantify');
        $cartbuttonDisplay = $this->data('cartbutton/display');
        $priceDisplay = $this->data('price_display');
        $otherUnitsDisplay = $this->data('other_units_display');

//        list($price, $units, $otherPrice, $otherUnits) = $this->getPriceAndUnits();

        $price = $this->data('price');

        $units = $this->product->units;

        if ($priceDisplay) {
            $v->assign('price', [
                'VALUE' => number_format__($price),
            ]);

            if ($units) {
                $v->assign('price/units', [
                    'CONTENT' => $units
                ]);
            }
        }

//        if ($otherUnitsDisplay && $otherPrice) {
//            $v->assign('alt_price', [
//                'VALUE' => number_format__($otherPrice)
//            ]);
//
//            if ($otherUnits) {
//                $v->assign('alt_price/units', [
//                    'CONTENT' => $otherUnits
//                ]);
//            }
//        }

        if ($quantify && $cartbuttonDisplay) {
            $v->assign('quantify', [
                'VALUE' => $stageQuantity
            ]);

            if ($priceDisplay) {
                $v->assign('quantify/total_cost', [
                    'VALUE' => number_format__($price * $stageQuantity)
                ]);
            }
        }

        if (!$quantify && $stageQuantity > 1) { // todo сброс на кратность
            cart($cartInstance)->stage->setQuantity($cartItemKey, 1);
        }

        if ($cartbuttonDisplay) {
            $cartButtonLabel = $this->data('cartbutton/label') or
            $cartButtonLabel = 'Купить';

            $v->assign('cartbutton', [
                'IN_CART_CLASS' => $quantity ? 'in_cart' : '',
                'LABEL'         => $cartButtonLabel
            ]);

            if ($quantity) {
                $v->assign('cartbutton/items_count', [
                    'ITEMS_COUNT' => $quantity
                ]);
            };

            $v->assign('CART_INSTANCE', j64_($cartInstance));
        }

        return $v;
    }

    private function assignProps(\ewma\Views\View $v)
    {
        if ($props = _j($this->product->props)) {
            $v->assign('props', [
                'CONTENT' => $this->c('>props:view', [
                    'props' => _j($this->product->props)
                ])
            ]);
        }

        return $v;
    }

    private function assignImage(\ewma\Views\View $v)
    {
        $image = $this->c('\std\images~:first', [
            'model'       => $this->product,
            'query'       => '528 528 fit',
            'href'        => $this->data('image/href'),
            'cache_field' => 'images_cache'
        ]);

        if ($image) {
            $v->assign('IMAGE', $image->view);
        }

        return $v;
    }

    private function assignNameFontSize(\ewma\Views\View $v)
    {
        $width = $this->data('image/width') ?: 225;

        $nameFontSize = 18;

        if (strlen($this->product->name) > 50) {
            $nameFontSize = 15;
        }

        if ($width < 225) {
            $nameFontSize *= 0.8;
        }

        $v->assign('NAME_FONT_SIZE', floor($nameFontSize));

        return $v;
    }

    private function assignStockInfo(\ewma\Views\View $v)
    {
        $product = $this->product;

        $inStock = $this->data('in_stock');

        $stockInfoSettingsPath = $inStock ? 'in_stock' : 'not_in_stock';

        if ($this->data('stock_info/' . $stockInfoSettingsPath . '/display')) {
            $stockInfoMode = $this->data('stock_info/' . $stockInfoSettingsPath . '/mode') ?? 'value';

            $v->assign('stock_info', [
                'MODE'        => $stockInfoMode,
                'STOCK_CLASS' => $inStock ? 'in_stock' : 'not_in_stock'
            ]);

            if ($stockInfoMode == 'value') {
                $stock = $this->data('stock');

                if ($this->data('stock_info/common/rounding/enabled')) {
                    $roundingMode = $this->data('stock_info/common/rounding/mode');

                    if ($roundingMode == 'floor') {
                        $stock = floor($stock);
                    }

                    if ($roundingMode == 'round') {
                        $stock = round($stock);
                    }

                    if ($roundingMode == 'ceil') {
                        $stock = ceil($stock);
                    }
                }

                $v->assign('stock_info/value', [
                    'LABEL' => $this->data('stock_info/common/stock_value_label'),
                    'VALUE' => trim_zeros($stock)
                ]);

                if ($product->units) {
                    $v->assign('stock_info/value/units', [
                        'CONTENT' => $product->units
                    ]);
                }
            }

            if ($stockInfoMode == 'label') {
                $v->assign('stock_info/label', [
                    'CONTENT' => $this->data('stock_info/' . $stockInfoSettingsPath . '/label')
                ]);
            }
        }

        return $v;
    }

    private function assignUnderOrderInfo(\ewma\Views\View $v)
    {
        $product = $this->product;

        $inUnderOrder = $this->data('in_under_order');

        $underOrderInfoSettingsPath = $inUnderOrder ? 'in_under_order' : 'not_in_under_order';

        if ($this->data('stock_info/' . $underOrderInfoSettingsPath . '/display')) {
            $underOrderInfoMode = $this->data('stock_info/' . $underOrderInfoSettingsPath . '/mode') ?? 'value';

            $v->assign('under_order_info', [
                'MODE'        => $underOrderInfoMode,
                'STOCK_CLASS' => $inUnderOrder ? 'in_under_order' : 'not_in_under_order'
            ]);

            if ($underOrderInfoMode == 'value') {
                $underOrder = $this->data('under_order');

                if ($this->data('stock_info/common/rounding/enabled')) {
                    $roundingMode = $this->data('stock_info/common/rounding/mode');

                    if ($roundingMode == 'floor') {
                        $underOrder = floor($underOrder);
                    }

                    if ($roundingMode == 'round') {
                        $underOrder = round($underOrder);
                    }

                    if ($roundingMode == 'ceil') {
                        $underOrder = ceil($underOrder);
                    }
                }

                $v->assign('under_order_info/value', [
                    'LABEL' => $this->data('stock_info/common/under_order_value_label'),
                    'VALUE' => trim_zeros($underOrder)
                ]);

                if ($product->units) {
                    $v->assign('under_order_info/value/units', [
                        'CONTENT' => $product->units
                    ]);
                }
            }

            if ($underOrderInfoMode == 'label') {
                $v->assign('under_order_info/label', [
                    'CONTENT' => $this->data('stock_info/' . $underOrderInfoSettingsPath . '/label')
                ]);
            }
        }

        return $v;
    }
}
