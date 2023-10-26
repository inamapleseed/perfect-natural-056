// var loading = {
// 	timeout_period : 300000,
// 	timeoutObj : null,
// 	start : function(msg) {
// 		$( "body" ).addClass ( "loading" );
// 		if(this.timeoutObj){
// 			clearTimeout(this.timeoutObj);
// 			this.timeoutObj = null;
// 		}
// 		this.timeoutObj = setTimeout(function () {
// 		alert("This action took too long to respond. You may want to refresh the page and try again.");
// 		}, this.timeout_period);
// 	},

// 	end : function() {
// 		clearTimeout ( this.timeoutObj );
// 		this.timeoutObj = null;
// 		$( "body" ).removeClass ( "loading" );
// 	}
// };

function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}

$(document).ready(function() {
	$('.dropdown-submenu a[href=\'#\']').on('click', function(e){
		e.preventDefault();
	});

	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();

		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});

	// Currency
	$('#form-currency .currency-select').on('click', function(e) {
		e.preventDefault();

		$('#form-currency input[name=\'code\']').val($(this).attr('name'));

		$('#form-currency').submit();
	});

	// Language
	$('#form-language .language-select').on('click', function(e) {
		e.preventDefault();

		$('#form-language input[name=\'code\']').val($(this).attr('name'));

		$('#form-language').submit();
	});

	/* Search */
	var url = $('base').attr('href');
	$('.search-custom button').on('click', function(){
		var value = $(this).prev().val();
		if ($('body').hasClass('short_hand')) {
			url += 'search';
		}
		else {
			url += 'index.php?route=product/search';
		}

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('.search-custom input[name=\'search\']').on('keydown', function (e) {
		if (e.keyCode == 13) {
			$(this).next().trigger('click');
		}
	});

	// Checkout
	$(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function(e) {
		if (e.keyCode == 13) {
			$('#collapse-checkout-option #button-login').trigger('click');
		}
	});

	// tooltips on hover
	$('.desktop [data-toggle=\'tooltip\']').tooltip({container: 'body'});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('.desktop [data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});

});

// Cart add remove functions
var clicked = 0;
var cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
				fbq('track', 'AddToCart');	
			},
			success: function(json) {
				$('.alert, .text-danger').remove();

				if (json['redirect']) {
					// comment below to prevent issue like footer alignment gone when adding product to cart in product listing
				//	$('body').removeAttr('style');
					setTimeout(function(){
						location = json['redirect'];
					}, 1000);
				}

				if (json['success']) {				
					// Need to set timeout otherwise it wont update the total
					setTimeout(function () {
						$('#cart-quantity-total').text(json['total_quantity']);
						$('#cart-total').text(json['total']);
					}, 100);

					swal({
						title: json['success_title'],
						html: json['success'],
						type: "success"
					});

					if (getURLVar('route') == 'checkout/cart') {
						setTimeout(function () {
							location.reload();
						}, 1000);
					}

					$('#cart > ul').load('index.php?route=common/cart/info ul > *');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart-quantity-total').text(json['total_quantity']);
					$('#cart-total').text(json['total']);
				}, 100);

				if ($('body.short_hand').length ){
					if (location.toString().indexOf('cart') > 1 || location.toString().indexOf('checkout') > 1 ){
						location.reload();
					}
					else{
						$('#cart > ul').load('index.php?route=common/cart/info ul > *');

						swal({
							title: json['success_remove_title'],
							html: json['success'],
							type: "success"
						});
					}
				}
				else{
					if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout' || getURLVar('route') == 'quickcheckout/checkout') {
						location = 'index.php?route=checkout/cart';
					} else {
						$('#cart > ul').load('index.php?route=common/cart/info ul > *');

						swal({
							title: json['success_remove_title'],
							html: json['success'],
							type: "success"
						});
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul > *');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var wishlist = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					// $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					swal({
						title: json['success_title'],
						html: json['success'],
						type: "success"
					});
				}

				$('#wishlist-total span').html(json['total']);
				$('#wishlist-total').attr('title', json['total']);
				if($('.product_wishlist_'+product_id+' .fa').hasClass("fa-heart-o")){
                    $('.product_wishlist_'+product_id+' .fa').removeClass("fa-heart-o");
                    $('.product_wishlist_'+product_id+' .fa').addClass("fa-heart");
                }else{
                    $('.product_wishlist_'+product_id+' .fa').removeClass("fa-heart");
                    $('.product_wishlist_'+product_id+' .fa').addClass("fa-heart-o");
                }

				$('#wishlist-total span').html(json['total']);
				$('#wishlist-total').attr('title', json['total']);
				
				fbq('track', 'AddToWishlist');

				//$('html, body').animate({ scrollTop: 0 }, 'slow');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

var compare = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=product/compare/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
				$('.alert').remove();

				if (json['success']) {
					// $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#compare-total').html(json['total']);

					swal({
						title: json['success_title'],
						html: json['success'],
						type: "success"
					});

					//$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function() {

	}
}

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});

// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();

			$.extend(this, option);

			$(this).attr('autocomplete', 'off');

			// Focus
			$(this).on('focus', function() {
				this.request();
			});

			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				value = $(event.target).parent().attr('data-value');

				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function() {
				var pos = $(this).position();

				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});

				$(this).siblings('ul.dropdown-menu').show();
			}

			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function(json) {
				html = '';

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}

					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}

					// Get all the ones with a categories
					var category = new Array();

					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}

							category[json[i]['category']]['item'].push(json[i]);
						}
					}

					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this).siblings('ul.dropdown-menu').html(html);
			}

			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

		});
	}
})(window.jQuery);

// product change image when hover - product/category
$(document).ready(function() {
	$('.product-block')
	  .mouseout(function() {
	  	if ($(this).find('.img2').length > 0) {
		    $(this).find('.img2').hide();
		    $(this).find('.img1').show();
	    }
	  })
	  .mouseover(function() {
	  	if ($(this).find('.img2').length > 0) {
		    $(this).find('.img1').hide();
		    $(this).find('.img2').show();
		}
	  });
});
// product change image when hover - product/category

// options in product list
$(document).ready(function() {
	$(document).on('click', '.btn-cart', function() {
		var product_id = $(this).data('product-id');
		var current_route = $(this).data('current-route');
		if($('#input-quantity-' + product_id).val() > 0) {
			$.ajax({
				url: 'index.php?route=checkout/cart/add',
				type: 'post',
				data: $('#product-' + product_id + ' input[type=\'text\'], #product-' + product_id + ' input[type=\'hidden\'], #product-' + product_id + ' input[type=\'radio\']:checked, #product-' + product_id + ' input[type=\'checkbox\']:checked, #product-' + product_id + ' select, #product-' + product_id + ' textarea'),
				dataType: 'json',
				success: function (json) {

					$('.alert.alert-danger, .text-danger').remove();
					$('.form-group').removeClass('has-error');

					if (json['redirect']) {
						// comment below to prevent issue like footer alignment gone when adding product to cart in product listing
					//	$('body').removeAttr('style');
						setTimeout(function(){
							location = json['redirect'];
						}, 1000);
					}


					if (json['error']) {
						if (json['error']['option']) {
							for (i in json['error']['option']) {
								var element = $('#input-option' + i.replace('_', '-'));

								if (element.parent().hasClass('input-group')) {
									element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								} else {
									element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								}
							}
						}

						if (json['error']['recurring']) {
							$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
						}

						// Highlight any found errors
						$('.text-danger').parent().addClass('has-error');
					}

					if (json['success']) {
						//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						swal({
							title: json['success_title'],
							html: json['success'],
							type: "success"
						});

						if (getURLVar('route') == 'checkout/cart') {
							setTimeout(function () {
								location.reload();
							}, 1000);
						}

						setTimeout(function () {
							$('#cart-quantity-total').text(json['total_quantity']);
							$('#cart-total').text(json['total']);
						}, 100);

						$('#cart > ul').load('index.php?route=common/cart/info ul > *');
					}

					if(json['error_stock_add']){
						swal({
							title: json['error_stock_add_title'],
							html: json['error_stock_add'],
							type: "error"
						});
					}

					if(json['error_outofstock']){
						swal({
							title: json['error_outofstock_title'],
							html: json['error_outofstock'],
							type: "error"
						});
					}

					if(json['error_minstock']){
						swal({
							title: json['error_minstock_title'],
							html: json['error_minstock'],
							type: "error"
						});
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});
	$(document).on('click', '.btn-enquiry', function() {
		var product_id = $(this).data('product-id');
		if($('#input-quantity-' + product_id).val() > 0) {
			$.ajax({
				url: 'index.php?route=enquiry/cart/add',
				type: 'post',
				data: $('#product-' + product_id + ' input[type=\'text\'], #product-' + product_id + ' input[type=\'hidden\'], #product-' + product_id + ' input[type=\'radio\']:checked, #product-' + product_id + ' input[type=\'checkbox\']:checked, #product-' + product_id + ' select, #product-' + product_id + ' textarea'),
				dataType: 'json',
				beforeSend: function () {
					$('.btn-enquiry').button('loading');
				},
				complete: function () {
					$('.btn-enquiry').button('reset');
				},
				success: function (json) {
					$('.alert, .text-danger').remove();
					$('.form-group').removeClass('has-error');

					if (json['redirect']) {
						// comment below to prevent issue like footer alignment gone when adding product to cart in product listing
						// $('body').removeAttr('style');
						setTimeout(function(){
							location = json['redirect'];
						}, 1000);
					}

					if (json['error']) {
						if (json['error']['option']) {
							for (i in json['error']['option']) {
								var element = $('#input-option' + i.replace('_', '-'));

								if (element.parent().hasClass('input-group')) {
									element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								} else {
									element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								}
							}
						}

						if (json['error']['recurring']) {
							$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
						}

						// Highlight any found errors
						$('.text-danger').parent().addClass('has-error');
					}

					if (json['success']) {
						//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						swal({
							title: json['success_title'],
							html: json['success'],
							type: "success"
						});

						setTimeout(function () {
							$('#enquiry-quantity-total').text(json['total_quantity']);
							$('#enquiry-total').text(json['total']);
						}, 100);

						$('#enquiry > ul').load('index.php?route=common/enquiry/info ul > *');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});
});
// options in product list

// Enquiry add remove functions
var enquiry = {
    'add': function (product_id, quantity) {
        $.ajax({
            url: 'index.php?route=enquiry/cart/add',
            type: 'post',
            data: 'product_id=' + product_id + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1),
            dataType: 'json',
            beforeSend: function () {
                $('#enquiry > button').button('loading');
            },
            complete: function () {
                $('#enquiry > button').button('reset');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    // $('body').removeAttr('style');
                    setTimeout(function () {
                        location = json['redirect'];
                    }, 1000);
                }

                if (json['success']) {
                    // Need to set timeout otherwise it wont update the total
                    setTimeout(function () {
                        $('#enquiry-quantity-total').text(json['total_quantity']);
                        $('#enquiry-total').text(json['total']);
                    }, 100);

                    swal({
                        title: json['success_title'],
                        html: json['success'],
                        type: "success"
                    });

                    $('#enquiry > ul').load('index.php?route=common/enquiry/info ul > *');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'remove': function (key) {
        $.ajax({
            url: 'index.php?route=enquiry/cart/remove',
            type: 'post',
            data: 'key=' + key,
            dataType: 'json',
            beforeSend: function () {
                $('#enquiry > button').button('loading');
            },
            complete: function () {
                $('#enquiry > button').button('reset');
            },
            success: function (json) {
                // Need to set timeout otherwise it wont update the total
                setTimeout(function () {
                    $('#enquiry-quantity-total').text(json['total_quantity']);
                    $('#enquiry-total').text(json['total']);
                }, 100);

                if ($('body.short_hand').length) {
                    if (location.toString().indexOf('cart') > 1 || location.toString().indexOf('checkout') > 1) {
                        location.reload();
                    }
                    else {
                        $('#enquiry > ul').load('index.php?route=common/enquiry/info ul > *');

                        swal({
                            title: json['success_remove_title'],
                            html: json['success'],
                            type: "success"
                        });
                    }
                }
                else {
                    if (getURLVar('route') == 'enquiry/cart' || getURLVar('route') == 'enquiry/checkout' || getURLVar('route') == 'quickenquiry/checkout') {
                        location = 'index.php?route=enquiry/cart';
                    } else {
                        $('#enquiry > ul').load('index.php?route=common/enquiry/info ul > *');

                        swal({
                            title: json['success_remove_title'],
                            html: json['success'],
                            type: "success"
                        });
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
}

// If value 1 only, then value 1 is address
// If value 1&2, then value 1 is street, and value 2 is block/unit
// If value 1,2,3 then value 1 is street, value 2 is building, value 3 is block/unit

// Sample return data
/*
	response{
	results: [
	0: [
	ADDRESS"158 KALLANG WAY PERFORMANCE BUILDING SINGAPORE 349245"
	BLK_NO:"158"
	BUILDING:"PERFORMANCE BUILDING"
	LATITUDE:"1.32320025483415"
	LONGITUDE:"103.876549935471"
	LONGTITUDE:"103.876549935471"
	POSTAL:"349245"
	ROAD_NAME:"KALLANG WAY"
	SEARCHVAL:"PERFORMANCE BUILDING"
	X:"32811.2183294455"
	Y:"33938.3202846274"
	]
	]
	}
*/

// Multilevel Accordion for Mobile menu
jQuery(document).ready(function(){var e=$(".cd-accordion-menu");e.length>0&&e.each(function(){$(this).on("change",'input[type="checkbox"]',function(){var e=$(this);console.log(e.prop("checked")),e.prop("checked")?e.siblings("ul").attr("style","display:none;").slideDown(300):e.siblings("ul").attr("style","display:block;").slideUp(300)})})});

// Quantity Increment
function increment(t) {UpdateQuantity(t.find(".input-number"), !0) } 
function descrement(t) { UpdateQuantity(t.find(".input-number"), !1) } 
function UpdateQuantity(t,n){var i=getQuantity(t);if(isNaN(i)) {i = 0}i+=1*(n?1:-1),1>i&&(i=1),t.attr("value",i.toString()).val(i.toString())}
function getQuantity(t){var n=parseInt(t.val());return("NaN"==typeof n||1>n)&&(n=1),n}
function quantity_increment(t){UpdateQuantity(t.find(".product-quantity"),!0)}function quantity_decrement(t){UpdateQuantity(t.find(".product-quantity"),!1)}

function postalcode(ele, value1, value2, value3){
	var loading_tpl = '<i class="loading-prefix-js fa fa-spinner fa-pulse absolute-center-right"></i>';
	if($(ele).length){
		var requesting = null;
		$(ele).on("keyup", function(e){

			e.preventDefault();
			e.stopPropagation();

			if(Number($(ele).val()) && $(ele).val().length == 6){

				var target_src = $(ele);

				if(target_src.length == 1) {
					var html = $(target_src[0]).prop("tagName");
					if(html == 'INPUT'){
						target_src.parent().addClass('relative');
						target_src.parent().append(loading_tpl);
					}
				}
				
				if(requesting){
					requesting.abort();
				}

				requesting = $.get("https://developers.onemap.sg/commonapi/search?returnGeom=Y&getAddrDetails=Y&pageNum=1&searchVal=" + $(ele).val(), function(response){
					if('results' in response && response.found > 0){
						block = response.results[0].BLK_NO;
						street = response.results[0].ROAD_NAME;
						building = response.results[0].BUILDING;
						
						//cl(response); // console.log
						if(value1 && value2 && value3){ 
							$(value1).val(street);
							$(value2).val(building);
							$(value3).val(block);
						}
						else if(value1 && value2){
							$(value1).val(block+" "+street);
							$(value2).val(building);
						}
						else if (value1){ 
							address = response.results[0].ADDRESS; // As a whole
							address_only = address.replace(response.results[0].POSTAL, "");
							
							$(value1).val(address_only);
						}
						else{
							// No value
						}
					}

					$('.loading-prefix-js').remove();
				});
			}

		});
	}
	$('.loading-prefix-js').remove();
}

function view_password(el){
	if (!el) return;
	var _input = $(el).prev();
	if (_input.attr('type') == 'password'){
		_input.attr('type', 'text');
	}
	else{
		_input.attr('type', 'password');
	}

	// Change icon
	if($(el).hasClass('fa-eye')){
		$(el).removeClass('fa-eye').addClass('fa-eye-slash');
	}
	else if($(el).hasClass('fa-eye-slash')){
		$(el).removeClass('fa-eye-slash').addClass('fa-eye');
	}
}

$(document).ready(function () {

	var safari_animation_fix = setTimeout(function(){
		$('.fixed-header').css({'left':'0'});
		$('.page_transition').css({'opacity':'0'});
	}, 100);

	$('#main-menu').smartmenus({
		subMenusSubOffsetY: -1
	});

	$('input[type="password"]').each(function () {

		$view_password = '<i style="[STYLE]" class="fa fa-eye pointer absolute view-password" aria-hidden="true" onclick="view_password(this);" ontouchstart="view_password(this);" ontouchend="view_password(this);" ></i>';
		//$view_password = '<i style="[STYLE]" class="fa fa-eye pointer absolute view-password" aria-hidden="true" onmousedown="view_password(this);" onmouseup="view_password(this);" ></i>';

		// Element Control
		el_password = $(this);

		// Label-control
		el_password_label = el_password.prev(); //cl(el_password_label);

		// Btn-group
		el_password_btn = el_password.next('.input-group-btn');

		el_password_parent = el_password.parent();
		// End Element Control

		// Position Control
		el_parent_padding_right = el_password_parent.css('padding-right');

		el_parent_padding_right = parseInt(el_parent_padding_right);

		el_password_parent.addClass('relative');

		let half_input_height = el_password.outerHeight() * 0.5;

		el_password_padding_right = el_password.css('padding-right');

		el_password_padding_right = parseInt(el_password_padding_right);

		right_input_padding =  el_parent_padding_right;

		if (el_password_label.length && el_password_label.is(':visible')){
			let label_height = $(el_password_label).outerHeight(true); 
			label_height = parseInt(label_height);
			half_input_height += label_height;
		}

		if (el_password_btn.length){
			let label_width = $(el_password_btn).outerWidth(true); 
			label_width = parseInt(label_width);
			right_input_padding += label_width;
		}
		// End Position Control
		
		$view_password_style = "transform: translateY(-50%);";
		$view_password_style += "height:" + el_password.innerHeight() + "px;";
		$view_password_style += "top:" + half_input_height + "px;";
		$view_password_style += "right:" + right_input_padding + "px;";
		$view_password_style += "padding: 0px " + el_password_padding_right + "px;";

		$view_password = $view_password.replace('[STYLE]', $view_password_style);
		
		el_password.after($view_password);

		var eye_render_width = el_password.next('i').width();
			eye_render_width = parseInt(eye_render_width);
			eye_render_width += (el_password_padding_right * 2);
		el_password.css('padding-right', eye_render_width);
	});

	// turn off because unused
	// var $link_condition = 'a[href]';
	// 	$link_condition += ':not([href="#"])';
	// 	$link_condition += ':not([href^="tel"])';
	// 	$link_condition += ':not([href^="mailto"])';
	// 	$link_condition += ':not([href^="fax"])';	//	 Mobile not supported
	// 	$link_condition += ':not([download])';
	// 	$link_condition += ':not(.agree)';
	// 	$link_condition += ':not(.esc)';
	// 	$link_condition += ':not(.colorbox)';
	// 	$link_condition += ':not([data-toggle="tab"])';
	// 	$link_condition += ':not([data-toggle="collapse"])';
	// 	$link_condition += ':not([data-toggle="dropdown"])';
	// 	$link_condition += ':not([data-toggle="modal-content"])';
	// 	$link_condition += ':not([target])';

	// var transition_duration = $('body').css('transition-duration');
	// var transition_delay = $('body').css('transition-delay');
	// var transition_in_seconds = 0; // Step up delay for javascript transition
	
	// if(transition_duration.indexOf('ms') > 0){ 
	// 	transition_in_seconds = parseInt(transition_duration.replace('ms', ''));
	// }
	// else if(transition_duration.indexOf('s') > 0){
	// 	transition_in_seconds = parseFloat(transition_duration.replace('s', '')) * 1000; 
	// }

	// if(transition_delay.indexOf('ms') > 0){ 
	// 	transition_in_seconds += parseInt(transition_delay.replace('ms', ''));
	// }
	// else if(transition_delay.indexOf('s') > 0){
	// 	transition_in_seconds += parseFloat(transition_delay.replace('s', '')) * 1000; 
	// }
	
	// $($link_condition).each(function () {
	// 	$(this).on('click', function (e) {
	// 		e.preventDefault();
	// 		var href = this.href;
			
	// 		//$('body').removeAttr('style');
	// 		$('body').addClass('transition-state');

	// 		setTimeout(function(){
	// 			location = href;
	// 		}, transition_in_seconds);
	// 	});
	// });
	// turn off because unused
});

var last_click = null;
$(window).load(function () {
	
	$('body').addClass('done');
	$(window).resize(function () {
		padding_top = $('.fixed-header').outerHeight(true);
		padding_bottom = $('#footer-area').outerHeight(true);
		$('body').css({ 'padding-top': padding_top, 'padding-bottom': padding_bottom});
	}).resize();
	
	$('#cart .dropdown-menu, #cart .dropdown-menu *').on('click', function (e) {
		e.stopPropagation();
	});
	
	$('.cke_iframe').each(function(){
		ele = $(this);
		iframe = ele.attr('data-cke-realelement');
		iframe = decodeURIComponent(iframe);
		// cl(iframe);
		ele.after(iframe);
		ele.remove();
	});

	$('#side-categories .toggle').on('click', function(e){
	
		e.preventDefault();
		ele = $(this).parent();
		
		if(ele.hasClass('active')){
			ele.removeClass('active');
		}
		else{ 
			if(ele.hasClass('.level-1')){
				$('.level-1.active').removeClass('active');
			}
			else if(ele.hasClass('.level-2')){
				$('.level-2.active').removeClass('active');
			}
			
			ele.addClass('active');
		}
	});

	$(".list-group .input-group .input-group-addon").click(function (e) {
		e.preventDefault();
		e.stopPropagation();
		
		if (this != last_click) {
			
			last_click = this;
			
			// Level Parent
			parent = $(this).data("parent");
			//cl(parent);
			$("." + parent).removeClass("active");
			
			// Level child
			level = $(this).data("level");
			$("." + level).stop().slideUp(300);
			
			$(this).prev().addClass("active");
			
			child = $("." + this.id);
			if (child.length) {
				child.stop().slideDown(300);
			}
		} else {
			
		}
	});
	
	numbers_only();

});


function numbers_only(){
	$('.input-number').each(function () {
		$(this).keydown(function (e) {
			//cl(e.keyCode);
			// Allow: backspace, delete, tab, escape, enter and .
			// 110 - dot (.)
			// 190 - angle right (>)

			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
				// Allow: Ctrl/cmd+A
				(e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
				// Allow: Ctrl/cmd+C
				(e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
				// Allow: Ctrl/cmd+X
				(e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
				// Allow: home, end, left, right
				(e.keyCode >= 35 && e.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});
	});
}


/* UItoTop jQuery */
jQuery(document).ready(function () {
	$().UItoTop({
		easingType: 'easeOutQuint'
	});
});

(function ($) {
	$.fn.UItoTop = function (options) {
		var defaults = {
			text: 'To Top',
			min: 200,
			inDelay: 600,
			outDelay: 400,
			containerID: 'ToTop',
			containerHoverID: 'ToTopHover',
			scrollSpeed: 800,
			easingType: 'linear'
		};
		
		var settings = $.extend(defaults, options);
		var containerIDhash = '#' + settings.containerID;
		var containerHoverIDHash = '#' + settings.containerHoverID;
		$('body').append('<span id="' + settings.containerID + '">' + settings.text + '</span>');
		$(containerIDhash).hide().click(function (event) {
			$('html, body').animate({
				scrollTop: 0
			}, settings.scrollSpeed);
			event.preventDefault();
		})
		.prepend('<span id="' + settings.containerHoverID + '"></span>')
		.hover(function () {
			$(containerHoverIDHash, this).stop().animate({
				'opacity': 1
			}, 600, 'linear');
			}, function () {
			$(containerHoverIDHash, this).stop().animate({
				'opacity': 0
			}, 700, 'linear');
		});
		
		$(window).scroll(function () {
			var sd = $(window).scrollTop();
			if (typeof document.body.style.maxHeight === "undefined") {
				$(containerIDhash).css({
					'position': 'absolute',
					'top': $(window).scrollTop() + $(window).height() - 50
				});
			}
			if (sd > settings.min)
			$(containerIDhash).fadeIn(settings.inDelay);
			else
			$(containerIDhash).fadeOut(settings.Outdelay);
		});

		initializeMobileNav();
	};
})(jQuery);

function initializeMobileNav(){

	$('#mobileNav').sidr({
		onOpen: function(){
			left = $('#sidr').width();
			$('.fixed-header').css('left', left);
			$('body').addClass('sidr-custom-open');
		},
		onClose: function(){
			$('.fixed-header').css('left', 0);
			$('body').addClass('closing');
		},
		onCloseEnd: function(){
			$('body').removeClass('closing');
			$('body').removeClass('sidr-custom-open');
		}
	});

	$('[data-toggle="jquery-accordion"]').accordion();
}
function cl(x) {
	console.log(x);
}

/* Google Map */

var mapObj = [];

function gmap() {
	
	var infowindow = [];
	var service = [];
	var marker = [];
	
	$("[data-toggle=\'gmap\']").each(function (index, value) {
		cmap = $(this);
		loadMapMarker(mapObj, index, cmap);
	});
	
	$("#accordion").on('shown.bs.collapse', function () {
		reintGmap();
	});
}

function loadMapMarker(mapObj, index, cmap) {
	
	var lat = cmap.data('lat');
	var lng = cmap.data('lng');
	var id = cmap.data('id');
	var store = cmap.data('store');
	var address = cmap.data('address');
	
	var place = { lat: lat, lng: lng };
	mapObj[index] = new google.maps.Map(
	document.getElementById(id), {
		zoom: 16,
		center: place
	});
	
	var contentString =
	'<b>' + store + '</b>' +
	'<p>' + address + '</p>';
	
	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});
	
	var marker = new google.maps.Marker({
		position: place,
		map: mapObj[index],
		title: store
	});
	
	marker.addListener('click', function () {
		infowindow.open(mapObj[index], marker);
	});
	
}

function reintGmap() {
	var center = null;
	$.each(mapObj, function (index, value) {
		center = mapObj[index].getCenter();
		google.maps.event.trigger(mapObj[index], "resize");
		mapObj[index].setCenter(center);
	});
}



// Data-toggle: modal-content
$(document).delegate('[data-toggle="modal-content"]', 'click', function (e) {
	e.preventDefault();

	$('#modal-content-custom').remove();

	var element = this;

	let title = $(element).text();
	if ($(element).attr('data-title').length){
		title = $(element).attr('data-title');
	}
	
	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function (data) {
			html = '<div id="modal-content-custom" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + title + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-content-custom').modal('show');
		}
	});
});
