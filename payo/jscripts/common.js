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

function MP_onreturn (json) {
	status=json.collection_status; 
	reference=json.external_reference;
	$.ajax({
		url: 'auxiliar.php?op=cc_payment&checkout=payed&status=' + status + '&reference=' + reference,
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-payment" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">Pagos</h4>';
			html += '      </div>';
			html += '      <div class="modal-body" id="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);
			$('#loading').remove();
			$('#modal-payment').modal('show');
		}
	});				
}

function MP_conreturn (json) {
	status=json.collection_status; 
	reference=json.external_reference;
	$.ajax({
		url: 'auxiliar.php?op=cot_payment&checkout=payed&status=' + status + '&reference=' + reference,
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-payment" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">Pagos</h4>';
			html += '      </div>';
			html += '      <div class="modal-body" id="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);
			$('#loading').remove();
			$('#modal-payment').modal('show');
		}
	});				
}

function MP_rate(){
	var MPImporte = getFieldFloatValue("input-importe");
	var MPRate = getFieldFloatValue("MPrate");
	importe = document.getElementById("importe");
	Rate = document.getElementById("Rate");
	Rate.value = round(MPImporte * (MPRate), 2);
	importe.value = round(MPImporte * (1+MPRate), 2);
}

function getFieldFloatValue(fieldId) {
	return parseFloat(document.getElementById(fieldId).value.replace("\,","."));
}

function round(n,dec) {
	X = n * Math.pow(10,dec);
	X= Math.round(X);
	return (X / Math.pow(10,dec)).toFixed(dec);
}

function resetValues(form)
{
  for(var i = 0; i < form.elements.length; i++) {
	if(form.elements[i].type == "text") { form.elements[i].value = "";}
  }
}


$(document).ready(function() {
	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();

		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});


	// Menu
	$('#menu .dropdown-menu').each(function() {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 5) + 'px');
		}
	});


	// Checkout
	$(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function(e) {
		if (e.keyCode == 13) {
			$('#collapse-checkout-option #button-login').trigger('click');
		}
	});

	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});
});

// Cart add remove functions
var cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'auxiliar.php?op=procesar&cotizacion=add_item',
			type: 'post',
			cache : false,
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
					location = json['redirect'];
				}

				if (json['success']) {
				//	$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					// Need to set timeout otherwise it wont update the total
					setTimeout(function () {
						$('#cart > button').load('auxiliar.php?op=procesar&cotizacion=info&cotizanum='+ json['cotizanum'] );
					}, 100);

					$('html, body').animate({ scrollTop: 0 }, 'slow');

					$('#cart > ul').load('auxiliar.php?op=procesar&cotizacion=info&cotizanum='+ json['cotizanum'] + ' ul li');
				}
			},
	        error: function(xhr, ajaxOptions, thrownError) {
							$('#alert-zone > span').html('<div class="alert alert-danger">' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

	            //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
		});
	},
	'remove': function(key, product_id) {
		$.ajax({
			url: 'auxiliar.php?op=procesar&cotizacion=remove_item',
			type: 'post',
			cache: false,
			data: 'cotizanum=' + key + '&itemnum=' + product_id,
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
				
				//if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
				//	location = 'index.php?route=checkout/cart';
				//} else {
					$('#cart > ul').load('auxiliar.php?op=procesar&cotizacion=info&cotizanum='+ key +' ul li');
				//}
				
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
			url: 'auxiliar.php?op=procesar&wishlist=add',
			type: 'post',
			cache: false,
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {

				if (json['success']) {
					$('#wishlist > button').tooltip("destroy");
					setTimeout(function () {
						$('#wishlist').html('<button class="btn" type="button" data-toggle="tooltip" title="Eliminar de Favoritos" onclick="wishlist.remove(\'' + product_id + '\');"><i class="fa fa-star" style="color:orange"></i></button>');
						$('#wishlist > button').tooltip();
					}, 100);

				}

			},
	        error: function(xhr, ajaxOptions, thrownError) {
	            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
		});
	},
	'remove': function(product_id) {
		$.ajax({
			url: 'auxiliar.php?op=procesar&wishlist=remove',
			type: 'post',
			cache: false,
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {

				if (json['success']) {
					$('#wishlist > button').tooltip("destroy");
					setTimeout(function () {
						$('#wishlist').html('<button class="btn" type="button" data-toggle="tooltip" title="Agregar a Favoritos" onclick="wishlist.add(\'' + product_id + '\');"><i class="fa fa-star"></i></button>');
						$('#wishlist > button').tooltip();
					}, 100);
				}

			},
	        error: function(xhr, ajaxOptions, thrownError) {
	            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	        }
		});

	}
}

$(document).delegate('#button-cart', 'click', function(e) {
	e.preventDefault();
	$.ajax({
		url: 'auxiliar.php?op=procesar&cotizacion=add_item',
		type: 'post',
		cache: false,
		data: $('#product input[type=\'text\'], #product input[type=\'number\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			//$('.alert, .text-danger').remove();
			//$('.form-group').removeClass('has-error');

			if (json['error']) {
				$('#alert-zone > span').html('<div class="alert alert-danger"><i class=\"fa fa-exclamation-circle\"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				//alert(json['error']);
			}

			if (json['success']) {
				$('#alert-zone > span').html('<div class="alert alert-success"><i class=\"fa fa-thumbs-o-up\"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			
				$('#input-quantity').val('');
				setTimeout(function () {
					$('#cart > button').load('auxiliar.php?op=procesar&cotizacion=info&cotizanum='+ json['cotizanum'] );
				}, 100);
				$('#cart > ul').load('auxiliar.php?op=procesar&cotizacion=info&cotizanum='+ json['cotizanum'] + ' ul li');
			}
		},
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});
});

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		cache: false,
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


/* View Cuotas */
$(document).delegate('.cuotas_lnk', 'click', function(e) {
	e.preventDefault();

	$('#modal-cuotas').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		cache: false,
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-cuotas" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).attr('title') + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-cuotas').modal('show');
		}
	});
});


/* View Product Page */
$(document).delegate('.view_product', 'click', function(e) {
	e.preventDefault();

	$('#modal-product').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		cache : false,
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-product" class="modal">';
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

			$('#modal-product').modal('show');
		}
	});
});

$(document).delegate('#edit_product', 'click', function(e) {
	e.preventDefault();

	$('#modal-product').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		cache : false,
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-product" class="modal">';
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

			$('#modal-product').modal('show');
		}
	});
});

/* View Telefonos */
$(document).delegate('.phone_lnk', 'click', function(e) {
	e.preventDefault();

	$('#modal-phones').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		cache: false,
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-phones" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + 'Tel&eacute;fonos' + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-phones').modal('show');
		}
	});
});

/* Payment */
$(document).delegate('.payment', 'click', function(e) {
	e.preventDefault();

	$('#modal-payment').remove();
	$('#loading').remove();
	var element = this;

	el = $('<div id="loading"><div></div></div>').appendTo('body');
	
	$.ajax({
		url: $(element).attr('href'),
		cache : false,
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-payment" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">Pagos</h4>';
			html += '      </div>';
			html += '      <div class="modal-body" id="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);
			$('#loading').remove();
			$('#modal-payment').modal('show');
		}
	});
});

$(document).delegate('#button-payment-next', 'click', function(e) {
	e.preventDefault();
	var element = this;
	$.ajax({
		url: 'auxiliar.php?op=cc_payment&checkout=pay',
		type: 'post',
		cache : false,
		data: $('#ctacte input[type=\'text\'], #ctacte input[type=\'number\']'),
		dataType: 'html',
		beforeSend: function() {
			$('#button-payment-next').button('loading');
		},
		success: function(data) {
			$('#modal-body').html(data);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	    },
	});
});


function PayPedido(cotizanum,importe){
//$(document).delegate('.payment-cot', 'click', function(e) {
	//e.preventDefault();

	$('#modal-payment').remove();
	$('#loading').remove();
	var element = this;

	el = $('<div id="loading"><div></div></div>').appendTo('body');
	
	$.ajax({
		url: 'auxiliar.php?op=cot_payment',
		cache : false,
		type: 'get',
		data: 'cotizanum=' + cotizanum + "&importe=" + importe, 
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-payment" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">Pagos</h4>';
			html += '      </div>';
			html += '      <div class="modal-body" id="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);
			$('#loading').remove();
			$('#modal-payment').modal('show');
		}
	});
};

$(document).delegate('#button-cot-payment-next', 'click', function(e) {
	e.preventDefault();
	var element = this;
	$.ajax({
		url: 'auxiliar.php?op=cot_payment&checkout=pay',
		type: 'post',
		cache : false,
		data: $('#ctacte input[type=\'text\'], #ctacte input[type=\'number\'], #ctacte input[type=\'hidden\']'),
		dataType: 'html',
		beforeSend: function() {
			$('#button-payment-next').button('loading');
		},
		success: function(data) {
			$('#modal-body').html(data);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	    },
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
