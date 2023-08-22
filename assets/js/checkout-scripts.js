var ajax_url = backendVars.ajax_url;

jQuery(document).ready(function($) {


	$('.destination-field_multi').hide();

  var arrIds = [];
	$('.food-list_item').on('click', function() {
    let item = $(this);
    let group = item.attr('data-group');
    if(item.hasClass('active')) {
      item.removeClass('active');
      if(!$('.food-list_item.active').length) {
        // $('.ty-sm-title').hide();
        // $('.food-list.food-list_edit').hide();
      }
    }
    else {
      let parentTitle = '';
      if(item.closest('.food-list').length) {
        parentTitle = '.food-list';
      } else {
        parentTitle = '.foods-list';
      }
      if(group) {
        item.closest(parentTitle).find('.food-list_item[data-group=' + group + ']').removeClass('active');
      } else {
        item.closest(parentTitle).find('.food-list_item').removeClass('active');
      }
      item.addClass('active');
      $('.ty-sm-title').show();
      // $('.food-list.food-list_edit').show();
    }
    isSwitched = false;
	});

  $('.menu-checkbox_toggle').on('click', function(e) {
    e.preventDefault();
    $(this).toggleClass('active');
    $('.sure-toggle_visibility').toggleClass('active');
    if($(this).hasClass('active')) {
        $('.food-list_item').removeClass('active');
        $('.products-id_input').attr('value', 'Not sure what to get');
        arrIds = [];
        $('input[name=is_menu]').val(1);
    } else {
        $('input[name=is_menu]').val(0);
    }
  });
  $('.food-checkbox_toggle').on('click', function(e) {
    e.preventDefault();
    $(this).toggleClass('active');
    $('.food-content_inner').toggleClass('active');
    if(!$(this).hasClass('active')) {
        $('.food-list_item').removeClass('active');
        $('.products-id_input').attr('value', 'Food not selected');
        arrIds = [];
        $('input[name=is_food]').val(0);
    } else {
        $('input[name=is_food]').val(1);
    }
  });

	var tableHeight = $('.woocommerce-billing-fields__field-wrapper').innerHeight();
	if (innerWidth > 990) {
		setInterval(function(){
      if($('.cancellation-policy_checkout--inner').length) {
          var checkPosition = $('.cancellation-policy_checkout--inner').offset().top;
        }
        if($('.coupon-wrap').length) {
          var checkPositionSecond = $('.coupon-wrap').offset().top;
        }
        if( $('.woocommerce-checkout-review-order-table').length) {
          var checkPositionThird = $('.woocommerce-checkout-review-order-table').scrollTop();
        }
        if( $('.woocommerce-checkout-review-order-table').length) {
         var checkPositionThirdOff = $('.woocommerce-checkout-review-order-table').offset().top;
        }

     	if(checkPositionThirdOff <= 150) {
     		$('.woocommerce-checkout-review-order-table').css('top', 175 + 'px');
     	}
     	var lastScrollTop = 0;
			$(window).scroll(function(event){
			   var st = $(this).scrollTop();
			   if (st > lastScrollTop){
          if($('.cart-discount').length) {
            // $('.checkout-sidebar_message').css('top', checkPositionThird + tableHeight + 440 + 'px');
          }
          else {
            // $('.checkout-sidebar_message').css('top', checkPositionThird + tableHeight + 370 + 'px');
          }
			       // $('.woocommerce-checkout-review-order-table').css('top', 100 + 'px');
             // $('.ts-product-image').hide();
			   }
			   else if(st == 0) {
			   	// $('.checkout-sidebar_message').css('top', checkPositionThird + tableHeight + 680 + 'px');
          // $('.ts-product-image').show();
			   }
			   lastScrollTop = st;
		});
	}, 500);
	}

  $('.cancellation-policy_checkout').insertAfter('.woocommerce-additional-fields');
  $('.woocommerce-billing-fields__field-wrapper').insertAfter('.cancellation-policy_checkout');
  $('.woocommerce-billing-fields__field-wrapper').prepend('<h3 class="checkout-step_title">Personal details</h3>');

  setTimeout(function(){
      //$('.wc_payment_method .payment-title').html('Pay by cart');
      if ( $('.variation-StartDate p').length ) {
        var dateCharter = $('.variation-StartDate p').html();
        var dateCharterParsed = dateCharter.substring(0, 2);
        var dateCharterMonth = dateCharter.substring(3, 5);
      }
      if ( $('.variation-EndDate p').length ) {
        var endDateCharter = $('.variation-EndDate p').html();
        var endDateCharterParsed = endDateCharter.substring(0, 2);
      }

      if(dateCharterMonth == '01') {
      	$('.month-reciever').html('Jan');
      	$('.endMonth-reciever').html('Jan');
      }
      else if(dateCharterMonth == '02') {
      	$('.month-reciever').html('Feb');
      	$('.endMonth-reciever').html('Feb');
      }
      else if(dateCharterMonth == '03') {
      	$('.month-reciever').html('Mar');
      	$('.endMonth-reciever').html('Mar');
      }
      else if(dateCharterMonth == '04') {
      	$('.month-reciever').html('Apr');
      	$('.endMonth-reciever').html('Apr');
      }
      else if(dateCharterMonth == '05') {
      	$('.month-reciever').html('May');
      	$('.endMonth-reciever').html('May');
      }
      else if(dateCharterMonth == '06') {
      	$('.month-reciever').html('Jun');
      	$('.endMonth-reciever').html('Jun');
      }
      else if(dateCharterMonth == '07') {
      	$('.month-reciever').html('Jul');
      	$('.endMonth-reciever').html('Jul');
      }
      else if(dateCharterMonth == '08') {
      	$('.month-reciever').html('Aug');
      	$('.endMonth-reciever').html('Aug');
      }
      else if(dateCharterMonth == '09') {
      	$('.month-reciever').html('Sep');
      	$('.endMonth-reciever').html('Sep');
      }
      else if(dateCharterMonth == '10') {
      	$('.month-reciever').html('Oct');
      	$('.endMonth-reciever').html('Oct');
      }
      else if(dateCharterMonth == '11') {
      	$('.month-reciever').html('Nov');
      	$('.endMonth-reciever').html('Nov');
      }
      else if(dateCharterMonth == '12') {
      	$('.month-reciever').html('Dec');
      	$('.endMonth-reciever').html('Dec');
      }
  $('.date-reciever').html(dateCharterParsed);

  if($('.cart-discount').length && !$('.cart-discount').hasClass('coupon-alldiscount')) {
    $('.coupon-congrats').fadeIn();
  }

  $('.loading-popup').hide();

  }, 3000);

  $(document).on('click', '.woocommerce-remove-coupon', function(e) {
    e.preventDefault();
    // let code = $(this).attr('data-coupon');
    // $.ajax({
    //   url: ajax_url,
    //   method: 'POST',
    //   data: {
    //     action: 'sailing_remove_coupon',
    //     coupon: code
    //   },
    //   success: function(response) {
    //     window.location.reload();
    //   }
    // });
  });

  if(innerWidth <= 900 && innerWidth > 500) {
  	setInterval(function(){
    if($('.cancellation-policy_checkout--inner').length) {
      var checkPosition = $('.cancellation-policy_checkout--inner').offset().top;
    }
    if($('.coupon-wrap').length) {
      var checkPositionSecond = $('.coupon-wrap').offset().top;
    }
    if($('.wc_payment_method').length) {
      var checkPositionPayment = $('.wc_payment_method').offset().top;
    }
   	var tableHeight = $('.woocommerce-checkout-review-order-table').height();
   	$('.woocommerce-billing-fields__field-wrapper').css('top', checkPosition - 100  + 'px');
   	$('.woocommerce-checkout-payment').css('top', checkPositionSecond - 150 + 'px');
   	$('.woocommerce-checkout-review-order-table').css('top', checkPositionPayment  + 'px');
   	$('.coupon-inner').css('margin-bottom', tableHeight + 700 + 'px');
   	var tableHeightMargin = tableHeight + 100;
   	// $('.wc_payment_method').attr('style', 'margin-bottom:' + tableHeightMargin + 'px!important' );
  	}, 500);
  }

  if(innerWidth <= 500) {
    if($('.woocommerce-billing-fields__field-wrapper').length) {
      var containerWidth = $('.col-md-7').width();
      $('.woocommerce-billing-fields__field-wrapper').css('width', containerWidth + 'px');
      var tableHeight = $('.woocommerce-checkout-review-order-table').height();
      var tableHeightMargin = tableHeight + 30;
      setInterval(function(){
      if($('.cancellation-policy_checkout--inner').length) {
        var checkPosition = $('.cancellation-policy_checkout--inner').offset().top;
      }
      var checkPositionSecond = $('.coupon-wrap').offset().top;
      var checkPositionPayment = $('.wc_payment_method').offset().top;
      $('.woocommerce-billing-fields__field-wrapper').css('top', checkPosition - 70 + 'px');
      $('.woocommerce-checkout-payment').css('top', checkPositionSecond - 110 + 'px');
        $('.coupon-inner').css('margin-bottom', tableHeight + 650 + 'px');
        $('.woocommerce-checkout-review-order-table').css('top', checkPositionPayment + 140 + 'px');
        // $('.wc_payment_method').attr('style', 'margin-bottom:' + tableHeightMargin + 'px!important' );
    }, 500);
    }
  }

  $(document).on('click', '.pay-deposit_button', function() {
    if(!$('.form-row').hasClass('woocommerce-invalid')) {
     // $('.loading-popup').show();
     // $('body').addClass('overflow');
    }
    else {
      $('.loading-popup').hide();
      $('body').removeClass('overflow');
    }
  });
  let $destination_current = $('.destination-holder').text();
  let $destination_edit = $destination_current;

  $('.destination-edit').on('click', function() {
  $('.destination-edit').removeClass('active');
  $(this).toggleClass('active');
  if($('.destination-edit').hasClass('active')) {
    $destination_edit = $.trim($(this).text());
  }
  });

  $('.edit-order_button').on('click', function(e){
    e.preventDefault();
    let orderId = $('.order-id_holder').text();
    let productsToAdd = [];
    if($('.ty__day-list').length) {
      let days = $('.ty__day-item').length;
      $('.food-list_item--ordered.active').each(function(){
        let id = parseInt($(this).attr('data-product_id'));
        let day = parseInt($(this).closest('.food-list_edit').attr('data-listday'));
        let isInArray = false;
        let arrIndex = 0;
        for(let i = 0; i < productsToAdd.length; i++) {
          if(productsToAdd[i][0] == id) {
            isInArray = true;
            arrIndex = i;
            break;
          }
        }
        if(isInArray) {
          productsToAdd[arrIndex][day] = 1;
        } else {
          if(day == 1) {
            let temp = [id,1,0];
            productsToAdd.push(temp);
          } else {
            let temp = [id,0,1];
            productsToAdd.push(temp);
          }
        }
      });
    } else {
      $('.checkout-menu_content.active .food-list_item--holder.active').each(function(){
        let id = parseInt($(this).attr('data-id'));
        let q = 1;
        if($(this).find('input').length) {
          q = parseInt($(this).find('input').val());
        }
        if(q > 0) {
          let temp = [id,q];
          productsToAdd.push(temp);
        }
      });
      if(parseInt($('.food-list_edit').attr('data-cat')) > 0) {
        if($('.food-list_edit').attr('data-cat') == $('.checkout-menu_list .menu-item.current').attr('data-id')) {
          $('.food-list_item--ordered').each(function(){
            let id = parseInt($(this).attr('data-product_id'));
            let q = parseInt($(this).find('input').val());
            if(q > 0) {
              let temp = [id,q];
              productsToAdd.push(temp);
            }
          });
        }
      }
    }

    let data = {
      'order_id': orderId,
      'added_ids': productsToAdd,
      'order_destination': $destination_current,
      'edit_destination': $destination_edit,
      action: 'edit_order',
    };
    if($('.destinatins-checkbox_toggle').hasClass('active')) {
      data.is_destination = 1;
    }
    console.log(data);
    $.ajax({
      url: ajax_url,
      method: 'POST',
      data: data,
      success: function(response) {
        console.log(response);
        window.location.reload();
      }
    });
  });

  $(document).on('click', '#coupon-button', function(e) {
    e.preventDefault();
    var coupon = $('#coupon_code').val();
    $.ajax({
      url: ajax_url,
      method: 'POST',
      data: {
        'coupon_code': coupon,
        action: 'my_special_action'
      },
      success: function(response) {
       if(response == 1) {
        // $('.loading-popup').show();
        // $('body').addClass('overflow');
        $('body').trigger('update_checkout', { update_shipping_method: true });
       }
       else {
        $('.coupon-failed').show();
       }
      }
    });
  });

  let isSwitched = false;
  $('.food-list_item-q .minus').on('click',function(){
    let parent = $(this).closest('.food-list_item-q');
    let input = parent.find('input');
    let value = parseInt(input.val());
    if(value > 0) {
      input.val(--value);
    }
    isSwitched = false;
  });

  $('.food-list_item-q .plus').on('click',function(){
    let parent = $(this).closest('.food-list_item-q');
    let input = parent.find('input');
    let max = parseInt(input.attr('data-max'));
    let value = parseInt(input.val());
    if(value < max) {
      input.val(++value);
    }
    isSwitched = false;
  });

  $('.to-payment-btn').on('click',function() {
    let isEnabled = false;
    if(!$('.woocommerce-billing-fields__field-wrapper .woocommerce-invalid').length) {
      isEnabled = true;
    } else {
      $([document.documentElement, document.body]).animate({
        scrollTop: $(".woocommerce-billing-fields__field-wrapper").offset().top - 100
      }, 600);
       return;
    }
    if(isEnabled) {
      $('.multistep-checkout__step.details').removeClass('active');
      $('.multistep-checkout__step.payment').addClass('active');
      if(!$('.food-content_inner').hasClass('active')) {
        $('.food-list_item-q input').val(0);
      }
      let products = [];
      if($('.multyday-foods_content').length) {
        let temp_arr = [];
        $('.multyday-foods_content .food-list_item.active').each(function(){
          let productId = parseInt($(this).attr('data-product-id'));
          let day = $(this).closest('.multyday-foods_content').index();
          let isInArray = false;
          let key = 0;
          for(let i = 0; i < products.length; i++) {
            if(products[i][0] == productId) {
              isInArray = true;
              key = i;
            }
          }
          if(!isInArray) {
            let temp = [productId];
            if(day > 1) {
              temp[2] = 1;
              temp[1] = 0;
            } else {
              temp[1] = 1;
              temp[2] = 0;
            }
            products.push(temp);
          } else {
            if(day > 1) {
            products[key][2] = 1;
            } else {
              products[key][1] = 1;
            }
          }
        });
      } else {
        if($('.food-list_item.active').length) {
          let productId = parseInt($('.food-list_item.active').attr('data-product-id'));
          let temp = [productId];
          products.push(temp);
        }
      }
      console.log(products);
      if(!isSwitched) {
        $.ajax({
            type: 'POST',
            url: ajax_url,
            data: {
                action: "checkout_add_products",
                products: products
            },
            success: function(response) {
              response = JSON.parse(response);
              console.log(response);
              if(response['items'] != '') {
                $('.product-details_food').remove();
                $('.checkout-food-rows').after(response['items']);
                $('.product-details_title.food').show();
              } else {
                $('.product-details_title.food').hide();
                $('.product-details_food').remove();
              }
              let currency = $('.shop_table').attr('data-currency');
              let boatPrice = $('.product-details_service.boat-price').attr('data-price');
              $('.product-details_service.boat-price .servise-price_order').text(currency + ' ' + boatPrice);

              $('.product-details_service.deposite .servise-price_order').html('').html(response['deposite']);
              $('.product-details_service.payable .servise-price_order').html('').html(response['payable']);
              $('.order-total .total-td').html('').html(response['total']);
              $('body').trigger('update_checkout', { update_shipping_method: true });
            }
        });
      }
      isSwitched = true;
    }

  });

  $('.to-details-btn').on('click',function() {
    $('.multistep-checkout__step.details').addClass('active');
    $('.multistep-checkout__step.payment').removeClass('active');
  });

  if(!$('.checkout-menu_list .menu-item.current').length && !$('.ty-page').length) {
    $('.checkout-menu_list .menu-item').eq(0).addClass('current');
    $('.checkout-menu_content').eq(0).addClass('active');
  }

  $('.ty__day-item').on('click',function(){
    if(!$(this).hasClass('active')) {
      $('.ty__day-item').removeClass('active');
      $(this).addClass('active');
      let dayN = $(this).attr('data-n');
      $('.food-list_edit').hide();
      $('.food-list_edit[data-listday=' + dayN + ']').show();
    }
  });

  if($('.ty__day-item.active').length) {
    let dayN = $('.ty__day-item.active').attr('data-n');
    $('.food-list_edit').hide();
    $('.food-list_edit[data-listday=' + dayN + ']').show();
  }

  $('.ty__popup-dayitem').on('click',function(){
    if(!$(this).hasClass('active')) {
      $('.ty__popup-dayitem').removeClass('active');
      $(this).addClass('active');
      let dayN = $(this).attr('data-n');
      $('.checkout-menu_content .foods-list').hide();
      $('.checkout-menu_content .foods-list[data-n=' + dayN + ']').show();
    }
  });

  if($('.ty__popup-dayitem').length) {
    let dayN = $('.ty__popup-dayitem.active').attr('data-n');
    $('.checkout-menu_content .foods-list').hide();
    $('.checkout-menu_content .foods-list[data-n=' + dayN + ']').show();
  }

  /*setTimeout(function(){
    if($('.page-bottom_message').length > 1) {
      $('#order_review > :last-child').remove();
    }
  },3000);*/

  $('.woocommerce-billing-fields__field-wrapper').after('<div class="clear"></div>');
});

jQuery( document ).ajaxComplete( function() {
    if ( jQuery( 'body' ).hasClass( 'woocommerce-checkout' ) || jQuery( 'body' ).hasClass( 'woocommerce-cart' ) ) {
        jQuery( 'html, body' ).stop();
    }
} );

