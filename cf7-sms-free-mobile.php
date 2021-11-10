<?php

/**
 * Plugin Name:       CF7 SMS Notifications - Free Mobile
 * Description:       Systeme de notifications par SMS pour Contact Form 7 utilisant l'API Free Mobile
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
    add_action('wpcf7_mail_sent', function ($cf7) {
        $settings_cf7_sms_settings = get_option('settings_cf7_sms_settings');

        $msg = "Message recu via CF7";
        $msg = trim($msg);
        if (!$settings_cf7_sms_settings['settings_cf7_sms_token_field'] || !$settings_cf7_sms_settings['settings_cf7_sms_user_field'] || empty($msg)) {
            throw new \Exception("Un des paramètres obligatoires est manquant", 400);
        }

        $_url = "https://smsapi.free-mobile.fr/sendmsg";
        $_args = [
            'user' => $settings_cf7_sms_settings['settings_cf7_sms_user_field'],
            'pass' => $settings_cf7_sms_settings['settings_cf7_sms_token_field'],
            'msg' => urlencode($msg),
        ];
        $_curl = curl_init();
        curl_setopt($_curl, CURLOPT_URL, $_url . "?" . http_build_query($_args));
        curl_setopt($_curl, CURLOPT_HEADER, false);
        curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_exec($_curl);
        if (curl_errno($_curl)) {
            echo 'Curl error: ' . curl_error($_curl);
            $statut = curl_getinfo($_curl, CURLINFO_HTTP_CODE);
            $erreur = false;
            switch ($statut) {
                case 60:
                    $erreur = "SSL ?!";
                    break;
                case 400:
                    $erreur = "Un des paramètres obligatoires est manquant.";
                    break;
                case 402:
                    $erreur = "Trop de SMS ont été envoyés en trop peu de temps.";
                    break;
                case 403:
                    $erreur = "Le service n'est pas activé sur l'espace abonné, ou login / clé incorrect.";
                    break;
                case 500:
                    $erreur = "Erreur côté serveur. Veuillez réessayez ultérieurement.";
                    break;
            }
            if ($erreur !== false) {
                echo $erreur;
            }
        }
        curl_close($_curl);
    });
});
