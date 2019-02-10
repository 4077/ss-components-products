<div class="{__NODE_ID__}" instance="{__INSTANCE__}">

    <div class="options">
        <div class="row">
            <div class="label">Приоритет наименования</div>
            <div class="control">
                {NAME_PRIORITY_SWITCHER}
            </div>
        </div>

        <div class="row">
            <div class="label">Кнопка корзины</div>
            <div class="control">
                {CARTBUTTON_TOGGLE}
            </div>
        </div>

        <!-- if cartbutton_enabled -->
        <div class="row l2">
            <div class="label">Надпись</div>
            <div class="control">
                <input type="text" class="cartbutton_label" field="cartbutton_label" value="{CARTBUTTON_LABEL}">
            </div>
        </div>

        <div class="row l2">
            <div class="label">Селектор количества</div>
            <div class="control">
                {QUANTIFY_TOGGLE}
            </div>
        </div>
        <!-- / -->

        <div class="row">
            <div class="label">Показывать цену</div>
            <div class="control">
                {PRICE_TOGGLE}
            </div>
        </div>

        <!-- if price_display -->
        <div class="row l2">
            <div class="label">Округление</div>
            <div class="control">
                {PRICE_ROUNDING_TOGGLE}
                <!-- if price_rounding_enabled -->
                {PRICE_ROUNDING_MODE_SWITCHER}
                <!-- / -->
            </div>
        </div>

        <div class="row l2">
            <div class="label">Надпись вместо нулевой цены</div>
            <div class="control">
                {ZEROPRICE_LABEL_TOGGLE}
            </div>
        </div>

        <!-- if zeroprice_label -->
        <div class="row l3">
            <div class="label">Надпись</div>
            <div class="control">
                <input type="text" class="zeroprice_label" field="zeroprice_label" value="{ZEROPRICE_LABEL_VALUE}">
            </div>
        </div>
        <!-- / -->
        <!-- / -->

        <div class="row">
            <div class="label">Единицы продажи</div>
            <div class="control">
                {SELL_UNITS_TOGGLE}
            </div>
        </div>

        <div class="row">
            <div class="label">Показывать другие единицы</div>
            <div class="control">
                {OTHER_UNITS_DISPLAY_TOGGLE}
            </div>
        </div>

        <div class="row">
            <div class="label">Информация о наличии</div>
        </div>

        {*

            stock

        *}

        <div class="row l2">
            <div class="label">Если есть в наличии</div>
            <div class="control">
                {IN_STOCK_DISPLAY_TOGGLE}
                <!-- if in_stock_info_enabled -->
                {IN_STOCK_INFO_MODE_SWITCHER}
                <!-- / -->
            </div>
        </div>

        <!-- if in_stock_label_control -->
        <div class="row l3">
            <div class="label">Надпись</div>
            <div class="control">
                <input type="text" class="in_stock_info_label" field="in_stock_info_label" value="{IN_STOCK_INFO_LABEL}">
            </div>
        </div>
        <!-- / -->

        <div class="row l2">
            <div class="label">Если нет в наличии</div>
            <div class="control">
                {NOT_IN_STOCK_DISPLAY_TOGGLE}
                <!-- if not_in_stock_info_enabled -->
                {NOT_IN_STOCK_INFO_MODE_SWITCHER}
                <!-- / -->
            </div>
        </div>

        <!-- if not_in_stock_label_control -->
        <div class="row l3">
            <div class="label">Надпись</div>
            <div class="control">
                <input type="text" class="not_in_stock_info_label" field="not_in_stock_info_label" value="{NOT_IN_STOCK_INFO_LABEL}">
            </div>
        </div>
        <!-- / -->

        <!-- stock_info_value_label -->
        <div class="row l2">
            <div class="label">Надпись перед значением</div>
            <div class="control">
                <input type="text" class="stock_value_label" field="stock_value_label" value="{CONTENT}">
            </div>
        </div>
        <!-- / -->

        <div class="row l2">
            <div class="label">Если есть под заказ</div>
            <div class="control">
                {IN_UNDER_ORDER_DISPLAY_TOGGLE}
                <!-- if in_under_order_info_enabled -->
                {IN_UNDER_ORDER_INFO_MODE_SWITCHER}
                <!-- / -->
            </div>
        </div>

        {*

            under_order

        *}

        <!-- if in_under_order_label_control -->
        <div class="row l3">
            <div class="label">Надпись</div>
            <div class="control">
                <input type="text" class="in_under_order_info_label" field="in_under_order_info_label" value="{IN_UNDER_ORDER_INFO_LABEL}">
            </div>
        </div>
        <!-- / -->

        <div class="row l2">
            <div class="label">Если нет под заказ</div>
            <div class="control">
                {NOT_IN_UNDER_ORDER_DISPLAY_TOGGLE}
                <!-- if not_in_under_order_info_enabled -->
                {NOT_IN_UNDER_ORDER_INFO_MODE_SWITCHER}
                <!-- / -->
            </div>
        </div>

        <!-- if not_in_under_order_label_control -->
        <div class="row l3">
            <div class="label">Надпись</div>
            <div class="control">
                <input type="text" class="not_in_under_order_info_label" field="not_in_under_order_info_label" value="{NOT_IN_UNDER_ORDER_INFO_LABEL}">
            </div>
        </div>
        <!-- / -->

        <!-- under_order_info_value_label -->
        <div class="row l2">
            <div class="label">Надпись перед значением</div>
            <div class="control">
                <input type="text" class="under_order_value_label" field="under_order_value_label" value="{CONTENT}">
            </div>
        </div>
        <!-- / -->

        {*

            stock info common

        *}

        <div class="row l2">
            <div class="label">Округление</div>
            <div class="control">
                {STOCK_ROUNDING_TOGGLE}
                <!-- if stock_rounding_enabled -->
                {STOCK_ROUNDING_MODE_SWITCHER}
                <!-- / -->
            </div>
        </div>

    </div>

    <div class="sep"></div>

    <div class="appearance">
        <div class="row">
            <div class="label">Шаблон плитки</div>
            <div class="control">
                {TEMPLATE_SELECTOR}
            </div>
        </div>

        <div class="row">
            <div class="label">Размер картинки</div>
            <div class="control image_size">
                <div class="inputs">
                    <input type="text" class="image_dimension" field="width" value="{IMAGE_WIDTH}">
                    <input type="text" class="image_dimension" field="height" value="{IMAGE_HEIGHT}">
                </div>
                {IMAGE_RESIZE_MODE_SWITCHER}
            </div>
        </div>
    </div>
    
</div>
