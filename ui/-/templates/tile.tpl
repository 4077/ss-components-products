<div class="{__NODE_ID__}" vmd5="{VMD5}" instance="{__INSTANCE__}" xpack="{XPACK}" product_id="{PRODUCT_ID}">

    <div class="top section">
        <div class="image">{IMAGE}</div>
        <div class="name_container">
            <div class="name" style="font-size: {NAME_FONT_SIZE}px">{NAME}</div>
        </div>
    </div>

    <div class="bottom section">
        {*<!-- alt_price -->
        <div class="alt_price">
            {VALUE} руб.<!-- alt_price/units -->/{CONTENT}<!-- / -->
        </div>
        <!-- / -->*}

        <!-- price_without_discount -->
        <div class="price_without_discount">
            <div class="value">
                {VALUE} руб.<!-- price_without_discount/units -->{* ps reformat bug *}/{CONTENT}<!-- / -->
            </div>
            <div class="tag">
                <div class="icon fa fa-tag"></div>
                <div class="overlay"></div>
                <div class="label">-{DISCOUNT}%</div>
            </div>
        </div>
        <!-- / -->

        <!-- price -->
        <div class="price">
            {VALUE} руб.<!-- price/units -->{* ps reformat bug *}/{CONTENT}<!-- / -->
        </div>
        <!-- / -->

        <!-- zeroprice_label -->
        <div class="zeroprice_label">{VALUE}</div>
        <!-- / -->

        <!-- stock -->
        <div class="stock">
            <!-- stock/group -->
            <div class="group {MODE} {TYPE} {CLASS}">
                <!-- stock/group/value -->
                <span class="label">{LABEL}</span>
                <span class="value">{VALUE}{UNITS}</span>
                <!-- / -->
                <!-- stock/group/label -->
                <span class="label">{CONTENT}</span>
                <!-- / -->
            </div>
            <!-- / -->
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
            <!-- if quantify/total_cost/has_value -->
            <div class="value"><span>{VALUE}</span> руб.</div>
            <!-- / -->
            <!-- if not quantify/total_cost/has_value -->
            <div class="value">— руб.</div>
            <!-- / -->
        </div>
        <!-- / -->
        <!-- / -->

        <!-- cartbutton -->
        <div class="add_to_cart_button_container">
            <div class="add_to_cart_button {IN_CART_CLASS}" data="{DATA}">{LABEL}</div>
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
