<div class="{__NODE_ID__}" instance="{__INSTANCE__}">

    <!-- name -->
    <div class="name">{CONTENT}</div>
    <!-- / -->

    <!-- description -->
    <div class="description">{CONTENT}</div>
    <!-- / -->

    <div class="tiles">
        <!-- tile -->
        <div class="tile {HIDDEN_CLASS}" n="{N}" item_id="{PRODUCT_ID}">
            <!-- if tile/cp -->
            <div class="cp">
                <div class="l">
                    <!-- tile/status -->
                    <div class="status {STATUS}" title="{STATUS_TITLE}">
                        <div class="icon {STATUS_ICON_CLASS}"></div>
                    </div>
                    <!-- / -->
                </div>
                <div class="r">
                    <!-- tile/not_published_mark -->
                    <div class="not_published_mark {HIDDEN_CLASS}">
                        <div class="fa fa-eye"></div>
                    </div>
                    <!-- / -->
                    {PRODUCT_DIALOG_BUTTON}
                </div>
            </div>
            <!-- / -->
            {CONTENT}
        </div>
        <!-- / -->
    </div>

</div>
