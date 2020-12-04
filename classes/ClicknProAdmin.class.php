<?php

class ClicknProAdmin
{

    public function customMetabox()
    {
        $screens = ['post', 'page'];
        foreach ($screens as $screen) {
            add_meta_box(
                'clicknpro_box_id',
                'Droits d\'accès',
                array($this, 'customBoxHtml'),
                $screen,
                'side'
            );
        }
    }

    public function customBoxHtml()
    {
        global $post;
        // Get Post Privacy Value if it set already.
        $isClicknProPrivate = (int) get_post_meta($post->ID, '_is_clicknpro_private', true);

        require PLUGIN_DIR_PATH . '/views/metabox.php';
    }


    public function savePostData($post_id)
    {
        if (array_key_exists('is_clicknpro_private', $_POST)) {
            update_post_meta(
                $post_id,
                '_is_clicknpro_private',
                $_POST['is_clicknpro_private']
            );
        }
    }

    public function subscriptionMetaFields($tabs): array
    {
        $tabs['cnpro-subscription-field'] = array(
            'label' => __('Subsciption Configs', 'woocommerce'),
            'target' => 'cnpro_subscription_field',
            'class' => array('show_if_simple'),
        );

        return $tabs;
    }

    public function customSubscMetaFields($fields): void
    {
        global $post;

        echo '<div id="cnpro_subscription_field" class="panel woocommerce_options_panel hidden">';

        woocommerce_wp_text_input(
            array(
                '_text_field',
                'label' => __('Custom Text Field', 'woocommerce'),
                'wrapper_class' => 'show_if_simple', //show_if_simple or show_if_variable
                'placeholder' => 'Custom text field',
                'desc_tip' => 'true',
                'description' => __('Enter the text here.', 'woocommerce')
            )
        );

        // Number Field
        woocommerce_wp_text_input(
            array(
                'id' => '_number_field',
                'label' => __('Custom Number Field', 'woocommerce'),
                'placeholder' => '',
                'description' => __('Enter the value here.', 'woocommerce'),
                'type' => 'number',
                'custom_attributes' => array(
                    'step' => 'any',
                    'min' => '15'
                )
            )
        );

        // Checkbox
        woocommerce_wp_checkbox(
            array(
                'id' => '_checkbox',
                'label' => __('Custom Checkbox Field', 'woocommerce'),
                'description' => __('I’ve read and accept the terms & conditions', 'woocommerce')
            )
        );

        // Select
        woocommerce_wp_select(
            array(
                'id' => '_select',
                'label' => __('Custom Select Field', 'woocommerce'),
                'options' => array(
                    'one' => __('Option 1', 'woocommerce'),
                    'two' => __('Option 2', 'woocommerce'),
                    'three' => __('Option 3', 'woocommerce')
                )
            )
        );

        echo '</div>';
    }
}
