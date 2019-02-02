<div class="{__NODE_ID__}" vmd5="{VMD5}" instance="{__INSTANCE__}" xpack="{XPACK}" item_key="{CART_ITEM_KEY}">

    <div class="top section">
        <div class="image">{IMAGE}</div>
        <div class="name_container">
            <div class="name" style="font-size: {NAME_FONT_SIZE}px">{NAME}</div>
        </div>
    </div>

    <div class="bottom section">
        <!-- alt_price -->
        <div class="alt_price">
            {VALUE} руб.<!-- alt_price/units -->/{CONTENT}<!-- / -->
        </div>
        <!-- / -->

        <!-- price -->
        <div class="price">
            {VALUE} руб.<!-- price/units -->/{CONTENT}<!-- / -->
        </div>
        <!-- / -->

        <!-- zeroprice_label -->
        <div class="zeroprice_label">{VALUE}</div>
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

        <!-- under_order_info -->
        <div class="stock_info {MODE} {STOCK_CLASS}">
            <div class="content">
                <!-- under_order_info/value -->
                <span class="label">{LABEL}</span>
                <span class="value">{VALUE}<!-- under_order_info/value/units --> {CONTENT}<!-- / --></span>
                <!-- / -->
                <!-- under_order_info/label -->
                <span class="label">{CONTENT}</span>
                <!-- / -->
            </div>
        </div>
        <!-- / -->

        <!-- quantify -->
        <div class="quantify">
            <div class="dec button" hover="hover">
                <div class="icon fa fa-minus"></div>
            </div>
            <div class="value" hover="hover">
                <input type="text" value="{VALUE}">
            </div>
            <div class="inc button" hover="hover">
                <div class="icon fa fa-plus"></div>
            </div>
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
            <div class="add_to_cart_button {IN_CART_CLASS}">{LABEL}</div>
            <!-- cartbutton/items_count -->
            <div class="items_count">
                {ITEMS_COUNT}
            </div>
            <!-- / -->
        </div>
        <!-- / -->

        <!-- if not cartbutton -->
        <div class="bottom_spacer"></div>
        <!-- / -->

    </div>

</div>
