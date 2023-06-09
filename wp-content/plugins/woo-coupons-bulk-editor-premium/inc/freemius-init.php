<?php

if ( !function_exists( 'wpsewcc_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wpsewcc_fs()
    {
        
        if ( ! class_exists( 'wpseFsNull' ) ) {
            class wpseFsNull {
                function is_registered() {
                    return true;
                }

                function can_use_premium_code() {
                    return true;
                }

                function can_use_premium_code__premium_only() {
                    return true;
                }

                function get_id() {
                    return;
                }

                function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
                    add_filter( $tag, $function_to_add, $priority, $accepted_args );
                }

                function add_action( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
                    add_action( $tag, $function_to_add, $priority, $accepted_args );
                }

                function checkout_url() {
                    return;
                }

                function get_account_url() {
                    return;
                }

                function pricing_url() {
                    return;
                }
            }
        }

        return new wpseFsNull();
    }
    
    // Init Freemius.
    wpsewcc_fs();
    // Signal that SDK was initiated.
    do_action( 'wpsewcc_fs_loaded' );
}
