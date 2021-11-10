<?php
defined('ABSPATH') || die();


/******************************
 * Setting up the admin pages
 ******************************/
add_action('admin_menu', 'settings_cf7_sms_menu_items');
/**
 * Registers our new menu items
 */
function settings_cf7_sms_menu_items()
{
    $page_hookname = add_management_page(
        __('Contact Form 7 - SMS Notifications', 'settings-cf7_sms'),
        __('CF7  - Notifs SMS', 'settings-cf7_sms'),
        'manage_options',
        'settings_cf7_sms_top_level_settings_page',
        'settings_cf7_sms_top_level_page_callback',
    );
}

/**
 * Displays our top level page content
 */
function settings_cf7_sms_top_level_page_callback()
{
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="POST">
            <?php
            settings_fields('settings_cf7_sms');
            do_settings_sections('settings_cf7_sms_top_level_settings_page');
            submit_button();
            ?>
        </form>
    </div>
<?php
}