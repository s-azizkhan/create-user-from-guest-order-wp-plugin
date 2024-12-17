<?php

/**
 * Create User From Guest Order
 *
 * @package           Create_User_From_Guest_Order
 * @author            Aziz Khan
 * @copyright         2024 s-azizkhan
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Create User From Guest Order
 * Plugin URI:        https://github.com/s-azizkhan/create-user-from-guest-order-wp-plugin
 * Description:       Automatically creates a user from a guest order in WooCommerce.
 * Version:           1.0.1
 * Author:            <a href="https://github.com/s-azizkhan">Aziz Khan</a>, <a href="https://github.com/greguly">Gabriel Reguly</a>
 * Author URI:        https://github.com/s-azizkhan
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       create-user-from-guest-order
 * Requires Plugins:  woocommerce
 * 
 * WC requires at least: 5.0
 * WC tested up to: 9.4.3
 * 
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define("CUFGO_VERSION", "1.0.1");

class CUFGO_User_From_Guest_Order
{
    /**
     * CreateUserFromGuestOrder constructor.
     */
    public function __construct()
    {
        add_action('init', array($this, 'run'));
    }

    public function run()
    {
        // Add settings to the WooCommerce settings general tab for enable and disable the feature
        add_filter('woocommerce_general_settings', array($this, 'createUserFromGuestOrderSettings'));

        // Create user from guest order
        add_action('woocommerce_new_order', array($this, 'createUserFromGuestOrder'), 10, 1);

        // Create user from guest order when admin update the order
        add_action('woocommerce_process_shop_order_meta', array($this, 'createUserFromGuestOrder'), 999, 1);

        // Maybe show a button to create an user.
        add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'maybeShowCreateUserButton' ) );

        // AJAX processing for user creation.
        add_action( 'wp_ajax_cufgo_maybe_create_user', array( $this, 'maybeCreateUser') );

        // Easy link to our settings page.
        add_filter( 'plugin_action_links_create-user-from-guest-order-wp-plugin/create-user-from-guest-order.php', array( $this, 'pluginActionLinks' ) );

    }

    /**
     * Add a link to our settings page at plugins listing page.
     * @param  array $links
     * @return array
     * 
     * @since 1.0.1
     */
    public function pluginActionLinks( $links ) {

        $plugin_links = array( '<a href="' .  admin_url( 'admin.php?page=wc-settings' ) . '">' . esc_html__( 'Settings', 'create-user-from-guest-order' ) . '</a>' );

        return array_merge( $plugin_links, $links );
    }


    /**
     * Show create user button if order customer does not exist.
     *
     * @param WC_Order $order
     * @since 1.0.1
     */
    public function maybeShowCreateUserButton( $order ) {

        if ( self::isFeatureEnabled() ) {

            $customer_id = $order->get_customer_id();
            $user        = get_user_by( 'id', $customer_id );
            if ( ! $user ) {

                printf( '<p class="form-field form-field-wide" ><a href="%s" class="button woocommerce_order_action_create_user">%s</a></p>',
                        wp_nonce_url( admin_url( 'admin-ajax.php?action=cufgo_maybe_create_user&order_id=' . $order->get_id()  ), 'create_user_action' ),
                        esc_html__( 'Create user from order details.', 'create-user-from-guest-order' ) );


            }
        }
    }

    /**
     * Maybe create user.
     * 
     * @since 1.0.1
     */
    public function maybeCreateUser() {

        if ( ! is_admin() ) {
            wp_die( esc_html__('Error. Not at admin panel.', 'create-user-from-guest-order' ) );
        }

        if ( ! current_user_can( 'edit_shop_orders' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'create-user-from-guest-order' ) );
        }

        if ( ! check_admin_referer( 'create_user_action' ) ) {
            wp_die( esc_html__( 'You have taken too long. Please go back and retry.', 'create-user-from-guest-order' ) );
        }

        $order_id = isset( $_GET['order_id'] ) && (int) $_GET['order_id'] ? (int) $_GET['order_id'] : '';
        if ( ! $order_id ) {
            wp_die( esc_html__( 'Empty order id.', 'create-user-from-guest-order' ) );
        }

        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            wp_die( esc_html__( 'Could not load order.', 'create-user-from-guest-order' ) );    
        }

        self::createUser( $order );

        wp_redirect( wp_get_referer() );
        exit;
    }



    /**
     * Check if the feature to create a user from a guest order is enabled.
     *
     * @return bool
     */
    public static function isFeatureEnabled(): bool
    {
        return get_option('cufgo_enable') == 'yes' ? true : false;
    }

    /**
     * Determines if user notification is enabled.
     *
     * @return bool True if user notification is enabled, false otherwise.
     */
    public static function isNotificationEnabled(): bool
    {
        return get_option('cufgo_send_user_notification_email') == 'yes' ? true : false;
    }

    /**
     * Create user from guest order Settings
     *
     * @param array $settings
     * @return array
     */
    public function createUserFromGuestOrderSettings($settings)
    {
        $settings[] = array(
            'title' => __('Create User From Guest Order', 'create-user-from-guest-order'),
            'desc' => __('Map existing user to guest order or Create new user while creating/updating guest order, ( Billing email used in validation ) imp: This will work when order created by Admin', 'create-user-from-guest-order'),
            'type' => 'title',
            'id' => 'cufgo_settings',
        );

        // Enable the feature checkbox
        $settings[] = array(
            'title' => __('Enable Create User From Guest Order', 'create-user-from-guest-order'),
            'desc' => __('Check this to enable user creation on guest order', 'create-user-from-guest-order'),
            'id' => 'cufgo_enable',
            'type' => 'checkbox',
            'default' => 'no',
        );

        // Send User Notification Email when user is created checkbox
        $settings[] = array(
            'title' => __('Send User Notification Email when user is created', 'create-user-from-guest-order'),
            'desc' => __('Check this to send user notification email when user is created', 'create-user-from-guest-order'),
            'id' => 'cufgo_send_user_notification_email',
            'type' => 'checkbox',
            'default' => 'no',
        );

        $settings[] = array(
            'type' => 'sectionend',
            'id' => 'cufgo_settings',
        );
        return $settings;
    }

    /**
     * Create user from guest order
     *
     * @param int $order_id
     */
    public function createUserFromGuestOrder($order_id)
    {
        try {
            // Check if the feature is enabled and continue
            if (self::isFeatureEnabled() && is_admin()) {
                $order = wc_get_order($order_id);
                //get customer ID
                $customer_id = $order->get_customer_id();
                // If customer ID is 0, then it's a guest order and we need to create a user
                if ($customer_id == 0) {
                    $this->createUser($order);
                }
            }
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Create user from Order
     *
     * @param WC_Order $order
     */
    public function createUser($order)
    {
        // get from billing
        $billing_email = $order->get_billing_email();

        // Validate email
        if (!is_email($billing_email)) {
            return;
        }

        // Find if user exists with the email
        $user = get_user_by('email', $billing_email);
        // If user exists, then we don't need to create a new user just update the customer ID
        if ($user) {
            $order->set_customer_id($user->ID);
            $order->save();
        } else {
            // Create a new WooCommerce customer
            $customer = new WC_Customer();

            // Set location for billing
            $customer->set_billing_location($order->get_billing_country() ?? '', $order->get_billing_state() ?? '', $order->get_billing_postcode() ?? '', $order->get_billing_city() ?? '');
            $customer->set_email($billing_email);
            $customer->set_first_name($order->get_billing_first_name() ?? '');
            $customer->set_last_name($order->get_billing_last_name() ?? '');
            $customer->set_billing_company($order->get_billing_company() ?? '');
            $customer->set_billing_address($order->get_billing_address_1() ?? '');
            $customer->set_billing_address_2($order->get_billing_address_2() ?? '');
            $customer->set_billing_phone($order->get_billing_phone() ?? '');

            // Set location & address for shipping
            $customer->set_shipping_location($order->get_shipping_country() ?? '', $order->get_shipping_state() ?? '', $order->get_shipping_postcode() ?? '', $order->get_shipping_city() ?? '');
            $customer->set_shipping_first_name($order->get_shipping_first_name() ?? '');
            $customer->set_shipping_last_name($order->get_shipping_last_name() ?? '');
            $customer->set_shipping_company($order->get_shipping_company() ?? '');
            $customer->set_shipping_address_1($order->get_shipping_address_1() ?? '');
            $customer->set_shipping_address_2($order->get_shipping_address_2() ?? '');
            $customer->set_shipping_phone($order->get_shipping_phone() ?? '');

            // Generate Password
            $customer->set_password(wp_generate_password());
            // Create the customer
            $customer_id = $customer->save();

            // Send User Notification
            if (self::isNotificationEnabled()) {
                wp_new_user_notification($customer_id, null, 'both');
            }

            // update order
            $order->set_customer_id($customer_id);
            $order->save();
        }
    }

    /**
     * Deactivate function that deletes specific options related to user notification and settings.
     */
    public static function deactivate()
    {
        delete_option('cufgo_send_user_notification_email');
        delete_option('cufgo_enable');
        delete_option('cufgo_settings');
    }
}

register_deactivation_hook(__FILE__, array(CUFGO_User_From_Guest_Order::class, 'deactivate'));

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function cufgo_init()
{
    // Initialize the plugin
    if (class_exists('CUFGO_User_From_Guest_Order')) {
        new CUFGO_User_From_Guest_Order();
    }
}

cufgo_init();
