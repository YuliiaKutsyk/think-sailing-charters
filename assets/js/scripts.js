let s_ajax_url = beVars.ajax_url;
//let sc_excluded_dates = JSON.parse(beVars.sc_excluded_dates);
let flatpickr;
let countMoreA = 0;
let countMoreC = 0;
let countMoreI = 0;


if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}



jQuery(document).ready(function ($) {

        /*if(window.location.href.indexOf('/product/') > -1){
            var dateText = $('.charter-time_single').html();
            setTimeout(function(){
                $('.charter-sidebar-cats').trigger('click');
                $('.charter-type_dropdown div[data-slug="day-charters"]').trigger('click');
                $('.charter-time.charter-time_single').html(dateText);
            },100);
        }*/

        if (window.location.href.indexOf('/checkout/') > -1) {
            checkDownloadMenuEmpty();
        }

        /* Banner session functionality */
        function getSession(name) {
            return sessionStorage.getItem(name);
        }

        function setSession(name, value) {
            sessionStorage.setItem(name, value);
        }

        function showBanner() {
            const banner = $('.banner.eu-malta');
            const body = $('body');

            if (!getSession('banner_shown')) {
                banner.show();
                body.addClass('banner-visible');
                setSession('banner_shown', 'true'); // Устанавливаем сессию
            } else {
                body.removeClass('banner-visible');
            }
        }

        function closeBanner() {
            const banner = $('.banner.eu-malta');
            const body = $('body');
            banner.hide();
            body.removeClass('banner-visible');
            setSession('banner_shown', 'true'); // Устанавливаем сессию
        }

        showBanner();

        // Обработчик события click для кнопки закрытия
        $('.banner.eu-malta .inner button').on('click', function() {
            closeBanner();
        });
        /* End of banner session functionality */

        if (sessionStorage.getItem('removeEditOpen')) {
            window.console.log('Cache event');
            var newUrl = window.location.href.replace("#edit-open", "");
            history.replaceState(null, null, newUrl);
            sessionStorage.removeItem('removeEditOpen');
        }

        $(".edit-order_button").click(function (e) {
            window.console.log('Click event');
            sessionStorage.setItem('removeEditOpen', true);
        });

        //Owl
        $("#review-slider").owlCarousel({
            items: 1,
            nav: true,
            margin: 20,
            stagePadding: 40,
            loop: true,
            autoplay: true,
            responsive: {
                500: {
                    items: 1,
                    nav: true,
                    margin: 10,
                    stagePadding: 0,
                }
            }
        });

        $("#services-wrap_mobile").owlCarousel({
            items: 1,
            nav: false,
            margin: 15,
            stagePadding: 67,
            responsive: {
                500: {
                    items: 2,
                    nav: false,
                    margin: 20,
                    stagePadding: 100,
                }
            }
        });

        $("#fleets-wrap_mobile").owlCarousel({
            items: 1,
            nav: false,
            margin: 15,
            stagePadding: 67,
            responsive: {
                500: {
                    items: 1,
                    nav: false,
                    margin: 20,
                    stagePadding: 190,
                }
            }
        });

        $("#about-gallery_slider").owlCarousel({
            items: 1,
            nav: true,
            margin: 10,
            onTranslate: function (event) {
                var beforeItem = 0;
                var afterItem = 0;
                $('#about-gallery_slider .owl-dot').each(function (ii) {
                    if ($(this).hasClass('active')) {
                        beforeItem = ii + 1;
                        afterItem = parseInt($('#about-gallery_slider .owl-dot').length) - beforeItem;
                        $('.owl-dot_current').html(beforeItem);
                        return;
                    }
                });
            },
        });

        var dotNumber = $("#about-gallery_slider .owl-dots button").length;
        $('.owl-dot_counter').html(dotNumber);

        //Parallax
        if (innerWidth > 990) {
            if ($('.parallax-image').length) {
                $(window).scroll(function () {
                    var st = $(this).scrollTop();
                    $('.parallax-image').css({
                        'transform': 'translate(0%, ' + st / 40 + '%'
                    });
                });
            }
        }

        if($('.poeple-summury').length) {
            let p1 = parseInt($('.poeple-summury').val());
            if(!isNaN(p1)) {
                countMoreA = p1;
            }
        }

        if($('.poeple-children_summury').length) {
            let p2 = parseInt($('.poeple-children_summury').val());
            if(!isNaN(p2)) {
                countMoreB = p2;
            }
        }

        if($('.poeple-infants_summury').length) {
            let p3 = parseInt($('.poeple-infants_summury').val());
            if(!isNaN(p3)) {
                countMoreC = p3;
            }
        }

        //Sticky header
        var headerDuplicate = $('.header-duplicate');
        headerDuplicate.height($('header').outerHeight());
        $(window).on('scroll', function () {
            var sticky = $('header');
            var stMenu = $('.mobile-menu');
            scroll = $(window).scrollTop();

            if (scroll >= 250) {
                sticky.addClass('fixed');
                stMenu.addClass('fixed');
            } else {
                sticky.removeClass('fixed');
                stMenu.removeClass('fixed');
            }
        });

        $('header').on('click', function (e) {
            e.stopPropagation();
        });

        //CHECKOUT
        var destSumm = $('.destination-field').length;
        //var destSummCalc = destSumm - 6;
        $('.destination-field').wrapAll("<div class='destinations-fields_wrap full active' />");
        if($('.form-row destination-field').length > 6) {
            $('.destinations-fields_wrap').after("<div class='more-destinations_checkout'>View all </div>");
        }
        if($('.current-dest-active').length) {
            $('.destinations-fields_wrap').removeClass('active');
        }
        $('.destinatins-checkbox_toggle').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
            if(!$('.woocommerce-order.ty-page').length) {
                $('.destinations-fields_wrap').toggleClass('active');
                $('.destinations-fields_wrap').toggleClass('full');
                $('.more-destinations_checkout').toggleClass('active');
                $('.more-destinations_checkout').toggleClass('showed');
                if (!$(this).hasClass('active')) {
                    $('.destination-field input').attr('value', 'Destination not selected');
                    $('.destination-field span').removeClass('active');
                }
            } else {
                if($(this).hasClass('active')) {
                    $('.destinations-fields_wrap').removeClass('active');
                } else {
                    $('.destinations-fields_wrap').addClass('active');
                    $('.destination-field .woocommerce-input-wrapper').removeClass('active');
                    $('.destination-field .checkbox destination-edit').removeClass('active');
                }
            }
        });

        $(document).on('click', '.more-destinations_checkout', function () {
            $(this).toggleClass('active');
            $('.destinations-fields_wrap').toggleClass('full');
            $('.woocommerce-order-received .destinations-fields_wrap').toggleClass('flow');
            if ($(this).hasClass('active')) {
                $(this).html('Collapse destinations');
            } else {
                $(this).html('View all');
            }
        });
        $('.destination-field .woocommerce-input-wrapper').on('click', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $('.optional', this).remove();
            var checkBoxes = $('input', this);
            var checkBoxesValue = $.trim($('label', this).text());
            $('.woocommerce-input-wrapper').removeClass('active');
            $('.woocommerce-input-wrapper input').prop('checked', false);
            checkBoxes.prop("checked", !checkBoxes.prop("checked"));
            checkBoxes.val(checkBoxesValue);
            $(this).toggleClass('active');
        });

        //Filter Search
        $('.filter-service').on('click', function (e) {
            e.stopPropagation();
            $('.services-chooser').toggle();
            $('.peoples-wrap').hide();
            $('.boats-type_wrap').hide();
            flatpickr.close();
        });
        if ($('.poeple-summury_adult').length) {
            let val = parseInt($('.poeple-summury_adult').val());
            if (val > 0) {
                countMoreA = val;
            }
        }
        if ($('.poeple-children_summury').length) {
            let val = parseInt($('.poeple-children_summury').val());
            if (val > 0) {
                countMoreC = val;
            }
        }
        if ($('.poeple-infants_summury').length) {
            let val = parseInt($('.poeple-infants_summury').val());
            if (val > 0) {
                countMoreI = val;
            }
        }
        $('.filter-people').on('click', function (e) {
            e.stopPropagation();
            $('.peoples-wrap').toggle();
            $('.services-chooser').hide();
            $('.boats-type_wrap').hide();
            flatpickr.close();
            var passengerAll = countMoreA + countMoreC + countMoreI;
            if (countMoreA > 0) {
                $('.filter-people').html(passengerAll + ' People ');
                var passengerCount = $('.filter-people').html(passengerAll + ' People ');
                for (var i = 0; i < passengerCount.length; i++) {
                    var pasCountFixed = passengerCount[i].innerText;
                    localStorage.setItem('pasAmount', pasCountFixed);
                }
            } else {
                $('.filter-people').html('People');
            }
        });

        $('.filter-boat').on('click', function (e) {
            e.stopPropagation();
            $('.boats-type_wrap').toggle();
            $('.peoples-wrap').hide();
            $('.services-chooser').hide();
        });

        // Mobile service/boat chooser
        $(document).on('click', '.services-chooser_item label', function () {
            let parent = $(this).parent();
            let prevService = $('.services-chooser_item.active').hasClass('md');
            $('#search-filled').remove();
            $('.services-chooser_item label').removeClass('active');
            $('.services-chooser_item').removeClass('active');
            $(this).addClass('active');
            parent.addClass('active');
            $('.services-chooser_item input').prop('name', '');
            parent.find('input').prop('name', 'activeType');
            $('.to_peoples-button').addClass('active');
            var labelTitleService = $(this).html();
            $('.filter-service').html(labelTitleService);
            $('.filter-boat').html('Boat Type');
            $('.boats-chooser_item .service-checkbox').attr('name', '');
            // $('.charter-time').text('Select date');
            if(!$('.single-services').length) {
                let service = $(this).closest('.services-chooser_item').hasClass('md');
                let serviceName = $(this).closest('.services-chooser_item').attr('data-slug');
                if(serviceName === undefined) {
                    serviceName = $(this).closest('.services-chooser_item').find('input').val();
                }
                if (service) {
                    flatpickr.clear();
                    flatpickr.destroy();
                    if($('.single-product').length) {
                        flatpickrConfig.mode = 'range';
                        if(serviceName == 'bareboat-charters' || serviceName == 'multi-day-charters') {
                            flatpickrConfig.showMonths = 2;
                        } else {
                            flatpickrConfig.showMonths = 1;
                        }
                        flatpickr = $(calendarBlock).flatpickr(flatpickrConfig);
                    } else {
                        flatpickrConfigS.mode = 'range';
                        if(serviceName == 'bareboat-charters' || serviceName == 'multi-day-charters') {
                            flatpickrConfigS.showMonths = 2;
                        } else {
                            flatpickrConfigS.showMonths = 1;
                        }
                        flatpickr = $(calendarBlock).flatpickr(flatpickrConfigS);
                    }
                    $('.day-start_input').val('');
                    $('.day-end_input').val('');
                    $('.filter-day').html('Days');
                } else {
                    flatpickr.clear();
                    flatpickr.destroy();
                    if($('.single-product').length) {
                        flatpickrConfig.mode = 'single';
                        if(serviceName == 'bareboat-charters' || serviceName == 'multi-day-charters') {
                            flatpickrConfig.showMonths = 2;
                        } else {
                            flatpickrConfig.showMonths = 1;
                        }
                        flatpickr = $(calendarBlock).flatpickr(flatpickrConfig);
                    } else {
                        flatpickrConfigS.mode = 'single';
                        if(serviceName == 'bareboat-charters' || serviceName == 'multi-day-charters') {
                            flatpickrConfigS.showMonths = 2;
                        } else {
                            flatpickrConfigS.showMonths = 1;
                        }
                        flatpickr = $(calendarBlock).flatpickr(flatpickrConfigS);
                    }
                    $('.day-start_input').val('');
                    $('.day-end_input').val('');
                    $('.filter-day').html('Day');
                }
            }

            if(!$('.single-content.third-party').length) {
                let price = parseInt(parent.attr('data-price'));
                let is_bookable = parseInt(parent.attr('data-bookable'));
                if(is_bookable && price > 0) {
                    $('.order-details_bottom').removeClass('hidden');
                } else {
                    $('.order-details_bottom').addClass('hidden');
                    $('.service-mobile_order-price').text('-');
                }
            }
        });

        //Search values recievers
        /*if($('.search-service_recieve').length) {
         $('.search-service_recieve').html(localStorage.getItem("servicelabel"));
         $('.filter-wrap_peoples--recieve').html(localStorage.getItem('pasAmount'));
         $('.filter-day_recieve').html(localStorage.getItem('orderDate'));
         $('.filter-boat-search--recieve').html(localStorage.getItem('boatlabel'));
         }*/

        $('.mobile_charter-type--dropdown label').on('click', function () {
            if($('.to-fleettype_button.boat').length) {
                $('#search-filled').remove();
                $('.to-fleettype_button.boat').addClass('active');
                let totalPeople = parseInt($(this).next().attr('data-total-people'));
                $('input.people-calc').val(0);
                $('input.people-calc').attr('max',totalPeople);
                $('.more-people,.less-people').attr('disabled',false);
                countMoreA = 0;
                countMoreC = 0;
                countMoreI = 0;
                maxTotal = totalPeople;
                peopleCounter = maxTotal;
            }
            let parent = $(this).parent();
            let currencyS = $('.mobile-order_form').attr('data-currency');
            let cleaning = parseInt($('.mobile-order_form').attr('data-cleaning'));
            var serviseCheckbox = $('.chooser-item_mobile input');
            let deposite_type = '';
            if($('.charter-options').length) {
                deposite_type = $('.charter-options').attr('data-deposite')
            } else {
                deposite_type = $('.mobile-order_form').attr('data-deposit') ? 'fixed' : 'percent';
            }
            let deposite_price = 0;
            let percentage = 0;
            if (deposite_type == 'fixed') {
                deposite_price = parseFloat($('.mobile-order_form').attr('data-depprice'));
            } else {
                percentage = parseFloat($('.mobile-order_form').attr('data-deppercentage'));
            }
            $('.servise-choser_label').removeClass('active');
            $('.chooser-item_mobile').removeClass('active');
            serviseCheckbox.prop('checked', false);
            $(this).toggleClass('active');
            $(this).parent().toggleClass('active');
            $('.service-checkbox', this).parent().prop('checked', true);
            $('.to-mdate_button').prop('disabled', false);
            $('.to-mdate_button').addClass('active');

            if ($('.service-boat_mobile-item').length) {
                var dataIDInput = $(this).closest('.service-boat_mobile-item').find('input').attr('data-id');
                $('.order-sidebar_form').attr('action', '/checkout/?add-to-cart=' + dataIDInput);
            } else {
                let value = $(this).closest('.services-chooser_item__mobile').find('input').val();
                let slug = $(this).closest('.services-chooser_item__mobile').attr('data-slug');
                if (value == 'Multi-Day Charters') {
                    $('.to-mdate_button').addClass('multi');
                } else {
                    $('.to-mdate_button').removeClass('multi');
                }
                if ($('select[name=attribute_pa_service]').length) {
                    $('select[name=attribute_pa_service]').val(slug);
                    $('select[name=attribute_pa_service]').trigger('change');
                }
            }


            let price = parseInt($(this).closest('.chooser-item_mobile').attr('data-price'));
            let total = price + cleaning;
            if (deposite_type == 'fixed') {
                $('.mobile-deposit span').text(currencyS + deposite_price);
            } else {
                deposite_price = total * (percentage / 100);
                $('.mobile-deposit span').text(currencyS + Math.round(deposite_price));
            }

            let defaultPpl = parseInt(parent.find('input').attr('data-default-ppl'));
            let extraPrice = parseInt(parent.find('input').attr('data-extra'));
            let passengerAll = countMoreA + countMoreC + countMoreI;
            if(defaultPpl > 0) {
                if(passengerAll > defaultPpl) {
                    let extraPeople = passengerAll - defaultPpl;
                    price += extraPeople * extraPrice;
                }
            }
            $('.details-bottom_row .service-mobile_order-price').text(currencyS + price);
            $('.order-top_left .service-mobile_order-price, .mobile-basefee .service-mobile_order-price').text(currencyS + Math.round(price - cleaning - deposite_price));
        });
        $('.boats-chooser_item label').on('click', function () {
            $('#search-filled').remove();
            let boatsValues = $('input[name="activeBoat"]').val();
            let boatsText = $('.filter-boat').text();
            let itemText = $(this).text();
            let value = '';
            if ($(this).parent().find('input').length) {
                value = $(this).parent().find('input').val();
            }

            if ($(this).parent().hasClass('all')) {
                $(this).toggleClass('active');
                $(this).parent().toggleClass('active');
                $('.boats-chooser_item:not(.all) label').removeClass('active');
                $('.boats-chooser_item:not(.all)').removeClass('active');
                $('input[name="activeBoat"]').val('');
                if (boatsText.length) {
                    localStorage.setItem("boatlabel", itemText);
                    $('.filter-boat').text(itemText);
                }
            } else {
                $('.boats-chooser_item.all label').removeClass('active');
                $('.boats-chooser_item.all').removeClass('active');
                if ($(this).parent().hasClass('active')) {
                    if (boatsValues != '') {
                        let new_value = boatsValues.replace(value + ',', '');
                        $('input[name="activeBoat"]').val(new_value);
                        if (boatsText.length) {
                            let new_text = '';
                            if (boatsText.indexOf(',' + itemText) !== -1) {
                                new_text = boatsText.replace(',' + itemText, '');
                            } else {
                                new_text = boatsText.replace(itemText, '');
                            }
                            $('.filter-boat').text(new_text);
                        }
                    }
                } else {
                    if (boatsValues != '') {
                        boatsValues += value + ',';
                        $('input[name="activeBoat"]').val(boatsValues);
                        if (boatsText.length) {
                            let new_text = boatsText + ',' + itemText;
                            $('.filter-boat').text(new_text);
                        }
                    } else {
                        $('input[name="activeBoat"]').val(value + ',');
                        $('.filter-boat').text(itemText);
                    }
                }
                let filterText = $('.filter-boat').text();
                let lastChar = filterText.slice(-1);
                if (lastChar == ',') {
                    filterText = filterText.slice(0, -1);
                }
                $('.filter-boat').text(filterText);
                $(this).parent().toggleClass('active');
                $(this).toggleClass('active');
            }
            if (!$('.boats-chooser_item.active').length) {
                $('.filter-boat').text('Boat Type')
            }

        });
        $('.peoples-wrap').on('click', function (e) {
            e.stopPropagation();
        })
        $('.peoples-wrap_inner').on('click', function (e) {
            e.stopPropagation();
        });

        let parent;
        if ($(this).closest('.mobile-order_form').length) {
            parent = $('.mobile-order_form');
        } else {
            parent = $('.order-sidebar');
        }
        let maxTotal = 0;
        if($('.mobile-order_form').length || $('.order-sidebar').length) {
            countMoreA = parseInt(parent.find('.people-calc[data-type=people]').val());
            countMoreC = parseInt(parent.find('.people-calc[data-type=children]').val());
            countMoreI = parseInt(parent.find('.people-calc[data-type=infants]').val());
            maxTotal = parseInt($('.charter-type_item.active').attr('max_people'));
        }
        let peopleCounter = maxTotal;
        if(countMoreA > 0) {
            peopleCounter = maxTotal - countMoreA;
        }
        $('.less-people').on('click', function () {
            $('#search-filled').remove();
            let input = $(this).next();
            let max = parseInt(input.attr('max'));
            let value = parseInt(input.val()) - 1;
            let type = input.attr('data-type');
            let parent = '';
            if ($(this).closest('.mobile-order_form').length) {
                parent = $('.mobile-order_form');
            } else {
                if($('.order-sidebar').length) {
                    parent = $('.order-sidebar');
                } else {
                    parent = $('.peoples-wrap');
                }
            }
            if (value >= 0) {
                input.val(value);
                if(peopleCounter < maxTotal) {
                    peopleCounter++;
                }
                if (peopleCounter == maxTotal) {
                    $('.less-people').prop('disabled', true).addClass('disabled');
                }
                $('.more-people').prop('disabled', false).removeClass('disabled');
                $("input[name=sc_people_adults]").val(parent.find('.people-calc[data-type=people]').val());
                $("input[name=sc_people_children]").val(parseInt(parent.find('.people-calc[data-type=children]').val()));
                $("input[name=sc_people_infants]").val(parseInt(parent.find('.people-calc[data-type=infants]').val()));
                countMoreA = parseInt(parent.find('.people-calc[data-type=people]').val());
                countMoreC = parseInt(parent.find('.people-calc[data-type=children]').val());
                countMoreI = parseInt(parent.find('.people-calc[data-type=infants]').val());
            }
            let passengerAll = countMoreA + countMoreC + countMoreI;
            $("input[name=sc_people_total]").val(passengerAll);

            if(is_hidden_filled()){
                update_prices();
            }

            if (peopleCounter == maxTotal) {
                if(!$('.single-product').length) {
                    $('.to-fleettype_button').attr('disabled', true);
                    $('.to-fleettype_button').removeClass('active');
                    $('.to-checkout_button').addClass('disabled');
                }
            }
        });
        $('.more-people').on('click', function () {
            $('#search-filled').remove();
            let input = $(this).prev();
            let max = parseInt(input.attr('max'));
            let value = parseInt(input.val());
            let parent = '';
            window.console.log(value);
            if ($(this).closest('.mobile-order_form').length) {
                parent = $('.mobile-order_form');
            } else {
                if ($(this).closest('.main-banner_filter').length) {
                    parent = $('.main-banner_filter');
                } else {
                    parent = $('.order-sidebar');
                }
            }
            let maxTotal = $('.charter-type_item.active').attr('max_people');
            if (value < maxTotal || isNaN(max)) {
                input.val(++value);
                if (!isNaN(peopleCounter)) {
                    peopleCounter--;
                    if (peopleCounter == 0) {
                        window.console.log(max);
                        window.console.log(maxTotal);
                        $('.more-people').prop('disabled', true).addClass('disabled');
                    }
                }
                $('.less-people').prop('disabled', false).removeClass('disabled');
                $("input[name=sc_people_adults]").val(parent.find('.people-calc[data-type=people]').val());
                $("input[name=sc_people_children]").val(parseInt(parent.find('.people-calc[data-type=children]').val()));
                $("input[name=sc_people_infants]").val(parseInt(parent.find('.people-calc[data-type=infants]').val()));
                countMoreA = parseInt(parent.find('.people-calc[data-type=people]').val());
                countMoreC = parseInt(parent.find('.people-calc[data-type=children]').val());
                countMoreI = parseInt(parent.find('.people-calc[data-type=infants]').val());
            }
            let passengerAll = countMoreA + countMoreC + countMoreI;
            $("input[name=sc_people_total]").val(passengerAll);

            console.log(is_hidden_filled());
            if(is_hidden_filled()){
                update_prices();
            }

            if (parseInt(parent.find('.people-calc[data-type=people]').val()) > 0) {
                if (Date.parse($('input[name=sc_trip_start]').val())) {
                    $('.to-checkout_button').removeClass('disabled');
                }
                $('.to-fleettype_button').attr('disabled', false);
                $('.to-fleettype_button').addClass('active');
                $('.to-mdate_button--search').addClass('active');
            }

        });

        $('.search-submit').on('click', function () {
            var peopleCountAll = parseInt(countMoreA) + parseInt(countMoreC) + parseInt(countMoreI);
            $('.people-input_holder').attr('value', peopleCountAll);
        });

        $('.charter-passenger').on('click', function (e) {
            e.stopPropagation();
            $('.charter-type_item').removeClass('hide');
            $('.peoples-wrap').toggle();
            $('.charter-type_dropdown').hide();
            var passengerAll = parseInt(countMoreA) + parseInt(countMoreC) + parseInt(countMoreI);
            if (countMoreA > 0) {
                $('.charter-passenger_amount').html(passengerAll + ' People ');
                var passengerCount = $('.charter-passenger_amount').html(passengerAll + ' People ');
                for (var i = 0; i < passengerCount.length; i++) {
                    var pasCountFixed = passengerCount[i].innerText;
                    localStorage.setItem('pasAmount', pasCountFixed);
                    if ($('.peoples-ava').length) {
                        $('.peoples-ava').attr('value', pasCountFixed);
                    }
                }
            } else {
                $('.charter-passenger_amount').html('People');
                if ($('.peoples-ava').length) {
                    $('.peoples-ava').attr('value', 'People');
                }
            }
        });

        $('.filter-wrap_calendar').on('click', function (e) {
            e.stopPropagation();
        });

        $('.close-order_form').on('click', function (e) {
            e.preventDefault();
            $('.mobile-order_form').hide();
            $('.main-banner_filter').hide();
            $('header').css('z-index', '100');
            if (!$('.hamburger').hasClass('active')) {
                $('body').removeClass('overflow');
            }
        });

        $('.to-fleettype_button').on('click', function (e) {
            e.preventDefault();
            if($(this).hasClass('boat')) {
                $('.mobile-order_form--fleet').hide();
                $('.mobile-order_form--people').show();
            } else {
                $('.mobile-order_form--fleet').show();
                $('.mobile-order_form--people').hide();
            }
            $('.mobile_charter-type--dropdown').show();
            if ($(this).hasClass('to-fleettype_button--service')) {
                if($('.mobile-order_form--fleet').length) {
                    $('.charter-type_item').each(function () {
                        var personsToCompare = $(this).attr("data-persons");
                        var personsToCompareParsed = parseInt(personsToCompare);
                        var personsToExist = countMoreA + countMoreC;
                        var personsToExistParsed = parseInt(personsToExist);
                        if (personsToCompareParsed < personsToExistParsed) {
                            $(this).addClass('hide');
                        }
                    });
                } else {
                    $('.mobile-order_form--date').show();
                    $('.mobile-order_form--fleet').hide();
                    $('.mobile-order_form--people').hide();
                    $(this).parent().parent().find('.filter-wrap_peoples').hide();
                    $(this).parent().parent().find('.filter-wrap_calendar').show();
                    flatpickr.open();
                    $('.flatpickr-calendar').addClass('unhidden');
                }
            }
            if($('.charter-type_item.active').length) {
                $('.to-mdate_button').addClass('active').attr('disabled',false);
            }
        });
        $('.to-mdate_button').on('click', function (e) {
            e.stopPropagation();
            $('.mobile-order_form--date').show();
            $('.mobile-order_form--fleet').hide();
            $('.mobile-order_form--people').hide();
            $(this).parent().parent().find('.filter-wrap_peoples').hide();
            $(this).parent().parent().find('.filter-wrap_calendar').show();
            flatpickr.open();
            $('.flatpickr-calendar').addClass('unhidden');
        });
        $('.to_people-button').on('click', function (e) {
            e.preventDefault();
            $('.mobile-order_form--people').show();
            $('.mobile-order_form--fleet').hide();
            $('.filter-wrap_calendar').hide();
            $('.flatpickr-calendar').hide();
            $('.filter-wrap_peoples').show();
            $('.charter-type_item').removeClass('hide');
            flatpickr.close();
            $('.flatpickr-calendar').removeClass('unhidden');
        });
        $('.to_peoples-button').on('click', function (e) {
            e.preventDefault();
            $(this).parent().parent().find('.filter-wrap_service').hide();
            $(this).parent().parent().find('.filter-wrap_peoples').show();
            if(countMoreA > 0) {
                $('.to-mdate_button').addClass('active').attr('disabled',false);
            }
        });
        $('.to_calendar-button').on('click', function (e) {
            e.preventDefault();
            $('.mobile-order_form--date').show();
            $('.mobile-order_form--fleettype').hide();
            $('.filter-wrap-boattype').hide();
            $('.filter-wrap_calendar').show();
            $('.flatpickr-calendar').show();
        });
        $('.to_mfleets-button').on('click', function (e) {
            e.preventDefault();

            if($('.mobile-order_form--fleet').length) {
                $('.mobile-order_form--fleet').show();
                $('.mobile-order_form--people').hide();
                $('.filter-wrap_service').show();
                $('.services-chooser').show();
                $('.mobile-order_form--date').hide();
                $('.filter-wrap_peoples').hide();
                $('.flatpickr-calendar').hide();
                flatpickr.close();
                $('.flatpickr-calendar').removeClass('unhidden');
            } else {
                $('.mobile-order_form--people').show();
                $('.filter-wrap_service').hide();
                $('.services-chooser').hide();
                $('.mobile-order_form--date').hide();
                $('.filter-wrap_peoples').hide();
                $('.flatpickr-calendar').hide();
                flatpickr.close();
                $('.flatpickr-calendar').removeClass('unhidden');
            }
        });

        $('.to-fleettypeS_button').on('click', function (e) {
            e.preventDefault();
            $('.flatpickr-calendar').hide();
            $(this).parent().parent().find('.mobile-order_form--fleet').hide();
            $(this).parent().parent().find('.mobile-order_form--date').hide();
            $(this).parent().parent().find('.filter-wrap_calendar').hide();
            $(this).parent().parent().find('.mobile-order_form--fleettype').show();
            $(this).parent().parent().find('.filter-wrap-boattype').show();
        });

        $('.mobile-filter').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $('.main-banner_filter').show();
            $('.main-banner_filter .filter-wrap_peoples').hide();
            $('header').css('z-index', '2');
            $('body').addClass('overflow');
        });

        $('.edit-order').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if(!$(this).hasClass('boat')) {
                if(countMoreA > 0) {
                    $('.to-fleettype_button').attr('disabled',false).addClass('active');
                }
            }

            $('.mobile-order_form').show();
            if($(this).hasClass('boat')) {
                $('.mobile-order_form .mobile-order_form--fleet').show();
                $('.mobile-order_form .mobile-order_form--people').show();
            } else {
                $('.mobile-order_form .mobile-order_form--people').show();
            }
        });

        /* Request availability conditional logic */

        $('button.request-av-more').on('click', function (e) {
          e.preventDefault();
          $(this).toggleClass('active');
          $('.mobile-order_sidebar').toggleClass('active');
        });

        (function($) {
          let $fields = $('input[type="hidden"].sc_to_price');
          let $button = $('.to-checkout_button.availability-check_button:not(.service-btn)');

          function updateButtonState() {
            let disableButton = false;

            $fields.each(function() {
              let $this = $(this);
              if (!$this.val() || $this.val() == "0") {
                disableButton = true;
              }
            });

            if (disableButton) {
              $button.addClass('disabled');
              $button.prop('disabled', true);
            } else {
              $button.removeClass('disabled');
              $button.prop('disabled', false);
            }
          }

          setInterval(updateButtonState, 500);

          $button.on('click', function(e) {
            if ($button.hasClass('disabled')) {
              e.preventDefault();
              return false;
            }
          });
        })(jQuery);

        /* End of Request availability conditional logic */

        // Check for availibility on boat page
        $('.availability-check_button:not(.service-btn)').on('click', function (e) {
            e.preventDefault();
              if(!$(this).hasClass('disabled')) {
                $.ajax({
                  type: 'POST',
                  url: s_ajax_url,
                  data: {
                    action: "sc_update_data",
                    people_total: $('input[name=sc_people_total]').val(),
                    adults: $('input[name=sc_people_adults]').val(),
                    children: $('input[name=sc_people_children]').val(),
                    infants: $('input[name=sc_people_infants]').val(),
                    trip_start: $('input[name=sc_trip_start]').val(),
                    trip_end: $('input[name=sc_trip_end]').val(),
                    trip_duration: $('input[name=sc_trip_duration]').val(),
                    variation_id: $('input[name=sc_variation_id]').val(),
                    service_id: $('input[name=sc_service_id]').val()
                  },
                  success: function (response) {
                    console.log(response);
                    prices = JSON.parse(response);
                    $('.request-boat-price').text('€ ' + prices['boat-price'])

                    if (!$(this).hasClass('disabled')) {

                      $('.availability-content').css('top', $('header').outerHeight() + 'px');
                      $('.availability-content').css('height', 'calc(100vh - ' + $('header').outerHeight() + 'px)');
                      $('.availability-content').show();
                      $('body').addClass('overflow');
                      if ($('.charter-type_current input').length) {
                        // setInterval(function(){
                        let service = $('input[name=sc_service_name]').val();
                        $('.wpcf7-form-control-wrap input[name="your-service"]').val(service);
                        $('.charter-type_aviability').text(service);

                        if (service == 'bareboat-charters') {
                          $('.details-row.nightrate').show();
                        } else {
                          $('.details-row.nightrate').hide();
                        }
                      }
                      $('.wpcf7-form-control-wrap input[name=your-date-start]').val($('input[name=sc_trip_start]').val());
                      $('.wpcf7-form-control-wrap.your-date-end input').val($('input[name=sc_trip_end]').val());
                      $('.wpcf7-form-control-wrap input[name=your-people-amount]').val(parseInt($('input[name=sc_people_total]').val()));

                    }
                    ;
                    // display_pre_order_totals(response);

                    checkConditions();
                    // $('.charter-options').show();
                  }
                });
              }
        });

        // Check for availibility on service page
        $('.availability-check_button.service-btn').on('click', function (e) {
            if(!$(this).hasClass('disabled')) {
                $('.availability-content').css('top',$('header').outerHeight()+'px');
                $('.availability-content').css('height','calc(100vh - ' + $('header').outerHeight()+'px)');
                $('.availability-content').show();
                $('body').addClass('overflow');
                $('.wpcf7-form-control-wrap.your-service input').val($('.availability-inner_description h4').text());
                if($('.single-services').length && !$('.charter-type_service').length) {
                    $('.wpcf7-form-control-wrap input[name="your-boat"]').val('');
                } else {
                    $('.wpcf7-form-control-wrap input[name="your-boat"]').val($('.charter-type_current input').val() + ' - ' + $('.charter-type_current input').attr('data-boatname'));
                }
                $('.wpcf7-form-control-wrap input[name=your-date-start]').val($('input[name=sc_trip_start]').val());
                $('.wpcf7-form-control-wrap.your-date-end input[name=your-date-end]').val($('input[name=sc_trip_end]').val());
                $('.wpcf7-form-control-wrap input[name=your-people-amount]').val(parseInt($('input[name=sc_people_total]').val()));
                $('.order-top_left').removeClass('active');
                $('.mobile-order_sidebar').removeClass('active');
                $('.mobile-order_sidebar').css('transform','translateY(100%)');
            }
        });


        $('.back-from_availability').on('click', function (e) {
            e.preventDefault();
            $('.availability-content').hide();
            $('body').removeClass('overflow');
            let sidebarH = $('.mobile-order_sidebar').outerHeight();
            let visibleH = sidebarH - 95;
            $('.mobile-order_sidebar').css('transform','translateY(' + visibleH + 'px)');
        });
        $('.checkout .back-from_availability').on('click', function (e) {
            e.preventDefault();
            window.history.back();
        });

        $('.custom-remove_button').on('click', function (e) {
            e.preventDefault();
            $(document.body).trigger('update_checkout');
            var product_id = $(this).attr("data-product_id"),
                cart_item_key = $(this).attr("data-cart_item_key");

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: s_ajax_url,
                data: {
                    action: "product_remove",
                    product_id: product_id,
                    cart_item_key: cart_item_key,
                },
                success: function (response) {
                    if (!response || response.error)
                        return;

                    var fragments = response.fragments;

                    // Replace fragments
                    if (fragments) {
                        $.each(fragments, function (key, value) {
                            $(key).replaceWith(value);
                        });
                    }
                    location.reload();
                }

            });

        });

        /*$('.search-submit').on('click', function() {
         var serviceChose = $('.chooser-item.active input').val();
         var peopleAChoose = 'people' + '+' + countMoreA;
         var searchResult = serviceChose + '+' + peopleAChoose;
         $('.search-input').attr('value', searchResult);
         });*/

        //Destinations popup
        $('.destination-button').on('click', function (e) {
            e.preventDefault();
            $('.destinations-popup').fadeIn();
            $('.destinations-popup_inner').eq($(this).parent().parent().index()).fadeIn();
            $('body').addClass('overflow');
        });
        $('.close-destinations').on('click', function (e) {
            e.preventDefault();
            $('.destinations-popup_inner').fadeOut();
            $('.destinations-popup').fadeOut();
            $('body').removeClass('overflow');
        });

        //Mobile menu toggle
        $('.hamburger').on('click', function (e) {
            e.stopPropagation();
            $(this).toggleClass('active');
            $('body').toggleClass('overflow');
            $('.mobile-menu').toggle();
            $('.main-banner_filter').hide();
            $('.mobile-order_sidebar').removeClass('active');
            $('header').css('z-index', '100');
            $('.flatpickr-calendar').hide();
        });

        //Tabs
        $(".rates-tabs_content--wrap").not(":first").hide();
        $(".rates-tabs_list li").click(function (e) {
            if(!$(this).hasClass('current')) {
                $(".rates-tabs_list li").removeClass("current");
                $(this).addClass("current");
                $(".rates-tabs_content--wrap").hide();
                let table = $(".rates-tabs_content--wrap").eq($(this).index());
                table.show();
                setTimeout(function(){
                    let tableW = 0;
                    table.find('.content-item_headers p').each(function(){
                        tableW += $(this).outerWidth();
                    });
                    table.css('width',tableW + 'px');
                },100);
            }
        }).eq(0).addClass("current");

        let menuItemIndex = 0;
        $(".checkout-menu_list .menu-item").click(function (e) {
            if(!$('.ty__day-list').length) {
                let index = $(this).index();
                let is_food_in_cart = $('.product-details_food').length;
                if (is_food_in_cart) {
                    $('.cmp__inner').fadeIn();
                    menuItemIndex = $(this).index();
                } else {
                    $('.checkout-menu_list .menu-item').removeClass('current');
                    $('.checkout-menu_list .menu-item').eq(index).addClass('current');
                    $(".checkout-menu_content").removeClass('active').eq(index).addClass('active');
                    if ($('.edit-order_menu--holder').length && $('.checkout-menu_content.active .food-list_item').length) {
                        $('.edit-order_menu--holder').show();
                    }
                }
                $('.food-list_item').removeClass('active');
            } else {
                $('.food-list_item').removeClass('active');
                $(this).toggleClass('current');
            }
        });

        $('.cmp__inner .cmp__btn').on('click', function () {
            $('.checkout-menu_list .menu-item').removeClass('current');
            $('.checkout-menu_list .menu-item').eq(menuItemIndex).addClass('current');
            $(".checkout-menu_content").removeClass('active').eq(menuItemIndex).addClass('active');
            if ($('.edit-order_menu--holder').length) {
                $('.edit-order_menu--holder').show();
            }
            $.ajax({
                type: 'POST',
                url: ajax_url,
                data: {
                    action: "sc_remove_food_from_cart"
                },
                success: function (response) {
                    response = JSON.parse(response);
                    $('.food-list_item-q input').val(0);
                    $('.product-details_food').remove();
                    $('.product-details_title.food').hide();
                    $('.cmp__inner').fadeOut();

                    let currency = $('.shop_table').attr('data-currency');
                    let boatPrice = $('.product-details_service.boat-price').attr('data-price');
                    $('.product-details_service.boat-price .servise-price_order').text(currency + ' ' + boatPrice);

                    $('.product-details_service.deposite .servise-price_order').html('').html(response['deposite']);
                    $('.order-total .total-td').html('').html(response['total']);
                }
            });
            $('.cmp__inner').fadeOut();
            menuItemIndex = 0;
        });

        $('.cmp__inner .cmp__close').on('click', function () {
            $('.cmp__inner').fadeOut();
        });

        $(".multyday-foods_content").not(":first").hide();
        $(".multyday-table_header li").click(function (e) {
            $(".multyday-table_header li").removeClass("current").eq($(this).index()).addClass("current");
            $(".multyday-foods_content").hide().eq($(this).index()).show();
        }).eq(0).addClass("current");


        //More description button
        if ($('.single-description_inner').height() > 100) {
            $('.single-description_inner').parent().find('.read-more').show();
        }
        // $('.read-more').on('click', function (e) {
        //     e.preventDefault();
        //     $(this).toggleClass('active');
        //     $('.single-description_inner').toggleClass('active');
        //     if ($(this).hasClass('active')) {
        //         $(this).html('Collapse text');
        //     } else {
        //         $(this).html('Read more');
        //     }
        // });

        //Read more button
        $('.single-description_inner').text(function(_, txt) {
            descriptionText = $('.single-description_inner').html();
            descriptionLength = $('.single-description_inner').html().length;
            if(txt.length > 250){
                $('.product-single_content .read-more').show();
                txt = descriptionText.slice(0, 250) + "...";
                $(this).html(txt);
            }
            else {
                $('.product-single_content .read-more').hide();
                $(".single-description_inner").css('margin-bottom', '40' + 'px');
            }

            $(".read-more").on("click", function (e) {
                e.preventDefault();
                $(this).toggleClass('active');
                if($(this).hasClass('active')) {
                    $(this).html('Read Less');
                    $(".single-description_inner").html(descriptionText);
                    $('.single-description_inner p').each(function() {
                        var tdText =  $(this).text();
                        if($.trim($(this).text()).length == 0) {
                            $(this).remove();
                        }
                    });
                }
                else {
                    $(this).html('Read More');
                    $(".single-description_inner").html(txt);
                    $('.single-description_inner p').each(function() {
                        var tdText =  $(this).text();
                        if($.trim($(this).text()).length == 0) {
                            $(this).remove();
                        }
                    });
                }
            });
        });

        $('.spec-description').each(function () {
            var $this = $(this);
            var paragraphCount = $('p', this).length;
            var listCount = $('ul li', this).length;
            if (paragraphCount > 4) {
                $(this).parent().find('.view-specification').show();
            }
            if (listCount > 4) {
                $(this).parent().find('.view-specification').show();
            }
        });

        $('.more-cancellation_text').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $('.cancellation-policy_checkout--description').toggleClass('active');
            if ($(this).hasClass('active')) {
                $(this).html('Collapse text');
            } else {
                $(this).html('Read more');
            }
        });

        $('.view-specification').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $(this).parent().find('.spec-description').toggleClass('active');
            if ($(this).hasClass('active')) {
                $(this).html('Collapse text');
            } else {
                $(this).html('View all');
            }
        });

        //Mobile Order visibility
        $('.order-top_left').on('click', function (e) {
            e.stopPropagation();
            $(this).toggleClass('active');
            $('.mobile-order_sidebar').toggleClass('active');
            if(!$('.mobile-order_sidebar.active').length && !$('.order-details_bottom').is(':visible')) {
                let sidebarH = $('.mobile-order_sidebar').outerHeight();
                let visibleH = sidebarH - 95;
                $('.mobile-order_sidebar').css('transform','translateY(' + visibleH + 'px)');
            }
        });

        $('.charter-type').on('click', function (e) {
            e.stopPropagation();
            $('.charter-type_dropdown').toggle();
            $('.flatpickr-calendar').hide();
            $('.peoples-wrap.peoples-wrap_inner').hide();
            $('.charter-time').removeClass('active');
            if ($(this).hasClass('charter-type_service')) {
                $('.charter-type_item').each(function () {
                    var personsToCompare = $(this).attr("data-persons");
                    var personsToCompareParsed = parseInt(personsToCompare);
                    var personsToExist = countMoreA + countMoreC;
                    var personsToExistParsed = parseInt(personsToExist);
                    if (personsToCompareParsed < personsToExistParsed) {
                        $(this).addClass('hide');
                    }
                });
            }
        });

        if ($('.boat-ava').length) {
            let productFullTitle = $('.product-single_title').text();
            if($('.boat-custom-name').length) {
                productFullTitle += ' - ' + $('.boat-custom-name').text();
            }
            $('.boat-ava').attr('value', productFullTitle);
        }


        if ($('select[name=attribute_pa_service]').length) {
            $('select[name=attribute_pa_service]').val($('.charter-type_current input').attr('data-slug'));
            $('select[name=attribute_pa_service]').trigger('change');
        }

        $('.charter-type_item').on('click', function () {
            $('.charter-type_item').removeClass('active');
            $(this).addClass('active');
            $('#search-filled').remove();
            let persons = $(this).attr('data-persons');
            let variation_id = $(this).attr('variation_id');
            let service_id = $(this).attr('service_id');
            if(persons == undefined) {
                persons = $(this).find('input').attr('data-total-ppl');
            }
            let slug = $(this).attr('data-slug');
            // let price = parseFloat($(this).attr('data-price'));
            $('.charter-time_multy').html('');
            $('.charter-time_multy').append('<span class="date-from">From</span>');
            $('.charter-time_multy').append('<span class="date-to">To</span>');

            $('input[name=sc_variation_id]').val(variation_id);
            $('input[name=sc_service_id]').val(service_id);

            $('input[name=sc_service_name]').val($.trim($('.charter-type_item.active').text()));

            let maxTotal = 0;
            if($('.mobile-order_form').length || $('.order-sidebar').length) {
                countMoreA = parseInt(parent.find('.people-calc[data-type=people]').val());
                countMoreC = parseInt(parent.find('.people-calc[data-type=children]').val());
                countMoreI = parseInt(parent.find('.people-calc[data-type=infants]').val());
                maxTotal = parseInt($('.charter-type_item.active').attr('max_people'));
            }
            peopleCounter = maxTotal;
            if(countMoreA > 0) {
                peopleCounter = maxTotal - countMoreA;
            }

            if(is_hidden_filled()){
                update_prices();
            }

        });

        $('body').on('click','.to-checkout_button.disabled', function (e) {
            if ($(this).hasClass('disabled')) {
                e.preventDefault();
                e.stopPropagation();
                if (countMoreA < 1) {
                    $('.peoples-wrap').show();
                } else {
                    if($('.single-product').length) {
                        if (!$('.charter-type_item.active').length) {
                            $('.charter-type').trigger('click');
                        } else {
                            $('.flatpickr-input').trigger('click');
                        }
                    } else {
                        if (!$('.charter-boat_item.active').length) {
                            $('.charter-type_service').trigger('click');
                        } else {
                            $('.flatpickr-input').trigger('click');
                        }
                    }
                }
            }
        })

        if ($('.charter-time').length) {
            var calendarPosition = $('.charter-time').offset().top;
        }
        if ($('.home .flatpickr-calendar').length) {
            var calendarPositionHome = $('.filter-day').offset().top;
            var positionCalendarLeft = $('.filter-wrap_calendar').position().left;
        }
        if ($('.search .flatpickr-calendar').length) {
            var calendarPositionSearch = $('.search .filter-day').offset().top;
            var positionCalendarLeftSearch = $('.filter-wrap_calendar').position().left;
        }

        $('.charter-time').on('click', function (e) {
            e.stopPropagation();
            $(this).toggleClass('active');
            // $('.flatpickr-calendar:not(.rangeMode)').toggle();
            var dateContent = $('.flatpickr-day.selected').attr("aria-label");
            $('.peoples-wrap.peoples-wrap_inner').hide();
            $('.charter-type_dropdown').hide();
            if ($('.flatpickr-day').hasClass('selected')) {
                var dateContentParsed = dateContent.lastIndexOf(" ");
                dateContent = dateContent.substring(0, dateContentParsed);
                var dateContentSliced = dateContent.slice(0, -1);
                //let startTime = $('.order-sidebar .charter-input span').text();
                $('.charter-time').html(dateContentSliced + startTime);
                var dateTImeParsed = $('.charter-time').html(dateContentSliced + startTime);
                $('.date-reciever_aviability').html(dateContentSliced + startTime);
                for (var i = 0; i < dateTImeParsed.length; i++) {
                    var dateFixed = dateTImeParsed[i].innerText;
                    localStorage.setItem('orderDate', dateFixed);
                    if ($('.startdate-ava').length) {
                        $('.startdate-ava').attr('value', dateFixed);
                    }
                }
                if (countMoreA > 0) {
                    $('.to-checkout_button').removeClass('disabled');
                }
            }
        });

        $('.charter-type_dropdown div').on('click', function () {
            $('.charter-type_current').html($(this).html());
            $('.charter-type_aviability').html($(this).html());
        });
        $('.mobile_charter-type--dropdown div').on('click', function () {
            $('.charter-type_current').html($(this).html());
        });


        // if($('.charter-time_single').length && $('.charter-type_current input').attr('data-slug') != 'multi-day-charters') {
        //     setInterval(function(){
        //         $('input[name=mvvwb_start]').val($('input[name="mvvwb_end"]').val());
        //     },100);
        // }

        if (innerWidth > 500) {
            //Body click
            $('body').on('click', function () {
                $('.charter-type_dropdown').hide();
                $('.peoples-wrap').hide();
                $('.charter-time').removeClass('active');
                $('.boats-type_wrap').hide();
                if($('.services-chooser').is(':visible')) {
                    $('.services-chooser').hide();
                }
                var passengerAll = parseInt(countMoreA) + parseInt(countMoreC) + parseInt(countMoreI);
                if (countMoreA > 0) {
                    $('.filter-people').html(passengerAll + ' People ');
                    var passengerCount = $('.filter-people').html(passengerAll + ' People ');
                    for (var i = 0; i < passengerCount.length; i++) {
                        var pasCountFixed = passengerCount[i].innerText;
                        localStorage.setItem('pasAmount', pasCountFixed);
                    }
                    $('.charter-passenger_amount').html(passengerAll + ' People ');
                    var passengerCount = $('.charter-passenger_amount').html(passengerAll + ' People ');
                    for (var i = 0; i < passengerCount.length; i++) {
                        var pasCountFixed = passengerCount[i].innerText;
                        localStorage.setItem('pasAmount', pasCountFixed);
                        if ($('.peoples-ava').length) {
                            $('.peoples-ava').attr('value', pasCountFixed);
                        }
                    }
                } else {
                    $('.charter-passenger_amount').html('People');
                    $('.filter-people').html('People');
                    if ($('.peoples-ava').length) {
                        $('.peoples-ava').attr('value', 'People');
                    }
                }
            });
        }

        if (innerWidth < 500) {
            $(document).on('click', '.services-chooser_item__mobile', function (e) {
                $('.services-chooser').show();
            });

            $('.search-preform_info').on('click', function () {
                $('.search-page_filter').show();
                if($('.services-chooser_item.chooser-item.active').length) {
                    $('.to_peoples-button').addClass('active').attr('disabled',false);
                }
            });

            $('.nav-top-button').on('click', function (e) {
                e.stopPropagation();
            });

            $('.service-label-multi-day-charters').on('click', function () {
                $('.flatpickr-calendar').css('opacity', '0 !important');
            });
        }

        if ($('form.cart .rangeMode').length) {
            $('form.cart .rangeMode').css('z-index', '999');
        }

        setTimeout(function () {
            if ($('.charter-type_current input').length) {
                let slug = $('.charter-type_current input').attr('data-slug');
                if (slug == 'multi-day-charters' || slug == 'bareboat-charters') {
                    let start = $('.charter-time_single').attr('data-startdate');
                    if (start != '') {
                        $('input[name="mvvwb_start"]').val(start);
                    }
                    let end = $('.charter-time_single').attr('data-enddate');
                    if (end != '') {
                        $('input[name="mvvwb_end"]').val(end);
                        let duration = parseInt($('.charter-time_single').attr('data-duration'));
                        let lastSelect = parseInt($('select[name=mvvwb_duration] option:last-child').val());
                        if (duration > lastSelect) {
                            $('input[name=mvvwb_duration],select[name=mvvwb_duration]').val(lastSelect);
                        } else {
                            $('input[name=mvvwb_duration],select[name=mvvwb_duration]').val(duration);
                        }
                    }
                } else {
                    let start = $('.charter-time_single').attr('data-startdate');
                    if (start != '') {
                        $('input[name="mvvwb_start"]').val(start);
                        $('input[name="mvvwb_end"]').val(start);
                    }
                }
            }
        }, 1500);


        if ($('.single-gallery a').length > 0 && $('.single-gallery a').length <= 4) {
            $('.single-gallery').addClass('one-image_gallery');
        }

        if ($('.cancellation-policy_checkout--description').length) {
            var countCharacters = $('.cancellation-policy_checkout--description').text().length;
            if (countCharacters > 200) {
                $('.more-cancellation_text').show();
            }
        }

        document.addEventListener("wpcf7mailsent", function (event) {
            window.setTimeout(function () {
                $('.thankyou-popup').show();
            }, 0);
        }, false);

        $('.edit-order_link').on('click', function () {
            $('.edit-order_popup').show();
            $('.thankyou-wrap').hide();
        });

        $('.edit-food_popup--button').on('click', function (e) {
            e.preventDefault();
            $('.edit-order_menu--holder').hide();
        });

        $('.undisabled').removeClass('disabled');

        if ($('.destinations-popup_inner').length) {
            $('.destinations-popup_inner').each(function () {
                $('.destinations-titles p', this).html($('.destination-item', this).length + ' Results found')
            })

        }

        //Viewbox
        $(".viewbox").viewbox();

        $('.charter-sidebar-cats .charter-type_item').on('click', function () {
            $('#search-filled').remove();
            let is_bookable = parseInt($(this).attr('data-bookable'));
            let price =  parseInt($(this).attr('data-price'));
            let value = $(this).closest('form').find('input[name="sc_service_name"]').val();


            if (value == 'Multi-Day Charter' || value == 'Bareboat Charter') {
                $('.charter-time_single').html('');
                $('.charter-time_single').append('<span class="date-from">From</span>');
                $('.charter-time_single').append('<span class="date-to">To</span>');
                $('.charter-time_single').addClass('multi');
                flatpickrConfig.mode = 'range';
                if(value == 'Bareboat Charter' || value == 'Multi-Day Charter') {
                    flatpickr.clear();
                    flatpickr.destroy();
                    flatpickrConfig.showMonths = 2;
                    flatpickr = $(calendarBlock).flatpickr(flatpickrConfig);
                } else {
                    flatpickr.clear();
                    flatpickr.destroy();
                    flatpickrConfig.showMonths = 1;
                    flatpickr = $(calendarBlock).flatpickr(flatpickrConfig);
                }
                flatpickr.clear();
            } else {
                $('.charter-time_single').html('Select date');
                $('.charter-time_single').removeClass('multi');
                flatpickr.clear();
                flatpickr.destroy();
                flatpickrConfig.mode = 'single';
                flatpickrConfig.showMonths = 1;
                flatpickr = $(calendarBlock).flatpickr(flatpickrConfig);
                flatpickr.clear();
            }
            if(!$('.fleet-content.third-party').length && !$('.single-content.third-party').length) {
                if(!is_bookable || price <= 0) {
                    // $('.charter-options').hide();
                    $('.cart-first_button').hide();
                    $('.availability-check_button').removeClass('hidden');
                    $('.availability-inner_details.price').hide();
                } else {
                    // $('.charter-options').show();
                    $('.cart-first_button').show();
                    $('.availability-check_button').addClass('hidden');
                    $('.availability-inner_details.price').show();
                }
            }
        });

        $('.back-link').on('click', function (e) {
            e.preventDefault();
            window.history.go(-1);
        });

        $('.to-payment-btn').on('click', function (e) {
            e.preventDefault();
            window.scrollTo({top: 0, behavior: 'smooth'});
        });

        $('body').on('click', '.to-checkout_button.mobile-btn:not(.disabled)', function (e) {
            e.preventDefault();
            $('.order-sidebar_form').submit();
        });

        if (countMoreA > 0 && $('input[name=mvvwb_start]').val() && $('input[name=mvvwb_end]').val() && $('.charter-type_current input').val()) {
            $('.to-checkout_button').removeClass('disabled');
        }
        if (countMoreA > 0) {
            $('input[name=mvvwb_adult]').val(countMoreA);
        }
        if (countMoreC > 0) {
            if (countMoreI > 0) {
                $('input[name=mvvwb_children]').val(countMoreC);
            } else {
                $('input[name=mvvwb_children]').val(countMoreC);
            }
        }

        $(window).on('load', function () {
            setTimeout(function () {
                if ($('.availability-top').attr('data-service') != '') {
                    $('input[name=your-service]').val($('.availability-top').attr('data-service'));
                }
            }, 1000);
        });

        // Calendar for single product/service pages
        let calendarBlock;
        let mode = "single";
        let showMonths = 1;
        let serviceName = '';
        if($('.charter-type_item.active').length) {
            serviceName = $('.charter-type_item.active').attr('data-slug');
        }


        if ($('.single-services,.single-product').length) {
            calendarBlock = '.charter-time_single';
            if ($('.multi-day-charters').length) {
                mode = "range";
                showMonths = 2;
                calendarBlock = '.charter-time_multy';
            }
            if($('.charter-type_current input').attr('data-slug') == 'multi-day-charters' || $('.charter-type_current input').attr('data-slug') == 'bareboat-charters') {
                mode = "range";
                showMonths = 2;
            }
            if($('.single-services.bareboat-charters').length || serviceName == 'bareboat-charters' || serviceName == 'multi-day-charters') {
                showMonths = 2;
                mode = "range";
            }
        }
        let initialStartTime = $('.order-sidebar .charter-input span').text().trim();
        let flatpickrConfig = {
                disable: [new Date()],
                dateFormat: "Y-m-d",
                monthSelectorType: "static",
                minDate: "today",
                disableMobile: true,
                mode: mode,
                maxRange: 30,
                showMonths: showMonths,
                onChange: function (selectedDates, dateStr, instance) {
                    let serviceName = $('.charter-type_item.active').attr('data-slug');
                    if(serviceName == 'bareboat-charters' || $('.single-services.bareboat-charters').length ) {
                        let diffTime = Math.abs(selectedDates[1] - selectedDates[0]);
                        let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        let addDays = Math.max(0, 7 - diffDays);
                        if (addDays > 0) {
                            selectedDates[1].setDate(selectedDates[1].getDate() + addDays);
                            instance.setDate(selectedDates);
                            setTimeout(() => {
                                instance.jumpToDate(selectedDates[0]);
                            instance.open();
                        });
                    }
                }
            },
        onClose: function (selectedDates, dateStr, instance) {
            $('.flatpickr-calendar').hide();
            let summerFrom = $('.order-sidebar').attr('data-summerfrom');
            let summerTo = $('.order-sidebar').attr('data-summerto');
            let midFrom = $('.order-sidebar').attr('data-midfrom');
            let midTo = $('.order-sidebar').attr('data-midto');
            let highFrom = $('.order-sidebar').attr('data-highfrom');
            let highTo = $('.order-sidebar').attr('data-highto');

            if (selectedDates.length == 1) {
                let date = new Date(selectedDates);
                var datestring = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
                let isSummerDate = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
                // let isSummer = isDateBetweenDates(summerFrom,summerTo,isSummerDate);
                let dateText = date.toLocaleString('en-US', {
                        month: 'long'
                    }) + ' ' + date.getDate();
                $('input[name=sc_trip_start]').val(datestring);
                $('input[name=sc_trip_end]').val(datestring);
                if(!$('.charter-type_value').attr('data-slug')) {
                    let startTime = initialStartTime ? ' ' + initialStartTime : '';
                    $('.charter-time').html(dateText + '<span class="start-time">' + startTime + '</span>');
                } else {
                    if ($('.charter-type_value').attr('data-slug') == 'day-charters'){
                        $('.charter-time').html(dateText + '<span class="start-time">' + ' 09:00' + '</span>');
                    }
                    if ($('.charter-type_value').attr('data-slug') == 'flotilla-charters'){
                        $('.charter-time').html(dateText + '<span class="start-time">' + ' 09:00' + '</span>');
                    }
                    if ($('.charter-type_value').attr('data-slug') == 'evening-cruises'){
                        $('.charter-time').html(dateText + '<span class="start-time">' + ' 19:00' + '</span>');
                    }
                    if ($('.charter-type_value').attr('data-slug') == 'romantic-evening-cruises'){
                        $('.charter-time').html(dateText + '<span class="start-time">' + ' 19:00' + '</span>');
                    }
                    if ($('.charter-type_value').attr('data-slug') == 'bareboat-charters'){
                        $('.charter-time').html(dateText + '<span class="start-time">' + ' 18:00' + '</span>');
                    }
                }
                $('input[name=sc_trip_duration]').val(1);
                $('.date-reciever_aviability').text('Date: ' + dateText);
            }
            if (selectedDates.length == 2) {
                let serviceName = $('.order-sidebar .charter-type_current input').attr('data-slug');
                if($('.single-services.multi-day-charters').length) {
                    serviceName = 'multi-day-charters';
                }
                let diffTime = Math.abs(selectedDates[1] - selectedDates[0]);
                let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                if(diffDays > 30) {
                    selectedDates[1].setDate(selectedDates[1].getDate() - Math.abs(30 - diffDays));
                    instance.setDate(selectedDates);
                    setTimeout(() => {
                        instance.jumpToDate(selectedDates[0]);
                        instance.open();
                    });
                }
                let date1 = new Date(selectedDates[0]);
                let date2 = new Date(selectedDates[1]);
                let datesList = getDatesInRange(date1, date2);
                var datestring1 = date1.getFullYear() + "-" + (date1.getMonth() + 1) + "-" + date1.getDate();
                var datestring2 = date2.getFullYear() + "-" + (date2.getMonth() + 1) + "-" + date2.getDate();
                $('input[name=sc_trip_start]').val(datestring1);
                $('input[name=sc_trip_end]').val(datestring2);
                let dateText1 = date1.toLocaleString('en-US', {
                        month: 'long'
                    }) + ' ' + date1.getDate();
                let dateText2 = date2.toLocaleString('en-US', {
                        month: 'long'
                    }) + ' ' + date2.getDate();
                $('.date-from').html('From:<br> ' + dateText1);
                $('.date-to').html('To: <br> ' + dateText2);
                $('.date-reciever_aviability').text('Date: ' + dateText1 + '-' + dateText2);
                $('.mobile-order_sidebar .charter-time').text(dateText1 + ' ' + dateText2);
                let Difference_In_Time = date2.getTime() - date1.getTime();
                let Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24) + 1;
                $('input[name=sc_trip_duration]').val(Difference_In_Days);

            }

            if(is_hidden_filled()){
                update_prices();
            }

            if ($('.charter-type_current input').val() != '' && countMoreA > 0 && $('input[name=mvvwb_start]').val() != 'false') {
                $('.to-checkout_button').removeClass('disabled');
            }
            if(serviceName) {
                // $('.charter-options').removeClass('hidden');
            }
        },
        onReady: function (selectedDates, dateStr, instance) {
        let days = [];
        if ($('.charter-type_item.active').length) {
            let bookedStr = $('.charter-type_item.active').attr('data-booked');
            if (bookedStr) {
                try {
                    let booked = JSON.parse(bookedStr);
                    if (booked.length > 0) {
                        for (let i = 0; i < booked.length; i++) {
                            let temp = {
                                "from": booked[i][0],
                                "to": booked[i][1]
                            };
                            days.push(temp);
                        }
                    }
                } catch (e) {
                    console.log('Error parsing JSON:', e);
                }
            }
        }
        days.push(new Date());
        if($('.single-product').length){
            if($('input[name=sc_service_name]').val() == 'Day Charter' || $('input[name=sc_service_name]').val() == 'Multi-Day Charter' || $('input[name=sc_service_name]').val() == 'Corporate Events'){
            let excluded_dates = $(".single-booked-block").attr("data-booked-day");
            if(excluded_dates !== ''){
                excluded_dates = JSON.parse(excluded_dates);
                excluded_dates.forEach(function(element){
                    console.log(element);
                    days.push(element);
                });
            }
            } else {
            let excluded_dates = $(".single-booked-block").attr("data-booked-evening");
            if(excluded_dates !== ''){
                excluded_dates = JSON.parse(excluded_dates);
                excluded_dates.forEach(function(element){
                    console.log(element);
                    days.push(element);
                });
            }
            }
        }
        instance.set('disable', days);
},
onOpen: function (selectedDates, dateStr, instance) {
    $('.flatpickr-calendar').show();
}
};

if ($('.single-services,.single-product').length) {
    flatpickr = $(calendarBlock).flatpickr(flatpickrConfig);
}


// Calendar for search
let flatpickrConfigS = {
    disable: [new Date()],
    dateFormat: "Y-m-d",
    monthSelectorType: "static",
    minDate: "today",
    disableMobile: true,
    mode: mode,
    onChange: function (selectedDates, dateStr, instance) {
        if (selectedDates.length == 1) {
            let date = new Date(selectedDates);
            var datestring = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            let dateText = date.toLocaleString('en-US', {
                    month: 'long'
                }) + ' ' + date.getDate();
            $('.filter-day').html(dateText);
            $('.day-start_input').attr('value', dateText);
            $('input[name=mvvwb_duration],select[name=mvvwb_duration]').val(1);
            $('input[name=mvvwb_start]').val(datestring);
        }
        if (selectedDates.length == 2) {
            let serviceName = $('.services-chooser_item.active').find('input').val();
            let diffTime = Math.abs(selectedDates[1] - selectedDates[0]);
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if(diffDays > 30) {
                selectedDates[1].setDate(selectedDates[1].getDate() - Math.abs(30 - diffDays));
                instance.setDate(selectedDates);
                setTimeout(() => {
                    instance.jumpToDate(selectedDates[0]);
                instance.open();
            });
        }
        if(serviceName == 'bareboat-charters') {
            let addDays = Math.max(0, 7 - diffDays);
            if (addDays > 0) {
                selectedDates[1].setDate(selectedDates[1].getDate() + addDays);
                instance.setDate(selectedDates);
                setTimeout(() => {
                    instance.jumpToDate(selectedDates[0]);
                instance.open();
            });
        }
    }

    let date1 = new Date(selectedDates[0]);
let date2 = new Date(selectedDates[1]);
var datestring1 = date1.getFullYear() + "-" + (date1.getMonth() + 1) + "-" + date1.getDate();
var datestring2 = date2.getFullYear() + "-" + (date2.getMonth() + 1) + "-" + date2.getDate();

let Difference_In_Time = date2.getTime() - date1.getTime();
let Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24) + 1;

$('input[name=mvvwb_start]').val(datestring1);
$('input[name=mvvwb_end]').val(datestring2);
$('input[name=mvvwb_duration],select[name=mvvwb_duration]').val(Difference_In_Days);
let dateText1 = date1.toLocaleString('en-US', {
        month: 'long'
    }) + ' ' + date1.getDate();
let dateText2 = date2.toLocaleString('en-US', {
        month: 'long'
    }) + ' ' + date2.getDate();
$('.filter-day').html(dateText1 + ' - ' + dateText2);
$('.day-start_input').attr('value', dateText1);
$('.day-end_input').attr('value', dateText2);
}
},
onOpen: function (selectedDates, dateStr, instance) {
    $('.flatpickr-calendar').show();
    $('.peoples-wrap').hide();
    $('.services-chooser').hide();
},
onClose: function (selectedDates, dateStr, instance) {
    $('.flatpickr-calendar').hide();
},
onReady: function(selectedDates, dateStr, instance) {
    instance.set('disable', [new Date()]);
}
};

if ($('.filter-div.filter-day').length) {
    mode = "single";
    calendarBlock = '.filter-div.filter-day';
    let active_service = $('.services-chooser_item.active').find('input').val();
    if ($('.multi-day-charters').length || (active_service == 'multi-day-charters' || active_service == 'bareboat-charters')) {
        flatpickrConfigS.mode = "range";
    }
    if(active_service == 'bareboat-charters') {
        flatpickrConfigS.showMonths = 2;
    }

    flatpickr = $(calendarBlock).flatpickr(flatpickrConfigS);
}

$('.charter-boat_item').on('click', function () {
    let booked = JSON.parse($(this).attr('data-booked'));
    // let price = parseFloat($(this).attr('data-price'));
    let days = [];
    if (booked.length > 0) {
        for (let i = 0; i < booked.length; i++) {
            let temp = {
                "from": booked[i][0],
                "to": booked[i][1]
            };
            days.push(temp);
        }

    }
    days.push(new Date());
    console.log('two');
    if($('.services_service-title').text() == 'Day Charter' || $('.services_service-title').text() == 'Multi-Day Charter' || $('.services_service-title').text() == 'Corporate Events'){
        let excluded_dates = $(".charter-type_item.active").attr("data-booked-day");
            console.log(excluded_dates);
            if(excluded_dates !== ''){
                excluded_dates = JSON.parse(excluded_dates);
                excluded_dates.forEach(function(element){
                    console.log(element);
                    days.push(element);
                });
            }
    } else {
            let excluded_dates = $(".charter-type_item.active").attr("data-booked-evening");
            if(excluded_dates !== ''){
                excluded_dates = JSON.parse(excluded_dates);
                excluded_dates.forEach(function(element){
                    console.log(element);
                    days.push(element);
                });
            }
    }
    flatpickr.set('disable', days);
    flatpickr.clear();
    $('.charter-time_single').text('Select date');
    // $('.charter-time').text('Select date');
    $('.to-checkout_button').addClass('disabled');
    let is_bookable = parseInt($(this).attr('data-bookable'));
    if(!$('.fleet-content.third-party').length && !$('.single-content.third-party').length) {
        if(is_bookable) {
            $('.single_add_to_cart_button').removeClass('hidden');
            $('.availability-check_button').addClass('hidden');
            $('.top-left_row').removeClass('hidden');
        } else {
            $('.single_add_to_cart_button').addClass('hidden');
            $('.availability-check_button').removeClass('hidden');
            $('.top-left_row').addClass('hidden');
        }
    }
});

// $('.charter-type_item').on('click', function () {
//     let booked = JSON.parse($(this).attr('data-booked'));
//     // let price = parseFloat($(this).attr('data-price'));
//     let days = [];
//     if (booked.length > 0) {
//         for (let i = 0; i < booked.length; i++) {
//             let temp = {
//                 "from": booked[i][0],
//                 "to": booked[i][1]
//             };
//             days.push(temp);
//         }

//     }
//     days.push(new Date());
//     console.log('three');
//     if($('input[name=sc_service_name]').val() == 'Day Charter' || $('input[name=sc_service_name]').val() == 'Multi-Day Charter' || $('input[name=sc_service_name]').val() == 'Corporate Events'){
//         sc_excluded_dates['morning_services'].forEach(function(element){
//             days.push(element);
//         });
//     } else {
//         sc_excluded_dates['evening_services'].forEach(function(element){
//             days.push(element);
//         });
//     }
//     flatpickr.set('disable', days);
//     flatpickr.clear();
// });

$('select[name=attribute_pa_service]').on('change',function(){
    if(!$('#search-filled').length) {
        $('.charter-time').text('Select date');
        $('.to-checkout_button').addClass('disabled').attr('disabled',true);
    }
});

let intlTel;
if($('input[type=tel]').length) {
    const input = document.querySelector("input[type=tel]");
    intlTel = intlTelInput(input, {
        hiddenInput: "full",
        preferredCountries: ["mt"],
        separateDialCode: true,
        utilsScript: window.location.origin + "/wp-content/themes/sailing-theme/assets/libs/intl-tel/js/utils.js"
    });
}

$('input[type=tel]').on('input',function(){
    $(this).val(intlTel.getNumber(intlTelInputUtils.numberFormat.E164));
});

if($('.single-product').length && $('.woocommerce-error').length) {
    if($('.woocommerce-error').text().trim() == 'Selected date range is not available') {
        $('.woocommerce-error').text('Selected date range is no longer available');
    }
    $('.woocommerce-error').append('<div class="close-btn"></div>');
    $('.woocommerce-error').append('<div class="ok-btn">OK</div>');
    $('.woocommerce-error').addClass('showed');
}

$('.woocommerce-error .close-btn,.woocommerce-error .ok-btn').on('click',function(){
    $(this).closest('.woocommerce-error').addClass('hided');
    $('.woocommerce-error').removeClass('showed');
});


$('body').on('click', function () {
    $('.woocommerce-error').addClass('hided');
    $('.woocommerce-error').removeClass('showed');
});

$('.service-boat_mobile-item').on('click',function(){
    let is_bookable = parseInt($(this).attr('data-bookable'));
    if(!$('.fleet-content.third-party').length && !$('.single-content.third-party').length) {
        if(is_bookable) {
            $('.boat-type_checkout--button').removeClass('hidden');
            $('.availability-check_button').addClass('hidden');
        } else {
            $('.boat-type_checkout--button').addClass('hidden');
            $('.availability-check_button').removeClass('hidden');
        }
    }
});

if(location.hash == '#edit-open' && $('.edit-order_popup').length) {
    $('.edit-order_popup').show();
    $('.thankyou-wrap').hide();
}

$('.mobile-order_sidebar').css('transform', 'translateY(' + ($('.mobile-order_sidebar').outerHeight() - 95) + 'px)');

if($('.single.single-services').length) {
    if(!$('.charter-type_current input').length) {
        // $('.charter-options').addClass('hidden');
    }
}

$('.srv-boat-select-item').on('click',function(){
    $('.srv-boat-select-item').removeClass('active');
    $(this).addClass('active');
});


setTimeout(function(){
    if($('.order-sidebar').attr('data-searchyear')) {
        let year = $('.order-sidebar').attr('data-searchyear');
        let d = $('input[name=mvvwb_start]').val();
        let dateArr = d.split('-');
        dateArr[0] = year;
        $('input[name=mvvwb_start]').val(dateArr[0] + '-' + dateArr[1] + '-' + dateArr[2]);
    }

    if($('form.variations_form.cart').length) {
        $('form.variations_form.cart').append('<input type="hidden" name="mvvwb_infants">');
        if(parseInt($('.poeple-infants_summury').val()) > 0) {
            $('input[name="mvvwb_infants"]').val(parseInt($('.poeple-infants_summury').val()));
        }
    }
},2000);

$('.search-submit,.search-submit_mobile').on('click',function(e){
    e.preventDefault();
    let newUrlParams = [];
    let formParams = $('.main-banner_filter').serializeArray();
    let peopleStr = '';
    if(!$('.services-chooser_item input[name=activeType]').length) {
        $('.search-error-popup').fadeIn();
        return;
    }
    for (let key in formParams) {
        if(formParams[key].name == 'activeType') {
            let temp = {
                name: 'service',
                value: formParams[key].value
            }
            newUrlParams.push(temp);
        }
        if(formParams[key].name == "adult_number") {
            if(formParams[key].value > 0) {
                peopleStr = '' + formParams[key].value + ',';
            } else {
                peopleStr = '0,';
            }
        }
        if(formParams[key].name == "children_number") {
            if(formParams[key].value > 0) {
                peopleStr += formParams[key].value + ',';
            } else {
                peopleStr += '0,';
            }
        }
        if(formParams[key].name == "infants_number") {
            if(formParams[key].value > 0) {
                peopleStr += formParams[key].value;
            } else {
                peopleStr += '0';
            }
        }
        if(formParams[key].name == "mvvwb_start") {
            let temp = {
                name: 'start',
                value: formParams[key].value
            }
            newUrlParams.push(temp);
        }
        if(formParams[key].name == "mvvwb_duration") {
            let temp = {
                name: 'duration',
                value: formParams[key].value
            }
            newUrlParams.push(temp);
        }
    }
    newUrlParams.push({
        name: 'people',
        value: peopleStr
    })
    let newUrl = '?';
    let i = 0;
    for (let key in newUrlParams) {
        i++;
        newUrl += newUrlParams[key]['name'] + '=' + newUrlParams[key]['value'];
        if(i < newUrlParams.length) {
            newUrl += '&';
        }
    }
    location.href = location.origin + '/search' + newUrl;
});

$('.search-error-popup .close-btn, .search-error-popup .ok-btn').on('click',function(){
    $('.search-error-popup').fadeOut();
});

if($('.single.single-services').length) {
    if(!$('.charter-type_service').length) {
        $('.charter-input.charter-passenger').addClass('nonbook');
    }
}

function display_pre_order_totals(data){
    let values = JSON.parse(data);
    $('.boat-price span').text(values['boat-price']);
    console.log(values);
    console.log(values['boat-price']);

    if ($('.more-people').closest('.mobile-order_form').length) {
        $('.service-mobile_order-price').text('€ ' + values['boat-price']);
        $('.mobile-deposit span').text('€ ' + values['charter-row_price']);
        // $('.service-mobile_order-price').text(values['boat-price']);
        $('.cleaning-price').text('€ ' + values['cleaning-price']);
        $('.mobile-deposit span').text('€ ' + values['deposite-price']);
        $('.mobile-basefee .service-mobile_order-price').text('€ ' + values['charter-row_price']);

        $('#cleaning-price, #deposite-price, #payable-price, #amount-price').show();
        $('.order-details_bottom').removeClass( "hidden" );
    }

    if($('.charter-type_item.active').attr('data-bookable') == 1){

        $('.deposite-price span').text(values['deposite-price']);
        $('.cleaning-price span').text(values['cleaning-price']);
        $('.payable span').text(values['payable']);
        $('.charter-row_price span').text(values['charter-row_price']);

        $('#cleaning-price, #deposite-price, #payable-price, #amount-price').show();
        $('.charter-options').show();

    } else {
        $('#cleaning-price, #deposite-price, #payable-price, #amount-price').hide();
        $('.charter-options').show();

    }
}

function is_hidden_filled(){
    let return_val = true;

    $('.sc_to_price').each(function(){
        if($(this).val() == ""){
            return_val = false;
        }
    })

    return return_val;
}

function update_prices(){
    $.ajax({
        type: 'POST',
        url: s_ajax_url,
        data: {
            action: "sc_update_data",
            people_total: $('input[name=sc_people_total]').val(),
            adults:  $('input[name=sc_people_adults]').val(),
            children:  $('input[name=sc_people_children]').val(),
            infants:  $('input[name=sc_people_infants]').val(),
            trip_start:  $('input[name=sc_trip_start]').val(),
            trip_end:  $('input[name=sc_trip_end]').val(),
            trip_duration:  $('input[name=sc_trip_duration]').val(),
            variation_id:  $('input[name=sc_variation_id]').val(),
            service_id:  $('input[name=sc_service_id]').val()
        },
        success: function (response) {
            console.log(response);
            display_pre_order_totals(response);

            checkConditions();
            // $('.charter-options').show();
        }
    });

}

/* Order details validaton */

function checkConditions() {
    var serviceTypeInputVal = $('input[type="hidden"][name="sc_people_total"]').val();
    var variationIdInputVal = $('input[type="hidden"][name="sc_variation_id"]').val();
    var tripStartInputVal = $('input[type="hidden"][name="sc_trip_start"]').val();
    var tripEndInputVal = $('input[type="hidden"][name="sc_trip_end"]').val();

    if(
        parseFloat(serviceTypeInputVal) > 0 &&
        parseFloat(variationIdInputVal) !== 0 &&
        parseFloat(tripStartInputVal) !== 0 &&
        parseFloat(tripEndInputVal) !== 0
    ){
        $('.to-checkout_button').removeClass('disabled');
    } else {
        $('.to-checkout_button').addClass('disabled');
    }
}

/* End of order details validaton */

function checkDownloadMenuEmpty() {
    var downloadButtonHref = $('.checkout .checkout-menu_list a.download-button, .ty-page .checkout-menu_list a.download-button').attr('href');

    if (downloadButtonHref === '' || downloadButtonHref === undefined) {
        $('.checkout .checkout-menu_list, .checkout .food-titles-menu, .checkout-menu_list').hide();
    }
}

$(window).on('load', function(){
    if($('.product-template-default').length){
        if(is_hidden_filled()){
            $('.to-checkout_button').removeClass('disabled');
            $('.charter-options').show();
            update_prices();
        }
    }
});

    $('.to-checkout_button.cart-first_button').on('click', function(e){
        e.preventDefault();

        if(is_hidden_filled()){
            $.ajax({
                type: 'POST',
                url: s_ajax_url,
                data: {
                    action: "sc_add_item_to_cart",
                    people_total: $('input[name=sc_people_total]').val(),
                    adults:  $('input[name=sc_people_adults]').val(),
                    children:  $('input[name=sc_people_children]').val(),
                    infants:  $('input[name=sc_people_infants]').val(),
                    trip_start:  $('input[name=sc_trip_start]').val(),
                    trip_end:  $('input[name=sc_trip_end]').val(),
                    trip_duration:  $('input[name=sc_trip_duration]').val(),
                    variation_id:  $('input[name=sc_variation_id]').val(),
                    service_id:  $('input[name=sc_service_id]').val()
                },

                success: function (response) {
                    console.log(response);
                    setTimeout(() => {
                        window.location = document.location.origin + '/checkout/';
                    }, 3000);

                    // display_pre_order_totals(response);
                    // $('.charter-options').show();
                },


            });
        }
    });
    if (innerWidth < 500) {
        $(document).on('click', '.services-chooser_item__mobile', function(e) {
            e.stopPropagation();
            $('.services-chooser').show();
        });
        $('.to-order_button').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.single_add_to_cart_button').attr('disabled', false);
            $('.flatpickr-calendar').hide();
            $('.flatpickr-calendar').removeClass('unhidden');
            $('.flatpickr-calendar').addClass('hidden');
            $('.mobile-order_form').hide();
            $('.mobile-order_form--date').hide();
            $('.mobile-order_form--people').show();
            var dateContent = $('.flatpickr-day.selected').attr("aria-label");
            if(dateContent.length) {
                var dateContentParsed = dateContent.lastIndexOf(" ");
                dateContent = dateContent.substring(0, dateContentParsed);
                var dateContentSliced = dateContent.slice(0,-1);
                $('.charter-time').html(dateContentSliced + '<span> 9 AM</span>');
                var dateTImeParsed = $('.charter-time').html(dateContentSliced + '<span> 9 AM</span>');
                for (var i = 0; i < dateTImeParsed.length; i++) {
                    var dateFixed = dateTImeParsed[i].innerText;
                    localStorage.setItem('orderDate', dateFixed);
                }
                $('.to-checkout_button').removeClass('disabled');
            }
            $('.charter-passenger_amount').html(localStorage.getItem('pasAmount'));
             var passengerAll = countMoreA + countMoreC + countMoreI;
             if(countMoreA > 0) {
                $('.charter-passenger_amount').html(passengerAll + ' Passengers ');
                var passengerCount = $('.charter-passenger_amount').html(passengerAll + ' Passengers ');
                for (var i = 0; i < passengerCount.length; i++) {
                    var pasCountFixed = passengerCount[i].innerText;
                    localStorage.setItem('pasAmount', pasCountFixed);
                }
            }
            else {
                $('.charter-passenger_amount').html('People');
            }
            if(is_hidden_filled()){
                update_prices();
            }
         });
        $('.search-preform_info').on('click', function() {
            $('.search-page_filter').show();
        });

        $('.nav-top-button').on('click', function(e) {
            e.stopPropagation();
        });

        $('.service-label-multi-day-charters').on('click', function() {
          $('.flatpickr-calendar').css('opacity', '0 !important');
        });
    }
});

function isDateBetweenDates(dateFrom,dateTo,dateCheck) {
    let separator = '/';
    if(dateFrom.indexOf('/') === -1) {
        separator = '-';
    }
    var d1 = dateFrom.split(separator);
    separator = '/';
    if(dateTo.indexOf('/') === -1) {
        separator = '-';
    }
    var d2 = dateTo.split(separator);
    separator = '/';
    if(dateCheck.indexOf('/') === -1) {
        separator = '-';
    }
    var c = dateCheck.split(separator);
    var from = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
    var to   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);
    var check = new Date(c[2], parseInt(c[1])-1, c[0]);
    return check >= from && check <= to;
}

function getDatesInRange(startDate, endDate) {
    const date = new Date(startDate.getTime());
    const dates = [];
    while (date <= endDate) {
        let month = date.getMonth() + 1;
        let dateString = date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
        dates.push(dateString);
        date.setDate(date.getDate() + 1);
    }
    return dates;
}

