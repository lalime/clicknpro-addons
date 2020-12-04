<?php

class ClicknProPublic
{
    private $_listingSubmitedKey = '_listing_submited';
    private $_listingKeywordsKey = 'rlisting_keywords';
    private $_unListingSubnKey = 'rlisting_un_listing_subn';
    private $_listSubnlimitKey = 'rlisting_list_subn_limit';

    /**
     * 
     */
    public function onTemplateRedirect()
    {
        //
        $this->checkPostPrivacy();
        //
        $this->checkCapabilities();
        
    }
    /**
     * 
     */
    public function checkPostPrivacy()
    {
        $postID = get_the_ID();
        
        if (is_singular('post') || is_singular('page')) {
        
            $isClicknProPrivate = (int) get_post_meta($postID, '_is_clicknpro_private', true);
            $hasSub = wcs_user_has_subscription('', '', 'active');
		
            if ($isClicknProPrivate && !$hasSub) {
                $offerPageId = (int) get_option('cnpro_offers_page', 471);
                wp_redirect(get_permalink($offerPageId));
                exit;
            }
        }
    }

    /**
     * 
     */
    public function checkCapabilities(): void
    {
        if (!$this->canListingInsert()) {
            $forbiddenPageId = (int) get_option('cnpro_forbidden_page', 0);
            wp_redirect(get_permalink($forbiddenPageId));
            exit;
        }

        $listingFormPageId = (int) get_option('cnpro_list_submit_page', 0);

        if ($listingFormPageId == get_the_ID()) {
            $totalAllowedTags = -1;
            $subscription = $this->getActiveSubscription();
            
            if ($subscription = $this->getActiveSubscription()) {
                $totalAllowedTags = $this->getSubscriptionMetas($subscription->get_parent_id(), $this->_listingKeywordsKey);
            }

            echo '<script> var totalAllowedTags = ' . $totalAllowedTags . ';</script>';
        }
    }

    /**
     * 
     */
    public function onListingInsert($postID, $post, $update): void
    {
        // If this is a revision, don't send the email.+
        if ($post->post_type == 'rlisting') {

            if ($subsId = $this->getActiveSubscriptionId()) {
                $listingCount = 0;

                if ($update) {
                    $listingCount = (int) get_post_meta($subsId, $this->_listingSubmitedKey, true);
                }

                update_post_meta($subsId, $this->_listingSubmitedKey, $listingCount+1);
            }
        }
    
    }

    /**
     * 
     */
    public function checkPostDatas($postID, $post, $update): void
    {
        // If this is a revision, don't send the email.+
        // if ($post->post_type == 'rlisting' && isset($_POST['rltags'])) {

        //     if ($subsId = $this->getActiveSubscriptionId()) {
                
        //         // $totalTags = (int) get_post_meta($this->_listingSubmitedKey, true);
        //         $allowedTags = (int) get_post_meta($subsId, $this->_listingKeywordsKey, true);
        //         $totalTags = explode(',', $_POST['rltags']);

        //         if ($totalTags > $allowedTags) {

        //         }
        //     }
            // $listingCount = 0;

            // if ($update) {
            //     $listingCount = (int) get_post_meta($this->_listingSubmitedKey, true);
            // }

            // update_post_meta($postID, $this->_listingSubmitedKey, $listingCount+1);
        // }
    
    }

    /**
     * 
     */
    public function canListingInsert(): bool
    {
        $listSubmitPage = get_option('cnpro_list_submit_page', 0);
        
        // If this is a revision, don't send the email.+
        if (get_the_ID() == $listSubmitPage) {
            $subscription = $this->getActiveSubscription();

            if (!$subscription) {
                return false;
            }
            $subsId = $subscription->get_id();
            
            $listingSubmited = (int) get_post_meta($subsId, $this->_listingSubmitedKey, true);
            $qty = $this->getSubscriptionQty($subscription->get_parent_id());

            if ($qty != -1 && ($listingSubmited >= $qty)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 
     */
    public function getActiveSubscription(): object
    {
        $userSubscriptions = wcs_get_users_subscriptions();
        
        foreach ($userSubscriptions as $subscription) {
            if ($subscription->has_status(array('active'))) {
                return  $subscription;
            }
        }

        return null;
    }

    /**
     * 
     */
    public function onInit(): void
    {
        
        if (is_admin())
            return ;
            
        if (is_singular('post') || is_singular('page')) {
            $postID = get_the_ID();
            $listingPageId = (int) get_option('_cnpro_listing_page', 0);


            // if ($postID == $listingPageId) {
                // $postID
                $subscription = $this->getActiveSubscription();
                die(var_dump(count($subscription)));
            // }

            $hasSub = wcs_user_has_subscription('', '', 'active');
		
            // if (...) {
            //     $forbiddenPageId = (int) get_option('cnpro_forbidden_page', 0);
            //     wp_redirect(get_permalink($forbiddenPageId));
            //     exit;
            // }
        }
        
    }

    public function getSubscriptionQty($orderId): int
    { 
        $total = 0;
        // Getting the order object "$order"
        $order = wc_get_order($orderId);
        
        // Getting the items in the order
        $orderItems = $order->get_items();
        // Iterating through each item in the order
        foreach ($orderItems as $item) {
            // Get the item quantity
            $qty = $item->get_quantity();
            $itemId = $item->get_product_id();
            // Product meta
            $listSubnlimit = (int) get_post_meta($itemId, $this->_listSubnlimitKey, true);
            $unListingSubn = (int) get_post_meta($itemId, $this->_unListingSubnKey, true);
            // var_dump($listSubnlimit, $unListingSubn);
            if ($unListingSubn) {
                $total = -1;
                break;
            }
            
            $total += ($qty * $listSubnlimit);
        }

        return $total;
    }

    public function getSubscriptionMetas($orderId, $key = ''): int
    {
        $listingKeywords = 0;
        // Getting the order object "$order"
        $order = wc_get_order($orderId);
        // Getting the items in the order
        $orderItems = $order->get_items();
        
        // Iterating through each item in the order
        foreach ($orderItems as $item) {
            $itemId = $item->get_product_id();

            // Product meta
            if (!$key) {
                return get_post_meta($itemId);
            }

            $listingKeywords = (int) get_post_meta($itemId, $this->_listingKeywordsKey, true);
        }

        return $listingKeywords;
    }

    public function getActiveSubscriptionId(): int
    {
        if (!$subscription = $this->getActiveSubscription()) {
            return 0;
        }
        
        return $subscription->get_id();
    }

    public function addFrontScripts(): void
    {
        wp_enqueue_script('cnpro_front', '/wp-content/plugins'. PLUGIN_DIR . '/assets/js/front.js', array('jquery'), true);
    }
}
