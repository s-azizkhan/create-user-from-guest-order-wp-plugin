# Create User From Guest Order

**Contributors:** S.Aziz Khan 
**Tags:** woocommerce, user creation, guest order  
**Requires at least:** 5.0  
**Tested up to:** 6.7.1  
**Requires PHP:** 7.2  
**Stable tag:** 1.0.1  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Auto-creates users from guest WooCommerce orders. Adds manual user creation button. Maps existing orders to users on registration.

## Description

The "Create User From Guest Order" plugin automatically creates an user account from a guest order in WooCommerce. This helps in converting guest customers to registered users, enabling better customer management and engagement.

### Features

- Automatically creates an user account from a guest order.
- Option to send a notification email to the newly created user.
- Configurable settings in WooCommerce General settings tab.
- NEW FEATURE: Button to manually create an user account from order details
- NEW FEATURE: When a user register, check if they have any existing guest orders with the user email. If yes, link all the previous orders to the user.


## Installation

1. **Upload the Plugin Files:**
   Upload the `create-user-from-guest-order` folder to the `/wp-content/plugins/` directory.

2. **Activate the Plugin:**
   Activate the plugin through the 'Plugins' screen in WordPress.

3. **Configure the Plugin:**
   Go to WooCommerce settings and navigate to the General tab to enable the feature and optionally enable user notification emails.

## Usage

1. **Enable the Feature:**
   - Go to `WooCommerce` -> `Settings` -> `General`. It can also be reached from `Settings` at plugins listing page.
   - Enable the checkbox for "Enable Create User From Guest Order".
   - Optionally, enable the checkbox for "Send User Notification Email when user is created".

2. **Place an Order as a Guest:**
   - Go to your WooCommerce store and place an order as a guest.

3. **Verify User Creation:**
   - After the order is placed, check the Users section in the WordPress admin dashboard to verify that a new user has been created with the guest's email and details.
   - Ensure that the order is now associated with the newly created user.

## Frequently Asked Questions

### Q: Does this plugin create a user for existing customers?
A: No, this plugin only creates a user account for guest orders. If the email already exists in the user database, it will link the order to the existing user.

### Q: Can I disable the notification email sent to new users?
A: Yes, you can disable the notification email by unchecking the "Send User Notification Email when user is created" option in the WooCommerce General settings tab.

## Changelog

### 1.0.1 (2024-12-13)
- Improvement: Manually create user from order details.
- Improvement: At plugins page, added a direct link to our settings page.
- Improvement: When a user register, check if they have any existing guest orders with the user email. If yes, link all the previous orders to the user.


### 1.0
- Initial release.

## Upgrade Notice

### 1.0.1 (2024-12-13)
* Improvements: upgrade safe, no breaking changes.

### 1.0
- Initial release.

## License

This plugin is licensed under the GPLv2 or later. For more information, see [GPLv2](http://www.gnu.org/licenses/gpl-2.0.html).

## Author

**Author:** S.Aziz Khan  
**Author URI:** [https://github.com/s-azizkhan](https://github.com/s-azizkhan)