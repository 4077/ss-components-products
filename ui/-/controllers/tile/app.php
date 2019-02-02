<?php namespace ss\components\products\ui\controllers\tile;

class App extends \Controller
{
//    public function dropCache()
//    {
//        if ($product = $this->data('product')) {
//            $cacheDirPath = abs_path(
//                'cache/ss/views',
//                'tree_' . $product->tree_id,
//                'cat_' . $product->cat_id,
//                'product_' . $product->id
//            );
//
//            delete_dir($cacheDirPath);
//        }
//    }

    public function renderTileData(\ss\models\Product $product, \ss\models\CatComponent $pivot, $stockRender = false, $multisource = [])
    {
        if ($pivot) {
            $pivotData = _j($pivot->data);

            $data = $pivotData['tile'] ?? [];

            if ($stockRender) {
                $stockWarehouses = ap($multisource, 'stock_warehouses');
                $underOrderWarehouses = ap($multisource, 'under_order_warehouses');

                $multisourceInstance = ss()->products->getMultisourceInstance($stockWarehouses, $underOrderWarehouses);

                if (!$multisourceSummary = $product->multisourceSummary()->where('instance', $multisourceInstance)->first()) {
                    $multisourceSummary = ss()->products->updateMultisourceSummary($product, $stockWarehouses, $underOrderWarehouses);
                }

                $stock = $multisourceSummary->stock;
                $underOrder = $multisourceSummary->under_order;

                $stockMinimumEnabled = ap($pivotData, 'grid/stock_minimum/enabled');
                $stockMinimumValue = ap($pivotData, 'grid/stock_minimum/value');

                $stockMinimum = $stockMinimumEnabled ? $stockMinimumValue : $product->unit_size;

                $underOrderMinimumEnabled = ap($pivotData, 'grid/under_order_minimum/enabled');
                $underOrderMinimumValue = ap($pivotData, 'grid/under_order_minimum/value');

                $underOrderMinimum = $underOrderMinimumEnabled ? $underOrderMinimumValue : $product->unit_size;

                $inStock = $stock >= $stockMinimum;
                $inUnderOrder = $underOrder >= $underOrderMinimum;

                $data['stock'] = $stock;
                $data['in_stock'] = $inStock;

                $data['under_order'] = $underOrder;
                $data['in_under_order'] = $inUnderOrder;

                if ($inStock) {
                    $data['price'] = max($multisourceSummary->stock_min_price, $multisourceSummary->stock_max_price);
                } else {
                    $data['price'] = max($multisourceSummary->under_order_min_price, $multisourceSummary->under_order_max_price);
                }
            }

            if (isset($data['template'])) {
                $templates = dataSets()->get('ss/components/products:tiles_templates');

                $data['template'] = $templates[$data['template']]['path'] ?? '';
            } else {
                $data['template'] = '';
            }

            $data['css'] = $data['template'];

            if (isset($data['image'])) {
                $data['image']['query'] = ($data['image']['width'] ?? '-') . ' ' . ($data['image']['height'] ?? '-') . ' ' . ($data['image']['resize_mode'] ?? 'fill');
            }

            $data['product'] = $product;
            $data['pivot'] = $pivot;

            return $data;
        }
    }
}
