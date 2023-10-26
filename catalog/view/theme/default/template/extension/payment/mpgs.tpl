<style>
iframe {
    background-color: rgba(255,255,255,0.9) !important;
}
</style>
<script type="text/javascript">
    function errorCallback(error) {
          console.log(JSON.stringify(error));
    }
    function cancelCallback() {
          console.log('Payment cancelled');
    }

    // Checkout.configure({
    //     merchant: '<?=$merchant?>',
    //     session: { 
    //         id: '<?=$session_id?>'
    //     },
    //     order: {
    //         amount: function() {
    //             //Dynamic calculation of amount
    //             return 80 + 20;
    //         },
    //         currency: 'SGD',
    //         description: 'Ordered goods',
    //         id: '<?=date("YmdHis")?>'
    //     },
    //     interaction: {
    //         operation: 'PURCHASE',
    //         merchant: {
    //             name: 'FDS',
    //             address: {
    //                 line1: '200 Sample St',
    //                 line2: '1234 Example Town'
    //             }
    //         }
    //     }
    // });
    Checkout.configure({
        merchant: '<?=$merchant_id?>',
        session: { 
            id: '<?=$session_id?>'
        },
        order: {
            amount: function() {
                //Dynamic calculation of amount
                return <?=$order_total?>;
            },
            currency: '<?=$currency?>',
            description: '<?=$merchant_name?> - Mastercard payment for order id <?=$order_id?>',
            id: '<?=$order_id?>'
        },
        interaction: {
            displayControl : {
              billingAddress : 'HIDE'
            },
            operation: 'PURCHASE',
            merchant: {
                name: '<?=$merchant_name?>',
                // address: {
                //     line1: '200 Sample St',
                //     line2: '1234 Example Town'
                // }
            }
        }
    });
</script>
<style type="text/css">
    .animsition {
        z-index: 2;
    }
</style>

<div class="pull-right"> 
    <input type="button" value="Make Payment" id="button-confirm" class="btn btn-primary" data-loading-text="loading..." onclick="Checkout.showLightbox();"> 
    <?php /* <input type="button" value="Make Payment Page" id="button-confirm" class="btn btn-primary" data-loading-text="loading..." onclick="Checkout.showPaymentPage();"> */ ?>
</div>


<script type="text/javascript">
    $('#button-confirm').on('click',function(){
        $(this).prop('disabled', true);
        $(this).button('loading');
    });
</script>
