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
            <div class="label">Минимальное наличие</div>
            <div class="control">
                {STOCK_MINIMUM_TOGGLE}
            </div>
        </div>

        <!-- stock_minimum -->
        <div class="row l2">
            <div class="label">Значение</div>
            <div class="control">
                {VALUE_INPUT}
            </div>
        </div>
        <!-- / -->

        <div class="row">
            <div class="label">Фильтр наличия</div>
            <div class="control">
                {STOCK_FILTER_TOGGLE}
                {STOCK_FILTER_MODE_SWITCHER}
            </div>
        </div>

        <!-- stock_filter -->
        <div class="group_row">

            <!-- stock_filter/warehouse_group -->
            <div class="row l2">
                <div class="label">{NAME}</div>
                <div class="control">
                    {TOGGLE}
                </div>
            </div>
            <!-- / -->

        </div>
        <!-- / -->

        <div class="row">
            <div class="label">Фильтр ненулевой цены</div>
            <div class="control">
                {NOTZEROPRICE_FILTER_TOGGLE}
            </div>
        </div>

    </div>

    <div class="tile">
        {TILE}
    </div>

</div>
