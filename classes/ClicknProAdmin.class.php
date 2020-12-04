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

        // Text Field
        woocommerce_wp_text_input(
            array(
                'id' => 'rlisting_p_icon_class',
                'label' => __('Classe icône', 'woocommerce'),
                'placeholder' => '',
                'description' => __('Entrer la valeur ici.', 'woocommerce'),
                'type' => 'text',
                'custom_attributes' => array(
                    'step' => 'any',
                    'min' => '15'
                )
            )
        );

        // Checkbox
        woocommerce_wp_checkbox(
            array(
                'id' => 'rlisting_recommended',
                'label' => __('Recommandé', 'woocommerce'),
                'description' => __('Pack recommandé ?', 'woocommerce')
            )
        );

        // Number Field
        woocommerce_wp_text_input(
            array(
                'id' => 'rlisting_p_sub_title',
                'label' => __('Sous Titre', 'woocommerce'),
                'placeholder' => '',
                'description' => __('Entrer la valeur ici.', 'woocommerce'),
                'type' => 'text',
            )
        );

        // Number Field
        woocommerce_wp_text_input(
            array(
                'id' => 'rlisting_featured_listings',
                'label' => __('Annonces en vedette', 'woocommerce'),
                'placeholder' => '',
                'description' => __('Nombre d\'annonces en vedette pour ce plan..', 'woocommerce'),
                'type' => 'number',
                'custom_attributes' => array(
                    'step' => 'any',
                    'min' => '1'
                )
            )
        );

        // Number Field
        woocommerce_wp_text_input(
            array(
                'id' => 'rlisting_keywords',
                'label' => __('Mots-clés autorisés', 'woocommerce'),
                'placeholder' => '',
                'description' => __('Nombre de Mots-clés autorisés pour ce plan..', 'woocommerce'),
                'type' => 'number',
                'custom_attributes' => array(
                    'step' => 'any',
                    'min' => '1'
                )
            )
        );

        // Checkbox
        woocommerce_wp_checkbox(
            array(
                'id' => 'rlisting_un_listing_subn',
                'label' => __('Soumission illimitée d\'annonces', 'woocommerce'),
                'description' => __('Cochez cette case si ce plan a une soumission illimitée d\'annonces.', 'woocommerce')
            )
        );

        echo '</div>';
    }

    public function customSubscMetaSave($post_id): void
    {
        $product = wc_get_product($post_id);

        $product->update_meta_data('rlisting_p_icon_class', sanitize_text_field($_POST['rlisting_p_icon_class']));
        $product->update_meta_data('rlisting_p_sub_title', sanitize_text_field($_POST['rlisting_p_sub_title']));
        $product->update_meta_data('rlisting_featured_listings', sanitize_text_field($_POST['rlisting_featured_listings']));
        $product->update_meta_data('rlisting_keywords', sanitize_text_field($_POST['rlisting_keywords']));

        $listingRecommended = isset($_POST['rlisting_recommended']) ? 'yes' : '';
        $product->update_meta_data('rlisting_recommended', $listingRecommended);

        $unListingSubnCheckbox = isset($_POST['rlisting_un_listing_subn']) ? 'yes' : '';
        $product->update_meta_data('rlisting_un_listing_subn', $unListingSubnCheckbox);

        $unListingSubnCheckbox = isset($_POST['rlisting_un_listing_subn']) ? 'yes' : '';
        $product->update_meta_data('rlisting_un_listing_subn', $unListingSubnCheckbox);

        $product->save();
    }
}
