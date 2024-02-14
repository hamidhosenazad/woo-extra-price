<?php

/**
 * Core plugin file
 *
 * @since      1.0
 * @package    woo-extra-price
 * @author     Hamid Azad
 */

/*
 * If this file is called directly, abort.
 */
if (!defined('ABSPATH')) {
    exit;
}

/*
 * Register the autoloader function for dynamically loading classes.
 */
spl_autoload_register('autoload');

/**
 * Autoloader for the plugin classes
 *
 * @param string $class The class to load
 */
function autoload($class)
{
    $prefix = 'Woo_Extra_Price\\';
    $base_dir = __DIR__ . '/includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
}

/**
 * The main plugin class
 */
final class Woo_Extra_Price
{

    /**
     * Plugin version
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * Class construcotr
     */
    private function __construct()
    {
        $this->wep_define_constants();
        $this->wep_installer();
        $this->wep_init_plugin();
    }

    /**
     * Initializes a singleton instance
     *
     * @return \Woo_Extra_Price
     */
    public static function wep_init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function wep_define_constants()
    {
        define('WOO_EXTRA_PRICE_VERSION', self::VERSION);
        define('WOO_EXTRA_PRICE_FILE', __FILE__);
        define('WOO_EXTRA_PRICE_PATH', __DIR__);
        define('WOO_EXTRA_PRICE_URL', plugins_url('', WOO_EXTRA_PRICE_FILE));
        define('WOO_EXTRA_PRICE_ASSETS', WOO_EXTRA_PRICE_URL . '/assets');
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function wep_init_plugin()
    {

        new \Woo_Extra_Price\Frontend();
        new \Woo_Extra_Price\Assets();

    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function wep_installer()
    {
        $installed = get_option('wep_installed');
        if (!$installed) {
            update_option('wep_installed', time());
        }
        update_option('wep_version', WOO_EXTRA_PRICE_VERSION);
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Woo_Extra_Price
 */
function woo_extra_price()
{
    return Woo_Extra_Price::wep_init();
}

// Kick-off the plugin.
woo_extra_price();
