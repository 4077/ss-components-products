<?php namespace ss\components\products;

/**
 * @param \ss\models\CatComponent $pivot
 *
 * @return Grid
 */
function grid(\ss\models\CatComponent $pivot)
{
    return Grid::getInstance($pivot);
}

/**
 * @param \ss\models\Product      $product
 * @param \ss\models\CatComponent $pivot
 *
 * @return Tile
 */
function tile(\ss\models\Product $product, \ss\models\CatComponent $pivot)
{
    return Tile::getInstance($product, $pivot);
}


function config($path)
{
    return Config::getInstance()->get($path);
}
