<?php
/**
 * Core plugin file
 *
 * @since      1.0
 * @package    woo-extra-price
 * @author     Hamid Azad
 */
namespace Woo_Extra_Price;

/**
 * Assets handler class
 */
class Assets
{

    /**
     * Class constructor
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'wep_register_assets'));
    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts()
    {
        return array(
            'wep-script' => array(
                'src' => WOO_EXTRA_PRICE_ASSETS . '/js/wep.js',
                'version' => filemtime(WOO_EXTRA_PRICE_PATH . '/assets/js/wep.js'),
                'deps' => array('jquery'),
            ),
        );
    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles()
    {
        return array(
            'wep-style' => array(
                'src' => WOO_EXTRA_PRICE_ASSETS . '/css/wep.css',
                'version' => filemtime(WOO_EXTRA_PRICE_PATH . '/assets/css/wep.css'),
            ),
        );
    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function wep_register_assets()
    {
        $scripts = $this->get_scripts();
        $styles = $this->get_styles();

        foreach ($scripts as $handle => $script) {
            $deps = isset($script['deps']) ? $script['deps'] : false;
            wp_register_script($handle, $script['src'], $deps, $script['version'], true);
            wp_enqueue_script($handle);
        }

        foreach ($styles as $handle => $style) {
            $deps = isset($style['deps']) ? $style['deps'] : false;
            wp_register_style($handle, $style['src'], $deps, $script['version']);
            wp_enqueue_style($handle);
        }
    }
}
