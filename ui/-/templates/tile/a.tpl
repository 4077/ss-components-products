<div class="{__NODE_ID__} {CLASS}" vmd5="{VMD5}" instance="{__INSTANCE__}">

    <div class="top section">
        <div class="image">{IMAGE}</div>
        <div class="name_container">
            <div class="name" style="font-size: {NAME_FONT_SIZE}px">{NAME}</div>
        </div>
    </div>

    <div class="bottom section">
        <!-- alt_price -->
        <div class="alt_price">
            <div class="label">Цена <!-- alt_price/units -->за {CONTENT}<!-- / --></div>
            <div class="value">{VALUE} руб.</div>
        </div>
        <!-- / -->

        <!-- price -->
        <div class="price">
            <!-- price/units -->
            <div class="label">Цена за {CONTENT}</div>
            <!-- / -->
            <div class="value">{VALUE} руб.</div>
        </div>
        <!-- / -->

        <!-- stock_info -->
        <div class="stock_info {MODE} {STOCK_CLASS}">
            <div class="content">
                <!-- stock_info/value -->
                <span class="label">{LABEL}</span>
                <span class="value">{VALUE}<!-- stock_info/value/units --> {CONTENT}<!-- / --></span>
                <!-- / -->
                <!-- stock_info/label -->
                <span class="label">{CONTENT}</span>
                <!-- / -->
            </div>
        </div>
        <!-- / -->

        <!-- quantify -->
        <div class="quantify">
            {QUANTIFY}
        </div>

        <!-- quantify/total_cost -->
        <div class="total_cost">
            <div class="label">Итого:</div>
            <div class="value">{VALUE} руб.</div>
        </div>
        <!-- / -->
        <!-- / -->

        <!-- cartbutton -->
        <div class="add_to_cart_button_container">
            {BUTTON}
            <!-- cartbutton/products_count -->
            <div class="products_count">
                {PRODUCTS_COUNT}
            </div>
            <!-- / -->
        </div>
        <!-- / -->

        <!-- if not cartbutton -->
        <div class="bottom_spacer"></div>
        <!-- / -->

    </div>

</div>
