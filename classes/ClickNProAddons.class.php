<?php

class ClickNProAddons
{
    protected $pluginPath;
    protected $pluginUrl;
     
    public function __construct()
    {

        // Set Plugin Path
        $this->pluginPath = WP_PLUGIN_DIR . '/clicknpro-addons';

        // Set Plugin URL
        $this->pluginUrl = WP_PLUGIN_URL . '/clicknpro-addons';
         
        // Init hooks
        $this->admin_hooks();
        $this->public_hooks();
    }

    public function admin_hooks()
    {
        $adminClass = new ClicknProAdmin;

        add_action('add_meta_boxes', array($adminClass, 'customMetabox'));
        add_action('save_post', array($adminClass, 'savePostData'));
        add_action('woocommerce_product_data_tabs', array($adminClass, 'subscriptionMetaFields'));
        add_action('woocommerce_product_data_panels', array($adminClass, 'customSubscMetaFields'));
        add_action('woocommerce_process_product_meta', array($adminClass, 'customSubscMetaSave'));
    }



    public function public_hooks()
    {
        $publicClass = new ClicknProPublic;

        add_action('template_redirect', array($publicClass, 'onTemplateRedirect'));
        add_action('wp_insert_post', array($publicClass, 'onListingInsert'), 10, 3);
        // add_action('wp_insert_post_data', array($publicClass, 'checkPostDatas'), 10, 3);

        add_action('wp_enqueue_scripts', array($publicClass, 'addFrontScripts'));   

        // For testing purpose
        // add_action('init', array($publicClass, 'onInit'));
    }
}