<?php

// Hide result count and ordering dropdown
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Change number of products that are displayed per page (shop page)
 */
add_filter( 'loop_shop_per_page', 'tby_loop_shop_per_page', 20 );
function tby_loop_shop_per_page( $cols ) {
  $cols = (!empty(get_field('product_per_page_for_shop','option'))) ? get_field('product_per_page_for_shop','option') : 16;
  return $cols;
}

/**
 * Register custom taxonomy Item Type for Products
 */
add_action( 'init', 'tby_custom_taxonomy_item_type', 0 );
function tby_custom_taxonomy_item_type()  {

    $labels = array(
        'name'                       => 'Item Types',
        'singular_name'              => 'Item Type',
        'menu_name'                  => 'Item Types',
        'all_items'                  => 'All Item Types',
        'parent_item'                => 'Parent Item Type',
        'parent_item_colon'          => 'Parent Item Type:',
        'new_item_name'              => 'New Item Type Name',
        'add_new_item'               => 'Add New Item Type',
        'edit_item'                  => 'Edit Item Type',
        'update_item'                => 'Update Item Type',
        'separate_items_with_commas' => 'Separate Item Type with commas',
        'search_items'               => 'Search Item Types',
        'add_or_remove_items'        => 'Add or remove Item Types',
        'choose_from_most_used'      => 'Choose from the most used Item Types',
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );

    register_taxonomy( 'item_type', 'product', $args );
    
}

/**
 * Ajax function for getting products by filters arguments 
 */
add_action( 'wp_ajax_tby_filter_shop_products', 'tby_filter_shop_products_callback' );
add_action( 'wp_ajax_nopriv_tby_filter_shop_products', 'tby_filter_shop_products_callback' );
function tby_filter_shop_products_callback(){
    $response = array();
    if ( ! wp_verify_nonce( $_POST['nonce'], 'tby-shop' ) ) {
        $response['msg'] = __('Invalid request! Please Try again.');
        wp_send_json_error( $response );
    }

    $selected_country 	    = ( !empty($_POST['selected_country'])) ? $_POST['selected_country'] : '';
	$selected_item_type 	= ( !empty($_POST['selected_item_type'])) ? $_POST['selected_item_type'] : '';
	$paged 				    = ( !empty($_POST['paged'])) ? $_POST['paged'] : 1;	
	$tax_query              = array(); 
	
	if (!empty($selected_country) && $selected_country != 0 ) {		
		$tax_query[] = array(
			'taxonomy' => 'country',
			'field'    => 'id',
			'terms'    => $selected_country,
			'include_children' => true,
			'operator' => 'IN'
		);	
	}
	if ( !empty($selected_item_type) && $selected_item_type != 0 ) {
        $tax_query[] = array(
			'taxonomy' => 'item_type',
			'field'    => 'id',
			'terms'    => $selected_item_type,
			'include_children' => true,
			'operator' => 'IN'
		);
	}
    $tax_query[] = array(
        'taxonomy'  => 'product_visibility',
        'terms'     => array( 'exclude-from-catalog' ),
        'field'     => 'name',
        'operator'  => 'NOT IN',
    );
    $posts_per_page = apply_filters( 'loop_shop_per_page', 16 );

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $posts_per_page,
        'tax_query' => $tax_query,
        'paged'=> $paged,
        'orderby' => 'publish_date',
        'order'   => 'DESC' 
    );
    $query = new WP_Query( $args );
    ob_start();
    $products = '';
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) : $query->the_post();
            wc_get_template_part( 'content', 'product' );
        endwhile;
    } else {
        echo 'No products found';
    }   
    // Optionally (if needed).
    wp_reset_query();
    wp_reset_postdata();
    
    $products = ob_get_clean();

    if ( $query->max_num_pages > 1 && $paged != $query->max_num_pages){
		$paged = $paged+1;
	}else{
		$paged = '';
	}		

	$response = array(
		'html_response' => $products,
		'paged' => $paged
	);

    wp_send_json_success( $response );

}

/**
 * Remove product page tabs
 */
add_action( 'woocommerce_after_single_product_summary', 'tby_removing_product_tabs', 2 );
function tby_removing_product_tabs(){
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    add_action( 'woocommerce_after_single_product_summary', 'tby_product_tab_templates_displayed', 10 );
}
/**
 * Show product description without tabs
 */
function tby_product_tab_templates_displayed() {
    wc_get_template( 'single-product/tabs/description.php' );
}

/**
 * Remove SKU from the product page
 */
add_filter( 'wc_product_sku_enabled', 'tby_remove_product_page_skus' );
function tby_remove_product_page_skus( $enabled ) {
    if ( ! is_admin() && is_product() ) return false;
    return $enabled;
}

/**
 * Add a starting div for add to cart form wrapper
 */
add_action( 'woocommerce_before_add_to_cart_form', 'tby_start_wrapper_before_add_to_cart_form', 10 );
function tby_start_wrapper_before_add_to_cart_form(){
    ?><div class="product-add-to-cart-wrapper"><div class="product-add-to-cart-wrapper-inner"><?php
}

/**
 * Add Preview PDF content and ending div for add to cart form wrapper
 */
add_action('woocommerce_after_add_to_cart_form', 'tby_end_wrapper_before_add_to_cart_form', 10 );
function tby_end_wrapper_before_add_to_cart_form(){
    global $product;
    ?>
    </div><!-- end product-add-to-cart-wrapper-inner -->
    <?php
    $file = get_field('preview_file');
    if( $file ):
        $url = wp_get_attachment_url( $file ); ?>
    <div class="product-preview-wrapper"> <!-- start product-preview-wrapper -->
        <label><?php esc_html_e( 'Preview', 'woocommerce' ) ?></label>
        <strong><?php esc_html_e( 'FREE PDF Download', 'woocommerce' ) ?></strong>
            <a href="<?php echo esc_html($url); ?>" class="elementor-button" target="_blank"><?php esc_html_e( 'Download', 'woocommerce' ) ?></a>
    </div> <!-- end product-preview-wrapper -->
    <?php endif; ?>

    </div> <!-- end product-add-to-cart-wrapper -->
    <?php 
}

// Add additional Fields on Woocoomerce Registration Page
add_action( 'woocommerce_register_form_start', 'tby_add_extra_fields_start_registration', 10 );
function tby_add_extra_fields_start_registration() {
    ?>
  
    <p class="woocommerce-form-row form-row-first form-row">
    <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
    </p>
  
    <p class="woocommerce-form-row form-row-last form-row">
    <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
    </p>
  
    <div class="clear"></div>
  
    <?php
}

// Validation for additional fields
add_filter( 'woocommerce_process_registration_errors', 'tby_validate_woo_account_registration_fields', 10, 4 );
function tby_validate_woo_account_registration_fields( $errors, $username, $password, $email ) {  
    
    if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
        $errors->add( 'billing_first_name_error', __( 'First name is required!', 'woocommerce' ) );
    }
    if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
        $errors->add( 'billing_last_name_error', 'Last name is required!.' );
    }

    return $errors;
}

// Save data for additional fields
add_action( 'woocommerce_created_customer', 'tby_save_extra_fields_registration' );
function tby_save_extra_fields_registration( $customer_id ) {
    if ( isset( $_POST['billing_first_name'] ) ) {
        update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
        update_user_meta( $customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']) );
    }
    if ( isset( $_POST['billing_last_name'] ) ) {
        update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
        update_user_meta( $customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']) );
    }  
}

function wpb_woo_endpoint_title( $title, $id ) {    
    if ( is_wc_endpoint_url( 'downloads' ) && in_the_loop() ) { // add your endpoint urls
        $title = "My Account"; // change your entry-title
    }
    elseif ( is_wc_endpoint_url( 'orders' ) && in_the_loop() ) {
        $title = "My Account";
    }
    elseif ( is_wc_endpoint_url( 'add-payment-method' ) && in_the_loop() ) {
        $title = "My Account";
    }   
    elseif ( is_wc_endpoint_url( 'view-order' ) && in_the_loop() ) {
        $title = "My Account";
    }
    elseif ( is_wc_endpoint_url( 'edit-address' ) && in_the_loop() ) {
        $title = "My Account";
    }
    elseif ( is_wc_endpoint_url( 'payment-methods' ) && in_the_loop() ) {
        $title = "My Account";
    }
    elseif ( is_wc_endpoint_url( 'edit-account' ) && in_the_loop() ) {
        $title = "My Account";
    }
    return $title;
}
add_filter( 'the_title', 'wpb_woo_endpoint_title', 10, 2 );

/**
 * @desc Remove in all product type
 */
function tby_remove_all_quantity_fields( $return, $product ) {
    return true;
}



add_filter( 'woocommerce_account_orders_columns', 'filter_woocommerce_account_orders_columns', 10, 1 );

/**
 * Change number of related products output
 */ 
add_filter( 'woocommerce_output_related_products_args', 'tby_related_products_args', 10, 1 );
function tby_related_products_args( $args ) {
    $args['posts_per_page'] = 5;    
    return $args;
}

add_filter( 'woocommerce_related_products_columns', 'tby_related_products_columns', 10, 1 );
function tby_related_products_columns( $columns ) {
    return 5;
}

add_filter( 'woocommerce_cart_item_name', 'tby_custom_variation_item_name', 10, 3 );
function tby_custom_variation_item_name( $item_name,  $cart_item,  $cart_item_key ){
    // Change item name only if is a product variation
    if( $cart_item['data']->is_type('variation') ){
        // HERE customize item name        
        $attribute_name = (!empty($cart_item['variation']['attribute_buy-method'])) ? $cart_item['variation']['attribute_buy-method'] : $cart_item['variation']['attribute_select-option'];
        
        if(is_checkout()){
            $item_name = $cart_item['data']->get_title().'  '.'<span class="attribute-name">('.$attribute_name.')</span>';
        }else{
            $item_name = $cart_item['data']->get_title().'  '.'<span class="attribute-name">'.$attribute_name.'</span>';
        }
        
        // For cart page we add back the product link
        if(is_checkout()){
            $item_name = sprintf( '<a href="%s">%s</a>', esc_url( $cart_item['data']->get_permalink() ), $item_name );
        }else{
            $item_name = sprintf( '<a href="%s">%s</a>', esc_url( $cart_item['data']->get_permalink() ), $item_name );
        }            
    }
    return $item_name;
}

add_filter( 'woocommerce_order_item_name', 'tby_change_order_item_name', 10, 3 );

/**
 * Function for `woocommerce_order_item_name` filter-hook.
 * 
 * @param  $item_name 
 * @param  $item      
 * @param  $false     
 *
 * @return 
 */
function tby_change_order_item_name( $item_name, $item, $false ){
    
    $variation_id = $item->get_variation_id();
    if($variation_id){
        $woocommerce_meta_data = $item->get_formatted_meta_data();
        foreach ($woocommerce_meta_data as $key => $value){ 
            $attr = (!empty($woocommerce_meta_data[$key]->value))? ' - '.$woocommerce_meta_data[$key]->value : $woocommerce_meta_data[$key]->display_value; 
        } 
    }   
	// filter...
    $item_name = $item_name.' <span class="attribute-name">'.$attr;
	return $item_name;
}

// WooCommerce Rename Checkout Fields
add_filter( 'woocommerce_checkout_fields' , 'custom_rename_wc_checkout_fields' );

// Change placeholder and label text
function custom_rename_wc_checkout_fields( $fields ) {
  $fields['billing']['billing_postcode']['label'] = 'Postcode / ZIP';
  $fields['shipping']['shipping_postcode']['label'] = 'Postcode / ZIP';
  return $fields;
}

// for adding phone and email field to woocommerce shipping form 
add_filter( 'woocommerce_checkout_fields' , 'tby_phone_email_shipping_checkout_fields' );

function tby_phone_email_shipping_checkout_fields( $fields ) {
	 $fields['shipping']['shipping_phone'] = array(
        'label'     => __('Phone', 'woocommerce'),
        'required'  => true,
        'class'     => array('form-row-wide'),
        'validate' 	=> array( 'phone' ),
        'clear'     => true
	 );
	 $fields['shipping']['shipping_email'] = array(
        'label'     => __('Email address', 'woocommerce'),
        'required'  => true,
        'class'     => array('form-row-wide'),
        'validate'		=> array( 'email' ),
        'clear'     => true
	 );

	 return $fields;
}

/* Display additional shipping fields (email, phone) in ADMIN area (i.e. Order display ) */
/* Note:  $fields keys (i.e. field names) must be in format:  WITHOUT the "shipping_" prefix (it's added by the code) */
add_filter( 'woocommerce_admin_shipping_fields' , 'my_additional_admin_shipping_fields' );

function my_additional_admin_shipping_fields( $fields ) {
        $fields['email'] = array(
            'label' => __( 'Order Ship Email', 'woocommerce' ),
        );
        $fields['phone'] = array(
            'label' => __( 'Order Ship Phone', 'woocommerce' ),
        );
        return $fields;
}
/* Display additional shipping fields (email, phone) in USER area (i.e. Admin User/Customer display ) */
/* Note:  $fields keys (i.e. field names) must be in format: shipping_ */
add_filter( 'woocommerce_customer_meta_fields' , 'my_additional_customer_meta_fields' );
function my_additional_customer_meta_fields( $fields ) {
        $fields['shipping']['fields']['shipping_phone'] = array(
            'label' => __( 'Telephone', 'woocommerce' ),
            'description' => '',
        );
        $fields['shipping']['fields']['shipping_email'] = array(
            'label' => __( 'Email', 'woocommerce' ),
            'description' => '',
        );
        return $fields;
}
/* Add CSS for ADMIN area so that the additional shipping fields (email, phone) display on left and right side of edit shipping details */
add_action('admin_head', 'my_custom_admin_css');
function my_custom_admin_css() {
  echo '<style>
    #order_data .order_data_column ._shipping_email_field {
        clear: left;
        float: left;
    }
    #order_data .order_data_column ._shipping_phone_field {
        float: right;
    }
  </style>';
}

