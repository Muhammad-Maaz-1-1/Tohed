<?php

/**
 * Theme functions and definitions.
 *
 * Sets up the theme and provides some helper functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Core Constants.
define('OCEANWP_THEME_DIR', get_template_directory());
define('OCEANWP_THEME_URI', get_template_directory_uri());

/**
 * OceanWP theme class
 */
final class OCEANWP_Theme_Class
{

    /**
     * Main Theme Class Constructor
     *
     * @since   1.0.0
     */
    public function __construct()
    {
        // Migrate
        $this->migration();

        // Define theme constants.
        $this->oceanwp_constants();

        // Load required files.
        $this->oceanwp_has_setup();

        // Load framework classes.
        add_action('after_setup_theme', array('OCEANWP_Theme_Class', 'classes'), 4);

        // Setup theme => add_theme_support, register_nav_menus, load_theme_textdomain, etc.
        add_action('after_setup_theme', array('OCEANWP_Theme_Class', 'theme_setup'), 10);

        // register sidebar widget areas.
        add_action('widgets_init', array('OCEANWP_Theme_Class', 'register_sidebars'));

        // Registers theme_mod strings into Polylang.
        if (class_exists('Polylang')) {
            add_action('after_setup_theme', array('OCEANWP_Theme_Class', 'polylang_register_string'));
        }

        /** Admin only actions */
        if (is_admin()) {

            // Load scripts in the WP admin.
            add_action('admin_enqueue_scripts', array('OCEANWP_Theme_Class', 'admin_scripts'));

            // Outputs custom CSS for the admin.
            add_action('admin_head', array('OCEANWP_Theme_Class', 'admin_inline_css'));

            /** Non Admin actions */
        } else {
            // Load theme js.
            add_action('wp_enqueue_scripts', array('OCEANWP_Theme_Class', 'theme_js'));

            // Load theme CSS.
            add_action('wp_enqueue_scripts', array('OCEANWP_Theme_Class', 'theme_css'));

            // Load his file in last.
            add_action('wp_enqueue_scripts', array('OCEANWP_Theme_Class', 'custom_style_css'), 9999);

            // Remove Customizer CSS script from Front-end.
            add_action('init', array('OCEANWP_Theme_Class', 'remove_customizer_custom_css'));

            // Add a pingback url auto-discovery header for singularly identifiable articles.
            add_action('wp_head', array('OCEANWP_Theme_Class', 'pingback_header'), 1);

            // Add meta viewport tag to header.
            add_action('wp_head', array('OCEANWP_Theme_Class', 'meta_viewport'), 1);

            // Add an X-UA-Compatible header.
            add_filter('wp_headers', array('OCEANWP_Theme_Class', 'x_ua_compatible_headers'));

            // Outputs custom CSS to the head.
            add_action('wp_head', array('OCEANWP_Theme_Class', 'custom_css'), 9999);

            // Minify the WP custom CSS because WordPress doesn't do it by default.
            add_filter('wp_get_custom_css', array('OCEANWP_Theme_Class', 'minify_custom_css'));

            // Alter the search posts per page.
            add_action('pre_get_posts', array('OCEANWP_Theme_Class', 'search_posts_per_page'));

            // Alter WP categories widget to display count inside a span.
            add_filter('wp_list_categories', array('OCEANWP_Theme_Class', 'wp_list_categories_args'));

            // Add a responsive wrapper to the WordPress oembed output.
            add_filter('embed_oembed_html', array('OCEANWP_Theme_Class', 'add_responsive_wrap_to_oembeds'), 99, 4);

            // Adds classes the post class.
            add_filter('post_class', array('OCEANWP_Theme_Class', 'post_class'));

            // Add schema markup to the authors post link.
            add_filter('the_author_posts_link', array('OCEANWP_Theme_Class', 'the_author_posts_link'));

            // Add support for Elementor Pro locations.
            add_action('elementor/theme/register_locations', array('OCEANWP_Theme_Class', 'register_elementor_locations'));

            // Remove the default lightbox script for the beaver builder plugin.
            add_filter('fl_builder_override_lightbox', array('OCEANWP_Theme_Class', 'remove_bb_lightbox'));

            add_filter('ocean_enqueue_generated_files', '__return_false');
        }
    }

    /**
     * Migration Functinality
     *
     * @since   1.0.0
     */
    public static function migration()
    {
        if (get_theme_mod('ocean_disable_emoji', false)) {
            set_theme_mod('ocean_performance_emoji', 'disabled');
        }

        if (get_theme_mod('ocean_disable_lightbox', false)) {
            set_theme_mod('ocean_performance_lightbox', 'disabled');
        }
    }

    /**
     * Define Constants
     *
     * @since   1.0.0
     */
    public static function oceanwp_constants()
    {

        $version = self::theme_version();

        // Theme version.
        define('OCEANWP_THEME_VERSION', $version);

        // Javascript and CSS Paths.
        define('OCEANWP_JS_DIR_URI', OCEANWP_THEME_URI . '/assets/js/');
        define('OCEANWP_CSS_DIR_URI', OCEANWP_THEME_URI . '/assets/css/');

        // Include Paths.
        define('OCEANWP_INC_DIR', OCEANWP_THEME_DIR . '/inc/');
        define('OCEANWP_INC_DIR_URI', OCEANWP_THEME_URI . '/inc/');

        // Check if plugins are active.
        define('OCEAN_EXTRA_ACTIVE', class_exists('Ocean_Extra'));
        define('OCEANWP_ELEMENTOR_ACTIVE', class_exists('Elementor\Plugin'));
        define('OCEANWP_BEAVER_BUILDER_ACTIVE', class_exists('FLBuilder'));
        define('OCEANWP_WOOCOMMERCE_ACTIVE', class_exists('WooCommerce'));
        define('OCEANWP_EDD_ACTIVE', class_exists('Easy_Digital_Downloads'));
        define('OCEANWP_LIFTERLMS_ACTIVE', class_exists('LifterLMS'));
        define('OCEANWP_ALNP_ACTIVE', class_exists('Auto_Load_Next_Post'));
        define('OCEANWP_LEARNDASH_ACTIVE', class_exists('SFWD_LMS'));
    }

    /**
     * Load all core theme function files
     *
     * @since 1.0.0oceanwp_has_setup
     */
    public static function oceanwp_has_setup()
    {

        $dir = OCEANWP_INC_DIR;

        require_once $dir . 'helpers.php';
        require_once $dir . 'header-content.php';
        require_once $dir . 'oceanwp-strings.php';
        require_once $dir . 'oceanwp-svg.php';
        require_once $dir . 'oceanwp-theme-icons.php';
        require_once $dir . 'template-helpers.php';
        require_once $dir . 'customizer/controls/typography/webfonts.php';
        require_once $dir . 'walker/init.php';
        require_once $dir . 'walker/menu-walker.php';
        require_once $dir . 'third/class-gutenberg.php';
        require_once $dir . 'third/class-elementor.php';
        require_once $dir . 'third/class-beaver-themer.php';
        require_once $dir . 'third/class-bbpress.php';
        require_once $dir . 'third/class-buddypress.php';
        require_once $dir . 'third/class-lifterlms.php';
        require_once $dir . 'third/class-learndash.php';
        require_once $dir . 'third/class-sensei.php';
        require_once $dir . 'third/class-social-login.php';
        require_once $dir . 'third/class-amp.php';
        require_once $dir . 'third/class-pwa.php';

        // WooCommerce.
        if (OCEANWP_WOOCOMMERCE_ACTIVE) {
            require_once $dir . 'woocommerce/woocommerce-config.php';
        }

        // Easy Digital Downloads.
        if (OCEANWP_EDD_ACTIVE) {
            require_once $dir . 'edd/edd-config.php';
        }
    }

    /**
     * Returns current theme version
     *
     * @since   1.0.0
     */
    public static function theme_version()
    {

        // Get theme data.
        $theme = wp_get_theme();

        // Return theme version.
        return $theme->get('Version');
    }

    /**
     * Compare WordPress version
     *
     * @access public
     * @since 1.8.3
     * @param  string $version - A WordPress version to compare against current version.
     * @return boolean
     */
    public static function is_wp_version($version = '5.4')
    {

        global $wp_version;

        // WordPress version.
        return version_compare(strtolower($wp_version), strtolower($version), '>=');
    }


    /**
     * Check for AMP endpoint
     *
     * @return bool
     * @since 1.8.7
     */
    public static function oceanwp_is_amp()
    {
        return function_exists('is_amp_endpoint') && is_amp_endpoint();
    }

    /**
     * Load theme classes
     *
     * @since   1.0.0
     */
    public static function classes()
    {

        // Admin only classes.
        if (is_admin()) {

            // Recommend plugins.
            require_once OCEANWP_INC_DIR . 'activation-notice/class-oceanwp-plugin-manager.php';
            require_once OCEANWP_INC_DIR . 'activation-notice/template.php';

            // Ajax Actions
            if (defined('DOING_AJAX') && DOING_AJAX) {
                require OCEANWP_INC_DIR . 'activation-notice/api.php';
            }

            // Front-end classes.
        }

        // Breadcrumbs class.
        require_once OCEANWP_INC_DIR . 'breadcrumbs.php';

        // Customizer class.
        require_once OCEANWP_INC_DIR . 'customizer/library/customizer-custom-controls/functions.php';
        require_once OCEANWP_INC_DIR . 'customizer/customizer.php';
    }

    /**
     * Theme Setup
     *
     * @since   1.0.0
     */
    public static function theme_setup()
    {

        // Load text domain.
        load_theme_textdomain('oceanwp', OCEANWP_THEME_DIR . '/languages');

        // Get globals.
        global $content_width;

        // Set content width based on theme's default design.
        if (!isset($content_width)) {
            $content_width = 1200;
        }

        // Register navigation menus.
        register_nav_menus(
            array(
                'topbar_menu' => esc_html__('Top Bar', 'oceanwp'),
                'main_menu'   => esc_html__('Main', 'oceanwp'),
                'footer_menu' => esc_html__('Footer', 'oceanwp'),
                'mobile_menu' => esc_html__('Mobile (optional)', 'oceanwp'),
            )
        );

        // Enable support for Post Formats.
        add_theme_support('post-formats', array('video', 'gallery', 'audio', 'quote', 'link'));

        // Enable support for <title> tag.
        add_theme_support('title-tag');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support('post-thumbnails');

        /**
         * Enable support for header image
         */
        add_theme_support(
            'custom-header',
            apply_filters(
                'ocean_custom_header_args',
                array(
                    'width'       => 2000,
                    'height'      => 1200,
                    'flex-height' => true,
                    'video'       => true,
                )
            )
        );

        /**
         * Enable support for site logo
         */
        add_theme_support(
            'custom-logo',
            apply_filters(
                'ocean_custom_logo_args',
                array(
                    'height'      => 45,
                    'width'       => 164,
                    'flex-height' => true,
                    'flex-width'  => true,
                )
            )
        );

        /*
		 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
		 * to output valid HTML5.
		 */
        add_theme_support(
            'html5',
            array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
                'widgets',
            )
        );

        // Declare WooCommerce support.
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // Add editor style.
        add_editor_style('assets/css/editor-style.min.css');

        // Declare support for selective refreshing of widgets.
        add_theme_support('customize-selective-refresh-widgets');
    }

    /**
     * Adds the meta tag to the site header
     *
     * @since 1.1.0
     */
    public static function pingback_header()
    {

        if (is_singular() && pings_open()) {
            printf('<link rel="pingback" href="%s">' . "\n", esc_url(get_bloginfo('pingback_url')));
        }
    }

    /**
     * Adds the meta tag to the site header
     *
     * @since 1.0.0
     */
    public static function meta_viewport()
    {

        // Meta viewport.
        $viewport = '<meta name="viewport" content="width=device-width, initial-scale=1">';

        // Apply filters for child theme tweaking.
        echo apply_filters('ocean_meta_viewport', $viewport); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

    /**
     * Load scripts in the WP admin
     *
     * @since 1.0.0
     */
    public static function admin_scripts()
    {
        global $pagenow;
        if ('nav-menus.php' === $pagenow) {
            wp_enqueue_style('oceanwp-menus', OCEANWP_INC_DIR_URI . 'walker/assets/menus.css', false, OCEANWP_THEME_VERSION);
        }
    }

    /**
     * Load front-end scripts
     *
     * @since   1.0.0
     */
    public static function theme_css()
    {

        // Define dir.
        $dir           = OCEANWP_CSS_DIR_URI;
        $theme_version = OCEANWP_THEME_VERSION;

        // Remove font awesome style from plugins.
        wp_deregister_style('font-awesome');
        wp_deregister_style('fontawesome');

        // Enqueue font awesome style.
        if (get_theme_mod('ocean_performance_fontawesome', 'enabled') === 'enabled') {
            wp_enqueue_style('font-awesome', OCEANWP_THEME_URI . '/assets/fonts/fontawesome/css/all.min.css', false, '6.4.2');
        }

        // Enqueue simple line icons style.
        if (get_theme_mod('ocean_performance_simple_line_icons', 'enabled') === 'enabled') {
            wp_enqueue_style('simple-line-icons', $dir . 'third/simple-line-icons.min.css', false, '2.4.0');
        }

        // Enqueue Main style.
        wp_enqueue_style('oceanwp-style', $dir . 'style.min.css', false, $theme_version);

        // Blog Header styles.
        if (
            'default' !== get_theme_mod('oceanwp_single_post_header_style', 'default')
            && is_single() && 'post' === get_post_type()
        ) {
            wp_enqueue_style('oceanwp-blog-headers', $dir . 'blog/blog-post-headers.css', false, $theme_version);
        }

        // Register perfect-scrollbar plugin style.
        wp_register_style('ow-perfect-scrollbar', $dir . 'third/perfect-scrollbar.css', false, '1.5.0');

        // Register hamburgers buttons to easily use them.
        wp_register_style('oceanwp-hamburgers', $dir . 'third/hamburgers/hamburgers.min.css', false, $theme_version);
        // Register hamburgers buttons styles.
        $hamburgers = oceanwp_hamburgers_styles();
        foreach ($hamburgers as $class => $name) {
            wp_register_style('oceanwp-' . $class . '', $dir . 'third/hamburgers/types/' . $class . '.css', false, $theme_version);
        }

        // Get mobile menu icon style.
        $mobile_menu = get_theme_mod('ocean_mobile_menu_open_hamburger', 'default');
        // Enqueue mobile menu icon style.
        if (!empty($mobile_menu) && 'default' !== $mobile_menu) {
            wp_enqueue_style('oceanwp-hamburgers');
            wp_enqueue_style('oceanwp-' . $mobile_menu . '');
        }

        // If Vertical header style.
        if ('vertical' === oceanwp_header_style()) {
            wp_enqueue_style('oceanwp-hamburgers');
            wp_enqueue_style('oceanwp-spin');
            wp_enqueue_style('ow-perfect-scrollbar');
        }
    }

    /**
     * Returns all js needed for the front-end
     *
     * @since 1.0.0
     */
    public static function theme_js()
    {

        if (self::oceanwp_is_amp()) {
            return;
        }

        // Get js directory uri.
        $dir = OCEANWP_JS_DIR_URI;

        // Get current theme version.
        $theme_version = OCEANWP_THEME_VERSION;

        // Get localized array.
        $localize_array = self::localize_array();

        // Main script dependencies.
        $main_script_dependencies = array('jquery');

        // Comment reply.
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        // Add images loaded.
        wp_enqueue_script('imagesloaded');

        /**
         * Load Venors Scripts.
         */

        // Isotop.
        wp_register_script('ow-isotop', $dir . 'vendors/isotope.pkgd.min.js', array(), '3.0.6', true);

        // Flickity.
        wp_register_script('ow-flickity', $dir . 'vendors/flickity.pkgd.min.js', array(), $theme_version, true);

        // Magnific Popup.
        wp_register_script('ow-magnific-popup', $dir . 'vendors/magnific-popup.min.js', array('jquery'), $theme_version, true);

        // Sidr Mobile Menu.
        wp_register_script('ow-sidr', $dir . 'vendors/sidr.js', array(), $theme_version, true);

        // Perfect Scrollbar.
        wp_register_script('ow-perfect-scrollbar', $dir . 'vendors/perfect-scrollbar.min.js', array(), $theme_version, true);

        // Smooth Scroll.
        wp_register_script('ow-smoothscroll', $dir . 'vendors/smoothscroll.min.js', array(), $theme_version, false);

        /**
         * Load Theme Scripts.
         */

        // Theme script.
        wp_enqueue_script('oceanwp-main', $dir . 'theme.min.js', $main_script_dependencies, $theme_version, true);
        wp_localize_script('oceanwp-main', 'oceanwpLocalize', $localize_array);
        array_push($main_script_dependencies, 'oceanwp-main');

        // Blog Masonry script.
        if ('masonry' === oceanwp_blog_grid_style()) {
            array_push($main_script_dependencies, 'ow-isotop');
            wp_enqueue_script('ow-isotop');
            wp_enqueue_script('oceanwp-blog-masonry', $dir . 'blog-masonry.min.js', $main_script_dependencies, $theme_version, true);
        }

        // Menu script.
        switch (oceanwp_header_style()) {
            case 'full_screen':
                wp_enqueue_script('oceanwp-full-screen-menu', $dir . 'full-screen-menu.min.js', $main_script_dependencies, $theme_version, true);
                break;
            case 'vertical':
                array_push($main_script_dependencies, 'ow-perfect-scrollbar');
                wp_enqueue_script('ow-perfect-scrollbar');
                wp_enqueue_script('oceanwp-vertical-header', $dir . 'vertical-header.min.js', $main_script_dependencies, $theme_version, true);
                break;
        }

        // Mobile Menu script.
        switch (oceanwp_mobile_menu_style()) {
            case 'dropdown':
                wp_enqueue_script('oceanwp-drop-down-mobile-menu', $dir . 'drop-down-mobile-menu.min.js', $main_script_dependencies, $theme_version, true);
                break;
            case 'fullscreen':
                wp_enqueue_script('oceanwp-full-screen-mobile-menu', $dir . 'full-screen-mobile-menu.min.js', $main_script_dependencies, $theme_version, true);
                break;
            case 'sidebar':
                array_push($main_script_dependencies, 'ow-sidr');
                wp_enqueue_script('ow-sidr');
                wp_enqueue_script('oceanwp-sidebar-mobile-menu', $dir . 'sidebar-mobile-menu.min.js', $main_script_dependencies, $theme_version, true);
                break;
        }

        // Search script.
        switch (oceanwp_menu_search_style()) {
            case 'drop_down':
                wp_enqueue_script('oceanwp-drop-down-search', $dir . 'drop-down-search.min.js', $main_script_dependencies, $theme_version, true);
                break;
            case 'header_replace':
                wp_enqueue_script('oceanwp-header-replace-search', $dir . 'header-replace-search.min.js', $main_script_dependencies, $theme_version, true);
                break;
            case 'overlay':
                wp_enqueue_script('oceanwp-overlay-search', $dir . 'overlay-search.min.js', $main_script_dependencies, $theme_version, true);
                break;
        }

        // Mobile Search Icon Style.
        if (oceanwp_mobile_menu_search_style() !== 'disabled') {
            wp_enqueue_script('oceanwp-mobile-search-icon', $dir . 'mobile-search-icon.min.js', $main_script_dependencies, $theme_version, true);
        }

        // Equal Height Elements script.
        if (oceanwp_blog_entry_equal_heights()) {
            wp_enqueue_script('oceanwp-equal-height-elements', $dir . 'equal-height-elements.min.js', $main_script_dependencies, $theme_version, true);
        }

        // Lightbox script.
        if (oceanwp_gallery_is_lightbox_enabled() || get_theme_mod('ocean_performance_lightbox', 'enabled') === 'enabled') {
            array_push($main_script_dependencies, 'ow-magnific-popup');
            wp_enqueue_script('ow-magnific-popup');
            wp_enqueue_script('oceanwp-lightbox', $dir . 'ow-lightbox.min.js', $main_script_dependencies, $theme_version, true);
        }

        // Slider script.
        array_push($main_script_dependencies, 'ow-flickity');
        wp_enqueue_script('ow-flickity');
        wp_enqueue_script('oceanwp-slider', $dir . 'ow-slider.min.js', $main_script_dependencies, $theme_version, true);

        // Scroll Effect script.
        if (get_theme_mod('ocean_performance_scroll_effect', 'enabled') === 'enabled') {
            wp_enqueue_script('oceanwp-scroll-effect', $dir . 'scroll-effect.min.js', $main_script_dependencies, $theme_version, true);
        }

        // Scroll to Top script.
        if (oceanwp_display_scroll_up_button()) {
            wp_enqueue_script('oceanwp-scroll-top', $dir . 'scroll-top.min.js', $main_script_dependencies, $theme_version, true);
        }

        // Custom Select script.
        if (get_theme_mod('ocean_performance_custom_select', 'enabled') === 'enabled') {
            wp_enqueue_script('oceanwp-select', $dir . 'select.min.js', $main_script_dependencies, $theme_version, true);
        }

        // Infinite Scroll script.
        if ('infinite_scroll' === get_theme_mod('ocean_blog_pagination_style', 'standard') || 'infinite_scroll' === get_theme_mod('ocean_woo_pagination_style', 'standard')) {
            wp_enqueue_script('oceanwp-infinite-scroll', $dir . 'ow-infinite-scroll.min.js', $main_script_dependencies, $theme_version, true);
        }

        // WooCommerce scripts.
        if (
            OCEANWP_WOOCOMMERCE_ACTIVE
            && 'yes' !== get_theme_mod('ocean_woo_remove_custom_features', 'no')
        ) {
            wp_enqueue_script('oceanwp-woocommerce-custom-features', $dir . 'wp-plugins/woocommerce/woo-custom-features.min.js', array('jquery'), $theme_version, true);
            wp_localize_script('oceanwp-woocommerce-custom-features', 'oceanwpLocalize', $localize_array);
        }

        // Register scripts for old addons.
        wp_register_script('nicescroll', $dir . 'vendors/support-old-oceanwp-addons/jquery.nicescroll.min.js', array('jquery'), $theme_version, true);
    }

    /**
     * Functions.js localize array
     *
     * @since 1.0.0
     */
    public static function localize_array()
    {

        // Create array.
        $sidr_side     = get_theme_mod('ocean_mobile_menu_sidr_direction', 'left');
        $sidr_side     = $sidr_side ? $sidr_side : 'left';
        $sidr_target   = get_theme_mod('ocean_mobile_menu_sidr_dropdown_target', 'link');
        $sidr_target   = $sidr_target ? $sidr_target : 'link';
        $vh_target     = get_theme_mod('ocean_vertical_header_dropdown_target', 'link');
        $vh_target     = $vh_target ? $vh_target : 'link';
        $scroll_offset = get_theme_mod('ocean_scroll_effect_offset_value');
        $scroll_offset = $scroll_offset ? $scroll_offset : 0;
        $array       = array(
            'nonce'                 => wp_create_nonce('oceanwp'),
            'isRTL'                 => is_rtl(),
            'menuSearchStyle'       => oceanwp_menu_search_style(),
            'mobileMenuSearchStyle' => oceanwp_mobile_menu_search_style(),
            'sidrSource'            => oceanwp_sidr_menu_source(),
            'sidrDisplace'          => get_theme_mod('ocean_mobile_menu_sidr_displace', true) ? true : false,
            'sidrSide'              => $sidr_side,
            'sidrDropdownTarget'    => $sidr_target,
            'verticalHeaderTarget'  => $vh_target,
            'customScrollOffset'    => $scroll_offset,
            'customSelects'         => '.woocommerce-ordering .orderby, #dropdown_product_cat, .widget_categories select, .widget_archive select, .single-product .variations_form .variations select',
        );

        // WooCart.
        if (OCEANWP_WOOCOMMERCE_ACTIVE) {
            $array['wooCartStyle'] = oceanwp_menu_cart_style();
        }

        // Apply filters and return array.
        return apply_filters('ocean_localize_array', $array);
    }

    /**
     * Add headers for IE to override IE's Compatibility View Settings
     *
     * @param obj $headers   header settings.
     * @since 1.0.0
     */
    public static function x_ua_compatible_headers($headers)
    {
        $headers['X-UA-Compatible'] = 'IE=edge';
        return $headers;
    }

    /**
     * Registers sidebars
     *
     * @since   1.0.0
     */
    public static function register_sidebars()
    {

        $heading = get_theme_mod('ocean_sidebar_widget_heading_tag', 'h4');
        $heading = apply_filters('ocean_sidebar_widget_heading_tag', $heading);

        $foo_heading = get_theme_mod('ocean_footer_widget_heading_tag', 'h4');
        $foo_heading = apply_filters('ocean_footer_widget_heading_tag', $foo_heading);

        // Default Sidebar.
        register_sidebar(
            array(
                'name'          => esc_html__('Default Sidebar', 'oceanwp'),
                'id'            => 'sidebar',
                'description'   => esc_html__('Widgets in this area will be displayed in the left or right sidebar area if you choose the Left or Right Sidebar layout.', 'oceanwp'),
                'before_widget' => '<div id="%1$s" class="sidebar-box %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<' . $heading . ' class="widget-title">',
                'after_title'   => '</' . $heading . '>',
            )
        );

        // Left Sidebar.
        register_sidebar(
            array(
                'name'          => esc_html__('Left Sidebar', 'oceanwp'),
                'id'            => 'sidebar-2',
                'description'   => esc_html__('Widgets in this area are used in the left sidebar region if you use the Both Sidebars layout.', 'oceanwp'),
                'before_widget' => '<div id="%1$s" class="sidebar-box %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<' . $heading . ' class="widget-title">',
                'after_title'   => '</' . $heading . '>',
            )
        );

        // Search Results Sidebar.
        if (get_theme_mod('ocean_search_custom_sidebar', true)) {
            register_sidebar(
                array(
                    'name'          => esc_html__('Search Results Sidebar', 'oceanwp'),
                    'id'            => 'search_sidebar',
                    'description'   => esc_html__('Widgets in this area are used in the search result page.', 'oceanwp'),
                    'before_widget' => '<div id="%1$s" class="sidebar-box %2$s clr">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<' . $heading . ' class="widget-title">',
                    'after_title'   => '</' . $heading . '>',
                )
            );
        }

        // Footer 1.
        register_sidebar(
            array(
                'name'          => esc_html__('Footer 1', 'oceanwp'),
                'id'            => 'footer-one',
                'description'   => esc_html__('Widgets in this area are used in the first footer region.', 'oceanwp'),
                'before_widget' => '<div id="%1$s" class="footer-widget %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<' . $foo_heading . ' class="widget-title">',
                'after_title'   => '</' . $foo_heading . '>',
            )
        );

        // Footer 2.
        register_sidebar(
            array(
                'name'          => esc_html__('Footer 2', 'oceanwp'),
                'id'            => 'footer-two',
                'description'   => esc_html__('Widgets in this area are used in the second footer region.', 'oceanwp'),
                'before_widget' => '<div id="%1$s" class="footer-widget %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<' . $foo_heading . ' class="widget-title">',
                'after_title'   => '</' . $foo_heading . '>',
            )
        );

        // Footer 3.
        register_sidebar(
            array(
                'name'          => esc_html__('Footer 3', 'oceanwp'),
                'id'            => 'footer-three',
                'description'   => esc_html__('Widgets in this area are used in the third footer region.', 'oceanwp'),
                'before_widget' => '<div id="%1$s" class="footer-widget %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<' . $foo_heading . ' class="widget-title">',
                'after_title'   => '</' . $foo_heading . '>',
            )
        );

        // Footer 4.
        register_sidebar(
            array(
                'name'          => esc_html__('Footer 4', 'oceanwp'),
                'id'            => 'footer-four',
                'description'   => esc_html__('Widgets in this area are used in the fourth footer region.', 'oceanwp'),
                'before_widget' => '<div id="%1$s" class="footer-widget %2$s clr">',
                'after_widget'  => '</div>',
                'before_title'  => '<' . $foo_heading . ' class="widget-title">',
                'after_title'   => '</' . $foo_heading . '>',
            )
        );
    }

    /**
     * Registers theme_mod strings into Polylang.
     *
     * @since 1.1.4
     */
    public static function polylang_register_string()
    {

        if (function_exists('pll_register_string') && $strings = oceanwp_register_tm_strings()) {
            foreach ($strings as $string => $default) {
                pll_register_string($string, get_theme_mod($string, $default), 'Theme Mod', true);
            }
        }
    }

    /**
     * All theme functions hook into the oceanwp_head_css filter for this function.
     *
     * @param obj $output output value.
     * @since 1.0.0
     */
    public static function custom_css($output = null)
    {

        // Add filter for adding custom css via other functions.
        $output = apply_filters('ocean_head_css', $output);

        // If Custom File is selected.
        if ('file' === get_theme_mod('ocean_customzer_styling', 'head')) {

            global $wp_customize;
            $upload_dir = wp_upload_dir();

            // Render CSS in the head.
            if (isset($wp_customize) || !file_exists($upload_dir['basedir'] . '/oceanwp/custom-style.css')) {

                // Minify and output CSS in the wp_head.
                if (!empty($output)) {
                    echo "<!-- OceanWP CSS -->\n<style type=\"text/css\">\n" . wp_strip_all_tags(oceanwp_minify_css($output)) . "\n</style>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
            }
        } else {

            // Minify and output CSS in the wp_head.
            if (!empty($output)) {
                echo "<!-- OceanWP CSS -->\n<style type=\"text/css\">\n" . wp_strip_all_tags(oceanwp_minify_css($output)) . "\n</style>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }
    }

    /**
     * Minify the WP custom CSS because WordPress doesn't do it by default.
     *
     * @param obj $css minify css.
     * @since 1.1.9
     */
    public static function minify_custom_css($css)
    {

        return oceanwp_minify_css($css);
    }

    /**
     * Include Custom CSS file if present.
     *
     * @param obj $output output value.
     * @since 1.4.12
     */
    public static function custom_style_css($output = null)
    {

        // If Custom File is not selected.
        if ('file' !== get_theme_mod('ocean_customzer_styling', 'head')) {
            return;
        }

        global $wp_customize;
        $upload_dir = wp_upload_dir();

        // Get all the customier css.
        $output = apply_filters('ocean_head_css', $output);

        // Get Custom Panel CSS.
        $output_custom_css = wp_get_custom_css();

        // Minified the Custom CSS.
        $output .= oceanwp_minify_css($output_custom_css);

        // Render CSS from the custom file.
        if (!isset($wp_customize) && file_exists($upload_dir['basedir'] . '/oceanwp/custom-style.css') && !empty($output)) {
            wp_enqueue_style('oceanwp-custom', trailingslashit($upload_dir['baseurl']) . 'oceanwp/custom-style.css', false, false);
        }
    }

    /**
     * Remove Customizer style script from front-end
     *
     * @since 1.4.12
     */
    public static function remove_customizer_custom_css()
    {

        // If Custom File is not selected.
        if ('file' !== get_theme_mod('ocean_customzer_styling', 'head')) {
            return;
        }

        global $wp_customize;

        // Disable Custom CSS in the frontend head.
        remove_action('wp_head', 'wp_custom_css_cb', 11);
        remove_action('wp_head', 'wp_custom_css_cb', 101);

        // If custom CSS file exists and NOT in customizer screen.
        if (isset($wp_customize)) {
            add_action('wp_footer', 'wp_custom_css_cb', 9999);
        }
    }

    /**
     * Adds inline CSS for the admin
     *
     * @since 1.0.0
     */
    public static function admin_inline_css()
    {
        echo '<style>div#setting-error-tgmpa{display:block;}</style>';
    }

    /**
     * Alter the search posts per page
     *
     * @param obj $query query.
     * @since 1.3.7
     */
    public static function search_posts_per_page($query)
    {
        $posts_per_page = get_theme_mod('ocean_search_post_per_page', '8');
        $posts_per_page = $posts_per_page ? $posts_per_page : '8';

        if ($query->is_main_query() && is_search()) {
            $query->set('posts_per_page', $posts_per_page);
        }
    }

    /**
     * Alter wp list categories arguments.
     * Adds a span around the counter for easier styling.
     *
     * @param obj $links link.
     * @since 1.0.0
     */
    public static function wp_list_categories_args($links)
    {
        $links = str_replace('</a> (', '</a> <span class="cat-count-span">(', $links);
        $links = str_replace(')', ')</span>', $links);
        return $links;
    }

    /**
     * Alters the default oembed output.
     * Adds special classes for responsive oembeds via CSS.
     *
     * @param obj $cache     cache.
     * @param url $url       url.
     * @param obj $attr      attributes.
     * @param obj $post_ID   post id.
     * @since 1.0.0
     */
    public static function add_responsive_wrap_to_oembeds($cache, $url, $attr, $post_ID)
    {

        // Supported video embeds.
        $hosts = apply_filters(
            'ocean_oembed_responsive_hosts',
            array(
                'vimeo.com',
                'youtube.com',
                'youtu.be',
                'blip.tv',
                'money.cnn.com',
                'dailymotion.com',
                'flickr.com',
                'hulu.com',
                'kickstarter.com',
                'vine.co',
                'soundcloud.com',
                '#http://((m|www)\.)?youtube\.com/watch.*#i',
                '#https://((m|www)\.)?youtube\.com/watch.*#i',
                '#http://((m|www)\.)?youtube\.com/playlist.*#i',
                '#https://((m|www)\.)?youtube\.com/playlist.*#i',
                '#http://youtu\.be/.*#i',
                '#https://youtu\.be/.*#i',
                '#https?://(.+\.)?vimeo\.com/.*#i',
                '#https?://(www\.)?dailymotion\.com/.*#i',
                '#https?://dai\.ly/*#i',
                '#https?://(www\.)?hulu\.com/watch/.*#i',
                '#https?://wordpress\.tv/.*#i',
                '#https?://(www\.)?funnyordie\.com/videos/.*#i',
                '#https?://vine\.co/v/.*#i',
                '#https?://(www\.)?collegehumor\.com/video/.*#i',
                '#https?://(www\.|embed\.)?ted\.com/talks/.*#i',
            )
        );

        // Supports responsive.
        $supports_responsive = false;

        // Check if responsive wrap should be added.
        foreach ($hosts as $host) {
            if (strpos($url, $host) !== false) {
                $supports_responsive = true;
                break; // no need to loop further.
            }
        }

        // Output code.
        if ($supports_responsive) {
            return '<p class="responsive-video-wrap clr">' . $cache . '</p>';
        } else {
            return '<div class="oceanwp-oembed-wrap clr">' . $cache . '</div>';
        }
    }

    /**
     * Adds extra classes to the post_class() output
     *
     * @param obj $classes   Return classes.
     * @since 1.0.0
     */
    public static function post_class($classes)
    {

        // Get post.
        global $post;

        // Add entry class.
        $classes[] = 'entry';

        // Add has media class.
        if (
            has_post_thumbnail()
            || get_post_meta($post->ID, 'ocean_post_self_hosted_media', true)
            || get_post_meta($post->ID, 'ocean_post_oembed', true)
            || get_post_meta($post->ID, 'ocean_post_video_embed', true)
        ) {
            $classes[] = 'has-media';
        }

        // Return classes.
        return $classes;
    }

    /**
     * Add schema markup to the authors post link
     *
     * @param obj $link   Author link.
     * @since 1.0.0
     */
    public static function the_author_posts_link($link)
    {

        // Add schema markup.
        $schema = oceanwp_get_schema_markup('author_link');
        if ($schema) {
            $link = str_replace('rel="author"', 'rel="author" ' . $schema, $link);
        }

        // Return link.
        return $link;
    }

    /**
     * Add support for Elementor Pro locations
     *
     * @param obj $elementor_theme_manager    Elementor Instance.
     * @since 1.5.6
     */
    public static function register_elementor_locations($elementor_theme_manager)
    {
        $elementor_theme_manager->register_all_core_location();
    }

    /**
     * Add schema markup to the authors post link
     *
     * @since 1.1.5
     */
    public static function remove_bb_lightbox()
    {
        return true;
    }
}

/**--------------------------------------------------------------------------------
#region Freemius - This logic will only be executed when Ocean Extra is active and has the Freemius SDK
---------------------------------------------------------------------------------*/

if (!function_exists('owp_fs')) {
    if (
        class_exists('Ocean_Extra') &&
        defined('OE_FILE_PATH') &&
        file_exists(dirname(OE_FILE_PATH) . '/includes/freemius/start.php')
    ) {
        /**
         * Create a helper function for easy SDK access.
         */
        function owp_fs()
        {
            global $owp_fs;

            if (!isset($owp_fs)) {
                // Include Freemius SDK.
                require_once dirname(OE_FILE_PATH) . '/includes/freemius/start.php';

                $owp_fs = fs_dynamic_init(
                    array(
                        'id'                             => '3752',
                        'bundle_id'                      => '3767',
                        'slug'                           => 'oceanwp',
                        'type'                           => 'theme',
                        'public_key'                     => 'pk_043077b34f20f5e11334af3c12493',
                        'bundle_public_key'              => 'pk_c334eb1ae413deac41e30bf00b9dc',
                        'is_premium'                     => false,
                        'has_addons'                     => true,
                        'has_paid_plans'                 => true,
                        'menu'                           => array(
                            'slug'    => 'oceanwp',
                            'account' => true,
                            'contact' => false,
                            'support' => false,
                        ),
                        'bundle_license_auto_activation' => true,
                        'navigation'                     => 'menu',
                        'is_org_compliant'               => true,
                    )
                );
            }

            return $owp_fs;
        }

        // Init Freemius.
        owp_fs();
        // Signal that SDK was initiated.
        do_action('owp_fs_loaded');
    }
}

// endregion

new OCEANWP_Theme_Class();

// Disable theme update notifications
remove_action('load-update-core.php', 'wp_update_themes');
add_filter('pre_site_transient_update_themes', '__return_null');
// 
// Your PHP function in functions.php or custom plugin
function quran_shortcode()
{
    ob_start(); // Start output buffering
?>

    <select id="surahSelect"></select>
    <div id="ayahContainer"></div>

    <script>
        function fetchAyahs(surahNumber) {
            const ayahContainer = document.getElementById('ayahContainer');

            // Clear previous content
            ayahContainer.innerHTML = '';

            // Fetch data from the API for the specific surah
            fetch(`http://api.alquran.cloud/v1/surah/${surahNumber}`)
                .then(response => response.json())
                .then(data => {
                    const ayahs = data.data.ayahs;

                    ayahs.forEach((ayah, index) => {
                        const ayahElement = document.createElement('div');
                        ayahElement.innerHTML = `
                            <p id="${ayah.number}" style="cursor: pointer;">${ayah.text}</p>
                            <audio id="audioPlayer${ayah.number}" controls></audio>
                        `;

                        ayahElement.addEventListener('click', function() {
                            const clickedAyahId = this.querySelector('p').id;
                            const audioPlayer = document.getElementById(`audioPlayer${clickedAyahId}`);
                            const audioUrl = `https://cdn.islamic.network/quran/audio/128/ar.alafasy/${clickedAyahId}.mp3`;

                            audioPlayer.src = audioUrl;
                            audioPlayer.play();
                        });

                        ayahContainer.appendChild(ayahElement);
                    });
                })
                .catch(error => console.error('Error fetching ayahs:', error));
        }

        // Fetch data from the API for all surahs
        fetch('http://api.alquran.cloud/v1/surah')
            .then(response => response.json())
            .then(data => {
                const surahs = data.data;
                const surahSelect = document.getElementById('surahSelect');

                surahs.forEach(surah => {
                    const option = document.createElement('option');
                    option.value = surah.number;
                    option.text = surah.name;
                    surahSelect.appendChild(option);
                });

                surahSelect.addEventListener('change', function() {
                    const selectedSurahNumber = this.value;
                    fetchAyahs(selectedSurahNumber);
                });

                const firstSurahNumber = surahs[0].number;
                fetchAyahs(firstSurahNumber);
            })
            .catch(error => console.error('Error fetching surahs:', error));
    </script>

<?php
    return ob_get_clean(); // Return the buffered content
}

add_shortcode('quran_shortcode', 'quran_shortcode');


function prayer_times_shortcode()
{
    ob_start(); // Start output buffering

?>

<?php

    // Output buffering contents and clean buffer
    return ob_get_clean();
}

// Hook the shortcode registration function
add_shortcode('prayer_times', 'prayer_times_shortcode');
// Register custom shortcode
function prayers_time_2024()
{
    ob_start(); ?>
    <h2 class="newNamaz">نمازوں کے اوقات</h2>
    <h3 class="newNamaz" id="cruntCityNameNew"></h3>
    <table class="todayTime">
        <thead>
            <tr>
                <th>عشاء</th>
                <th>مغرب</th>
                <th>عصر</th>
                <th>ظہر</th>
                <th>فجر</th>
                <th>تاریخ</th>
            </tr>
        </thead>
        <tbody id="today-ramadan-timings-table"></tbody>
    </table>
    <table id="ramadan-timings-table2">
        <tr>
            <th>عشاء</th>
            <th>مغرب</th>
            <th>عصر</th>
            <th>ظہر</th>
            <th>فجر</th>
            <th>تاریخ</th>
        </tr>
    </table>
    <div class="ramadan-timings">
        <h2>دیگر شہروں اور ملکوں کے اوقات نماز جاننے کے لیئے مطلوبہ شہر اور ملک کا انتخاب کیجیے
        </h2>
        <label for="country-dropdown">ملک کا انتخاب کیجیے</label>
        <select id="country-dropdown" onchange="updateCities()">
            <option value="Select Country" selected id="mycountry">ملک کا انتخاب کیجیے</option>
        </select>

        <label for="city-dropdown">شہر کا انتخاب کیجیے</label>
        <select id="city-dropdown">
            <option value="Select City" selected id="mycity">شہر کا انتخاب کیجیے</option>
        </select>

        <table>
            <thead>
                <tr>
                    <th>عشاء</th>
                    <th>مغرب</th>
                    <th>عصر</th>
                    <th>ظہر</th>
                    <th>فجر</th>
                    <th>تاریخ</th>
                </tr>
            </thead>
            <tbody id="ramadan-timings-table"></tbody>
        </table>
    </div>
    <script>
        // Function to format date as YYYY-MM-DD for comparison
        function formatDateForComparison(dateString) {
            var date = new Date(dateString);
            var day = String(date.getDate()).padStart(2, '0');
            var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var month = monthNames[date.getMonth()];
            var year = date.getFullYear();
            return `${day} ${month} ${year}`;
        }

        function getCurrentMonth() {
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth() + 1;
            return currentMonth;
        }

        function getCurrentYear() {
            var currentDate = new Date();
            var currentYear = currentDate.getFullYear();
            return currentYear;
        }

        // First, fetch client IP, city, and country
        fetch('https://ipinfo.io/json')
            .then(response => response.json())
            .then(data => {
                var clientCity = data.city;
                var clientCountry = data.country;
                var currentMonth = getCurrentMonth();
                var currentYear = getCurrentYear();
                fetch(
                        `https://api.aladhan.com/v1/calendarByCity/${currentYear}/${currentMonth}?city=${clientCity}&country=${clientCountry}&method=1`)
                    .then(response => response.json())
                    .then(prayerData => {
                        var today = formatDateForComparison(new Date());
                        var prayerTimesdate = document.getElementById('prayerTimesdate');
                        var prayerTimessehri = document.getElementById('prayerTimessehri');
                        var prayerTimesaftari = document.getElementById('prayerTimesaftari');
                        var prayerTimesDiv = document.getElementById('prayerTimes');
                        var todayDateHeading = document.getElementById('todayDate');
                        var cityName = document.getElementById('cityName');
                        var ramadanTiming = document.getElementById('ramadanTiming');
                        var headingName = document.getElementById('headingName');
                        var todayDateHeading2 = document.getElementById('todayDate2');
                        var timingsTable = document.getElementById('ramadan-timings-table2');
                        var todaytimingsTable = document.getElementById('today-ramadan-timings-table');
                        var cruntCityNameNew = document.getElementById('cruntCityNameNew');

                        var currentDate = new Date();
                        var options = {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
                        var formattedDate = currentDate.toLocaleDateString('ur-PK', options);

                        cruntCityNameNew.innerHTML = `<p> <span>میں آج مورخہ ${formattedDate} کو نمازوں کے اوقات درج زیل ہیں </span> ${clientCity}<span> آپکے شہر</span></p>`;
                        for (var i = 0; i < prayerData.data.length; i++) {
                            console.log(prayerData.data.Fajr);
                            var date = prayerData.data[i].date.readable;
                            var formattedDate = formatDateForComparison(prayerData.data[i].date.gregorian.date);

                            console.log(prayerData.data[i].timings.Fajr);
                            var sehriTime = prayerData.data[i].timings.Fajr;
                            var iftarTime = prayerData.data[i].timings.Maghrib;
                            const sehriTimenew = sehriTime.replace(/\s*\(PKT\)/, '');
                            const iftarTimenew = iftarTime.replace(/\s*\(PKT\)/, '');
                            // Example time string: "18:05"
                            const timeString = iftarTimenew;

                            // Extract hours and minutes
                            const [hours, minutes] = timeString.split(':');

                            if (date == today) {
                                var row = `<tr><td>${formatTime(prayerData.data[i].timings.Isha)}</td><td>${formatTime(prayerData.data[i].timings.Maghrib)}</td><td>${formatTime(prayerData.data[i].timings.Asr)}</td><td>${formatTime(prayerData.data[i].timings.Dhuhr)}</td><td>${formatTime(prayerData.data[i].timings.Fajr)}</td><td>${date}</td></tr>`;
                                todaytimingsTable.innerHTML += row;
                            }
                            // Convert to 12-hour format
                            const ampm = hours >= 12 ? 'pm' : 'am';
                            const formattedHours = hours % 12 || 12; // Handle 12:00 PM as 12:00 PM, not 00:00 PM
                            const formattedTime = `${formattedHours}:${minutes} ${ampm}`;
                            // if (date >= today) {
                            var row = `<tr><td>${formatTime(prayerData.data[i].timings.Isha)}</td><td>${formatTime(prayerData.data[i].timings.Maghrib)}</td><td>${formatTime(prayerData.data[i].timings.Asr)}</td><td>${formatTime(prayerData.data[i].timings.Dhuhr)}</td><td>${formatTime(prayerData.data[i].timings.Fajr)}</td><td>${date}</td></tr>`;
                            timingsTable.innerHTML += row;
                            // }

                        }
                    })
                    .catch(error => console.error('Error fetching prayer times:', error));
            })
            .catch(error => console.error('Error fetching IP address:', error));

        function getNext30Days(today) {
            var next30Days = new Date(today);
            next30Days.setDate(next30Days.getDate() + 30);
            return formatDateForComparison(next30Days);
        }
    </script>
    <script>
        var countries = [
            'pakistan', 'usa', 'uk', 'canada', 'australia', 'germany', 'france', 'japan',
            'brazil', 'south africa', 'south korea', 'russia', 'china', 'mexico', 'india',
            'afghanistan', 'albania', 'algeria', 'andorra', 'angola', 'antigua and barbuda',
            'argentina', 'armenia', 'austria', 'azerbaijan', 'bahamas', 'bahrain', 'bangladesh',
            'barbados', 'belarus', 'belgium', 'belize', 'benin', 'bhutan', 'bolivia',
            'bosnia and herzegovina', 'botswana', 'brazil', 'brunei', 'bulgaria', 'burkina faso',
            'burundi', 'cabo verde', 'cambodia', 'cameroon', 'canada', 'central african republic',
            'chad', 'chile', 'china', 'colombia', 'comoros', 'congo', 'costa rica', 'croatia',
            'cuba', 'cyprus', 'czech republic', 'denmark', 'djibouti', 'dominica', 'dominican republic',
            'east timor', 'ecuador', 'egypt', 'el salvador', 'equatorial guinea', 'eritrea', 'estonia',
            'ethiopia', 'fiji', 'finland', 'france', 'gabon', 'gambia', 'georgia', 'germany', 'ghana',
            'greece', 'grenada', 'guatemala', 'guinea', 'guinea-bissau', 'guyana', 'haiti', 'honduras',
            'hungary', 'iceland', 'india', 'indonesia', 'iran', 'iraq', 'ireland', 'israel', 'italy',
            'jamaica', 'japan', 'jordan', 'kazakhstan', 'kenya', 'kiribati', 'korea, north', 'korea, south',
            'kosovo', 'kuwait', 'kyrgyzstan', 'laos', 'latvia', 'lebanon', 'lesotho', 'liberia', 'libya',
            'liechtenstein', 'lithuania', 'luxembourg', 'macedonia', 'madagascar', 'malawi', 'malaysia',
            'maldives', 'mali', 'malta', 'marshall islands', 'mauritania', 'mauritius', 'mexico', 'micronesia',
            'moldova', 'monaco', 'mongolia', 'montenegro', 'morocco', 'mozambique', 'myanmar', 'namibia', 'nauru',
            'nepal', 'netherlands', 'new zealand', 'nicaragua', 'niger', 'nigeria', 'norway', 'oman', 'pakistan',
            'palau', 'panama', 'papua new guinea', 'paraguay', 'peru', 'philippines', 'poland', 'portugal', 'qatar',
            'romania', 'russia', 'rwanda', 'saint kitts and nevis', 'saint lucia', 'saint vincent and the grenadines',
            'samoa', 'san marino', 'sao tome and principe', 'saudi arabia', 'senegal', 'serbia', 'seychelles', 'sierra leone',
            'singapore', 'slovakia', 'slovenia', 'solomon islands', 'somalia', 'south africa', 'south korea', 'south sudan',
            'spain', 'sri lanka', 'sudan', 'suriname', 'swaziland', 'sweden', 'switzerland', 'syria', 'taiwan', 'tajikistan',
            'tanzania', 'thailand', 'togo', 'tonga', 'trinidad and tobago', 'tunisia', 'turkey', 'turkmenistan', 'tuvalu',
            'uganda', 'ukraine', 'united arab emirates', 'united kingdom', 'united states of america', 'uruguay', 'uzbekistan',
            'vanuatu', 'vatican city', 'venezuela', 'vietnam', 'yemen', 'zambia', 'zimbabwe'
        ];
        // Get the country and city dropdown elements
        var countryDropdown = document.getElementById('country-dropdown');
        var cityDropdown = document.getElementById('city-dropdown');

        // Populate the country dropdown
        for (var i = 0; i < countries.length; i++) {
            var countryName = countries[i];
            var option = document.createElement('option');
            option.value = countryName;
            option.text = countryName.charAt(0).toUpperCase() + countryName.slice(1);
            countryDropdown.appendChild(option);
        }

        // Function to update cities based on the selected country
        function updateCities() {
            var selectedCountry = countryDropdown.value;
            var cityOptions = getCities(selectedCountry);

            // Clear existing city options
            cityDropdown.innerHTML = '<option value="Select City" selected>Select City</option>';

            // Populate the city dropdown with new options
            for (var i = 0; i < cityOptions.length; i++) {
                var cityName = cityOptions[i];
                var option = document.createElement('option');
                option.value = cityName;
                option.text = cityName.charAt(0).toUpperCase() + cityName.slice(1);
                cityDropdown.appendChild(option);
            }

            // Update timings based on the selected country and city
            updateRamadanTimings();
        }

        // Function to get the list of cities for a given country
        function getCities(country) {
            // You can customize this function to fetch cities based on the selected country
            // For simplicity, I'm providing a static list of cities for each country
            var citiesByCountry = {
                'pakistan': ['karachi', 'lahore', 'islamabad', 'rawalpindi', 'faisalabad', 'multan', 'peshawar', 'quetta', 'sialkot', 'gujranwala', 'sargodha', 'bahawalpur', 'sukkur', 'jhelum', 'abbottabad', 'mirpur', 'larkana', 'hyderabad'],
                'usa': ['new york', 'los angeles', 'chicago', 'houston', 'miami'],
                'uk': ['london', 'manchester', 'birmingham', 'glasgow', 'cardiff'],
                'canada': ['toronto', 'vancouver', 'montreal', 'calgary', 'ottawa'],
                'australia': ['sydney', 'melbourne', 'brisbane', 'perth', 'adelaide'],
                'germany': ['berlin', 'munich', 'hamburg', 'cologne', 'frankfurt'],
                'france': ['paris', 'marseille', 'lyon', 'toulouse', 'nice'],
                'japan': ['tokyo', 'osaka', 'kyoto', 'nagoya', 'sapporo'],
                'brazil': ['rio de janeiro', 'sao paulo', 'brasilia', 'salvador', 'fortaleza'],
                'south africa': ['cape town', 'johannesburg', 'pretoria', 'durban', 'bloemfontein'],
                'russia': ['moscow', 'saint petersburg', 'novosibirsk', 'yekaterinburg', 'nizhny novgorod'],
                'china': ['beijing', 'shanghai', 'guangzhou', 'shenzhen', 'tianjin'],
                'mexico': ['mexico city', 'guadalajara', 'monterrey', 'puebla', 'tijuana'],
                'india': ['delhi', 'mumbai', 'kolkata', 'chennai', 'bangalore'],
                'afghanistan': ['kabul', 'herat', 'mazar-i-sharif', 'kandahar', 'jalalabad'],
                'albania': ['tirana', 'durres', 'vlore', 'shkoder', 'elbasan'],
                'algeria': ['algiers', 'oran', 'constantine', 'batna', 'annaba'],
                'andorra': ['andorra la vella', 'encamp', 'escaldes-engordany', 'ordino', 'la massana'],
                'angola': ['luanda', 'lobito', 'huambo', 'benguela', 'kuito'],
                'antigua and barbuda': ['saint johns', 'all saints', 'liberta', 'potterville', 'bolands'],
                'argentina': ['buenos aires', 'cordoba', 'rosario', 'mendoza', 'la plata'],
                'armenia': ['yerevan', 'gyumri', 'vanadzor', 'vagharshapat', 'hrazdan'],
                'austria': ['vienna', 'graz', 'linz', 'salzburg', 'innsbruck'],
                'azerbaijan': ['baku', 'ganja', 'sumqayit', 'mingachevir', 'lankaran'],
                'bahamas': ['nassau', 'freeport', 'lucaya', 'marsh harbour', 'east end'],
                'bahrain': ['manama', 'al-muharraq', 'ar-rifa', 'dar kulaib', 'madinat hamad'],
                'bangladesh': ['dhaka', 'chittagong', 'khulna', 'rajshahi', 'comilla'],
                'barbados': ['bridgetown', 'speightstown', 'oistins', 'bathsheba', 'holetown'],
                'belarus': ['minsk', 'gomel', 'mogilev', 'vitebsk', 'hrodna'],
                'belgium': ['brussels', 'antwerp', 'ghent', 'bruges', 'liege'],
                'belize': ['belmopan', 'belize city', 'san ignacio', 'orange walk', 'dangriga'],
                'benin': ['porto-novo', 'cotonou', 'parakou', 'djougou', 'bohicon'],
                'bhutan': ['thimphu', 'phuntsholing', 'punakha', 'paro', 'ha'],
                'bolivia': ['la paz', 'sucre', 'santa cruz de la sierra', 'cochabamba', 'oruro'],
                'bosnia and herzegovina': ['sarajevo', 'banja luka', 'mostar', 'tuzla', 'zenica'],
                'botswana': ['gaborone', 'francistown', 'molepolole', 'serowe', 'selibe-phikwe'],
                'brunei': ['bandar seri begawan', 'kuala belait', 'seria', 'tutong', 'bangar'],
                'bulgaria': ['sofia', 'plovdiv', 'varna', 'burgas', 'rousse'],
                'burkina faso': ['ouagadougou', 'bobodioulasso', 'koudougou', 'ouahigouya', 'banfora'],
                'burundi': ['bujumbura', 'muyinga', 'rumonge', 'gitega', 'ngozi'],
                'cabo verde': ['praia', 'mindelo', 'assomada', 'santa maria', 'cova figueira'],
                'cambodia': ['phnom penh', 'sihanoukville', 'battambang', 'siem reap', 'prey veng'],
                'cameroon': ['yaounde', 'douala', 'bamenda', 'bafoussam', 'garoua'],
                'canada': ['toronto', 'vancouver', 'montreal', 'calgary', 'ottawa'],
                'central african republic': ['bangui', 'bimbo', 'berberati', 'kaga-bandoro', 'bambari'],
                'chad': ['djamena', 'moundou', 'sagh', 'abecher', 'bitkine'],
                'chile': ['santiago', 'valparaiso', 'concepcion', 'antofagasta', 'vina del mar'],
                'china': ['beijing', 'shanghai', 'guangzhou', 'shenzhen', 'tianjin'],
                'colombia': ['bogota', 'medellin', 'cali', 'barranquilla', 'cartagena'],
                'comoros': ['moroni', 'mfumbuni', 'fomboni', 'domoni', 'tsemo'],
                'congo': ['kinshasa', 'lubumbashi', 'mbuji-mayi', 'kisangani', 'kananga'],
                'costa rica': ['san jose', 'limon', 'alajuela', 'heredia', 'puerto limon'],
                'croatia': ['zagreb', 'split', 'rijeka', 'osijek', 'zadar'],
                'cuba': ['havana', 'santiago de cuba', 'camaguey', 'holguin', 'santa clara'],
                'cyprus': ['nicosia', 'limassol', 'larnaca', 'paphos', 'famagusta'],
                'czech republic': ['prague', 'brno', 'ostrava', 'plzen', 'liberec'],
                'denmark': ['copenhagen', 'aarhus', 'odense', 'aalborg', 'esbjerg'],
                'djibouti': ['djibouti', 'ali sabieh', 'tadjoura', 'obock', 'dikhil'],
                'dominica': ['roseau', 'portsmouth', 'marigot', 'berekua', 'mahaut'],
                'dominican republic': ['santo domingo', 'santiago de los caballeros', 'santo domingo oeste', 'santo domingo este', 'santo domingo norte'],
                'east timor': ['dili', 'suai', 'maliana', 'baucau', 'aileu'],
                'ecuador': ['quito', 'guayaquil', 'quito', 'cuenca', 'machala'],
                'egypt': ['cairo', 'alexandria', 'giza', 'shubra el-kheima', 'port said'],
                'el salvador': ['san salvador', 'santa ana', 'san miguel', 'mejicanos', 'soyapango'],
                'equatorial guinea': ['malabo', 'bata', 'ebebiyin', 'aconibe', 'anisoc'],
                'eritrea': ['asmara', 'massawa', 'keren', 'asmara', 'assab'],
                'estonia': ['tallinn', 'tartu', 'narva', 'parnu', 'viljandi'],
                'ethiopia': ['addis ababa', 'dire dawa', 'addis ababa', 'mek\'ele', 'gondar'],
                'fiji': ['suva', 'lautoka', 'nadi', 'labasa', 'ba'],
                'finland': ['helsinki', 'espoo', 'tampere', 'vantaa', 'turku'],
                'france': ['paris', 'marseille', 'lyon', 'toulouse', 'nice'],
                'gabon': ['libreville', 'port-gentil', 'port-gentil', 'oyem', 'moanda'],
                'gambia': ['banjul', 'serrekunda', 'brikama', 'bakau', 'banjul'],
                'georgia': ['tbilisi', 'kutaisi', 'batumi', 'rustavi', 'zestafoni'],
                'germany': ['berlin', 'munich', 'hamburg', 'cologne', 'frankfurt'],
                'ghana': ['accra', 'kumasi', 'tamale', 'sekondi-takoradi', 'cape coast'],
                'greece': ['athens', 'thessaloniki', 'patras', 'piraeus', 'larissa'],
                'grenada': ['saint georges', 'grenville', 'gouyave'],
                'guatemala': ['guatemala city', 'mixco', 'villa nueva', 'petapa', 'san juan sacatepequez'],
                'Spain': ['madrid', 'barcelona', 'valencia', 'seville', 'zaragoza'],
                'Sri Lanka': ['colombo', 'kandy', 'galle', 'jaffna', 'negombo'],
                'Sudan': ['khartoum', 'omdurman', 'khartoum north', 'port sudan', 'kassala'],
                'Suriname': ['paramaribo', 'lelydorp', 'nickerie', 'moengo', 'nieuw amsterdam'],
                'Swaziland': ['mbabane', 'manzini', 'lobamba', 'siteki', 'piggs peak'],
                'Sweden': ['stockholm', 'gothenburg', 'malmo', 'uppsala', 'västerås'],
                'Switzerland': ['zurich', 'geneva', 'basel', 'bern', 'lausanne'],
                'Syria': ['damascus', 'aleppo', 'homs', 'hama', 'latakia'],
                'Taiwan': ['taipei', 'kaohsiung', 'taichung', 'tainan', 'hsinchu'],
                'Tajikistan': ['dushanbe', 'khujand', 'kulob', 'qurghonteppa', 'isorqul'],
                'Tanzania': ['dar es salaam', 'dodoma', 'mwanza', 'zanzibar city', 'arusha'],
                'Thailand': ['bangkok', 'nonthaburi', 'nakhon ratchasima', 'chiang mai', 'hat yai'],
                'Togo': ['lome', 'sokode', 'kara', 'palime', 'atakpame'],
                'Tonga': ['nukualofa', 'neiafu', 'pangai', 'ohonua', 'haveluloto'],
                'Trinidad': ['port of spain', 'san fernando', 'chaguanas', 'arima', 'point fortin'],
                'Tunisia': ['tunis', 'sfax', 'sousse', 'kairouan', 'bizerte'],
                'Turkey': ['istanbul', 'ankara', 'izmir', 'bursa', 'adana'],
                'Turkmenistan': ['ashgabat', 'turkmenabat', 'dasoguz', 'mary', 'balkanabat'],
                'Tuvalu': ['funafuti', 'vaiaku', 'fongafale', 'asau', 'savave'],
                'Uganda': ['kampala', 'nansana', 'kira', 'mbarara', 'gulu'],
                'Ukraine': ['kyiv', 'kharkiv', 'odessa', 'dnipro', 'donetsk'],
                'United Arab Emirates': ['abu dhabi', 'dubai', 'sharjah', 'al ain', 'ajman'],
                'United Kingdom': ['london', 'manchester', 'birmingham', 'glasgow', 'cardiff'],
                'United States of America': ['new york', 'los angeles', 'chicago', 'houston', 'miami'],
                'Uruguay': ['montevideo', 'salto', 'paysandu', 'las piedras', 'rivera'],
                'Uzbekistan': ['tashkent', 'namangan', 'samarkand', 'andijan', 'nukus'],
                'Vanuatu': ['port vila', 'lakatoro', 'saratamata', 'norsup', 'isangel'],
                'Vatican': ['vatican city'],
                'Venezuela': ['caracas', 'maracaibo', 'valencia', 'barquisimeto', 'maracay'],
                'Vietnam': ['hanoi', 'ho chi minh city', 'da nang', 'hai phong', 'can tho'],
                'Yemen': ['sanaa', 'taiz', 'aden', 'ibb', 'al mukalla'],
                'Zambia': ['lusaka', 'ndola', 'kitwe', 'kabwe', 'chingola'],
                'Zimbabwe': ['harare', 'bulawayo', 'chitungwiza', 'mutare', 'gweru'],

            };

            return citiesByCountry[country] || [];
        }

        // Function to update Ramadan timings based on the selected country and city

        // Function to update Ramadan timings based on the selected country and city
        // Assuming you have two dropdowns with the IDs 'country-dropdown' and 'city-dropdown'
        var countryDropdown = document.getElementById('country-dropdown');
        var cityDropdown = document.getElementById('city-dropdown');

        // Add event listeners to both dropdowns
        countryDropdown.addEventListener('change', updateRamadanTimings);
        cityDropdown.addEventListener('change', updateRamadanTimings);

        // Function to update Ramadan timings
        function getCurrentMonth() {
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth() + 1;
            return currentMonth;
        }

        function getCurrentYear() {
            var currentDate = new Date();
            var currentYear = currentDate.getFullYear();
            return currentYear;
        }
        fetch('https://ipinfo.io/json')
            .then(response => response.json())
            .then(data => {
                var clientCity = data.city;
                var clientCountry = data.country;
                var mycountry = document.getElementById('mycountry');
                var mycountry = document.getElementById('mycity');

            })

        function getCurrentMonth() {
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth() + 1;
            return currentMonth;
        }

        function getCurrentYear() {
            var currentDate = new Date();
            var currentYear = currentDate.getFullYear();
            return currentYear;
        }

        function getFormattedDate() {
            var today = new Date();

            var day = today.getDate();
            var month = today.getMonth() + 1;
            var year = today.getFullYear();

            var formattedDay = (day < 10) ? '0' + day : day;
            var formattedMonth = (month < 10) ? '0' + month : month;

            // Format the date as DD-MM-YYYY
            var formattedDate = formattedDay + '-' + formattedMonth + '-' + year;

            return formattedDate;
        }

        function updateRamadanTimings() {
            var currentMonth = getCurrentMonth();
            var currentYear = getCurrentYear();
            var selectedCountry = countryDropdown.value;
            var selectedCity = cityDropdown.value;

            var currentMonth = getCurrentMonth();
            var currentYear = getCurrentYear();
            var formattedDate = getFormattedDate();
            // API URL for Aladhan
            var apiURL = 'https://api.aladhan.com/v1/calendarByCity';

            // Fetch Ramadan timings using the selected country and city
            fetch(`${apiURL}?city=${selectedCity}&country=${selectedCountry}&method=1`)
                .then(response => response.json())
                .then(data => {
                    // Update the timings table
                    var timingsTable = document.getElementById('ramadan-timings-table');
                    timingsTable.innerHTML = '';
                    // timingsTable.innerHTML = '<tr><th>تاریخ</th><th>سحری کے اوقات</th><th>افطاری کے اوقات</th></tr>';

                    for (var i = 0; i < data.data.length; i++) {
                        var day = data.data[i];
                        var date = day.date.gregorian.date;
                        // if (date >= formattedDate) {
                        var row = `<tr><td>${formatTime(day.timings.Isha)}</td><td>${formatTime(day.timings.Maghrib)}</td><td>${formatTime(day.timings.Asr)}</td><td>${formatTime(day.timings.Dhuhr)}</td><td>${formatTime(day.timings.Fajr)}</td><td>${date}</td></tr>`;
                        timingsTable.innerHTML += row;
                        // }
                    }
                })
                .catch(error => {
                    console.error('Error fetching Ramadan timings:', error);
                });
        }

        // Function to format time
        function formatTime(timeString) {
            // Assuming the timeString is in the format 'HH:mm:ss'
            var parts = timeString.split(':');
            var hour = parseInt(parts[0], 10);
            var minute = parseInt(parts[1], 10);
            var period = hour >= 12 ? 'PM' : 'AM';

            // Convert to 12-hour time format
            hour = hour % 12 || 12;

            return `${hour}:${minute < 10 ? '0' : ''}${minute} ${period}`;
        }
    </script>
<?php
    return ob_get_clean();
}
add_shortcode('prayer_times_2024', 'prayers_time_2024');
// Register custom shortcode

function ramadan_2024()
{
    ob_start(); ?>
    <div class="locationBaseTiming">
        <h1 id="headingName"></h1>
        <h2 id="cityName"> City Name</h2>
        <h2 id="cityNamewithdate"> City Name</h2>
        <h1 id="todayDate">Sehri Time</h1>
        <h1 id="todayDate2">Aftar Time</h1>
        <div id="prayerTimes"></div>
    </div>

    <div class="ramadan-timings">
        <h2>دیگر شہروں اور ملکوں کے اوقات سحری و افطار جاننے کے لیئے مطلوبہ شہر اور ملک کا انتخاب کیجیے
        </h2>
        <label for="country-dropdown">ملک کا انتخاب کیجیے</label>
        <select id="country-dropdown" onchange="updateCities()">
            <option value="Select Country" selected id="mycountry">ملک کا انتخاب کیجیے</option>
        </select>

        <label for="city-dropdown">شہر کا انتخاب کیجیے</label>
        <select id="city-dropdown">
            <option value="Select City" selected id="mycity">شہر کا انتخاب کیجیے</option>
        </select>

        <table>
            <thead>
                <tr>
                    <th>تاریخ</th>
                    <th>سحری</th>
                    <th>افطاری</th>
                </tr>
            </thead>
            <tbody id="ramadan-timings-table"></tbody>
        </table>
    </div>
    <script>
        // Function to format date as YYYY-MM-DD for comparison
        function formatDateForComparison(dateString) {
            var date = new Date(dateString);
            var day = String(date.getDate()).padStart(2, '0');
            var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var month = monthNames[date.getMonth()];
            var year = date.getFullYear();
            return `${day} ${month} ${year}`;
        }

        function getCurrentMonth() {
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth() + 1;
            return currentMonth;
        }

        function getCurrentYear() {
            var currentDate = new Date();
            var currentYear = currentDate.getFullYear();
            return currentYear;
        }

        // First, fetch client IP, city, and country
        fetch('https://ipinfo.io/json')
            .then(response => response.json())
            .then(data => {
                var clientCity = data.city;
                var clientCountry = data.country;
                var currentMonth = getCurrentMonth();
                var currentYear = getCurrentYear();
                fetch(
                        `https://api.aladhan.com/v1/calendarByCity/${currentYear}/${currentMonth}?city=${clientCity}&country=${clientCountry}&method=1&month=${currentMonth}&year=${currentYear}`)
                    .then(response => response.json())
                    .then(prayerData => {
                        var today = formatDateForComparison(new Date());
                        var prayerTimesDiv = document.getElementById('prayerTimes');
                        var todayDateHeading = document.getElementById('todayDate');
                        var cityName = document.getElementById('cityName');
                        var cityNamewithdate = document.getElementById('cityNamewithdate');
                        // var ramadanTiming = document.getElementById('ramadanTiming');
                        var headingName = document.getElementById('headingName');
                        var todayDateHeading2 = document.getElementById('todayDate2');
                        for (var i = 0; i < prayerData.data.length; i++) {
                            var date = prayerData.data[i].date.readable;
                            var formattedDate = formatDateForComparison(prayerData.data[i].date.gregorian.date);

                            console.log(prayerData.data[i].timings.Fajr);
                            var sehriTime = prayerData.data[i].timings.Fajr;
                            var iftarTime = prayerData.data[i].timings.Maghrib;
                            const sehriTimenew = sehriTime.replace(/\s*\(PKT\)/, '');
                            const iftarTimenew = iftarTime.replace(/\s*\(PKT\)/, '');
                            // Example time string: "18:05"
                            const timeString = iftarTimenew;

                            // Extract hours and minutes
                            const [hours, minutes] = timeString.split(':');

                            // Convert to 12-hour format
                            const ampm = hours >= 12 ? 'pm' : 'am';
                            const formattedHours = hours % 12 || 12; // Handle 12:00 PM as 12:00 PM, not 00:00 PM
                            const formattedTime = `${formattedHours}:${minutes} ${ampm}`;

                            const cityTranslations = {
                                "Islamabad": "Islamabad",
                                "Karachi": "Karachi",
                                "Lahore": "Lahore",
                                "Rawalpindi": "Rawalpindi",
                                "Faisalabad": "Faisalabad",
                                "Multan": "Multan",
                                "Peshawar": "Peshawar",
                                "Quetta": "Quetta",
                                "Gujranwala": "Gujranwala",

                            };
                            const clientCityTranslate = cityTranslations[clientCity] || clientCity;

                            var currentDate = new Date();
                            var options = {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            };
                            var formattedDate = currentDate.toLocaleDateString('ur-PK', options);

                            if (date == today) {

                                cityName.innerHTML = ` <p>  میں آج سحری و افطاری کے اوقات ${clientCityTranslate} </p> `;
                                cityNamewithdate.innerHTML = ` میں آج مورخہ ${formattedDate} کے اوقات-سحری-و-افطار درج زیل ہیں ${clientCityTranslate}`;
                                todayDateHeading.innerHTML = ` <p> ${sehriTimenew} am</p> <strong>سحر</strong>`;
                                todayDateHeading2.innerHTML = `<p>${formattedTime}</p> <strong>افطار</strong>`;
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching prayer times:', error));
            })
            .catch(error => console.error('Error fetching IP address:', error));

        function getNext30Days(today) {
            var next30Days = new Date(today);
            next30Days.setDate(next30Days.getDate() + 30);
            return formatDateForComparison(next30Days);
        }
    </script>

    <script>
        var countries = [
            'pakistan', 'usa', 'uk', 'canada', 'australia', 'germany', 'france', 'japan',
            'brazil', 'south africa', 'south korea', 'russia', 'china', 'mexico', 'india',
            'afghanistan', 'albania', 'algeria', 'andorra', 'angola', 'antigua and barbuda',
            'argentina', 'armenia', 'austria', 'azerbaijan', 'bahamas', 'bahrain', 'bangladesh',
            'barbados', 'belarus', 'belgium', 'belize', 'benin', 'bhutan', 'bolivia',
            'bosnia and herzegovina', 'botswana', 'brazil', 'brunei', 'bulgaria', 'burkina faso',
            'burundi', 'cabo verde', 'cambodia', 'cameroon', 'canada', 'central african republic',
            'chad', 'chile', 'china', 'colombia', 'comoros', 'congo', 'costa rica', 'croatia',
            'cuba', 'cyprus', 'czech republic', 'denmark', 'djibouti', 'dominica', 'dominican republic',
            'east timor', 'ecuador', 'egypt', 'el salvador', 'equatorial guinea', 'eritrea', 'estonia',
            'ethiopia', 'fiji', 'finland', 'france', 'gabon', 'gambia', 'georgia', 'germany', 'ghana',
            'greece', 'grenada', 'guatemala', 'guinea', 'guinea-bissau', 'guyana', 'haiti', 'honduras',
            'hungary', 'iceland', 'india', 'indonesia', 'iran', 'iraq', 'ireland', 'israel', 'italy',
            'jamaica', 'japan', 'jordan', 'kazakhstan', 'kenya', 'kiribati', 'korea, north', 'korea, south',
            'kosovo', 'kuwait', 'kyrgyzstan', 'laos', 'latvia', 'lebanon', 'lesotho', 'liberia', 'libya',
            'liechtenstein', 'lithuania', 'luxembourg', 'macedonia', 'madagascar', 'malawi', 'malaysia',
            'maldives', 'mali', 'malta', 'marshall islands', 'mauritania', 'mauritius', 'mexico', 'micronesia',
            'moldova', 'monaco', 'mongolia', 'montenegro', 'morocco', 'mozambique', 'myanmar', 'namibia', 'nauru',
            'nepal', 'netherlands', 'new zealand', 'nicaragua', 'niger', 'nigeria', 'norway', 'oman', 'pakistan',
            'palau', 'panama', 'papua new guinea', 'paraguay', 'peru', 'philippines', 'poland', 'portugal', 'qatar',
            'romania', 'russia', 'rwanda', 'saint kitts and nevis', 'saint lucia', 'saint vincent and the grenadines',
            'samoa', 'san marino', 'sao tome and principe', 'saudi arabia', 'senegal', 'serbia', 'seychelles', 'sierra leone',
            'singapore', 'slovakia', 'slovenia', 'solomon islands', 'somalia', 'south africa', 'south korea', 'south sudan',
            'spain', 'sri lanka', 'sudan', 'suriname', 'swaziland', 'sweden', 'switzerland', 'syria', 'taiwan', 'tajikistan',
            'tanzania', 'thailand', 'togo', 'tonga', 'trinidad and tobago', 'tunisia', 'turkey', 'turkmenistan', 'tuvalu',
            'uganda', 'ukraine', 'united arab emirates', 'united kingdom', 'united states of america', 'uruguay', 'uzbekistan',
            'vanuatu', 'vatican city', 'venezuela', 'vietnam', 'yemen', 'zambia', 'zimbabwe'
        ];
        // Get the country and city dropdown elements
        var countryDropdown = document.getElementById('country-dropdown');
        var cityDropdown = document.getElementById('city-dropdown');

        // Populate the country dropdown
        for (var i = 0; i < countries.length; i++) {
            var countryName = countries[i];
            var option = document.createElement('option');
            option.value = countryName;
            option.text = countryName.charAt(0).toUpperCase() + countryName.slice(1);
            countryDropdown.appendChild(option);
        }

        // Function to update cities based on the selected country
        function updateCities() {
            var selectedCountry = countryDropdown.value;
            var cityOptions = getCities(selectedCountry);

            // Clear existing city options
            cityDropdown.innerHTML = '<option value="Select City" selected>Select City</option>';

            // Populate the city dropdown with new options
            for (var i = 0; i < cityOptions.length; i++) {
                var cityName = cityOptions[i];
                var option = document.createElement('option');
                option.value = cityName;
                option.text = cityName.charAt(0).toUpperCase() + cityName.slice(1);
                cityDropdown.appendChild(option);
            }

            // Update timings based on the selected country and city
            updateRamadanTimings();
        }

        // Function to get the list of cities for a given country
        function getCities(country) {
            // You can customize this function to fetch cities based on the selected country
            // For simplicity, I'm providing a static list of cities for each country
            var citiesByCountry = {
                'pakistan': ['karachi', 'lahore', 'islamabad', 'rawalpindi', 'faisalabad', 'multan', 'peshawar'],
                'usa': ['new york', 'los angeles', 'chicago', 'houston', 'miami'],
                'uk': ['london', 'manchester', 'birmingham', 'glasgow', 'cardiff'],
                'canada': ['toronto', 'vancouver', 'montreal', 'calgary', 'ottawa'],
                'australia': ['sydney', 'melbourne', 'brisbane', 'perth', 'adelaide'],
                'germany': ['berlin', 'munich', 'hamburg', 'cologne', 'frankfurt'],
                'france': ['paris', 'marseille', 'lyon', 'toulouse', 'nice'],
                'japan': ['tokyo', 'osaka', 'kyoto', 'nagoya', 'sapporo'],
                'brazil': ['rio de janeiro', 'sao paulo', 'brasilia', 'salvador', 'fortaleza'],
                'south africa': ['cape town', 'johannesburg', 'pretoria', 'durban', 'bloemfontein'],
                'russia': ['moscow', 'saint petersburg', 'novosibirsk', 'yekaterinburg', 'nizhny novgorod'],
                'china': ['beijing', 'shanghai', 'guangzhou', 'shenzhen', 'tianjin'],
                'mexico': ['mexico city', 'guadalajara', 'monterrey', 'puebla', 'tijuana'],
                'india': ['delhi', 'mumbai', 'kolkata', 'chennai', 'bangalore'],
                'afghanistan': ['kabul', 'herat', 'mazar-i-sharif', 'kandahar', 'jalalabad'],
                'albania': ['tirana', 'durres', 'vlore', 'shkoder', 'elbasan'],
                'algeria': ['algiers', 'oran', 'constantine', 'batna', 'annaba'],
                'andorra': ['andorra la vella', 'encamp', 'escaldes-engordany', 'ordino', 'la massana'],
                'angola': ['luanda', 'lobito', 'huambo', 'benguela', 'kuito'],
                'antigua and barbuda': ['saint johns', 'all saints', 'liberta', 'potterville', 'bolands'],
                'argentina': ['buenos aires', 'cordoba', 'rosario', 'mendoza', 'la plata'],
                'armenia': ['yerevan', 'gyumri', 'vanadzor', 'vagharshapat', 'hrazdan'],
                'austria': ['vienna', 'graz', 'linz', 'salzburg', 'innsbruck'],
                'azerbaijan': ['baku', 'ganja', 'sumqayit', 'mingachevir', 'lankaran'],
                'bahamas': ['nassau', 'freeport', 'lucaya', 'marsh harbour', 'east end'],
                'bahrain': ['manama', 'al-muharraq', 'ar-rifa', 'dar kulaib', 'madinat hamad'],
                'bangladesh': ['dhaka', 'chittagong', 'khulna', 'rajshahi', 'comilla'],
                'barbados': ['bridgetown', 'speightstown', 'oistins', 'bathsheba', 'holetown'],
                'belarus': ['minsk', 'gomel', 'mogilev', 'vitebsk', 'hrodna'],
                'belgium': ['brussels', 'antwerp', 'ghent', 'bruges', 'liege'],
                'belize': ['belmopan', 'belize city', 'san ignacio', 'orange walk', 'dangriga'],
                'benin': ['porto-novo', 'cotonou', 'parakou', 'djougou', 'bohicon'],
                'bhutan': ['thimphu', 'phuntsholing', 'punakha', 'paro', 'ha'],
                'bolivia': ['la paz', 'sucre', 'santa cruz de la sierra', 'cochabamba', 'oruro'],
                'bosnia and herzegovina': ['sarajevo', 'banja luka', 'mostar', 'tuzla', 'zenica'],
                'botswana': ['gaborone', 'francistown', 'molepolole', 'serowe', 'selibe-phikwe'],
                'brunei': ['bandar seri begawan', 'kuala belait', 'seria', 'tutong', 'bangar'],
                'bulgaria': ['sofia', 'plovdiv', 'varna', 'burgas', 'rousse'],
                'burkina faso': ['ouagadougou', 'bobodioulasso', 'koudougou', 'ouahigouya', 'banfora'],
                'burundi': ['bujumbura', 'muyinga', 'rumonge', 'gitega', 'ngozi'],
                'cabo verde': ['praia', 'mindelo', 'assomada', 'santa maria', 'cova figueira'],
                'cambodia': ['phnom penh', 'sihanoukville', 'battambang', 'siem reap', 'prey veng'],
                'cameroon': ['yaounde', 'douala', 'bamenda', 'bafoussam', 'garoua'],
                'canada': ['toronto', 'vancouver', 'montreal', 'calgary', 'ottawa'],
                'central african republic': ['bangui', 'bimbo', 'berberati', 'kaga-bandoro', 'bambari'],
                'chad': ['djamena', 'moundou', 'sagh', 'abecher', 'bitkine'],
                'chile': ['santiago', 'valparaiso', 'concepcion', 'antofagasta', 'vina del mar'],
                'china': ['beijing', 'shanghai', 'guangzhou', 'shenzhen', 'tianjin'],
                'colombia': ['bogota', 'medellin', 'cali', 'barranquilla', 'cartagena'],
                'comoros': ['moroni', 'mfumbuni', 'fomboni', 'domoni', 'tsemo'],
                'congo': ['kinshasa', 'lubumbashi', 'mbuji-mayi', 'kisangani', 'kananga'],
                'costa rica': ['san jose', 'limon', 'alajuela', 'heredia', 'puerto limon'],
                'croatia': ['zagreb', 'split', 'rijeka', 'osijek', 'zadar'],
                'cuba': ['havana', 'santiago de cuba', 'camaguey', 'holguin', 'santa clara'],
                'cyprus': ['nicosia', 'limassol', 'larnaca', 'paphos', 'famagusta'],
                'czech republic': ['prague', 'brno', 'ostrava', 'plzen', 'liberec'],
                'denmark': ['copenhagen', 'aarhus', 'odense', 'aalborg', 'esbjerg'],
                'djibouti': ['djibouti', 'ali sabieh', 'tadjoura', 'obock', 'dikhil'],
                'dominica': ['roseau', 'portsmouth', 'marigot', 'berekua', 'mahaut'],
                'dominican republic': ['santo domingo', 'santiago de los caballeros', 'santo domingo oeste', 'santo domingo este', 'santo domingo norte'],
                'east timor': ['dili', 'suai', 'maliana', 'baucau', 'aileu'],
                'ecuador': ['quito', 'guayaquil', 'quito', 'cuenca', 'machala'],
                'egypt': ['cairo', 'alexandria', 'giza', 'shubra el-kheima', 'port said'],
                'el salvador': ['san salvador', 'santa ana', 'san miguel', 'mejicanos', 'soyapango'],
                'equatorial guinea': ['malabo', 'bata', 'ebebiyin', 'aconibe', 'anisoc'],
                'eritrea': ['asmara', 'massawa', 'keren', 'asmara', 'assab'],
                'estonia': ['tallinn', 'tartu', 'narva', 'parnu', 'viljandi'],
                'ethiopia': ['addis ababa', 'dire dawa', 'addis ababa', 'mek\'ele', 'gondar'],
                'fiji': ['suva', 'lautoka', 'nadi', 'labasa', 'ba'],
                'finland': ['helsinki', 'espoo', 'tampere', 'vantaa', 'turku'],
                'france': ['paris', 'marseille', 'lyon', 'toulouse', 'nice'],
                'gabon': ['libreville', 'port-gentil', 'port-gentil', 'oyem', 'moanda'],
                'gambia': ['banjul', 'serrekunda', 'brikama', 'bakau', 'banjul'],
                'georgia': ['tbilisi', 'kutaisi', 'batumi', 'rustavi', 'zestafoni'],
                'germany': ['berlin', 'munich', 'hamburg', 'cologne', 'frankfurt'],
                'ghana': ['accra', 'kumasi', 'tamale', 'sekondi-takoradi', 'cape coast'],
                'greece': ['athens', 'thessaloniki', 'patras', 'piraeus', 'larissa'],
                'grenada': ['saint georges', 'grenville', 'gouyave'],
                'guatemala': ['guatemala city', 'mixco', 'villa nueva', 'petapa', 'san juan sacatepequez'],
                'Spain': ['madrid', 'barcelona', 'valencia', 'seville', 'zaragoza'],
                'Sri Lanka': ['colombo', 'kandy', 'galle', 'jaffna', 'negombo'],
                'Sudan': ['khartoum', 'omdurman', 'khartoum north', 'port sudan', 'kassala'],
                'Suriname': ['paramaribo', 'lelydorp', 'nickerie', 'moengo', 'nieuw amsterdam'],
                'Swaziland': ['mbabane', 'manzini', 'lobamba', 'siteki', 'piggs peak'],
                'Sweden': ['stockholm', 'gothenburg', 'malmo', 'uppsala', 'västerås'],
                'Switzerland': ['zurich', 'geneva', 'basel', 'bern', 'lausanne'],
                'Syria': ['damascus', 'aleppo', 'homs', 'hama', 'latakia'],
                'Taiwan': ['taipei', 'kaohsiung', 'taichung', 'tainan', 'hsinchu'],
                'Tajikistan': ['dushanbe', 'khujand', 'kulob', 'qurghonteppa', 'isorqul'],
                'Tanzania': ['dar es salaam', 'dodoma', 'mwanza', 'zanzibar city', 'arusha'],
                'Thailand': ['bangkok', 'nonthaburi', 'nakhon ratchasima', 'chiang mai', 'hat yai'],
                'Togo': ['lome', 'sokode', 'kara', 'palime', 'atakpame'],
                'Tonga': ['nukualofa', 'neiafu', 'pangai', 'ohonua', 'haveluloto'],
                'Trinidad': ['port of spain', 'san fernando', 'chaguanas', 'arima', 'point fortin'],
                'Tunisia': ['tunis', 'sfax', 'sousse', 'kairouan', 'bizerte'],
                'Turkey': ['istanbul', 'ankara', 'izmir', 'bursa', 'adana'],
                'Turkmenistan': ['ashgabat', 'turkmenabat', 'dasoguz', 'mary', 'balkanabat'],
                'Tuvalu': ['funafuti', 'vaiaku', 'fongafale', 'asau', 'savave'],
                'Uganda': ['kampala', 'nansana', 'kira', 'mbarara', 'gulu'],
                'Ukraine': ['kyiv', 'kharkiv', 'odessa', 'dnipro', 'donetsk'],
                'United Arab Emirates': ['abu dhabi', 'dubai', 'sharjah', 'al ain', 'ajman'],
                'United Kingdom': ['london', 'manchester', 'birmingham', 'glasgow', 'cardiff'],
                'United States of America': ['new york', 'los angeles', 'chicago', 'houston', 'miami'],
                'Uruguay': ['montevideo', 'salto', 'paysandu', 'las piedras', 'rivera'],
                'Uzbekistan': ['tashkent', 'namangan', 'samarkand', 'andijan', 'nukus'],
                'Vanuatu': ['port vila', 'lakatoro', 'saratamata', 'norsup', 'isangel'],
                'Vatican': ['vatican city'],
                'Venezuela': ['caracas', 'maracaibo', 'valencia', 'barquisimeto', 'maracay'],
                'Vietnam': ['hanoi', 'ho chi minh city', 'da nang', 'hai phong', 'can tho'],
                'Yemen': ['sanaa', 'taiz', 'aden', 'ibb', 'al mukalla'],
                'Zambia': ['lusaka', 'ndola', 'kitwe', 'kabwe', 'chingola'],
                'Zimbabwe': ['harare', 'bulawayo', 'chitungwiza', 'mutare', 'gweru'],

            };

            return citiesByCountry[country] || [];
        }

        // Function to update Ramadan timings based on the selected country and city

        // Function to update Ramadan timings based on the selected country and city
        // Assuming you have two dropdowns with the IDs 'country-dropdown' and 'city-dropdown'
        var countryDropdown = document.getElementById('country-dropdown');
        var cityDropdown = document.getElementById('city-dropdown');

        // Add event listeners to both dropdowns
        countryDropdown.addEventListener('change', updateRamadanTimings);
        cityDropdown.addEventListener('change', updateRamadanTimings);

        // Function to update Ramadan timings
        function getCurrentMonth() {
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth() + 1;
            return currentMonth;
        }

        function getCurrentYear() {
            var currentDate = new Date();
            var currentYear = currentDate.getFullYear();
            return currentYear;
        }
        fetch('https://ipinfo.io/json')
            .then(response => response.json())
            .then(data => {
                var clientCity = data.city;
                var clientCountry = data.country;
                var mycountry = document.getElementById('mycountry');
                var mycountry = document.getElementById('mycity');

            })

        function getCurrentMonth() {
            var currentDate = new Date();
            var currentMonth = currentDate.getMonth() + 1;
            return currentMonth;
        }

        function getCurrentYear() {
            var currentDate = new Date();
            var currentYear = currentDate.getFullYear();
            return currentYear;
        }

        function getFormattedDate() {
            var today = new Date();

            var day = today.getDate();
            var month = today.getMonth() + 1;
            var year = today.getFullYear();

            var formattedDay = (day < 10) ? '0' + day : day;
            var formattedMonth = (month < 10) ? '0' + month : month;

            // Format the date as DD-MM-YYYY
            var formattedDate = formattedDay + '-' + formattedMonth + '-' + year;

            return formattedDate;
        }

        function updateRamadanTimings() {
            var currentMonth = getCurrentMonth();
            var currentYear = getCurrentYear();
            var selectedCountry = countryDropdown.value;
            var selectedCity = cityDropdown.value;

            var currentMonth = getCurrentMonth();
            var currentYear = getCurrentYear();
            var formattedDate = getFormattedDate();
            // API URL for Aladhan
            var apiURL = 'https://api.aladhan.com/v1/calendarByCity';

            // Fetch Ramadan timings using the selected country and city
            fetch(`${apiURL}?city=${selectedCity}&country=${selectedCountry}&method=1&month=${currentMonth}&year=${currentYear}`)
                .then(response => response.json())
                .then(data => {
                    // Update the timings table
                    var timingsTable = document.getElementById('ramadan-timings-table');
                    // timingsTable.innerHTML = '<tr><th>تاریخ</th><th>سحری کے اوقات</th><th>افطاری کے اوقات</th></tr>';
                    timingsTable.innerHTML = '';
                    for (var i = 0; i < data.data.length; i++) {
                        var day = data.data[i];
                        var date = day.date.gregorian.date;


                        var row = `<tr><td>${date}</td><td>${formatTime(day.timings.Fajr)}</td><td>${formatTime(day.timings.Maghrib)}</td></tr>`;
                        timingsTable.innerHTML += row;

                        // var row = `<tr><td>${date}</td><td>${formatTime(day.timings.Fajr)}</td><td>${formatTime(day.timings.Dhuhr)}</td><td>${formatTime(day.timings.Asr)}</td><td>${formatTime(day.timings.Maghrib)}</td><td>${formatTime(day.timings.Isha)}</td></tr>`;
                        // timingsTable.innerHTML += row;

                    }
                })
                .catch(error => {
                    console.error('Error fetching Ramadan timings:', error);
                });
        }

        // Function to format time
        function formatTime(timeString) {
            // Assuming the timeString is in the format 'HH:mm:ss'
            var parts = timeString.split(':');
            var hour = parseInt(parts[0], 10);
            var minute = parseInt(parts[1], 10);
            var period = hour >= 12 ? 'PM' : 'AM';

            // Convert to 12-hour time format
            hour = hour % 12 || 12;

            return `${hour}:${minute < 10 ? '0' : ''}${minute} ${period}`;
        }
    </script>
<?php
    return ob_get_clean();
}
add_shortcode('ramadan_timing_2024', 'ramadan_2024');


// masjid
// Add a custom admin menu item
add_action('admin_menu', 'add_custom_admin_menu');

function add_custom_admin_menu() {
    add_menu_page(
        'Custom Admin Page', // Page title
        'Masjid Table',      // Menu title
        'manage_options',    // Capability required to access the menu
        'custom-admin-page',  // Menu slug
        'render_custom_admin_page' // Callback function to render the page content
    );
}

// Render the custom admin page content
function render_custom_admin_page() {
    ?>
    <div class="wrap">
        <h1>Masjid Table</h1>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Country</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Your PHP code to fetch data and populate the table rows
                // Example:
                $masjids = get_masjids(); // Custom function to fetch masjids data
                foreach ($masjids as $masjid) {
                    echo '<tr>';
                    echo '<td>' . esc_html($masjid['name']) . '</td>';
                    echo '<td>' . esc_html($masjid['address']) . '</td>';
                    echo '<td>' . esc_html($masjid['city']) . '</td>';
                    echo '<td>' . esc_html($masjid['country']) . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Custom function to fetch masjids data (replace this with your actual data retrieval logic)
function get_masjids() {
    // Example data array, replace with actual data retrieval logic
    $masjids = array(
        array('name' => 'Masjid A', 'address' => '123 Main St', 'city' => 'New York', 'country' => 'USA'),
        array('name' => 'Masjid B', 'address' => '456 Elm St', 'city' => 'London', 'country' => 'UK'),
        array('name' => 'Masjid C', 'address' => '789 Oak St', 'city' => 'Paris', 'country' => 'France'),
    );

    return $masjids;
}
// MASJID FORM
// Register shortcode for masjid form
function masjid_form_shortcode() {
    ob_start(); // Start output buffering
    ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="submit_masjid_form">

        <!-- Masjid Name -->
        <label for="masjid_name">مسجد کا نام*</label>
        <input type="text" id="masjid_name" name="masjid_name" required>

        <!-- Masjid Address -->
        <label for="masjid_address">مسجد کا مکمل پتہ*</label>
        <input type="text" id="masjid_address" name="masjid_address" required>

        <!-- City -->
        <label for="city">شہر*</label>
        <input type="text" id="city" name="city" required>

        <!-- Country -->
        <label for="country">ملک*</label>
        <input type="text" id="country" name="country" required>

        <!-- Imam Name -->
        <label for="imam_name">امام مسجد کا نام*</label>
        <input type="text" id="imam_name" name="imam_name" required>

        <!-- Khateeb Name -->
        <label for="khateeb_name">نماز جمعہ کے خطیب کا نام</label>
        <input type="text" id="khateeb_name" name="khateeb_name">

        <!-- Images -->
        <label for="images">تصاویر</label>
        <input type="file" id="images" name="images">

        <!-- Contact Number -->
        <label for="contact_number">مسجد انتظامیہ کا رابطہ نمبر</label>
        <input type="text" id="contact_number" name="contact_number" required>

        <!-- Submit Button -->
        <input type="submit" value="Submit">
    </form>
    <?php
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('masjid_form', 'masjid_form_shortcode');
