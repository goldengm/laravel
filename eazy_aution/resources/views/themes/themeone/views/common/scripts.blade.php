<!-- scripts -->
<script src="{!! asset('public/js/app.js') !!}"></script>
<script src="{!! asset('public/js/jquery-ui.js') !!}"></script>

<!-- owl carousel -->
<script src="{!! asset('public/js/owl.carousel.js') !!}"></script>

<!--- google map-->
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&libraries=geometry&key=AIzaSyCQq_d3bPGfsIAlenXUG5RtZsKZKzOmrMw"></script>

<!--- one signal-->
@if(Request::path() == 'checkout')	
<!------- //paypal -------->

<script type="text/javascript">
window.onload = function(e){ 
 	
	var paypal_public_key = document.getElementById('paypal_public_key').value;
	var acount_type = document.getElementById('paypal_environment').value;
	
	if(acount_type=='Test'){
		var paypal_environment = 'sandbox'
	}else if(acount_type=='Live'){
		var paypal_environment = 'production'
	}
	
     paypal.Button.render({			
		env: paypal_environment, // sandbox | production		
		style: {
            label: 'checkout',
            size:  'small',    // small | medium | large | responsive
            shape: 'pill',     // pill | rect
            color: 'gold'      // gold | blue | silver | black
        },
		
		// PayPal Client IDs - replace with your own
		// Create a PayPal app: https://developer.paypal.com/developer/applications/create
		
		client: {
			sandbox:     paypal_public_key,
			production:  paypal_public_key
		},

		// Show the buyer a 'Pay Now' button in the checkout flow
		commit: true,

		// payment() is called when the button is clicked
		payment: function(data, actions) {
			var payment_currency = document.getElementById('payment_currency').value;
			var total_price = '<?php echo number_format((float)$total_price+0, 2, '.', '');?>';
			
			// Make a call to the REST api to create the payment
			return actions.payment.create({
				payment: {
					transactions: [
						{
							amount: { total: total_price, currency: payment_currency }
						}
					]
				}
			});
		},

		// onAuthorize() is called when the buyer approves the payment
		onAuthorize: function(data, actions) {

			// Make a call to the REST api to execute the payment
			return actions.payment.execute().then(function() {
			   	jQuery('#update_cart_form').prepend('<input type="hidden" name="nonce" value='+JSON.stringify(data)+'>');
				jQuery("#update_cart_form").submit();
			});
		}

	}, '#paypal_button');
};
</script>

<script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script> 
<script type="text/javascript">
jQuery(document).ready(function(e) {
	
	braintree.setup(
		// Replace this with a client token from your server
		" <?php print session('braintree_token')?>",
		"dropin", {
		container: "payment-form"
	});
	
	 
});
</script> 


<script src="{!! asset('public/js/stripe_card.js') !!}" data-rel-js></script> 

<script type="application/javascript">
(function() {
  'use strict';

  var elements = stripe.elements({
    fonts: [
      {
        cssSrc: 'https://fonts.googleapis.com/css?family=Source+Code+Pro',
      },
    ],
    // Stripe's examples are localized to specific languages, but if
    // you wish to have Elements automatically detect your user's locale,
    // use `locale: 'auto'` instead.
    locale: window.__exampleLocale
  });

  // Floating labels
  var inputs = document.querySelectorAll('.cell.example.example2 .input');
  Array.prototype.forEach.call(inputs, function(input) {
    input.addEventListener('focus', function() {
      input.classList.add('focused');
    });
    input.addEventListener('blur', function() {
      input.classList.remove('focused');
    });
    input.addEventListener('keyup', function() {
      if (input.value.length === 0) {
        input.classList.add('empty');
      } else {
        input.classList.remove('empty');
      }
    });
  });

  var elementStyles = {
    base: {
      color: '#32325D',
      fontWeight: 500,
      fontFamily: 'Source Code Pro, Consolas, Menlo, monospace',
      fontSize: '16px',
      fontSmoothing: 'antialiased',

      '::placeholder': {
        color: '#CFD7DF',
      },
      ':-webkit-autofill': {
        color: '#e39f48',
      },
    },
    invalid: {
      color: '#E25950',

      '::placeholder': {
        color: '#FFCCA5',
      },
    },
  };

  var elementClasses = {
    focus: 'focused',
    empty: 'empty',
    invalid: 'invalid',
  };

  var cardNumber = elements.create('cardNumber', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardNumber.mount('#example2-card-number');

  var cardExpiry = elements.create('cardExpiry', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardExpiry.mount('#example2-card-expiry');

  var cardCvc = elements.create('cardCvc', {
    style: elementStyles,
    classes: elementClasses,
  });
  cardCvc.mount('#example2-card-cvc');

  registerElements([cardNumber, cardExpiry, cardCvc], 'example2');
})();
</script> 
@endif 

<script type="application/javascript">

@if(Request::path() != 'shop')	
  jQuery(function() {
    jQuery( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  maxDate: '0',
    });
  });
@endif

jQuery( document ).ready( function () {
	jQuery('#loader').hide();
	
	@if($result['commonContent']['setting'][54]->value=='onesignal')
	 OneSignal.push(function () {
	  OneSignal.registerForPushNotifications();
	  OneSignal.on('subscriptionChange', function (isSubscribed) {
	   if (isSubscribed) {
		OneSignal.getUserId(function (userId) {
		 device_id = userId;
		 //ajax request
		 jQuery.ajax({
			url: '{{ URL::to("/subscribeNotification")}}',
			type: "POST",
			data: '&device_id='+device_id,			
			success: function (res) {},
		});
		 
		 //$scope.oneSignalCookie();
		});
	   }
	  });
	
	 });
	@endif
	
	//load google map
	@if(Request::path() == 'contact-us')		
		initialize();
	@endif	
	
	@if(Request::path() == 'checkout')		
		getZonesBilling();	
		paymentMethods();
	@endif
	wrap_text_1();
	

	$.noConflict();
	
	//stripe_ajax
	jQuery(document).on('click', '#stripe_ajax', function(e){
		jQuery('#loader').css('display','flex');
		jQuery.ajax({
			url: '{{ URL::to("/stripeForm")}}',
			type: "POST",			
			success: function (res) {
				if(res.trim() == "already added"){					
				}else{
					jQuery('.head-cart-content').html(res);	
					jQuery(parent).removeClass('cart');
					jQuery(parent).addClass('active');
				}
				message = "@lang('website.Product is added')";			
				notification(message);
				jQuery('#loader').hide();
			},
		});
	});	
	
	
	//commeents
	jQuery(document).on('focusout','#order_comments', function(e){
		jQuery('#loader').css('display','flex');
		var comments = jQuery('#order_comments').val();
		jQuery.ajax({
			url: '{{ URL::to("/commentsOrder")}}',
			type: "POST",
			data: '&comments='+comments,
			async: false,
			success: function (res) {	
				jQuery('#loader').hide();			
			},
		});		
	});
	
		//hyperpayresponse
		var resposne = jQuery('#hyperpayresponse').val();
		if(typeof resposne  !== "undefined"){
			if(resposne.trim() =='success'){
				jQuery('#loader').css('display','flex');
				jQuery("#update_cart_form").submit();
			}else if(resposne.trim() =='error'){
				jQuery.ajax({
					url: '{{ URL::to("/checkout/payment/changeresponsestatus")}}',
					type: "POST",
					async: false,
					success: function (res) {	
					},
				});
				jQuery('#paymentError').css('display','block');
			}
		}
	
	//cash_on_delivery_button
	jQuery(document).on('click', '#cash_on_delivery_button', function(e){	
		jQuery('#loader').css('display','flex');
		jQuery("#update_cart_form").submit();
	});	
	
	
	//shipping_mehtods_form
	jQuery(document).on('submit', '#shipping_mehtods_form', function(e){
		jQuery('.error_shipping').hide();		
		var checked = jQuery(".shipping_data:checked").length > 0;
		if (!checked){
			jQuery('.error_shipping').show();
			return false;
		}				
	});
	
	//update_cart
	jQuery(document).on('click', '#update_cart', function(e){	
		jQuery('#loader').css('display','flex');
		jQuery("#update_cart_form").submit();
	});	
	
	//shipping_data	
	jQuery(document).on('click', '.shipping_data', function(e){	
		getZonesBilling();		
	});	
	
	//billling method
	jQuery(document).on('click', '#same_billing_address', function(e){		
		if(jQuery(this).prop('checked') == true){
			jQuery("#billing_firstname").val(jQuery("#firstname").val());
			jQuery("#billing_lastname").val(jQuery("#lastname").val());
			jQuery("#billing_company").val(jQuery("#company").val());
			jQuery("#billing_street").val(jQuery("#street").val());
			jQuery("#billing_city").val(jQuery("#city").val());
			jQuery("#billing_zip").val(jQuery("#postcode").val());			
			jQuery("#billing_countries_id").val(jQuery("#entry_country_id").val());
			jQuery("#billing_zone_id").val(jQuery("#entry_zone_id").val());				
			
			jQuery(".same_address").attr('readonly','readonly');
			jQuery(".same_address_select").attr('disabled','disabled');						
		}else{
			jQuery(".same_address").removeAttr('readonly');
			jQuery(".same_address_select").removeAttr('disabled');
		}
	});	
	
	//apply_coupon_cart
	jQuery(document).on('submit', '#apply_coupon', function(e){
		jQuery('#coupon_code').remove('error');
		jQuery('#coupon_require_error').hide();
		jQuery('#loader').css('display','flex');
		
		if(jQuery('#coupon_code').val().length > 0){		
			var formData = jQuery(this).serialize();
			jQuery.ajax({
				url: '{{ URL::to("/apply_coupon")}}',
				type: "POST",
				data: formData,
				success: function (res) {
					var obj = JSON.parse(res);	
					var message = obj.message;
					jQuery('#loader').hide();
					if(obj.success==0){
						jQuery("#coupon_error").html(message).show();
						return false;
					}else if(obj.success==2){			
						jQuery("#coupon_error").html(message).show();
						return false;
					}else if(obj.success==1){						
						window.location.reload(true);	
					}					
				},
			});
		}else{
			jQuery('#loader').css('display','none');
			jQuery('#coupon_code').addClass('error');
			jQuery('#coupon_require_error').show();
			return false;
		}
		jQuery('#loader').hide();
		return false;
	});
	
	//coupon_code
	jQuery(document).on('keyup', '#coupon_code', function(e){
		jQuery("#coupon_error").hide();
		if(jQuery(this).val().length >0){			
			jQuery('#coupon_code').removeClass('error');
			jQuery('#coupon_require_error').hide();
		}else{
			jQuery('#coupon_code').addClass('error');
			jQuery('#coupon_require_error').show();
		}
		
	});
	
	@if(!empty($result['detail']['product_data'][0]->attributes))
		@foreach( $result['detail']['product_data'][0]->attributes as $key=>$attributes_data )	
	@php
		$functionValue = 'attributeid_'.$key; 
		$attribute_sign = 'attribute_sign_'.$key++;
	@endphp
	
	//{{ $functionValue }}();
	function {{ $functionValue }}(){
		var value_price = jQuery('option:selected', ".{{$functionValue}}").attr('value_price');
		jQuery("#{{ $functionValue }}").val(value_price);
	}
		
	//change_options
	jQuery(document).on('change', '.{{ $functionValue }}', function(e){
		
		var {{ $functionValue }} = jQuery("#{{ $functionValue }}").val();
		
		var old_sign = jQuery("#{{ $attribute_sign }}").val();
		
		var value_price = jQuery('option:selected', this).attr('value_price');
		var prefix = jQuery('option:selected', this).attr('prefix');
		var current_price = jQuery('#products_price').val();
		var {{ $attribute_sign }} = jQuery("#{{ $attribute_sign }}").val(prefix);
		
		if(old_sign.trim()=='+'){
			var current_price = current_price - {{ $functionValue }};
		} 
		
		if(old_sign.trim()=='-'){
			var current_price = parseFloat(current_price) + parseFloat({{ $functionValue }});
		}
		
		if(prefix.trim() == '+' ){			
			var total_price = parseFloat(current_price) + parseFloat(value_price);		
		}
		if(prefix.trim() == '-' ){
			total_price = current_price - value_price;
		}
		
		jQuery("#{{ $functionValue }}").val(value_price);
		jQuery('#products_price').val(total_price);
		var qty = jQuery('.qty').val();
		var products_price = jQuery('#products_price').val();
		var total_price = qty * products_price;
		jQuery('.total_price').html('<?=$web_setting[19]->value?>'+total_price.toFixed(2));
		
	});
	@endforeach
@endif

	//change language
	function changeLanguage(locale){
		jQuery('#loader').css('display','flex');								
		jQuery.ajax({			
			url: '{{ URL::to("/language")}}',			
			type: "POST",			
			data: '&locale='+locale,
			//dataType:"json",			
						
			success: function (res) {	
				window.location.reload(true);		
			},			
		});	
		
	};
	
	jQuery( function() {
		jQuery.widget( "custom.iconselectmenu", jQuery.ui.selectmenu, {
		  _renderItem: function( ul, item ) {
			var li = jQuery( "<li>" ),
			  wrapper = jQuery( "<div>", { text: item.label } );
	 
			if ( item.disabled ) {
			  li.addClass( "ui-state-disabled" );
			}
	 
			jQuery( "<span>", {
			  style: item.element.attr( "data-style" ),
			  "class": "ui-icon " + item.element.attr( "data-class" )
			})
			  .appendTo( wrapper );
	 
			return li.append( wrapper ).appendTo( ul );
		  }
		});
	 

		
		jQuery("#change_language")
		.iconselectmenu({
		  create: function (event, ui) {
			  var widget = jQuery(this).iconselectmenu("widget");
			  $span = jQuery('<span id="' + this.id + '_image" class="ui-selectmenu-image"> ').html("&nbsp;").appendTo(widget);
			  $span.attr("style", jQuery(this).children(":selected").data("style"));
			  
		  },		  		 
		  change: function (event, ui) {
			  jQuery("#" + this.id + '_image').attr("style", ui.item.element.data("style"));
			  var locale = jQuery(this).val();
			  changeLanguage(locale);
			  
		  }
		}).iconselectmenu("menuWidget").addClass("ui-menu-icons customicons");
		
  } );
  
	jQuery( function() {
    	jQuery( "#category_id" ).selectmenu();
		jQuery( ".attributes_data" ).selectmenu();
	});
	
	//is_liked
	jQuery(document).on('click', '.is_liked', function(e){
		var products_id = jQuery(this).attr('products_id');
		var selector = jQuery(this);
		jQuery('#loader').css('display','flex');	
		var user_count = jQuery('#wishlist-count').html();		
		jQuery.ajax({			
			url: '{{ URL::to("/likeMyProduct")}}',			
			type: "POST",			
			data: '&products_id='+products_id,			
						
			success: function (res) {			
				//jQuery('.head-cart-content').html(res);	
				var obj = JSON.parse(res);	
				var message = obj.message;
				
				if(obj.success==0){
					
				}else if(obj.success==2){
					jQuery(selector).removeClass('fa-heart-o');
					jQuery(selector).addClass('fa-heart');
					jQuery(selector).children('span').html(obj.total_likes);
					jQuery('#wishlist-count').html(parseInt(user_count)+ parseInt(1));	
					jQuery(selector).children('.badge').html(obj.total_likes);
				}else if(obj.success==1){
					jQuery(selector).removeClass('fa-heart');
					jQuery(selector).addClass('fa-heart-o');
					
					jQuery(selector).children('span').html(obj.total_likes);
					jQuery('#wishlist-count').html(user_count-1);	
					jQuery(selector).children('.badge').html(obj.total_likes);
				}	
				jQuery('#loader').hide();
				notification(message);
						
			},			
		});	
		
	});
	
	//wishlist_liked
	jQuery(document).on('click', '.wishlist_liked', function(e){
		var products_id = jQuery(this).attr('products_id');
		var selector = jQuery(this).parents('.product').remove();
		jQuery('#loader').css('display','flex');	
		var user_count = jQuery('#wishlist-count').html();		
		jQuery.ajax({			
			url: '{{ URL::to("/likeMyProduct")}}',			
			type: "POST",			
			data: '&products_id='+products_id,			
						
			success: function (res) {				
				var obj = JSON.parse(res);	
				var message = obj.message;
				
				if(obj.success==0){
					
				}else if(obj.success==2){
					//jQuery(selector).children('span').html(obj.total_likes);
					jQuery('#wishlist-count').html(parseInt(user_count)+ parseInt(1));	
					//jQuery(selector).children('span').html(obj.total_likes+" @lang('website.Likes')");
				}else if(obj.success==1){
					//jQuery(selector).addClass(hidden);
					
					//jQuery(selector).children('span').html(obj.total_likes);
					var count = user_count-1;
					jQuery('#wishlist-count').html(count);
					
					if(count==0){
						jQuery(".loaded_content").hide();
						jQuery("#loaded_content_empty").show();
					}else{						
						jQuery('.showing_record').html(count);	
						jQuery('.showing_total_record').html(parseInt(jQuery('.showing_total_record').html())-parseInt(1));	
					}
					//website.product is not added to your wish list
					//jQuery(selector).children('span').html(obj.total_likes+" @lang('website.Likes')");
				}	
				jQuery('#loader').hide();
				notification(message);
						
			},			
		});	
		
	});
	
	@if(session('direction')=='rtl')
		var direction = true; 
	@else
		var direction = false;
	@endif
	//product slider
	jQuery(".owl_featured").owlCarousel({
		margin:10,
		loop:false,
		nav:true,
		rtl:direction,
		responsive:{
			0:{
				items:1
			},
			576:{
				items:2
			},
			768:{
				items:3
			},
			992:{
				items:4
			},
			1199:{
				items:5
			}
		}
	});
	

	jQuery("#owl_special").owlCarousel({
		loop:false,
		margin:10,
		nav:true,
		rtl:direction,
		responsive:{
			0:{
				items:1
			},
			576:{
				items:2
			},
			768:{
				items:3
			},
			992:{
				items:4
			},
			1199:{
				items:5
			}
		}
	});

	jQuery("#owl_liked").owlCarousel({
		loop:false,
		margin:10,
		nav:true,
		rtl:direction,
		responsive:{
			0:{
				items:1
			},
			576:{
				items:2
			},
			768:{
				items:3
			},
			992:{
				items:4
			},
			1199:{
				items:5
			}
		}
	});
	
	jQuery("#owl_brands").owlCarousel({
		loop:false,
		margin:10,
		nav:true,
		rtl:direction,
		responsive:{
			0:{
				items:1
			},
			576:{
				items:1
			},
			768:{
				items:3
			},
			992:{
				items:4
			},
			1199:{
				items:6
			}
		}
	});
	
	jQuery("#owl_flashsale").owlCarousel({
		loop:false,
		margin:10,
		nav:true,
		rtl:direction,
		responsive:{
			0:{
				items:1
			},
			576:{
				items:2
			},
			768:{
				items:3
			},
			992:{
				items:4
			},
			1199:{
				items:5
			}
		}
	});

	jQuery( ".owl-prev").html('<i class="fa fa-angle-left"></i>');
	jQuery( ".owl-next").html('<i class="fa fa-angle-right"></i>');


//change_language
jQuery(document).on('click', '.change_language', function(e){
	jQuery('#loader').css('display','flex');
	var languages_id = jQuery(this).attr('languages_id');
	jQuery.ajax({
		url: '{{ URL::to("/change_language")}}',
		type: "POST",
		data: '&languages_id='+languages_id,
		success: function (res) {
			jQuery('#loader').hide();
		},
	});
});	


//sortby
jQuery(document).on('change', '.sortby', function(e){	
	jQuery('#loader').css('display','flex');
	jQuery("#load_products_form").submit();
});
	

//load more products
jQuery(document).on('click', '#load_products', function(e){	
	jQuery('#loader').css('display','flex');
	var page_number = jQuery('#page_number').val();
	var total_record = jQuery('#total_record').val();
	var formData = jQuery("#load_products_form").serialize();
	jQuery.ajax({
		url: '{{ URL::to("/filterProducts")}}',
		type: "POST",
		data: formData,
		success: function (res) {
			if(jQuery.trim().res==0){						
				jQuery('#load_products').hide();
				jQuery('#loaded_content').show();
			}else{
				page_number++;
				jQuery('#page_number').val(page_number);
				jQuery('#listing-products').append(res);
				var record_limit = jQuery('#record_limit').val();
				var showing_record = page_number*record_limit;
				if(total_record<=showing_record){
					jQuery('.showing_record').html(total_record);					
					jQuery('#load_products').hide();
					jQuery('#loaded_content').show();
				}else{
					jQuery('.showing_record').html(showing_record);
				}
			}			
			jQuery('#loader').hide();
			wrap_text_1();
		},
	});
});

//sortby
jQuery(document).on('change', '.sortbywishlist', function(e){	
	jQuery('#loader').css('display','flex');
	jQuery("#load_wishlist_form").submit();
});
	

//load more products
jQuery(document).on('click', '#load_wishlist', function(e){	
	jQuery('#loader').css('display','flex');
	var page_number = jQuery('#page_number').val();
	var formData = jQuery("#load_wishlist_form").serialize();
	jQuery.ajax({
		url: '{{ URL::to("/loadMoreWishlist")}}',
		type: "POST",
		data: formData,
		success: function (res) {
			
			if(jQuery.trim().res==0){						
				jQuery('#load_wishlist').hide();
				jQuery('#loaded_content').show();
			}else{
				page_number++;
				jQuery('#page_number').val(page_number);
				jQuery('#listing-wishlist').append(res);
				
				var record_limit = jQuery('#record_limit').val();
				var total_record = jQuery('#total_record').val();
				
				var showing_record = page_number*record_limit;
				if(total_record<=showing_record){
					jQuery('#load_wishlist').hide();
					jQuery('.showing_record').html(total_record);
				}else{
					jQuery('.showing_record').html(showing_record);
				}
			}
			jQuery('#loader').hide();	
			wrap_text_1();
			
		},
	});
});



//sortbynews
jQuery(document).on('change', '.sortbynews', function(e){	
	jQuery('#loader').css('display','flex');
	jQuery("#load_news_form").submit();
});

//load more news
jQuery(document).on('click', '#load_news', function(e){	
	jQuery('#loader').css('display','flex');
	var page_number = jQuery('#page_number').val();
	var formData = jQuery("#load_news_form").serialize();
	jQuery.ajax({
		url: '{{ URL::to("/loadMoreNews")}}',
		type: "POST",
		data: formData,
		success: function (res) {
			if(jQuery.trim().res==0){						
				jQuery('#load_news').hide();
				jQuery('#loaded_content').show();
			}else{
				page_number++;
				jQuery('#page_number').val(page_number);
				jQuery('#listing-news').append(res);
				
				var record_limit = jQuery('#record_limit').val();
				var total_record = jQuery('#total_record').val();
				//alert(record_limit);
				var showing_record = page_number*record_limit;
				if(total_record<showing_record){
					jQuery('#load_news').hide();
					jQuery('.showing_record').html(total_record);
				}else{
					jQuery('.showing_record').html(showing_record);
				}
			}
			jQuery('#loader').hide();
		},
	});
});

jQuery(document).on('click', '#apply_options_btn', function(e){	
	if (jQuery('input:checkbox.filters_box:checked').length > 0) {
      	jQuery('#filters_applied').val(1);
		jQuery('#apply_options_btn').removeAttr('disabled');
	} else {
      	jQuery('#filters_applied').val(0);
		jQuery('#apply_options_btn').attr('disabled',true);
    }	
	jQuery('#load_products_form').submit();
	
})

//add-to-Cart with custom options
jQuery(document).on('click', '.add-to-Cart', function(e){	
	jQuery('#loader').css('display','flex');
	var formData = jQuery("#add-Product-form").serialize();
	var url = jQuery('#checkout_url').val();
	var message;
	jQuery.ajax({
		url: '{{ URL::to("/addToCart")}}',
		type: "POST",
		data: formData,
		
		success: function (res) {
			if(res.trim() == "already added"){
				//notification
				message = 'Product is added!';
			}else{
				jQuery('.head-cart-content').html(res);
				message = 'Product is added!';
				jQuery(parent).addClass('active');
			}
				if(url.trim()=='true'){
					window.location.href = '{{ URL::to("/checkout")}}';
				}else{
					jQuery('#loader').css('display','none');
					//window.location.href = '{{ URL::to("/viewcart")}}';
					message = "@lang('website.Product is added')";			
					notification(message);
				}
		},
	});
});

//update-single-Cart with
jQuery(document).on('click', '.update-single-Cart', function(e){	
	jQuery('#loader').css('display','flex');
	var formData = jQuery("#add-Product-form").serialize();
	var url = jQuery('#checkout_url').val();
	var message;
	jQuery.ajax({
		url: '{{ URL::to("/updatesinglecart")}}',
		type: "POST",
		data: formData,
		
		success: function (res) {
			if(res.trim() == "already added"){
				//notification
				message = 'Product is added!';
			}else{
				jQuery('.head-cart-content').html(res);
				message = 'Product is added!';
				jQuery(parent).addClass('active');
			}
				if(url.trim()=='true'){
					window.location.href = '{{ URL::to("/viewcart")}}';
				}else{
					jQuery('#loader').css('display','none');
					//window.location.href = '{{ URL::to("/viewcart")}}';
					//message = "@lang('website.Product is added')";			
					//notification(message);
				}
		},
	});
});

//validate form

jQuery(document).on('submit', '.form-validate', function(e){

	var error = "";
	
	//to validate text field

	jQuery(".field-validate").each(function() {
		if(this.value == '') {
			jQuery(this).closest(".form-group").addClass('has-error');
			jQuery(this).next(".error-content").removeAttr('hidden');
			error = "has error";
		}else{
			jQuery(this).closest(".form-group").removeClass('has-error');
			jQuery(this).next(".error-content").attr('hidden', true);
		}
	});
	
	/*jQuery(".phone-validate").each(function() {
		if(this.value == '' && isNaN(this.value)) {
			jQuery(this).closest(".form-group").addClass('has-error');
			jQuery(this).next(".error-content").removeAttr('hidden');
			error = "has error";
		}else{
			jQuery(this).closest(".form-group").removeClass('has-error');
			jQuery(this).next(".error-content").attr('hidden', true);
		}
	});*/
	
	
	var check = 0;
	jQuery(".password").each(function() {
		var regex = "^\\s+$";
		if(this.value.match(regex)) {
			jQuery(this).closest(".form-group").addClass('has-error');
			jQuery(this).next(".error-content").removeAttr('hidden');
			error = "has error";				
		}else{
			if(check == 1){
				 var res = passwordMatch();

					if(res=='matched'){
						jQuery('.password').closest(".form-group").removeClass('has-error');
						jQuery('#re_password').closest('.re-password-content').children('.error-content-password').add('hidden');
					}else if(res=='error'){
						jQuery('.password').closest(".form-group").addClass('has-error');						
						jQuery('#re_password').closest('.re-password-content').children('.error-content-password').removeAttr('hidden');						
						error = "has error";
					}
				}else{
					jQuery(this).closest(".form-group").removeClass('has-error');
					jQuery(this).next(".error-content").attr('hidden', true);
				}
				 check++;
			}

	});
	

	jQuery(".number-validate").each(function() {
		if(this.value == '' || isNaN(this.value)) {
			jQuery(this).closest(".form-group").addClass('has-error');
			jQuery(this).next(".error-content").removeAttr('hidden');
			error = "has error";
		}else{
			jQuery(this).closest(".form-group").removeClass('has-error');
			jQuery(this).next(".error-content").attr('hidden', true);
		}
	});



	//

	jQuery(".email-validate").each(function() {

		var validEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

		if(this.value != '' && validEmail.test(this.value)) {

			jQuery(this).closest(".form-group").removeClass('has-error');

			jQuery(this).next(".error-content").attr('hidden', true);



		}else{

			jQuery(this).closest(".form-group").addClass('has-error');

			jQuery(this).next(".error-content").removeAttr('hidden');

			error = "has error";

		}

	});

	
	jQuery(".checkbox-validate").each(function() {
		
		if(jQuery(this).prop('checked') == true){
			jQuery(this).closest(".form-group").removeClass('has-error');
			jQuery(this).closest('.checkbox-parent').children('.error-content').attr('hidden', true);						
		}else{
			jQuery(this).closest(".form-group").addClass('has-error');
			jQuery(this).closest('.checkbox-parent').children('.error-content').removeAttr('hidden');

			error = "has error";
		}

	});



	if(error=="has error"){

		return false;

	}



});



//focus form field

jQuery(document).on('keyup focusout change', '.field-validate', function(e){
	if(this.value == '') {		
		jQuery(this).closest(".form-group").addClass('has-error');
		jQuery(this).next(".error-content").removeAttr('hidden');
	}else{
		jQuery(this).closest(".form-group").removeClass('has-error');
		jQuery(this).next(".error-content").attr('hidden', true);
	}
});



//focus form field
jQuery(document).on('keyup', '.number-validate', function(e){
	if(this.value == '' || isNaN(this.value)) {
		jQuery(this).closest(".form-group").addClass('has-error');
		jQuery(this).next(".error-content").removeAttr('hidden');
	}else{
		jQuery(this).closest(".form-group").removeClass('has-error');
		jQuery(this).next(".error-content").attr('hidden', true);
	}
});

//match password
jQuery(document).on('keyup focusout', '.password', function(e){
	var regex = "^\\s+$";
	if(this.value.match(regex)) {			
		jQuery(this).closest(".form-group").addClass('has-error');
		jQuery(this).next(".error-content").removeAttr('hidden');
	}else{
		jQuery(this).closest(".form-group").removeClass('has-error');
		jQuery(this).next(".error-content").attr('hidden', true);
	}
});



jQuery(document).on('keyup focusout', '.email-validate', function(e){

	var validEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

	if(this.value != '' && validEmail.test(this.value)) {
		jQuery(this).closest(".form-group").removeClass('has-error');
		jQuery(this).next(".error-content").attr('hidden', true);
	}else{
		jQuery(this).closest(".form-group").addClass('has-error');
		jQuery(this).next(".error-content").removeAttr('hidden');
		error = "has error";
	}

});


	

	//sorting grid/list
	jQuery(document).on('click','#list',function(){		
		if (!jQuery(this).hasClass('active')) {
			jQuery('#listing-products, .load-more-area').hide();		
			jQuery( '#listing-products' ).removeClass( 'products-3x' );
			jQuery( '#listing-products' ).addClass( 'products-list' );
			jQuery( '#grid' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );		
			jQuery('#listing-products, .load-more-area').fadeIn(1000);	
		}
	});

	jQuery(document).on('click','#grid',function(){	
		if (!jQuery(this).hasClass('active')){ 		
			jQuery('#listing-products, .load-more-area').hide();	
			jQuery( '#listing-products' ).removeClass( 'products-list' );
			jQuery( '#listing-products' ).addClass( 'products-3x' );
			jQuery( '#list' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );
			jQuery('#listing-products, .load-more-area').fadeIn(1000);
		}
	});

	//sorting grid/list
	jQuery(document).on('click','#list_wishlist',function(){		
		if (!jQuery(this).hasClass('active')) {
			jQuery('#listing-wishlist, .load-more-area').hide();		
			jQuery( '#listing-wishlist' ).removeClass( 'products-3x' );
			jQuery( '#listing-wishlist' ).addClass( 'products-list' );
			jQuery( '#grid_wishlist' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );		
			jQuery('#listing-wishlist, .load-more-area').fadeIn(1000);	
		}
	});

	jQuery(document).on('click','#grid_wishlist',function(){	
		if (!jQuery(this).hasClass('active')){ 		
			jQuery('#listing-wishlist, .load-more-area').hide();	
			jQuery( '#listing-wishlist' ).removeClass( 'products-list' );
			jQuery( '#listing-wishlist' ).addClass( 'products-3x' );
			jQuery( '#list_wishlist' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );
			jQuery('#listing-wishlist, .load-more-area').fadeIn(1000);
		}
	});
	
	//sorting grid/list
	jQuery(document).on('click','#list_news',function(){		
		if (!jQuery(this).hasClass('active')) {
			jQuery('#listing-news, .load-more-area').hide();		
			jQuery( '#listing-news' ).removeClass( 'blogs-4x' );
			jQuery( '#listing-news' ).addClass( 'blogs-list' );
			jQuery( '#grid_news' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );		
			jQuery('#listing-news, .load-more-area').fadeIn(1000);	
		}
	});

	jQuery(document).on('click','#grid_news',function(){	
		if (!jQuery(this).hasClass('active')){ 		
			jQuery('#listing-news, .load-more-area').hide();	
			jQuery( '#listing-news' ).removeClass( 'blogs-list' );
			jQuery( '#listing-news' ).addClass( 'blogs-4x' );
			jQuery( '#list_news' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );
			jQuery('#listing-news, .load-more-area').fadeIn(1000);
		}
	});
	
	/*$(".show_commentsandnotes_container").click(function () {
		$('.commentsandnotes_bg').fadeIn(1000, function() {
		   $('.commentsandnotes_bg').addClass('show');
		});
		$('.commentsandnotes_container').fadeIn(1000, function() {
		   $('.commentsandnotes_container').addClass('show');
		});
	});
	$(".commentsandnotes_bg").click(function () {
		$('.commentsandnotes_bg').fadeOut(1000, function() { 
		   $('.commentsandnotes_bg').removeClass('show');
		});
		$('.commentsandnotes_container').fadeOut(1000, function() { 
		   $('.commentsandnotes_container').removeClass('show'); 
		});
	});*/
	

	
	// This button will increment the value
	jQuery('.qtyplus').click(function(e){
		// Stop acting like a button
		e.preventDefault();
		// Get the field name
		fieldName = jQuery(this).attr('field');
		// Get its current value
		var currentVal = parseInt(jQuery(this).prev('.qty').val());
		var maximumVal =  jQuery('.qty').attr('max');
		//alert(maximum);
		// If is not undefined
		if (!isNaN(currentVal)) {
			if(maximumVal!=0){				
				if(currentVal < maximumVal ){
					// Increment
					jQuery(this).prev('.qty').val(currentVal + 1);					
				}				
			}

		} else {
			// Otherwise put a 0 there
			jQuery(this).prev('.qty').val(0);
		}
		
		var qty = jQuery('.qty').val();
		var products_price = jQuery('#products_price').val();
		var total_price = parseFloat(qty) * parseFloat(products_price); 
		jQuery('.total_price').html('<?=$web_setting[19]->value?>'+total_price.toFixed(2));
	});

	// This button will decrement the value till 0

	jQuery(".qtyminus").click(function(e) {
		
		// Stop acting like a button
		e.preventDefault();
		
		// Get the field name
		fieldName = jQuery(this).attr('field');
		var maximumVal =  jQuery('.qty').attr('max');
		var minimumVal =  jQuery('.qty').attr('min');

		// Get its current value
		var currentVal = parseInt(jQuery(this).next('.qty').val());
		// If it isn't undefined or its greater than 0
		if (!isNaN(currentVal) && currentVal > minimumVal) {
			// Decrement one
			jQuery(this).next('.qty').val(currentVal - 1);
		} else {			
			// Otherwise put a 0 there
			jQuery(this).next('.qty').val(minimumVal);

		}
		
		var qty = jQuery('.qty').val();
		var products_price = jQuery('#products_price').val();
		var total_price = parseFloat(qty) * parseFloat(products_price); 
		jQuery('.total_price').html('<?=$web_setting[19]->value?>'+total_price.toFixed(2));

	});
	
	
	// This button will increment the value
	jQuery('.qtypluscart').click(function(e){
		// Stop acting like a button
		e.preventDefault();
		// Get the field name
		fieldName = jQuery(this).attr('field');
		// Get its current value
		var currentVal = parseInt(jQuery(this).prev('.qty').val());
		var maximumVal =  jQuery(this).prev('.qty').attr('max');
		//alert(maximum);
		// If is not undefined
		if (!isNaN(currentVal)) {
			if(maximumVal!=0){				
				if(currentVal < maximumVal ){
					// Increment
					jQuery(this).prev('.qty').val(currentVal + 1);					
				}				
			}

		} else {
			// Otherwise put a 0 there
			jQuery(this).prev('.qty').val(0);
		}		
	});
	
	jQuery(".qtyminuscart").click(function(e) {
		
		// Stop acting like a button
		e.preventDefault();
		
		// Get the field name
		fieldName = jQuery(this).attr('field');

		// Get its current value
		var currentVal = parseInt(jQuery(this).next('.qty').val());
		var minimumVal =  jQuery(this).next('.qty').attr('min');
		
		// If it isn't undefined or its greater than 0
		if (!isNaN(currentVal) && currentVal > minimumVal) {
			// Decrement one
			jQuery(this).next('.qty').val(currentVal - 1);
		} else {			
			// Otherwise put a 0 there
			jQuery(this).next('.qty').val(minimumVal);

		}
		

	});
	
	

	function cart_item_price(){
		
		var subtotal = 0;
		jQuery(".cart_item_price").each(function() {
			subtotal= parseFloat(subtotal) + parseFloat(jQuery(this).val());				
		});
		jQuery('#subtotal').html('<?=$web_setting[19]->value?>'+subtotal);
		
		var discount = 0;			
		jQuery(".discount_price_hidden").each(function() {
			discount =  parseFloat(discount) - parseFloat(jQuery(this).val());				
		});
		
		jQuery('.discount_price').val(Math.abs(discount));
		
		jQuery('#discount').html('<?=$web_setting[19]->value?>'+Math.abs(discount));
		
		//total value
		var total_price = parseFloat(subtotal) - parseFloat(discount);
		jQuery('#total_price').html('<?=$web_setting[19]->value?>'+total_price);
	};
	
	//default_address
	jQuery(document).on('click', '.default_address', function(e){
		jQuery('#loader').css('display','flex');
		var address_id = jQuery(this).attr('address_id');
		jQuery.ajax({
			url: '{{ URL::to("/myDefaultAddress")}}',
			type: "POST",
			data: '&address_id='+address_id,
			
			success: function (res) {
				 window.location = 'shipping-address?action=default';
			},

		});

	});

	

	//deleteMyAddress
	jQuery(document).on('click', '.deleteMyAddress', function(e){
		jQuery('#loader').css('display','flex');
		var address_id = jQuery(this).attr('address_id');
		jQuery.ajax({
			url: '{{ URL::to("/delete-address")}}',
			type: "POST",
			data: '&address_id='+address_id,
			
			success: function (res) {
				window.location = 'shipping-address?action=detele';
			},
		});
	});

jQuery('.slide-toggle').on('click', function(event){
 jQuery('.color-panel').toggleClass('active');
});

	 jQuery( function() {		 
	  var maximum_price = jQuery( ".maximum_price" ).val();
	  jQuery( "#slider-range" ).slider({
		range: true,
		min: 0,
		max: maximum_price,
		values: [ 0, maximum_price ],
		slide: function( event, ui ) {
			jQuery('#min_price').val(ui.values[ 0 ] );
			jQuery('#max_price').val(ui.values[ 1 ] );
		   
			jQuery('#min_price_show').val( ui.values[ 0 ] );
			jQuery('#max_price_show').val( ui.values[ 1 ] );
		},
		create: function(event, ui){
			jQuery(this).slider('value',20);
		}
	   });	   
	   jQuery( "#min_price_show" ).val( jQuery( "#slider-range" ).slider( "values", 0 ) );	   
	   jQuery( "#max_price_show" ).val(jQuery( "#slider-range" ).slider( "values", 1 ) );
	   //jQuery( "#slider-range" ).slider( "option", "max", 50 );
	 });
	 
 	
		

//tooltip enable
jQuery(function () {
  jQuery('[data-toggle="tooltip"]').tooltip()
});		

function initialize(location){	

	@if(!empty($result['commonContent']['setting'][9]->value) or $result['commonContent']['setting'][10]->value)
		var address = '{{$result['commonContent']['setting'][9]->value}}, {{$result['commonContent']['setting'][10]->value}}';
	@else
		var address = '';
	@endif
	
	var map = new google.maps.Map(document.getElementById('googleMap'), {
		mapTypeId: google.maps.MapTypeId.TERRAIN,
		zoom: 13
	});
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({
		'address': address
	}, 
	function(results, status) {
		if(status == google.maps.GeocoderStatus.OK) {
		 new google.maps.Marker({
			position: results[0].geometry.location,
			map: map
		 });
		 map.setCenter(results[0].geometry.location);
		}
	});
   }
  
//default product cart
jQuery(document).on('click', '.cart', function(e){	
	var parent = jQuery(this);
	var products_id = jQuery(this).attr('products_id');
	var message ;
	jQuery.ajax({
		url: '{{ URL::to("/addToCart")}}',
		type: "POST",
		data: '&products_id='+products_id,		
		success: function (res) {
			if(res.trim() == "already added"){							
			}else{
				jQuery('.head-cart-content').html(res);				
				jQuery(parent).removeClass('cart');
				jQuery(parent).addClass('active');
				jQuery(parent).html("@lang('website.Added')");
			}
			message = "@lang('website.Product is added')";			
			notification(message);
		},
	});

});
	
});
jQuery( document ).ready( function () {
	
	jQuery.ajax({
		url: '{{ URL::to("/cartButton")}}',
		type: "GET",		
		success: function (res) {
			jQuery('.head-cart-content').html(res);	
		},
	});

});


//ready doument end
jQuery('.dropdown-menu').on('click', function(event){
	// The event won't be propagated up to the document NODE and 
	// therefore delegated events won't be fired
	event.stopPropagation();
});
jQuery(".alert.fade").fadeTo(2000, 500).slideUp(500, function(){
    jQuery(".alert.fade").slideUp(500);
});

function delete_cart_product(cart_id){
	jQuery('#loader').css('display','flex');
	var id = cart_id;
	jQuery.ajax({
		url: '{{ URL::to("/deleteCart")}}',
		type: "GET",
		data: '&id='+id+'&type=header cart',		
		success: function (res) {
			window.location.reload(true);
		},
	});
};

//paymentMethods
function paymentMethods(){
	//jQuery('#loader').css('display','flex');
	var payment_method = jQuery(".payment_method:checked").val();
	jQuery(".payment_btns").hide();
	
	jQuery("#"+payment_method+'_button').show();
	jQuery.ajax({
		url: '{{ URL::to("/paymentComponent")}}',
		type: "POST",
		data: '&payment_method='+payment_method,			
		success: function (res) {
			//jQuery('#loader').hide();
		},
	});
}

//pay_instamojo
jQuery(document).on('click', '#pay_instamojo', function(e){	
	var formData = jQuery("#instamojo_form").serialize();	
	jQuery.ajax({
		url: '{{ URL::to("/pay-instamojo")}}',
		type: "POST",
		data: formData,
		success: function (res) {	
			var data = JSON.parse(res);	
			
			var success = data.success;
			if(success==false){	
				var phone = data.message.phone;
				var email = data.message.email;
				
				if(phone != null){
					var message = phone;	
				}else if(email != null){
					var message = email;
				}else{
					var message = 'Something went wrong!';
				}
											
				jQuery('#insta_mojo_error').show();			
				jQuery('#instamojo-error-text').html(message);
				
			}else{		
				jQuery('#insta_mojo_error').hide();	
				jQuery('#instamojoModel').modal('hide');										
				jQuery('#update_cart_form').prepend('<input type="hidden" name="nonce" value='+JSON.stringify(data)+'>');
				jQuery("#update_cart_form").submit();		
			}		
			
		},
	});

});


//notification
function notification(message) {
	jQuery('#message_content').html(message);
	var x = document.getElementById("message_content");
	x.className = "show";
	setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}

function passwordMatch(){
	
	var password = jQuery('#password').val();
	var re_password = jQuery('#re_password').val();	
	
	if(password == re_password){
		return 'matched';
	}else{
		return 'error';
	}
}

function getZones() {
	jQuery('#loader').css('display','flex');
	var country_id = jQuery('#entry_country_id').val();
	jQuery.ajax({
		url: '{{ URL::to("/ajaxZones")}}',
		type: "POST",
		//data: '&country_id='+country_id,
		 data: {'country_id': country_id},
		
		success: function (res) {
			var i;
			var showData = [];
			for (i = 0; i < res.length; ++i) {
				var j = i + 1; 
				showData[i] = "<option value='"+res[i].zone_id+"'>"+res[i].zone_name+"</option>"; 
			}
			showData.push("<option value='Other'>@lang('website.Other')</option>");
			jQuery("#entry_zone_id").html(showData);
			jQuery('#loader').hide();
		},
	});

};

function getBillingZones() {
	console.log('here');
	jQuery('#loader').css('display','flex');
	var country_id = jQuery('#billing_countries_id').val();
	jQuery.ajax({
		url: '{{ URL::to("/ajaxZones")}}',
		type: "POST",
		 data: {'country_id': country_id},
		
		success: function (res) {
			var i;
			var showData = [];
			for (i = 0; i < res.length; ++i) {
				var j = i + 1; 
				showData[i] = "<option value='"+res[i].zone_id+"'>"+res[i].zone_name+"</option>"; 
			}
			showData.push("<option value='Other'>@lang('website.Other')</option>");
			jQuery("#billing_zone_id").html(showData);
			jQuery('#loader').hide();
		},
	});

};

function getZonesBilling() {
	var field_name = jQuery('.shipping_data:checked');
	var mehtod_name = jQuery(field_name).attr('method_name');
	var shipping_price = jQuery(field_name).attr('shipping_price');
	jQuery("#mehtod_name").val(mehtod_name);
	jQuery("#shipping_price").val(shipping_price);
}

'use strict';
function showPreview(objFileInput) {
	if (objFileInput.files[0]) {
		var fileReader = new FileReader();
		fileReader.onload = function (e) {
			jQuery("#uploaded_image").html('<img src="'+e.target.result+'" width="150px" height="150px" class="upload-preview" />');
			jQuery("#uploaded_image").css('opacity','1.0');
			jQuery(".upload-choose-icon").css('opacity','0.8');
		}
		fileReader.readAsDataURL(objFileInput.files[0]);
	}
}

jQuery(document).ready(function() {
  /******************************
      BOTTOM SCROLL TOP BUTTON
   ******************************/

  // declare variable
  var scrollTop = jQuery(".floating-top");

  jQuery(window).scroll(function() {
    // declare variable
    var topPos = jQuery(this).scrollTop();

    // if user scrolls down - show scroll to top button
    if (topPos > 150) {
      jQuery(scrollTop).css("opacity", "1");

    } else {
      jQuery(scrollTop).css("opacity", "0");
    }

  }); 

  //Click event to scroll to top
  jQuery(scrollTop).click(function() {
    jQuery('html, body').animate({
      scrollTop: 0
    }, 800);
    return false;

  });
});


jQuery('body').on('mouseenter mouseleave','.dropdown.open',function(e){
  var _d=jQuery(e.target).closest('.dropdown');
  _d.addClass('show');
  setTimeout(function(){
    _d[_d.is(':hover')?'addClass':'removeClass']('show');	
    
  },300);
  jQuery('.dropdown-menu', _d).attr('aria-expanded',_d.is(':hover'));
});


 
jQuery(document).ready(function(e) {
	
   @if(!empty($result['flash_sale']['success']) and $result['flash_sale']['success']==1)
       @foreach($result['flash_sale']['product_data'] as $key=>$products)
	   @if( $products->server_time >= $products->flash_start_date)
	    var product_div_{{$products->products_id}} = 'product_div_{{$products->products_id}}';
		var counter_id_{{$products->products_id}} = 'counter_{{$products->products_id}}';
		var inputTime_{{$products->products_id}} = "{{date('M d, Y H:i:s' ,$products->flash_expires_date)}}";
		
		var distance_{{$products->products_id}} = {{$products->flash_expires_date}} - {{$products->server_time}};
				
		// Update the count down every 1 second
		var x_{{$products->products_id}} = setInterval(function() {
		    distance_{{$products->products_id}} = distance_{{$products->products_id}} - 1;
		   
		
		  // Time calculations for days, hours, minutes and seconds
			  var days_{{$products->products_id}} = Math.floor(distance_{{$products->products_id}} / ( 60 * 60 * 24));
			  var hours_{{$products->products_id}} = Math.floor((distance_{{$products->products_id}} % ( 60 * 60 * 24)) / ( 60 * 60));
			  var minutes_{{$products->products_id}} = Math.floor((distance_{{$products->products_id}} % ( 60 * 60)) / ( 60));
			  var seconds_{{$products->products_id}} = Math.floor(distance_{{$products->products_id}} % (60));

			  // Display the result in the element with id="demo"
			  document.getElementById(counter_id_{{$products->products_id}}).innerHTML = days_{{$products->products_id}} + "d " + hours_{{$products->products_id}} + "h "
			  + minutes_{{$products->products_id}} + "m " + seconds_{{$products->products_id}} + "s ";
			  document.getElementById('product_div_{{$products->products_id}}').style.display = 'block'; 

			  // If the count down is finished, write some text 
			  if (distance_{{$products->products_id}} < 0) {
				clearInterval(x_{{$products->products_id}});
				//document.getElementById(counter_id_{{$products->products_id}}).innerHTML = "EXPIRED";
				document.getElementById('product_div_{{$products->products_id}}').remove();
			  }
		}, 1000);
  	   @endif	
	 @endforeach
   @endif	
  
	@if(!empty($result['detail']['product_data'][0]->flash_start_date))
		@if( $result['detail']['product_data'][0]->server_time >= $result['detail']['product_data'][0]->flash_start_date)
			var distance = {{$result['detail']['product_data'][0]->flash_expires_date}} - {{$result['detail']['product_data'][0]->server_time}};
			
			// Update the count down every 1 second
			var x = setInterval(function() {
			  // Find the distance between now and the count down date
			  distance = distance-1;
			  
			  // Time calculations for days, hours, minutes and seconds
			  var days = Math.floor(distance / ( 60 * 60 * 24));
			  var hours = Math.floor((distance % ( 60 * 60 * 24)) / ( 60 * 60));
			  var minutes = Math.floor((distance % ( 60 * 60)) / ( 60));
			  var seconds = Math.floor(distance % (60));
			
			  // Display the result in the element with id="demo"
			  document.getElementById("counter_product").innerHTML = days + "d " + hours + "h "
			  + minutes + "m " + seconds + "s ";
				document.getElementById("counter_product").style.display = 'block'; 
			  // If the count down is finished, write some text 
			  if (distance < 0) {
				clearInterval(x);
				document.getElementById("counter_product").innerHTML = "EXPIRED";
				document.getElementById("add-to-Cart").style.display = 'none'; 
			  }
			}, 1000);
		@endif
	@endif
});
  
jQuery('.nav-index').on('show.bs.tab', function (e) {
	  console.log('fire');
	  e.target // newly activated tab
	  e.relatedTarget // previous active tab
	  jQuery('.overlay').show();   
})
  jQuery('.nav-index').on('hidden.bs.tab', function (e) {
	  console.log('expire');
	  e.target // newly activated tab
	  e.relatedTarget // previous active tab
	  jQuery('.overlay').hide();   
})

@if(!empty($result['detail']['product_data'][0]->products_type) and $result['detail']['product_data'][0]->products_type==1)
	getQuantity();
	cartPrice();
@endif

function cartPrice(){
	var i = 0;
	jQuery(".currentstock").each(function() {
		var value_price = jQuery('option:selected', this).attr('value_price');
		var attributes_value = jQuery('option:selected', this).attr('attributes_value');
		var prefix = jQuery('option:selected', this).attr('prefix');		
		jQuery('#attributeid_' + i).val(value_price);
		jQuery('#attribute_sign_' + i++).val(prefix);
			
	});
}

//ajax call for add option value
function getQuantity(){
	var attributeid = [];
	var i = 0;
	
	jQuery(".currentstock").each(function() {
		var value_price = jQuery('option:selected', this).attr('value_price');
		var attributes_value = jQuery('option:selected', this).attr('attributes_value');
		jQuery('#function_' + i).val(value_price);
		jQuery('#attributeids_' + i++).val(attributes_value);		
	});
	
	var formData = jQuery('#add-Product-form').serialize();
	jQuery.ajax({
		url: '{{ URL::to("getquantity")}}',
		type: "POST",
		data: formData,
		dataType: "json",
		success: function (res) {
			jQuery('#current_stocks').html(res.remainingStock);
			var min_level = 0;
			var max_level = 0;
			var inventory_ref_id = res.inventory_ref_id;
			
			if(res.minMax != ''){
				min_level = res.minMax[0].min_level;
				max_level = res.minMax[0].max_level;
			}
			
			if(res.remainingStock>0){
				jQuery('.stock-cart').removeAttr('hidden');
				jQuery('.stock-out-cart').attr('hidden',true);
				var max_order = jQuery('#max_order').val();
				
				if(max_order.trim()!=0){
					
					if(max_order.trim()>=res.remainingStock){
						jQuery('.qty').attr('max',res.remainingStock);
					}else{
						jQuery('.qty').attr('max',max_order);
					}
				}else{
					
					
					jQuery('.qty').attr('max',res.remainingStock);					
				}
				
							
			}else{				
				jQuery('.stock-out-cart').removeAttr('hidden');
				jQuery('.stock-cart').attr('hidden',true);
				jQuery('.qty').attr('max',0);
			}
			
		},
	});	
}

function cancelOrder() {
	if (confirm("@lang('website.Are you sure you want to cancel this order?')")) {
		return true;
	} else {
		return false;
	}
}

function returnOrder() {
	if (confirm("@lang('website.Are you sure you want to return this order?')")) {
		return true;
	} else {
		return false;
	}
}
	
//subscribe
jQuery(document).on('click', '#subscribe', function(e){
	var email = jQuery('#email').val();
	var validEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
	jQuery('.error-subscribte').hide();
	jQuery('.success-subscribte').hide();
	
	if(this.email != '' && validEmail.test(email)) {

		jQuery('#loader').css('display','flex');		
		jQuery.ajax({			
			url: '{{ URL::to("/subscribe")}}',			
			type: "GET",			
			data: '&email='+email,			

			success: function (res) {			
				
				var obj = JSON.parse(res);	
				var message = obj.message;

				if(obj.success==1){
					jQuery('.success-subscribte').show();
					jQuery('.success-subscribte').html(obj.message);				
				}else if(obj.success==0){				
					jQuery('.error-subscribte').show();
					jQuery('.error-subscribte').html(obj.message);				
				}

			},			
		});	
	}else{
		jQuery('.error-subscribte').show();
		jQuery('.error-subscribte').html("@lang('website.Please enter your email address')");	
		
	}
		jQuery('#loader').css('display','none');
});


function wrap_text_1(){
	var words_length = 40;
	jQuery(".wrap-dot-1").each(function() {
		if(jQuery(this).text().length > words_length) {
			var replaced_text = jQuery(this).text().slice(0,words_length);
			//console.log(replaced_text);
			jQuery(this).text(replaced_text+'...');
		}
	});	
}

</script>