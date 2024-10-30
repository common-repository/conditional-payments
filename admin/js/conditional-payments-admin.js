(function ($) {
	'use strict';
    
	$(window).load(function () {
        $('.dscpw-section-left #doaction').click(function() {
            window.onbeforeunload = null;
        });

        $('.dscpw-section-left #search-submit').click(function() {
            window.onbeforeunload = null;
        });

        $('.multiselect2').select2();

        /**
         * Datepicker for date rule
         */
        $('.dscpw_datepicker').datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: '0',
            onSelect: function () {
                var dt = $(this).datepicker('getDate');
                dt.setDate(dt.getDate() + 1);
            }
        });

        function allowSpeicalCharacter(str) {
            return str.replace('&#8211;', '–').replace('&gt;', '>').replace('&lt;', '<').replace('&#197;', 'Å');
        }

        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (err) {
                return false;
            }
            return true;
        }

        function setAllAttributes(element, attributes) {
            Object.keys(attributes).forEach(function (key) {
                element.setAttribute(key, attributes[key]);
                // use val
            });
            return element;
        }

        function insertOptions(parentElement, options) {
            for (var i = 0; i < options.length; i++) {
                if (options[i].type === 'optgroup') {
                    var optgroup = document.createElement('optgroup');
                    optgroup = setAllAttributes(optgroup, options[i].attributes);
                    for (var j = 0; j < options[i].options.length; j++) {
                        let option = document.createElement('option');
                        option = setAllAttributes(option, options[i].options[j].attributes);
                        option.textContent = options[i].options[j].name;
                        optgroup.appendChild(option);
                    }
                    parentElement.appendChild(optgroup);
                } else {
                    let option = document.createElement('option');
                    option = setAllAttributes(option, options[i].attributes);
                    option.textContent = allowSpeicalCharacter(options[i].name);
                    parentElement.appendChild(option);
                }
            }
            return parentElement;

        }

        function get_all_condition() {
            return [
                {
                    'type': 'optgroup',
                    'attributes': {'label': coditional_vars.product_specific},
                    'options': [
                        {'name': coditional_vars.product, 'attributes': {'value': 'product'}},
                        {'name': coditional_vars.variable_product, 'attributes': {'value': 'variable_product'}},
                        {'name': coditional_vars.product_categories_disabled, 'attributes': {'value': 'product_categories_disabled'}},
                        {'name': coditional_vars.product_tags_disabled, 'attributes': {'value': 'product_tags_disabled'}},
                        {'name': coditional_vars.product_type_disabled, 'attributes': {'value': 'product_type_disabled'}},
                        {'name': coditional_vars.product_visibility_disabled, 'attributes': {'value': 'product_visibility_disabled'}},
                        {'name': coditional_vars.product_quantity_disabled, 'attributes': {'value': 'product_quantity_disabled'}},
                    ]
                },
                {
                    'type': 'optgroup',
                    'attributes': {'label': coditional_vars.cart_specific},
                    'options': [
                        {'name': coditional_vars.cart_total, 'attributes': {'value': 'cart_total'}},
                        {'name': coditional_vars.cart_totalafter, 'attributes': {'value': 'cart_totalafter'}},
                        {'name': coditional_vars.cart_quantity_disabled, 'attributes': {'value': 'cart_quantity_disabled'}},
                        {'name': coditional_vars.shipping_class_disabled, 'attributes': {'value': 'shipping_class_disabled'}},
                        {'name': coditional_vars.coupon_disabled, 'attributes': {'value': 'coupon_disabled'}},
                        {'name': coditional_vars.total_weight_disabled, 'attributes': {'value': 'total_weight_disabled'}},
                        {'name': coditional_vars.number_of_items_disabled, 'attributes': {'value': 'number_of_items_disabled'}},
                        {'name': coditional_vars.total_volume_disabled, 'attributes': {'value': 'total_volume_disabled'}},
                    ]
                },
                {
                    'type': 'optgroup',
                    'attributes': {'label': coditional_vars.shipping_specific},
                    'options': [
                        {'name': coditional_vars.shipping_method, 'attributes': {'value': 'shipping_method'}},
                    ]
                },
                {
                    'type': 'optgroup',
                    'attributes': {'label': coditional_vars.billing_address_group},
                    'options': [
                        {'name': coditional_vars.billing_first_name, 'attributes': {'value': 'billing_first_name'}},
                        {'name': coditional_vars.billing_last_name, 'attributes': {'value': 'billing_last_name'}},
                        {'name': coditional_vars.billing_company, 'attributes': {'value': 'billing_company'}},
                        {'name': coditional_vars.billing_address_1, 'attributes': {'value': 'billing_address_1'}},
                        {'name': coditional_vars.billing_address_2, 'attributes': {'value': 'billing_address_2'}},
                        {'name': coditional_vars.billing_country, 'attributes': {'value': 'billing_country'}},
                        {'name': coditional_vars.billing_city, 'attributes': {'value': 'billing_city'}},
                        {'name': coditional_vars.billing_postcode, 'attributes': {'value': 'billing_postcode'}},
                        {'name': coditional_vars.billing_email_disabled, 'attributes': {'value': 'billing_email_disabled'}},
                        {'name': coditional_vars.previous_order_disabled, 'attributes': {'value': 'previous_order_disabled'}},
                    ]
                },
                {
                    'type': 'optgroup',
                    'attributes': {'label': coditional_vars.customer_group},
                    'options': [
                        {'name': coditional_vars.customer_authenticated_disabled, 'attributes': {'value': 'customer_authenticated_disabled'}},
                        {'name': coditional_vars.user_disabled, 'attributes': {'value': 'user_disabled'}},
                        {'name': coditional_vars.user_role_disabled, 'attributes': {'value': 'user_role_disabled'}},
                    ]
                },
                {
                    'type': 'optgroup',
                    'attributes': {'label': coditional_vars.shipping_address_group},
                    'options': [
                        {'name': coditional_vars.shipping_first_name, 'attributes': {'value': 'shipping_first_name'}},
                        {'name': coditional_vars.shipping_last_name, 'attributes': {'value': 'shipping_last_name'}},
                        {'name': coditional_vars.shipping_company, 'attributes': {'value': 'shipping_company'}},
                        {'name': coditional_vars.shipping_address_1, 'attributes': {'value': 'shipping_address_1'}},
                        {'name': coditional_vars.shipping_address_2, 'attributes': {'value': 'shipping_address_2'}},
                        {'name': coditional_vars.shipping_country, 'attributes': {'value': 'shipping_country'}},
                        {'name': coditional_vars.shipping_city, 'attributes': {'value': 'shipping_city'}},
                        {'name': coditional_vars.shipping_postcode, 'attributes': {'value': 'shipping_postcode'}},
                    ]
                },
                {
                    'type': 'optgroup',
                    'attributes': {'label': coditional_vars.time_specific},
                    'options': [
                        {'name': coditional_vars.day_of_week, 'attributes': {'value': 'day_of_week'}},
                        {'name': coditional_vars.date, 'attributes': {'value': 'date'}},
                        {'name': coditional_vars.time_disabled, 'attributes': {'value': 'time_disabled'}},
                    ]
                },
            ];
        }

        function get_all_action() {
            return [
                {'name': coditional_vars.enable_payments, 'attributes': {'value': 'enable_payments'}},
                {'name': coditional_vars.disable_payments, 'attributes': {'value': 'disable_payments'}},
                {'name': coditional_vars.add_payment_method_fee_disabled, 'attributes': {'value': 'add_payment_method_fee_disabled'}},
            ];
        }

        function condition_types(text) {
            if (text === 'conditions1') {
                return [
                    {'name': coditional_vars.equal_to, 'attributes': {'value': 'is_equal_to'}},
                    {'name': coditional_vars.less_or_equal_to, 'attributes': {'value': 'less_equal_to'}},
                    {'name': coditional_vars.less_than, 'attributes': {'value': 'less_then'}},
                    {'name': coditional_vars.greater_or_equal_to, 'attributes': {'value': 'greater_equal_to'}},
                    {'name': coditional_vars.greater_than, 'attributes': {'value': 'greater_then'}},
                    {'name': coditional_vars.not_equal_to, 'attributes': {'value': 'not_in'}},
                ];
            } else if( text === 'conditions2' ) {
                return [
                    {'name': coditional_vars.equal_to, 'attributes': {'value': 'is_equal_to'}},
                    {'name': coditional_vars.not_equal_to, 'attributes': {'value': 'not_in'}},
                ];
            } else {
                return [
                    {'name': coditional_vars.equal_to, 'attributes': {'value': 'is_equal_to'}},
                    {'name': coditional_vars.not_equal_to, 'attributes': {'value': 'not_in'}},
                    {'name': coditional_vars.is_empty, 'attributes': {'value': 'is_empty'}},
                    {'name': coditional_vars.is_not_empty, 'attributes': {'value': 'is_not_empty'}},
                ];
            }

        }

        function productFilter() {
            $('.payment_conditions_values_product').each(function () {
                $('.payment_conditions_values_product').select2({
                    ajax: {
                        url: coditional_vars.ajaxurl,
                        dataType: 'json',
                        delay: 250,
                        cache: true,
                        minimumInputLength: 3,
                        data: function (params) {
                            return {
                                value: params.term,
                                action: 'dscpw_conditional_payments_product_list_ajax',
                                security: coditional_vars.dscpw_ajax_nonce,
                                _page: params.page || 1,
                                posts_per_page: 10 
                            };
                        },
                        processResults: function( data ) {
                            var options = [], more = true;
                            if ( data ) {
                                $.each( data, function( index, text ) {
                                    options.push( { id: text[ 0 ], text: allowSpeicalCharacter( text[ 1 ] ) } );
                                } );
                            }
                            //for stop paination on all data laod 
                            if( 0 === options.length ){ 
                                more = false; 
                            }
                            return {
                                results: options,
                                pagination: {
                                    more: more
                                }
                            };
                        },
                    },
                });
            });
        }
        productFilter();

        function varproductFilter() {
            $('.payment_conditions_values_var_product').each(function () {
                $('.payment_conditions_values_var_product').select2({
                    ajax: {
                        url: coditional_vars.ajaxurl,
                        dataType: 'json',
                        delay: 250,
                        cache: true,
                        minimumInputLength: 3,
                        data: function (params) {
                            return {
                                value: params.term,
                                action: 'dscpw_conditional_payments_variable_product_list_ajax',
                                security: coditional_vars.dscpw_ajax_nonce,
                                _page: params.page || 1,
                                posts_per_page: 10 
                            };
                        },
                        processResults: function( data ) {
                            var options = [], more = true;
                            if ( data ) {
                                $.each( data, function( index, text ) {
                                    options.push( { id: text[ 0 ], text: allowSpeicalCharacter( text[ 1 ] ) } );
                                } );
                            }
                            //for stop paination on all data laod 
                            if( 0 === options.length ){ 
                                more = false; 
                            }
                            return {
                                results: options,
                                pagination: {
                                    more: more
                                }
                            };
                        },
                    },
                });
            });
        }
        varproductFilter();

        // Set cookies
        function setCookie(name, value, minutes) {
            var expires = '';
            if (minutes) {
                var date = new Date();
                date.setTime(date.getTime() + (minutes * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            document.cookie = name + '=' + (value || '') + expires + '; path=/';
        }

        // Get cookies
        function getCookie(name) {
            let nameEQ = name + '=';
            let ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i].trim();
                if (c.indexOf(nameEQ) === 0) {
                    return c.substring(nameEQ.length, c.length);
                }
            }
            return null;
        }

        /** Script for Freemius upgrade popup */
        function upgradeToProFreemius( couponCode ) {
            let handler;
            handler = FS.Checkout.configure({
                plugin_id: '10262',
                plan_id: '17320',
                public_key:'pk_9edf804dccd14eabfd00ff503acaf',
                coupon: couponCode,
                show_reviews: true,
                show_refund_badge: true
            });
            handler.open({
                name: 'Conditional Payment Methods for WooCommerce',
                subtitle: 'You’re a step closer to our Pro features',
                licenses: jQuery('input[name="licence"]:checked').val(),
                purchaseCompleted: function( response ) {
                    console.log (response);
                },
                success: function (response) {
                    console.log (response);
                }
            });
        }

		var ele = $('#conditions_total_row').val();
        var count;
        if (ele > 2) {
            count = ele;
        } else {
            count = 2;
        }

        // add condition on click of delete button
        $(document).on('click', '#conition-add-field', function () {
            var condition_add_field = $('#tbl-condition-payment-rules tbody').get(0);

            var tr = document.createElement('tr');
            tr = setAllAttributes(tr, {'id': 'row_' + count});
            condition_add_field.appendChild(tr);

            // generate td of condition
            var td = document.createElement('td');
            td = setAllAttributes(td, {});
            tr.appendChild(td);
            var conditions = document.createElement('select');
            conditions = setAllAttributes(conditions, {
                'rel-id': count,
                'id': 'conditional_payments_conditions_' + count,
                'name': 'payment[conditional_payments_conditions][]',
                'class': 'conditional_payments_conditions'
            });
            conditions = insertOptions(conditions, get_all_condition());
            td.appendChild(conditions);
            // td ends

            // generate td for equal or no equal to
            td = document.createElement('td');
            td = setAllAttributes(td, {});
            tr.appendChild(td);
            var conditions_is = document.createElement('select');
            conditions_is = setAllAttributes(conditions_is, {
                'name': 'payment[payments_conditions_is][]',
                'class': 'payments_conditions_is payments_conditions_is_' + count
            });
            conditions_is = insertOptions(conditions_is, condition_types('conditions2'));
            td.appendChild(conditions_is);
            // td ends

            // td for condition values
            td = document.createElement('td');
            td = setAllAttributes(td, {'id': 'column_' + count});
            tr.appendChild(td);
            condition_values($('#conditional_payments_conditions_' + count));

            var condition_key = document.createElement('input');
            condition_key = setAllAttributes(condition_key, {
                'type': 'hidden',
                'name': 'condition_key[value_' + count + '][]',
                'value': '',
            });
            td.appendChild(condition_key);
            $('.payment_conditions_values_' + count).trigger('chosen:updated');
            $('.multiselect2').select2();

            /**
             * Datepicker for date rule
             */
            $('.dscpw_datepicker').datepicker({
                dateFormat: 'dd-mm-yy',
                minDate: '0',
                onSelect: function () {
                    var dt = $(this).datepicker('getDate');
                    dt.setDate(dt.getDate() + 1);
                }
            });
            // td ends

            // td for delete button
            td = document.createElement('td');
            tr.appendChild(td);
            var delete_button = document.createElement('span');
            delete_button = setAllAttributes(delete_button, {
                'rel-id': count,
                'title': coditional_vars.delete,
                'class': 'condition-delete-field'
            });
            var deleteicon = document.createElement('span');
            deleteicon = setAllAttributes(deleteicon, {
                'class': 'dashicons dashicons-trash'
            });
            delete_button.appendChild(deleteicon);
            td.appendChild(delete_button);
            // td ends

            count++;
        });

        // remove condition on click of delete button
        $(document).on('click', '.condition-delete-field', function () {
            $(this).parent().parent().remove();
        });

        $(document).on('change', '.conditional_payments_conditions', function () {
            let selectedOption = $(this).find(':selected').val();
            if( selectedOption.includes('_disabled') ){
                $(this).find(':selected').prop('selected', false);

                $('body').addClass('dscpw-modal-visible');
                condition_values(this);
            } else {
                condition_values(this);
            }
        });

        function condition_values(element) {
            var posts_per_page = 3; // Post per page
            var page = 0; // What page we are on.
            var condition = $(element).val();
            var count = $(element).attr('rel-id');
            var column = $('#column_' + count).get(0);
            $(column).empty();
            var loader = document.createElement('img');
            loader = setAllAttributes(loader, {'src': coditional_vars.plugin_url + 'images/ajax-loader.gif'});
            column.appendChild(loader);

            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'dscpw_conditional_payments_conditions_values_ajax',
                    'security': coditional_vars.dscpw_ajax_nonce,
                    'condition': condition,
                    'count': count,
                    'posts_per_page': posts_per_page,
                    'offset': (page * posts_per_page),
                },
                contentType: 'application/json',
                success: function (response) {
                    var condition_values;
                    $('.payments_conditions_is_' + count).empty();
                    var column = $('#column_' + count).get(0);
                    var condition_is = $('.payments_conditions_is_' + count).get(0);

                    if ( condition === 'cart_total' || condition === 'cart_totalafter' || condition === 'date' ) {
                        condition_is = insertOptions(condition_is, condition_types('conditions1'));
                    } else if( condition === 'product' || condition === 'variable_product' || condition === 'shipping_method' || condition === 'billing_country' || condition === 'shipping_country' || condition === 'day_of_week' ) {
                        condition_is = insertOptions(condition_is, condition_types('conditions2'));
                    } else {
                        condition_is = insertOptions(condition_is, condition_types());
                    }
                    $('.payments_conditions_is_' + count).trigger('change');
                    $(column).empty();

                    var placeholder_msg = '';
                    var condition_values_id = '';
                    var extra_class = '';
                    if (condition === 'product') {
                        condition_values_id = 'product-filter-' + count;
                        placeholder_msg = coditional_vars.validation_length1;
                        extra_class = 'payment_conditions_values_product';
                    } else if (condition === 'variable_product') {
                        condition_values_id = 'var-product-filter-' + count;
                        placeholder_msg = coditional_vars.validation_length1;
                        extra_class = 'payment_conditions_values_var_product';
                    } else if (condition === 'date') {
                        extra_class = 'dscpw_datepicker';
                    } else {
                        placeholder_msg = coditional_vars.select_some_options;
                    }

                    if (isJson(response)) {
                        condition_values = document.createElement('select');
                        condition_values = setAllAttributes(condition_values, {
                            'name': 'payment[payment_conditions_values][value_' + count + '][]',
                            'class': 'dscpw_select payment_conditions_values payment_conditions_values_' + count + ' multiselect2 ' + extra_class,
                            'multiple': 'multiple',
                            'id': condition_values_id,
                            'data-placeholder': placeholder_msg
                        });
                        column.appendChild(condition_values);
                        var data = JSON.parse(response);
                        condition_values = insertOptions(condition_values, data);
                    } else {
                        condition_values = document.createElement($.trim(response));
                        condition_values = setAllAttributes(condition_values, {
                            'name': 'payment[payment_conditions_values][value_' + count + ']',
                            'class': 'payment_conditions_values ' + extra_class,
                            'type': 'text',

                        });
                        column.appendChild(condition_values);
                    }

                    column = $('#column_' + count).get(0);
                    var input_node = document.createElement('input');
                    input_node = setAllAttributes(input_node, {
                        'type': 'hidden',
                        'name': 'condition_key[value_' + count + '][]',
                        'value': ''
                    });
                    column.appendChild(input_node);

                    var p_node, b_node, b_text_node, text_node, a_node;
                    
                    if ( condition === 'billing_city' 
                        || condition === 'billing_postcode' 
                        || condition === 'shipping_city' 
                        || condition === 'shipping_postcode' 
                        || condition === 'cart_totalafter' 
                    ) {
                        p_node = document.createElement( 'p' );
                        b_node = document.createElement( 'b' );
                        b_node = setAllAttributes( b_node, {
                            'style': 'color: red;',
                        } );
                        b_text_node = document.createTextNode( coditional_vars.note );
                        b_node.appendChild( b_text_node );
                        
                        a_node = document.createElement( 'a' );
                        a_node = setAllAttributes(a_node, {
                            'href': coditional_vars.docs_url,
                            'target': '_blank'
                        });
                        if ( condition === 'billing_city' ) {
                            text_node = document.createTextNode( coditional_vars.billing_city_msg );
                        }
                        if ( condition === 'billing_postcode' ) {
                            text_node = document.createTextNode( coditional_vars.billing_postcode_msg );
                        }
                        if ( condition === 'shipping_city' ) {
                            text_node = document.createTextNode( coditional_vars.shipping_city_msg );
                        }
                        if ( condition === 'shipping_postcode' ) {
                            text_node = document.createTextNode( coditional_vars.shipping_postcode_msg );
                        }
                        if ( condition === 'cart_totalafter' ) {
                            text_node = document.createTextNode( coditional_vars.cart_totalafter_msg );
                        }

                        p_node.appendChild( b_node );
                        p_node.appendChild( text_node );
                        column.appendChild( p_node );
                        
                    }

                    if ( condition === 'cart_totalafter' ) {
                        var a_text_node = document.createTextNode(coditional_vars.click_here);
                        a_node.appendChild(a_text_node);
                        p_node.appendChild(b_node);
                        p_node.appendChild(text_node);
                        p_node.appendChild(a_node);
                    }

                    $('.multiselect2').select2();

                    /**
                     * Datepicker for date rule
                     */
                    $('.dscpw_datepicker').datepicker({
                        dateFormat: 'dd-mm-yy',
                        minDate: '0',
                        onSelect: function () {
                            var dt = $(this).datepicker('getDate');
                            dt.setDate(dt.getDate() + 1);
                        }
                    });

                    productFilter();
                    varproductFilter();

                }
            });
        }

        $(document).on('change', '.payments_conditions_is', function () {
            let selectedCondition = $(this).val();
            if ( 'is_empty' === selectedCondition || 'is_not_empty' === selectedCondition ) {
                $(this).parent().next().children().hide();
            } else {
                $(this).parent().next().children().show();
            }
        });

        var action_ele = $('#action_total_row').val();
        var action_count;
        if (action_ele > 2) {
            action_count = action_ele;
        } else {
            action_count = 2;
        }

        // add action on click of add action button
        $(document).on('click', '#action-add-field', function () {
            var action_add_field = $('#tbl-actions-payment-rules tbody').get(0);

            var tr = document.createElement('tr');
            tr = setAllAttributes(tr, {'id': 'action_row_' + action_count});
            action_add_field.appendChild(tr);

            // generate td of action
            var td = document.createElement('td');
            td = setAllAttributes(td, {});
            tr.appendChild(td);
            var actions = document.createElement('select');
            actions = setAllAttributes(actions, {
                'rel-id': action_count,
                'id': 'conditional_payments_actions_' + action_count,
                'name': 'cp_actions[conditional_payments_actions][]',
                'class': 'conditional_payments_actions'
            });
            actions = insertOptions(actions, get_all_action());
            td.appendChild(actions);
            // td ends

            // td for actions values
            td = document.createElement('td');
            td = setAllAttributes(td, {'id': 'action_column_' + action_count});
            tr.appendChild(td);

            action_values($('#conditional_payments_actions_' + action_count));

            var actions_key = document.createElement('input');
            actions_key = setAllAttributes(actions_key, {
                'type': 'hidden',
                'name': 'actions_key[value_' + action_count + '][]',
                'value': '',
            });
            td.appendChild(actions_key);
            $('.payment_actions_values_' + action_count).trigger('chosen:updated');
            $('.multiselect2').select2();
            // td ends

            // td for delete button
            td = document.createElement('td');
            tr.appendChild(td);
            var delete_button = document.createElement('span');
            delete_button = setAllAttributes(delete_button, {
                'rel-id': action_count,
                'title': coditional_vars.delete,
                'class': 'action-delete-field'
            });
            var deleteicon = document.createElement('span');
            deleteicon = setAllAttributes(deleteicon, {
                'class': 'dashicons dashicons-trash'
            });
            delete_button.appendChild(deleteicon);
            td.appendChild(delete_button);
            // td ends

            action_count++;
        });

        // remove condition on click of delete button
        $(document).on('click', '.action-delete-field', function () {
            $(this).parent().parent().remove();
        });

        $(document).on('change', '.conditional_payments_actions', function () {
            let selectedOption = $(this).find(':selected').val();
            if( selectedOption.includes('_disabled') ){
                $(this).find(':selected').prop('selected', false);

                $('body').addClass('dscpw-modal-visible');
                action_values(this);
            } else {
                action_values(this);
            }
        });

        function action_values(element) {
            var payment_action = $(element).val();
            var payment_count = $(element).attr('rel-id');
            var column = $('#action_column_' + payment_count).get(0);
            $(column).empty();
            var loader = document.createElement('img');
            loader = setAllAttributes(loader, {'src': coditional_vars.plugin_url + 'images/ajax-loader.gif', 'id': 'cp_action_loader'});
            column.appendChild(loader);

            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'dscpw_conditional_payments_actions_values_ajax',
                    'security': coditional_vars.dscpw_ajax_nonce,
                    'payment_action': payment_action,
                    'payment_count': payment_count
                },
                contentType: 'application/json',
                success: function (response) {
                    var action_values;

                    var removeLoader = document.getElementById('cp_action_loader');
                    removeLoader.remove();

                    var column = $('#action_column_' + payment_count).get(0);

                    var action_values_id = '';

                    if (isJson(response)) {
                        action_values = document.createElement('select');
                        action_values = setAllAttributes(action_values, {
                            'name': 'cp_actions[payment_actions_values][value_' + payment_count + '][]',
                            'class': 'dscpw_select payment_actions_values payment_actions_values_' + payment_count + ' multiselect2 ',
                            'multiple': 'multiple',
                            'id': action_values_id,
                            'data-placeholder': coditional_vars.select_some_options
                        });
                        column.appendChild(action_values);
                        var data = JSON.parse(response);
                        action_values = insertOptions(action_values, data);
                    } else {
                        action_values = document.createElement($.trim(response));
                        action_values = setAllAttributes(action_values, {
                            'name': 'cp_actions[payment_actions_values][value_' + payment_count + ']',
                            'class': 'payment_actions_values',
                            'type': 'text',

                        });
                        column.appendChild(action_values);
                    }
                    column = $('#action_column_' + payment_count).get(0);
                    var input_node = document.createElement('input');
                    input_node = setAllAttributes(input_node, {
                        'type': 'hidden',
                        'name': 'actions_key[value_' + payment_count + '][]',
                        'value': ''
                    });
                    column.appendChild(input_node);

                    $('.multiselect2').select2();
                }
            });
        }

        /**
         * Change rule status form listing page
         * */
        $(document).on( 'click', '.dscpw_status_on_listing', function() {
            var current_rule_id = $( this ).attr( 'cp-rule-id' );
            var current_value = $( this ).prop( 'checked' );
            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'dscpw_change_status_from_listing_page',
                    'security': coditional_vars.dscpw_ajax_nonce,
                    'current_rule_id': current_rule_id,
                    'current_value': current_value
                }, beforeSend: function() {
                    var div = document.createElement( 'div' );
                    div = setAllAttributes( div, {
                        'class': 'loader-overlay',
                    } );

                    var img = document.createElement( 'img' );
                    img = setAllAttributes( img, {
                        'id': 'before_ajax_id',
                        'src': coditional_vars.ajax_icon
                    } );

                    div.appendChild( img );
                    var tBodyTrLast = document.querySelector('.dscpw-section-left');
                    tBodyTrLast.appendChild(div);
                }, complete: function() {
                    jQuery( '.dscpw-section-left .loader-overlay' ).remove();
                }, success: function( response ) {
                    console.log( jQuery.trim( response ) );
                }
            });
        });

        // Script for Beacon configuration
        var helpBeaconCookie = getCookie( 'dscpw-help-beacon-hide' );
        if ( ! helpBeaconCookie ) {
            Beacon('init', 'afe1c188-3c3b-4c5f-9dbd-87329301c920');
            Beacon('config', {
                display: {
                    style: 'icon',
                    iconImage: 'message',
                    zIndex: '99999'
                }
            });

            // Add plugin articles IDs to display in beacon
            Beacon('suggest', ['62d501b903382e4311cf5488', '62d1146503382e4311cf4c62', '62d117fe360964399e47101f', '62d11a9eeabe9a7235b3e08a', '62d1215aeabe9a7235b3e0a9']);

            // Add custom close icon form beacon
            setTimeout(function() {
                if ( $( '.hsds-beacon .BeaconFabButtonFrame' ).length > 0 ) {
                    let newElement = document.createElement('span');
                    newElement.classList.add('dashicons', 'dashicons-no-alt', 'dots-beacon-close');
                    let container = document.getElementsByClassName('BeaconFabButtonFrame');
                    container[0].appendChild( newElement );
                }
            }, 3000);

            // Hide beacon
            $(document).on('click', '.dots-beacon-close', function(){
                Beacon('destroy');
                setCookie( 'dscpw-help-beacon-hide' , 'true', 24 * 60 );
            });
        }

        /** Script for Freemius upgrade popup */
        $(document).on('click', '.dscpw-pro-label', function(){
            $('body').addClass('dscpw-modal-visible');
        });
        $(document).on('click', '.upgrade-to-pro-modal-main .modal-close-btn', function(){
            $('body').removeClass('dscpw-modal-visible');
        });
        $(document).on('click', '.dots-header .dots-upgrade-btn, .dotstore-upgrade-dashboard .upgrade-now', function(e){
            e.preventDefault();
            upgradeToProFreemius( '' );
        });
        $(document).on('click', '.upgrade-to-pro-modal-main .upgrade-now', function(e){
            e.preventDefault();
            $('body').removeClass('dscpw-modal-visible');
            let couponCode = $('.upgrade-to-pro-discount-code').val();
            upgradeToProFreemius( couponCode );
        });
	});
})(jQuery);