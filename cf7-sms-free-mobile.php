<?php

/**
 * Plugin Name:       SMS Notifications for Contact Form 7 - FreeMobile
 * Description:       SMS notification system for Contact Form 7 using the FreeMobile API
 * Version:           1.0
 * Author:            Emilien M.
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       settings-cf7_sms
 * Domain Path:       languages/
 */
defined('ABSPATH') || die();


add_action('init', 'settings_cf7_sms_load_textdomain');
/**
 * Load translations
 */
function settings_cf7_sms_load_textdomain()
{
    load_plugin_textdomain('settings-cf7_sms', FALSE, basename(dirname(__FILE__)) . '/languages/');
}


include 'inc/admin-menu.php';
include 'inc/settings.php';



add_action('init', function () {
    // add_filter('https_ssl_verify', '__return_false');
    add_action('wpcf7_mail_sent', function ($contact_form) {

        $submission = WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        $settings_cf7_sms_settings = get_option('settings_cf7_sms_settings');


        $msg = "Contact\n" . implode("\n", $posted_data);

        $msg = trim($msg);
        if ($settings_cf7_sms_settings['settings_cf7_sms_token_field'] && $settings_cf7_sms_settings['settings_cf7_sms_user_field'] && !empty($msg)) {
            $_url = "https://smsapi.free-mobile.fr/sendmsg";
            $_args = [
                'user' => $settings_cf7_sms_settings['settings_cf7_sms_user_field'],
                'pass' => $settings_cf7_sms_settings['settings_cf7_sms_token_field'],
                'msg' => $msg,
            ];
            $response = wp_safe_remote_get($_url . "?" . http_build_query($_args), []);
        }

    });
});
