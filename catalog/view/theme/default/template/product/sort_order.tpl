<div class="row sorts">
    <div class="flex flex-wrap filters-wrap pd-l15 pd-r15">

        <div class="col-xs-6 text-center hidden-lg hidden-md hidden-sm"> 
            <button id="filter_group_tigger_open" class="btn btn-primary" onclick="$('#filter-groups').addClass('open');"><?= $button_filter; ?></button> 
        </div> 

        <div class="col-md-5 col-sm-6 col-xs-6 sort-con">
            <div class="form-group input-group-b4">
                <div class="input-group-prepend hidden-xs"> 
                    <label class="input-group-text" for="input-sort">Sort by</label>
                </div>
                <select id="input-sort" class="form-control no-custom" onchange="select_handler();">
                <?php foreach ($sorts as $sorts) { ?>
                    <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                        <option value="<?php echo $sorts['value']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $sorts['value']; ?>"><?php echo $sorts['text']; ?></option>
                    <?php } ?>
                <?php } ?>
                </select>
            </div>
        </div>

        <div class="col-md-auto col-sm-6 col-xs-12 front-con">
            <label for="">Show</label>
            <?php foreach ($limits as $limits2) { ?>
                <div class="le-front" onclick="select_handler();" data-value="<?php echo $limits2['value']; ?>"><?php echo $limits2['text']; ?></div>
            <?php } ?>
        </div>

        <div class="hidden">
            <select id="input-limit" class="form-control no-custom">
                <?php foreach ($limits as $limits) { ?>
                    <?php if ($limits['value'] == $limit) { ?>
                        <option value="<?php echo $limits['value']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $limits['value']; ?>"><?php echo $limits['text']; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>

    </div>
</div>
<script>
    // const queryString = window.location.search;
    // const urlParams = new URLSearchParams(queryString);
    // const limit = urlParams.get('limit');

    $(".le-front").on("click", function(e) {
        $("#input-limit").val($(this).data("value"));
        // const le_val = $(this).data("value");
    });
    
</script>
<script type="text/javascript">
    function adjustFitlerPos() {
        $('.filters-wrap').css('top', $('.fixed-header').outerHeight(true));
    }
    function resetFitlerPos() {
        $('.filters-wrap').css('top', 'auto');
    }
    function adjustBannerPos() {
        $('#pg-banner-wrap').css('margin-top', $('.filters-wrap').outerHeight(true));
    }
    function resetBannerPos() {
        $('#pg-banner-wrap').css('margin-top', 0);
    }

    // Note: there is one calling of this function in applyFilter() - extension/module/category filters there
    function handleFilterPos() {
        const width  = window.innerWidth || document.documentElement.clientWidth || 
        document.body.clientWidth;

        if(width <= 767) {
            adjustFitlerPos();
            adjustBannerPos();
        }
        else {
            resetFitlerPos();
            resetBannerPos();
        }

        //console.log('handle filter pos');
    }

    $(window).on('load resize', function(){
        handleFilterPos();
    });

    /* layout toggle */
    $(".js-row").click(function() {
        $(".product-view").addClass('rows');
        $('.js-row').addClass('active');
        $('.js-grid').removeClass('active');
    });

    $(".js-grid").click(function() {
        $(".product-view").removeClass('rows');
        $('.js-grid').addClass('active');
        $('.js-row').removeClass('active');
    });

    $( window ).resize(function() {
        let isMobile = window.matchMedia("only screen and (max-width: 991px)").matches;

        if (isMobile) {
            //Conditional script here
            $(".js-grid").trigger('click');
        }
    });
    /* layout toggle */

</script>