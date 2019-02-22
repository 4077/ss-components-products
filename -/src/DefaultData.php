<?php namespace ss\components\products;

class DefaultData
{
    public static function stockInfoGroup()
    {
        return [
            'enabled'      => true,
            'in_stock'     => [
                'display' => true,
                'mode'    => 'value',
                'label'   => 'В наличии'
            ],
            'not_in_stock' => [
                'display' => true,
                'mode'    => 'value',
                'label'   => 'Нет в наличии'
            ],
            'value_label'  => ''
        ];
    }
}
