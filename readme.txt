=== Create User From Guest Order ===
Contributors: aziz22
Donate link: https://github.com/s-azizkhan
Tags: woocommerce, user creation, guest order
Requires at least: 5.0
Tested up to: 6.7.1
Requires PHP: 7.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically creates an user from a guest order in WooCommerce. Button to manually create an user from order details.

== Description ==

The "Create User From Guest Order" plugin automatically creates an user account from a guest order in WooCommerce. This helps in converting guest customers to registered users, enabling better customer management and engagement.

== Features ==

* Automatically creates an user account from a guest order.
* Option to send a notification email to the newly created user.
* Configurable settings in WooCommerce General settings tab.
* NEW FEATURE: Button to manually create an user account from order details.

== Installation ==

1. Download the plugin ZIP file.
2. Log in to your WordPress dashboard.
3. Go to Plugins > Add New.
4. Click "Upload Plugin" and choose the ZIP file.
5. Click "Install Now" and then "Activate Plugin."

== Usage ==

1. **Enable the Feature:**
   - Go to `WooCommerce` -> `Settings` -> `General`. It can be reached from `Settings` at plugins listing page.
   - Enable the checkbox for "Enable Create User From Guest Order".
   - Optionally, enable the checkbox for "Send User Notification Email when user is created".

2. **Place an Order as a Guest:**
   - Go to your WooCommerce store and place an order as a guest.

3. **Verify User Creation:**
   - After the order is placed, check the Users section in the WordPress admin dashboard to verify that a new user has been created with the guest's email and details.
   - Ensure that the order is now associated with the newly created user.

== Frequently Asked Questions ==

= Does this plugin create a user for existing customers? =

No, this plugin only creates a user account for guest orders. If the email already exists in the user database, it will link the order to the existing user.

= Can I disable the notification email sent to new users? =

Yes, you can disable the notification email by unchecking the "Send User Notification Email when user is created" option in the WooCommerce General settings tab.

== Changelog ==

= 1.0.1 2024-11-30 =
* Improvement: Manually create user from order details.
* Improvement: At plugins page, added a direct link to our settings page.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.1 2024-11-30 =
* Improvements: upgrade safe, no breaking changes.

= 1.0 =
* Initial release.

== License ==

This plugin is licensed under the GPLv2 or later. For more information, see [GPLv2](http://www.gnu.org/licenses/gpl-2.0.html).

== Author ==

**Author:** S.Aziz Khan
**Author URI:** [https://github.com/s-azizkhan](https://github.com/s-azizkhan)
