<div class="{__NODE_ID__}" instance="{__INSTANCE__}">

    <div class="options">

        {* name *}

        <div class="row">
            <div class="label">Приоритет наименования</div>
            <div class="control">
                {NAME_PRIORITY_SWITCHER}
            </div>
        </div>

        {* cart *}

        <div class="row">
            <div class="label">Кнопка корзины</div>
            <div class="control">
                {CARTBUTTON_TOGGLE}
            </div>
        </div>

        <!-- cartbutton -->
        <div class="row l2">
            <div class="label">Надпись</div>
            <div class="control">
                {LABEL_INPUT}
            </div>
        </div>

        <div class="row l2">
            <div class="label">Селектор количества</div>
            <div class="control">
                {QUANTIFY_TOGGLE}
            </div>
        </div>
        <!-- / -->

        {* price *}

        <div class="row">
            <div class="label">Показывать цену</div>
            <div class="control">
                {PRICE_TOGGLE}
            </div>
        </div>

        <!-- price -->
        <div class="row_group">
            <div class="row l2">
                <div class="label">Округление</div>
                <div class="control">
                    {ROUNDING_TOGGLE}
                    {ROUNDING_MODE_SWITCHER}
                </div>
            </div>

            <div class="row l2">
                <div class="label">Надпись вместо нулевой цены</div>
                <div class="control">
                    {ZEROPRICE_LABEL_TOGGLE}
                </div>
            </div>

            <!-- price/zeroprice_label -->
            <div class="row l3">
                <div class="label">Надпись</div>
                <div class="control">
                    {LABEL_INPUT}
                </div>
            </div>
            <!-- / -->

            <div class="row l2">
                <div class="label">Показывать скидку</div>
                <div class="control">
                    {DISCOUNT_TOGGLE}
                </div>
            </div>
        </div>
        <!-- / -->

        {* units *}

        <div class="row">
            <div class="label">Единицы продажи</div>
            <div class="control">
                {SELL_UNITS_TOGGLE}
            </div>
        </div>

        <div class="row l2">
            <div class="label">Приоритет единицам</div>
            <div class="control">
                {TRY_FORCE_UNITS_TOGGLE}
            </div>
        </div>

        <!-- try_force_units -->
        <div class="row l3">
            <div class="label">Список</div>
            <div class="control">
                {LIST_INPUT}
            </div>
        </div>
        <!-- / -->

        {*<div class="row">
            <div class="label">Показывать другие единицы</div>
            <div class="control">
                {OTHER_UNITS_DISPLAY_TOGGLE}
            </div>
        </div>*}

        {* stock info *}

        <div class="row stock_info">
            <div class="label">Информация о наличии</div>
            <div class="control">
                {STOCK_TOGGLE}
            </div>
            {*<!-- if stock -->*}
            {*<div class="add_group_control">*}
                {*<div class="button">*}
                    {*<div class="icon fa fa-plus"></div>*}
                {*</div>*}
                {*<div class="selector">*}
                    {*{STOCK_WAREHOUSE_GROUP_SELECTOR}*}
                {*</div>*}
            {*</div>*}
            {*<!-- / -->*}
        </div>

        <!-- stock -->
        <div class="row_group">
            <div class="row l2">
                <div class="label">Округление</div>
                <div class="control">
                    {ROUNDING_TOGGLE}
                    {ROUNDING_MODE_SWITCHER}
                </div>
            </div>

            {*

                selected group

            *}

            <div class="row l2 warehouse_group">
                <div class="label">Для выбранной группы/для всех, если не выбрана</div>
            </div>

            <div class="row l3">
                <div class="label">Если есть в наличии</div>
                <div class="control">
                    {SELECTED_GROUP_IN_STOCK_DISPLAY_TOGGLE}
                    {SELECTED_GROUP_IN_STOCK_MODE_SWITCHER}
                </div>
            </div>

            <!-- stock/selected_group_in_stock_label -->
            <div class="row l4">
                <div class="label">Надпись</div>
                <div class="control">
                    {LABEL_INPUT}
                </div>
            </div>
            <!-- / -->

            <div class="row l3">
                <div class="label">Если нет в наличии</div>
                <div class="control">
                    {SELECTED_GROUP_NOT_IN_STOCK_DISPLAY_TOGGLE}
                    {SELECTED_GROUP_NOT_IN_STOCK_MODE_SWITCHER}
                </div>
            </div>

            <!-- stock/selected_group_not_in_stock_label -->
            <div class="row l4">
                <div class="label">Надпись</div>
                <div class="control">
                    {LABEL_INPUT}
                </div>
            </div>
            <!-- / -->

            <!-- stock/selected_group_value_label -->
            <div class="row l3">
                <div class="label">Надпись перед значением</div>
                <div class="control">
                    {LABEL_INPUT}
                </div>
            </div>

            <div class="row l4">
                <div class="label">Использовать имя группы если выбрана</div>
                <div class="control">
                    {GROUP_NAME_IF_POSSIBLE_TOGGLE}
                </div>
            </div>

            {*<!-- stock/selected_group_value_label/label -->*}
            {*<div class="row l4">*}
                {*<div class="label">Надпись</div>*}
                {*<div class="control">*}
                    {*{LABEL_INPUT}*}
                {*</div>*}
            {*</div>*}
            {*<!-- / -->*}
            <!-- / -->

            {*

                other groups

            *}

            <div class="row l2 warehouse_group">
                <div class="label">Для остальных групп</div>
            </div>

            <div class="row l3">
                <div class="label">Если есть в наличии</div>
                <div class="control">
                    {OTHER_GROUPS_IN_STOCK_DISPLAY_TOGGLE}
                    {OTHER_GROUPS_IN_STOCK_MODE_SWITCHER}
                </div>
            </div>

            <!-- stock/other_groups_in_stock_label -->
            <div class="row l4">
                <div class="label">Надпись</div>
                <div class="control">
                    {LABEL_INPUT}
                </div>
            </div>
            <!-- / -->

            <div class="row l3">
                <div class="label">Если нет в наличии</div>
                <div class="control">
                    {OTHER_GROUPS_NOT_IN_STOCK_DISPLAY_TOGGLE}
                    {OTHER_GROUPS_NOT_IN_STOCK_MODE_SWITCHER}
                </div>
            </div>

            <!-- stock/other_groups_not_in_stock_label -->
            <div class="row l4">
                <div class="label">Надпись</div>
                <div class="control">
                    {LABEL_INPUT}
                </div>
            </div>
            <!-- / -->

            <!-- stock/other_groups_value_label -->
            <div class="row l3">
                <div class="label">Надпись перед значением</div>
                <div class="control">
                    {LABEL_INPUT}
                </div>
            </div>

            <div class="row l4">
                <div class="label">Использовать имя группы если товар в наличии только в ней</div>
                <div class="control">
                    {GROUP_NAME_IF_POSSIBLE_TOGGLE}
                </div>
            </div>
            <!-- / -->

            {*

                assigned groups

            *}

            {*<!-- stock/warehouse_group -->*}
            {*<div class="row_group">*}
                {*<div class="row l2 warehouse_group">*}
                    {*<div class="label">{GROUP_NAME}</div>*}
                    {*{DISABLE_BUTTON}*}
                {*</div>*}

                {*<div class="row l3">*}
                    {*<div class="label">Если есть в наличии</div>*}
                    {*<div class="control">*}
                        {*{IN_STOCK_DISPLAY_TOGGLE}*}
                        {*{IN_STOCK_MODE_SWITCHER}*}
                    {*</div>*}
                {*</div>*}

                {*<!-- stock/warehouse_group/in_stock_label -->*}
                {*<div class="row l4">*}
                    {*<div class="label">Надпись</div>*}
                    {*<div class="control">*}
                        {*{LABEL_INPUT}*}
                    {*</div>*}
                {*</div>*}
                {*<!-- / -->*}

                {*<div class="row l3">*}
                    {*<div class="label">Если нет в наличии</div>*}
                    {*<div class="control">*}
                        {*{NOT_IN_STOCK_DISPLAY_TOGGLE}*}
                        {*{NOT_IN_STOCK_MODE_SWITCHER}*}
                    {*</div>*}
                {*</div>*}

                {*<!-- stock/warehouse_group/not_in_stock_label -->*}
                {*<div class="row l4">*}
                    {*<div class="label">Надпись</div>*}
                    {*<div class="control">*}
                        {*{LABEL_INPUT}*}
                    {*</div>*}
                {*</div>*}
                {*<!-- / -->*}

                {*<!-- stock/warehouse_group/value_label -->*}
                {*<div class="row l3">*}
                    {*<div class="label">Надпись перед значением</div>*}
                    {*<div class="control">*}
                        {*{LABEL_INPUT}*}
                    {*</div>*}
                {*</div>*}
                {*<!-- / -->*}
            {*</div>*}
            {*<!-- / -->*}
        </div>
        <!-- / -->

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
                    {IMAGE_WIDTH_INPUT}
                    {IMAGE_HEIGHT_INPUT}
                </div>
                {IMAGE_RESIZE_MODE_SWITCHER}
            </div>
        </div>
    </div>

</div>