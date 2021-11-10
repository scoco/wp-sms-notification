<?php
defined('ABSPATH') || die();

/*******************************************************
 * Adding a group of settings fields to the top level page
 *******************************************************/
add_action('admin_init', 'settings_cf7_sms_settings');
/**
 * Registers a single setting
 */
function settings_cf7_sms_settings()
{

    register_setting(
        'settings_cf7_sms',
        'settings_cf7_sms_settings',
        'settings_cf7_sms_settings_sanitize'
    );

    // Register a section in our top level page, to house our group of settings
    add_settings_section(
        'settings_cf7_sms_settings_section',
        __('Configurations', 'settings-cf7_sms'),
        'settings_cf7_sms_settings_section_display',
        'settings_cf7_sms_top_level_settings_page'
    );

    // Registers a token field example
    add_settings_field(
        'settings_cf7_sms_token_field',
        __('FreeMobile API Token', 'settings-cf7_sms'),
        'settings_cf7_sms_settings_token_field_display',
        'settings_cf7_sms_top_level_settings_page',
        'settings_cf7_sms_settings_section',
        array(
            'label_for' => 'settings_cf7_sms_token_field',
        )
    );
    // Registers a user field example
    add_settings_field(
        'settings_cf7_sms_user_field',
        __('FreeMobile user', 'settings-cf7_sms'),
        'settings_cf7_sms_settings_user_field_display',
        'settings_cf7_sms_top_level_settings_page',
        'settings_cf7_sms_settings_section',
        array(
            'label_for' => 'settings_cf7_sms_user_field',
        )
    );
}


/**
 * Displays the content of the multiple settings section
 * 
 * @param  array  $args  Arguments passed to the add_settings_section() function call
 */
function settings_cf7_sms_settings_section_display($args)
{
    // Just var_dumping data here to help you visualize the array organization.
    // var_dump(get_option('settings_cf7_sms_settings'));
}


/**
 * Displays the token field example setting
 * Note the `name` attribute of the input,refering now to an array of settings.
 * 
 * @param  array  $args  Arguments passed to corresponding add_settings_field() call
 */
function settings_cf7_sms_settings_token_field_display($args)
{
    $settings = get_option('settings_cf7_sms_settings');
    $value = !empty($settings['settings_cf7_sms_token_field']) ? $settings['settings_cf7_sms_token_field'] : '';
?>
    <input id="<?php echo esc_attr($args['label_for']); ?>" class="regular-text" type="text" name="settings_cf7_sms_settings[settings_cf7_sms_token_field]" value="<?php echo esc_attr($value); ?>">
<?php
}
/**
 * Displays the user field example setting
 * Note the `name` attribute of the input,refering now to an array of settings.
 * 
 * @param  array  $args  Arguments passed to corresponding add_settings_field() call
 */
function settings_cf7_sms_settings_user_field_display($args)
{
    $settings = get_option('settings_cf7_sms_settings');
    $value = !empty($settings['settings_cf7_sms_user_field']) ? $settings['settings_cf7_sms_user_field'] : '';
?>
    <input id="<?php echo esc_attr($args['label_for']); ?>" class="regular-text" type="text" name="settings_cf7_sms_settings[settings_cf7_sms_user_field]" value="<?php echo esc_attr($value); ?>">
<?php
}




/**
 * Sanitize callback for our settings.
 * We have to sanitize each of our field ourselves.
 * 
 * @param  array  $settings  An array of settings (due to the inputs' name attributes)
 */
function settings_cf7_sms_settings_sanitize($settings)
{
    // Sanitizes the fields
    $settings['settings_cf7_sms_token_field']     = !empty($settings['settings_cf7_sms_token_field']) ? sanitize_text_field($settings['settings_cf7_sms_token_field']) : '';
    $settings['settings_cf7_sms_user_field']     = !empty($settings['settings_cf7_sms_user_field']) ? sanitize_text_field($settings['settings_cf7_sms_user_field']) : '';

    return $settings;
}
