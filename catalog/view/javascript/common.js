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
			url += '&search=' + encodeURIComponent(value).replace(/\(/g, "%28").replace(/\)/g, "%29");
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

	if($('body').hasClass('parent-menu-non-clickable')) {
		//Mobile menu link with child will toggle submenu instead of open link
		$('#sidr  ul  li.has-children > a').on('click',function(e){
			e.preventDefault();
			$(this).next().trigger('click');
		});
	}

	if($('body').hasClass('parent-menu-non-clickable')) {
		//Desktop header menu if got child will not open link
		$(document).on('click','#main-menu  li  a.has-submenu',function(e){
			e.preventDefault();
		});
	}
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

					//FACEBOOK EVENT - ADDTOCART
					if(json['facebookevent_status']){
						if (typeof fbq == 'function') {
							fbq('track', 'AddToCart', {
								content_name: json['content_name'], 
								content_ids: json['content_ids'],
								content_type: json['content_type'],
								value: json['price'],
								currency: json['currency']
							});
						}else{
							console.log('Pixel not found');
						}
					}
					//FACEBOOK EVENT - ADDTOCART END

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
					//FACEBOOK EVENT - ADDTOWISHLIST
					if(json['facebookevent_status']){
						if (typeof fbq == 'function') {
							fbq('track', 'AddToWishlist', {
								content_name: json['content_name'], 
								content_ids: json['content_ids'],
								content_type: json['content_type'],
								value: json['price'],
								currency: json['currency']
							});
						}else{
							console.log('Pixel not found');
						}
					}
					//FACEBOOK EVENT - ADDTOWISHLIST END

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
					$('#compare-total').html(json['total']);

					swal({
						title: json['success_title'],
						html: json['success'],
						type: "success"
					});
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

/*from alpha.js*/

// Multilevel Accordion for Mobile menu
jQuery(document).ready(function(){var e=$(".cd-accordion-menu");e.length>0&&e.each(function(){$(this).on("change",'input[type="checkbox"]',function(){var e=$(this);console.log(e.prop("checked")),e.prop("checked")?e.siblings("ul").attr("style","display:none;").slideDown(300):e.siblings("ul").attr("style","display:block;").slideUp(300)})})});

// Quantity Increment
function increment(t) {UpdateQuantity(t.find(".input-number"), !0) } 
function descrement(t) { UpdateQuantity(t.find(".input-number"), !1) } 
function UpdateQuantity(t,n){var i=getQuantity(t);if(isNaN(i)) {i = 0}i+=1*(n?1:-1),1>i&&(i=1),t.attr("value",i.toString()).val(i.toString())}
function getQuantity(t){var n=parseInt(t.val());return("NaN"==typeof n||1>n)&&(n=1),n}
function quantity_increment(t){UpdateQuantity(t.find(".product-quantity"),!0)}function quantity_decrement(t){UpdateQuantity(t.find(".product-quantity"),!1)}
/* minified */
function postalcode(e,a,l,s){if($(e).length){var t=null;$(e).on("keyup",function(r){if(r.preventDefault(),r.stopPropagation(),Number($(e).val())&&6==$(e).val().length){var n=$(e);if(1==n.length)"INPUT"==$(n[0]).prop("tagName")&&(n.parent().addClass("relative"),n.parent().append('<i class="loading-prefix-js fa fa-spinner fa-pulse absolute-center-right"></i>'));t&&t.abort(),t=$.get("https://developers.onemap.sg/commonapi/search?returnGeom=Y&getAddrDetails=Y&pageNum=1&searchVal="+$(e).val(),function(e){"results"in e&&e.found>0&&(block=e.results[0].BLK_NO,street=e.results[0].ROAD_NAME,building=e.results[0].BUILDING,"NIL"==street&&(street=""),"NIL"==building&&(building=""),a&&l&&s?($(a).val(street),$(l).val(building),$(s).val(block)):a&&l?($(a).val(block+" "+street),$(l).val(building)):a&&(address=e.results[0].ADDRESS,address_only=address.replace(e.results[0].POSTAL,""),$(a).val(address_only))),$(".loading-prefix-js").remove()})}})}$(".loading-prefix-js").remove()}
function view_password(a){if(a){var s=$(a).prev();"password"==s.attr("type")?s.attr("type","text"):s.attr("type","password"),$(a).hasClass("fa-eye")?$(a).removeClass("fa-eye").addClass("fa-eye-slash"):$(a).hasClass("fa-eye-slash")&&$(a).removeClass("fa-eye-slash").addClass("fa-eye")}}

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

$(window).load(function(){
	// Hide Header on on scroll down
	var didScroll;
	var lastScrollTop = 0;
	var delta = 5;
	var navbarHeight = $('.fixed-header').outerHeight();

	$(window).scroll(function(event){
		didScroll = true;
	});

	setInterval(function() {
		if (didScroll) {
			hasScrolled();
			didScroll = false;
		}
	}, 250);

	function hasScrolled() {
		var st = $(this).scrollTop();
		
		// Make sure they scroll more than delta
		if(Math.abs(lastScrollTop - st) <= delta)
			return;
		
		if (st > lastScrollTop && st > navbarHeight){
			// Scroll Down
			$('.fixed-header').addClass('hide-header');
			$('.filters-wrap').addClass('hide-filter');
		} else {
			// Scroll Up
			if(st + $(window).height() < $(document).height()) {
				$('.fixed-header').removeClass('hide-header');
				$('.filters-wrap').removeClass('hide-filter');
			}
		}
		
		lastScrollTop = st;
	}
});

/* minified */
function numbers_only(){$(".input-number").each(function(){$(this).keydown(function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13])||65==e.keyCode&&(!0===e.ctrlKey||!0===e.metaKey)||67==e.keyCode&&(!0===e.ctrlKey||!0===e.metaKey)||88==e.keyCode&&(!0===e.ctrlKey||!0===e.metaKey)||e.keyCode>=35&&e.keyCode<=39||(e.shiftKey||e.keyCode<48||e.keyCode>57)&&(e.keyCode<96||e.keyCode>105)&&e.preventDefault()})})}function initializeMobileNav(){$("#mobileNav").sidr({onOpen:function(){left=$("#sidr").width(),$(".fixed-header").css("left",left),$("body").addClass("sidr-custom-open")},onClose:function(){$(".fixed-header").css("left",0),$("body").addClass("closing")},onCloseEnd:function(){$("body").removeClass("closing"),$("body").removeClass("sidr-custom-open")}}),$('[data-toggle="jquery-accordion"]').accordion()}function cl(e){console.log(e)}jQuery(document).ready(function(){$().UItoTop({easingType:"easeOutQuint"})}),function(e){e.fn.UItoTop=function(t){var o=e.extend({text:"To Top",min:200,inDelay:600,outDelay:400,containerID:"ToTop",containerHoverID:"ToTopHover",scrollSpeed:800,easingType:"linear"},t),n="#"+o.containerID,i="#"+o.containerHoverID;e("body").append('<span id="'+o.containerID+'">'+o.text+"</span>"),e(n).hide().click(function(t){e("html, body").animate({scrollTop:0},o.scrollSpeed),t.preventDefault()}).prepend('<span id="'+o.containerHoverID+'"></span>').hover(function(){e(i,this).stop().animate({opacity:1},600,"linear")},function(){e(i,this).stop().animate({opacity:0},700,"linear")}),e(window).scroll(function(){var t=e(window).scrollTop();void 0===document.body.style.maxHeight&&e(n).css({position:"absolute",top:e(window).scrollTop()+e(window).height()-50}),t>o.min?e(n).fadeIn(o.inDelay):e(n).fadeOut(o.Outdelay)}),initializeMobileNav()}}(jQuery),$(document).delegate('[data-toggle="modal-content"]',"click",function(e){e.preventDefault(),$("#modal-content-custom").remove();let t=$(this).text();$(this).attr("data-title").length&&(t=$(this).attr("data-title")),$.ajax({url:$(this).attr("href"),type:"get",dataType:"html",success:function(e){html='<div id="modal-content-custom" class="modal">',html+='  <div class="modal-dialog">',html+='    <div class="modal-content">',html+='      <div class="modal-header">',html+='        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>',html+='        <h4 class="modal-title">'+t+"</h4>",html+="      </div>",html+='      <div class="modal-body">'+e+"</div>",html+="    </div",html+="  </div>",html+="</div>",$("body").append(html),$("#modal-content-custom").modal("show")}})});
