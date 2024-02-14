<?php
/**
 * Core plugin file
 *
 * @since      1.0
 * @package    woo-extra-price
 * @author     Hamid Azad
 */
namespace Woo_Extra_Price;

/*
 * If this file is called directly, abort.
 */
if (!defined('ABSPATH')) {
    exit;
}
class Frontend
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        add_action('woocommerce_before_add_to_cart_button', array($this, 'wep_product_add_on'), 9);
        add_filter('woocommerce_add_to_cart_validation', array($this, 'wep_product_add_on_validation'), 10, 3);
        add_filter('woocommerce_add_cart_item_data', array($this, 'wep_product_add_on_cart_item_data'), 10, 2);
        add_filter('woocommerce_cart_calculate_fees', array($this, 'wep_add_checkout_fee'), 10, 1);
        add_filter('woocommerce_get_item_data', array($this, 'wep_product_add_on_display_cart'), 10, 2);
        add_action('woocommerce_add_order_item_meta', array($this, 'wep_product_add_on_order_item_meta'), 10, 2);
        add_filter('woocommerce_order_item_product', array($this, 'wep_product_add_on_display_order'), 10, 2);
        add_filter('woocommerce_email_order_meta_fields', array($this, 'wep_product_add_on_display_emails'));

    }

    /**
     * Show custom input fields above Add to Cart
     */
    public function wep_product_add_on()
    {
        echo '<div>';
        echo '<p style="font-weight:bold;">' . esc_html__('Extra Item', 'woo-extra-price') . '</p>';

        // Extra Items
        echo '<label class="extra-item-label">';
        echo '<span class="extra-item-label-text">' . esc_html__('Extra Items?*', 'woo-extra-price') . '</span>';
        echo '<span class="dropdown-icon"></span>';
        echo '</label>';
        echo '<p>';
        echo '<input type="checkbox" name="extra_items[]" data-price="2" value="extra_item_1"> ' . esc_html__('Extra Item 1', 'woo-extra-price') . '<span class="item-price">+<span id="extra-item-1">2</span>' . $this->get_currency_symbol() . '</span><br>';
        echo '<input type="checkbox" name="extra_items[]" data-price="4" value="extra_item_2"> ' . esc_html__('Extra Item 2', 'woo-extra-price') . '<span class="item-price">+<span id="extra-item-2">4</span>' . $this->get_currency_symbol() . '</span><br>';
        echo '<input type="checkbox" name="extra_items[]" data-price="5" value="extra_item_3"> ' . esc_html__('Extra Item 3', 'woo-extra-price') . '<span class="item-price">+<span id="extra-item-3">5</span>' . $this->get_currency_symbol() . '</span>';
        echo '</p>';

        // Accessories
        echo '<label class="extra-item-label">';
        echo '<span class="extra-item-label-text">' . esc_html__('Accessories*', 'woo-extra-price') . '</span>';
        echo '<span class="dropdown-icon"></span>';
        echo '</label>';
        echo '<p>';
        echo '<input type="checkbox" name="accessories[]" data-price="55" value="long_cable"> ' . esc_html__('Long Cable', 'woo-extra-price') . '<span class="item-price">+<span id="accessories-1">55</span>' . $this->get_currency_symbol() . '</span><br>';
        echo '<input type="checkbox" name="accessories[]" data-price="96" value="type_c_cable"> ' . esc_html__('Type C Cable', 'woo-extra-price') . '<span class="item-price">+<span id="accessories-2">96</span>' . $this->get_currency_symbol() . '</span>';
        echo '</p>';
        $product_id = get_the_ID(); // Get the product ID
        $product = wc_get_product($product_id); // Get the product object
        $price = $product->get_price(); // Get the product price
        $formatted_price = wc_price($price); // Get the formatted price with currency

        echo '<p class="wep-total-price">';
        echo esc_html__('Total Price:', 'woo-extra-price') . ' ';
        echo '<span class="price-amount" id="wep-total-price-amount">' . wc_get_price_to_display($product) . '</span>'; // Price without currency
        echo '<span class="price-currency">' . get_woocommerce_currency_symbol() . '</span>'; // Currency symbol
        echo '</p>';



        echo '</div>';
    }


    /**
     * Get the currency symbol from WooCommerce
     */
    private function get_currency_symbol()
    {
        return get_woocommerce_currency_symbol();
    }


    /**
     * Throw error if custom input fields are empty
     */
    public function wep_product_add_on_validation($passed, $product_id, $qty)
    {
        $extra_items = isset($_POST['extra_items']) ? $_POST['extra_items'] : array();
        $accessories = isset($_POST['accessories']) ? $_POST['accessories'] : array();

        if (empty($extra_items) || empty($accessories)) {
            wc_add_notice(esc_html__('Please select at least one item from both Extra Items and Accessories.', 'woo-extra-price'), 'error');
            $passed = false;
        }

        return $passed;
    }


    /**
     * Save custom input field values into cart item data
     */
    public function wep_product_add_on_cart_item_data($cart_item, $product_id)
    {
        $extra_items = isset($_POST['extra_items']) ? $_POST['extra_items'] : array();
        $accessories = isset($_POST['accessories']) ? $_POST['accessories'] : array();

        $cart_item['extra_items'] = $extra_items;
        $cart_item['accessories'] = $accessories;

        return $cart_item;
    }

    /**
     * Add fee into cart item data based on selection
     */
    public function wep_add_checkout_fee($cart)
    {
        $extra_item_fee = 0;
        $accessory_fee = 0;

        if (!empty($cart->cart_contents)) {
            foreach ($cart->cart_contents as $cart_item) {
                if (isset($cart_item['extra_items'])) {
                    foreach ($cart_item['extra_items'] as $extra_item) {
                        if ($extra_item === 'extra_item_1') {
                            $extra_item_fee += 2;
                        } elseif ($extra_item === 'extra_item_2') {
                            $extra_item_fee += 4;
                        } elseif ($extra_item === 'extra_item_3') {
                            $extra_item_fee += 5;
                        }
                    }
                }

                if (isset($cart_item['accessories'])) {
                    foreach ($cart_item['accessories'] as $accessory) {
                        if ($accessory === 'long_cable') {
                            $accessory_fee += 55;
                        } elseif ($accessory === 'type_c_cable') {
                            $accessory_fee += 96;
                        }
                    }
                }
            }
        }

        if ($extra_item_fee > 0) {
            $cart->add_fee('Extra Items Fee', $extra_item_fee);
        }

        if ($accessory_fee > 0) {
            $cart->add_fee('Accessories Fee', $accessory_fee);
        }
    }

    /**
     * Display selected items' titles @ Cart
     */
    public function wep_product_add_on_display_cart($data, $cart_item)
    {
        if (isset($cart_item['extra_items'])) {
            $extra_items_titles = array(
                'extra_item_1' => 'Extra Item 1',
                'extra_item_2' => 'Extra Item 2',
                'extra_item_3' => 'Extra Item 3',
            );

            $extra_items_display = array_map(function ($item) use ($extra_items_titles) {
                return isset($extra_items_titles[$item]) ? $extra_items_titles[$item] : $item;
            }, $cart_item['extra_items']);

            $data[] = array(
                'name' => 'Extra Items',
                'value' => implode(', ', $extra_items_display),
            );
        }

        if (isset($cart_item['accessories'])) {
            $accessories_titles = array(
                'long_cable' => 'Long Cable',
                'type_c_cable' => 'Type C Cable',
            );

            $accessories_display = array_map(function ($item) use ($accessories_titles) {
                return isset($accessories_titles[$item]) ? $accessories_titles[$item] : $item;
            }, $cart_item['accessories']);

            $data[] = array(
                'name' => 'Accessories',
                'value' => implode(', ', $accessories_display),
            );
        }

        return $data;
    }


    /**
     * Save selected items' values into order item meta
     */
    public function wep_product_add_on_order_item_meta($item_id, $values)
    {
        if (!empty($values['extra_items'])) {
            wc_add_order_item_meta($item_id, esc_html__('Extra Items', 'woo-extra-price'), implode(', ', $values['extra_items']), true);
        }

        if (!empty($values['accessories'])) {
            wc_add_order_item_meta($item_id, esc_html__('Accessories', 'woo-extra-price'), implode(', ', $values['accessories']), true);
        }
    }


    /**
     * Display selected items' values into order table
     */
    public function wep_product_add_on_display_order($cart_item, $order_item)
    {
        if (isset($order_item['extra_items'])) {
            $cart_item['extra_items'] = $order_item['extra_items'];
        }

        if (isset($order_item['accessories'])) {
            $cart_item['accessories'] = $order_item['accessories'];
        }

        return $cart_item;
    }

    /**
     * Display selected items' values into order emails
     */
    public function wep_product_add_on_display_emails($fields)
    {
        $fields['extra_items'] = 'Extra Items';
        $fields['accessories'] = 'Accessories';

        return $fields;
    }

}

