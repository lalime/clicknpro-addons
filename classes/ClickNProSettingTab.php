<?php
class ClickNProSettingTab
{

    public static function init()
    {
        add_filter('woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50);
        add_action('woocommerce_settings_tabs_settings_cnpro', __CLASS__ . '::settings_tab');
        add_action('woocommerce_update_options_settings_cnpro', __CLASS__ . '::update_settings');
    }


    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab($settings_tabs)
    {
        $settings_tabs['settings_cnpro'] = __('ClicknPro', 'woocommerce-settings-cnpro');
        return $settings_tabs;
    }


    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab()
    {
        woocommerce_admin_fields(self::get_settings());
    }


    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings()
    {
        woocommerce_update_options(self::get_settings());
    }


    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings()
    {

        $settings = array(
            'section_title' => array(
                'name'     => __('Section Title', 'woocommerce-settings-cnpro'),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_settings_cnpro_section_title'
            ),
            'list_submit_page' => array(
                'title'    => __('Page ajout listing', 'woocommerce'),
                'desc'     => sprintf(__('Page contents: [%s]', 'woocommerce'), 'Nos offres'),
                'id'       => 'cnpro_list_submit_page',
                'type'     => 'single_select_page',
                'default'  => '',
                'class'    => 'wc-enhanced-select-nostd',
                'css'      => 'min-width:300px;',
                'args'     => array(
                    'exclude' =>
                    array(
                        wc_get_page_id('checkout'),
                        wc_get_page_id('myaccount'),
                    ),
                ),
                'desc_tip' => true,
                'autoload' => false,
                'desc' => __('Page contenant le formulaire de listing.', 'woocommerce-settings-cnpro'),
            ),
            'offers_page' => array(
                'title'    => __('Page Abonements', 'woocommerce'),
                'desc'     => sprintf(__('Page contents: [%s]', 'woocommerce'), 'Nos offres'),
                'id'       => 'cnpro_offers_page',
                'type'     => 'single_select_page',
                'default'  => '',
                'class'    => 'wc-enhanced-select-nostd',
                'css'      => 'min-width:300px;',
                'args'     => array(
                    'exclude' =>
                    array(
                        wc_get_page_id('checkout'),
                        wc_get_page_id('myaccount'),
                    ),
                ),
                'desc_tip' => true,
                'autoload' => false,
                'desc' => __('Page ou sont redirigés les utilisateurs non abonnés.', 'woocommerce-settings-cnpro'),
            ),
            'unauthorized_page' => array(
                'title'    => __('Page non autorisé', 'woocommerce'),
                'desc'     => sprintf(__('Page contents: [%s]', 'woocommerce'), 'Non autorisé'),
                'id'       => 'cnpro_forbidden_page',
                'type'     => 'single_select_page',
                'default'  => '',
                'class'    => 'wc-enhanced-select-nostd',
                'css'      => 'min-width:300px;',
                'args'     => array(
                    'exclude' =>
                    array(
                        wc_get_page_id('checkout'),
                        wc_get_page_id('myaccount'),
                    ),
                ),
                'desc_tip' => true,
                'autoload' => false,
                'desc' => __('Page ou sont redirigés les utilisateurs après verification des droits sur les actions.', 'woocommerce-settings-cnpro'),
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_cnpro_section_end'
            )
        );

        return apply_filters('wc_settings_cnpro_settings', $settings);
    }
}
ClickNProSettingTab::init();
