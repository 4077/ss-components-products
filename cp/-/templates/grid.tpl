<div class="{__NODE_ID__}" instance="{__INSTANCE__}">

    <div class="grid">
        <div class="row">
            <div class="label">Показывать название</div>
            <div class="control">
                {DISPLAY_NAME_TOGGLE}
            </div>
        </div>
        <div class="row">
            <div class="label">Показывать описание</div>
            <div class="control">
                {DISPLAY_DESCRIPTION_TOGGLE}
            </div>
        </div>
        <div class="row">
            <div class="label">Показывать товары, которых нет в наличии</div>
            <div class="control">
                {NOT_IN_STOCK_PRODUCTS_TOGGLE}
            </div>
        </div>
        <div class="row">
            <div class="label">Минимальное наличие (в основных ед. изм.)</div>
            <div class="control">
                {STOCK_MINIMUM_TOGGLE}
            </div>
        </div>
        <!-- if stock_minimum_enabled -->
        <div class="row l2">
            <div class="label">Значение</div>
            <div class="control">
                <input type="text" class="stock_minimum_value" field="stock_minimum_value" value="{STOCK_MINIMUM_VALUE}">
            </div>
        </div>
        <!-- / -->
        <div class="row">
            <div class="label">Показывать товары, которых нет под заказ</div>
            <div class="control">
                {NOT_IN_UNDER_ORDER_PRODUCTS_TOGGLE}
            </div>
        </div>
        <div class="row">
            <div class="label">Минимальное наличие под заказ (в основных ед. изм.)</div>
            <div class="control">
                {UNDER_ORDER_MINIMUM_TOGGLE}
            </div>
        </div>
        <!-- if under_order_minimum_enabled -->
        <div class="row l2">
            <div class="label">Значение</div>
            <div class="control">
                <input type="text" class="under_order_minimum_value" field="under_order_minimum_value" value="{UNDER_ORDER_MINIMUM_VALUE}">
            </div>
        </div>
        <!-- / -->
        <div class="row">
            <div class="label">Показывать товары с нулевой ценой</div>
            <div class="control">
                {ZEROPRICE_PRODUCTS_DISPLAY_TOGGLE}
            </div>
        </div>
    </div>

    <div class="tile">
        {TILE}
    </div>

</div>
