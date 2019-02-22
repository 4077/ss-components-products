<?php namespace ss\components\products\ui\controllers;

class Tile extends \Controller
{
    private $product;

    private $pivot;

    /**
     * @var \ss\components\products\Tile
     */
    private $tile;

    private $cacheEnabled;

    public function __create()
    {
        $this->product = $this->unpackModel('product');
        $this->pivot = $this->unpackModel('pivot');

        if ($this->product && $this->pivot) {
            $this->instance_($this->product->id);

            $this->tile = \ss\components\products\tile($this->product, $this->pivot);

            $this->cacheEnabled = \ss\components\products\config('cache_enabled');
        } else {
            $this->lock();
        }
    }

    public function reload()
    {
        $this->jquery($this->_selector($this->tile->template . ':|'))->replace($this->view());
    }

    public function view()
    {
        $product = $this->product;

        $tile = $this->tile;

        $v = $this->v($tile->template . '|');

        $cacheFilePath = $this->getCacheFilePath();

        if ($this->cacheEnabled && $cache = aread($cacheFilePath)) {
            $v->setData($cache['v_data']);
        } else {
            $cacheData = [];

            $v->assign([
                           'PRODUCT_ID' => $product->id,
                           'XPACK'      => xpack_model($product),
                           'NAME'       => $tile->name
                       ]);

            $this->assignCartCp($v);
            $this->assignProps($v);
            $this->assignImage($v);
            $this->assignNameFontSize($v);
            $this->assignStockInfo($v);

            $cacheData['css'] = $this->cssVars($v);

            $cache = [
                'data'   => $cacheData,
                'v_data' => $v->getData()
            ];

            if ($this->cacheEnabled) {
                awrite($cacheFilePath, $cache);
            }
        }

        $css = $cache['data']['css'];

        $this->css($css['path'] . '|' . $css['vmd5'])->setVars($css['vars']);

        return $v;
    }

    private function getCacheFilePath()
    {
        $cacheKey = jmd5([
                             $this->tile->pivotData,
                             $this->tile->stageQuantity,
                             $this->tile->cartQuantity,
                             ss()->cats->getSelectedWarehousesGroup($this->product->tree_id)
                         ]);

        $cacheFilePath = abs_path(
            'cache/ss/views',
            'tree_' . $this->product->tree_id,
            'cat_' . $this->product->cat_id,
            'product_' . $this->product->id,
            $this->_nodeId(),
            $cacheKey . '.php'
        );

        return $cacheFilePath;
    }

    private function cssVars(\ewma\Views\View $v)
    {
        $tile = $this->tile;

        $cssVars = [
            'imageWidth'  => $tile->imageWidth . 'px',
            'imageHeight' => $tile->imageHeight ? $tile->imageHeight . 'px' : 'auto'
        ];

        $cssVarsMd5 = '_' . jmd5($cssVars);

        $cssVars['vmd5'] = $cssVarsMd5;

        $v->assign('VMD5', $cssVarsMd5);

        $cssCacheData = [
            'path' => $tile->css,
            'vmd5' => $cssVarsMd5,
            'vars' => $cssVars
        ];

        return $cssCacheData;
    }

    private function assignCartCp(\ewma\Views\View $v)
    {
        $product = $this->product;
        $pivot = $this->pivot;

        $tile = $this->tile;

        if ($tile->priceDisplay) {
            if ($tile->price == 0 && $tile->zeropriceLabelEnabled) {
                $v->assign('zeroprice_label', [
                    'VALUE' => $tile->zeropriceLabelValue
                ]);
            } else {
                $v->assign('price', [
                    'VALUE' => $tile->priceFormatted,
                ]);

                if ($tile->units) {
                    $v->assign('price/units', [
                        'CONTENT' => $tile->units
                    ]);
                }

                if ($tile->discountApplied) {
                    $v->assign('price_without_discount', [
                        'VALUE'    => $tile->priceWithoutDiscountFormatted,
                        'DISCOUNT' => $tile->discount
                    ]);

                    if ($tile->units) {
                        $v->assign('price_without_discount/units', [
                            'CONTENT' => $tile->units
                        ]);
                    }
                }
            }
        }

        if ($tile->cartbuttonQuantify && $tile->cartbuttonDisplay) {
            $v->assign('quantify', [
                'VALUE' => $tile->stageQuantity
            ]);

            if ($tile->priceDisplay) {
                $v->assign('quantify/total_cost', [
                    'VALUE' => $tile->getTotalCost($tile->stageQuantity)
                ]);

                if ($tile->price > 0) {
                    $v->assign('quantify/total_cost/has_value');
                }
            }
        }

        if (!$tile->cartbuttonQuantify && $tile->stageQuantity > 1) {
            $tile->cart->stage->setQuantity($product, 1);
        }

        if ($tile->cartbuttonDisplay) {
            $cartButtonData = [
                'cart_instance'          => $tile->cartInstance,
                'product'                => pack_model($product),
                'pivot'                  => pack_model($pivot),
                'name'                   => $tile->name,
                'price'                  => $tile->price,
                'price_without_discount' => $tile->priceWithoutDiscount,
                'discount'               => $tile->discountApplied ? $tile->discount : 0,
                'units'                  => $tile->units,
                'price_display'          => $tile->priceDisplay
            ];

            $v->assign('cartbutton', [
                'IN_CART_CLASS' => $tile->cartQuantity ? 'in_cart' : '',
                'LABEL'         => $tile->cartbuttonLabel ?: 'Купить',
                'DATA'          => j64_($cartButtonData)
            ]);

            if ($tile->cartQuantity) {
                $v->assign('cartbutton/products_count', [
                    'PRODUCTS_COUNT' => $tile->cartQuantity
                ]);
            };
        }

        return $v;
    }

    private function assignProps(\ewma\Views\View $v)
    {
        if ($props = _j($this->product->props)) {
            foreach ($props as $prop) {
                $v->assign('prop', [
                    'NAME'  => $prop['label'],
                    'VALUE' => $prop['value']
                ]);
            }
        }

        return $v;
    }

    private function assignImage(\ewma\Views\View $v)
    {
        $tile = $this->tile;

        $image = $this->c('\std\images~:first', [
            'model'       => $this->product,
            'query'       => $tile->imageQuery,
            'href'        => $tile->imageHref,
            'cache_field' => 'images_cache'
        ]);

        if ($image) {
            $v->assign([
                           'IMAGE'     => $image->view,
                           'IMAGE_SRC' => abs_url($image->versionModel->file_path)
                       ]);
        }

        return $v;
    }

    private function assignNameFontSize(\ewma\Views\View $v)
    {
        $tile = $this->tile;

        $nameFontSize = 16;

        if (strlen($tile->name) > 50) {
            $nameFontSize = 15;
        }

        if ($tile->imageWidth < 225) {
            $nameFontSize *= 0.8;
        }

        $v->assign('NAME_FONT_SIZE', floor($nameFontSize));

        return $v;
    }

    private function assignStockInfo(\ewma\Views\View $v)
    {
        $tile = $this->tile;

        $v->assign('stock');

        foreach ($tile->stockGroupsInfo as $groupInfo) {
            $v->assign('stock/group', [
                'TYPE'  => $groupInfo['type'],
                'CLASS' => $groupInfo['in_stock'] ? 'in_stock' : 'not_in_stock',
            ]);

            if ($groupInfo['mode'] == 'value') {
                $v->assign('stock/group/value', [
                    'LABEL' => $groupInfo['value_label'],
                    'VALUE' => $groupInfo['value'],
                    'UNITS' => $tile->units ? ' ' . $tile->units : ''
                ]);
            }

            if ($groupInfo['mode'] == 'label') {
                $v->assign('stock/group/label', [
                    'CONTENT' => $groupInfo['label']
                ]);
            }
        }

        return $v;
    }
}
