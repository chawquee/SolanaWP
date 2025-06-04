<?php
/**
 * SolanaWP functions and definitions
 *
 * This file acts as a hub for including all the theme's functional components,
 * ensuring cleaner code organization and adherence to WordPress best practices.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package SolanaWP
 * @since SolanaWP 1.0.0
 */

// Exit if accessed directly to prevent direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// --- Core Theme Setup ---
// Includes theme supports, navigation menus, image sizes, etc.
require get_template_directory() . '/inc/theme-setup.php';
if ( function_exists( 'solanawp_setup' ) ) {
    add_action( 'after_setup_theme', 'solanawp_setup' );
}

// --- Enqueue Scripts and Styles ---
// Manages loading of all CSS and JavaScript files for front-end and admin.
require get_template_directory() . '/inc/enqueue.php';
if ( function_exists( 'solanawp_scripts_styles' ) ) {
    add_action( 'wp_enqueue_scripts', 'solanawp_scripts_styles' );
}
if ( function_exists( 'solanawp_admin_scripts_styles' ) ) {
    add_action( 'admin_enqueue_scripts', 'solanawp_admin_scripts_styles' );
}

// --- Widget Areas (Sidebars) ---
// Registers all widgetized areas for the theme.
require get_template_directory() . '/inc/widget-areas.php';
if ( function_exists( 'solanawp_widgets_init' ) ) {
    add_action( 'widgets_init', 'solanawp_widgets_init' );
}

// --- Custom Widgets ---
// Defines and registers custom widgets for the theme.
require get_template_directory() . '/inc/custom-widgets.php';
if ( function_exists( 'solanawp_register_custom_widgets' ) ) {
    add_action( 'widgets_init', 'solanawp_register_custom_widgets' );
}

// --- Theme Customizer Additions ---
// Adds options and controls to the WordPress Theme Customizer.
require get_template_directory() . '/inc/customizer.php';
if ( function_exists( 'solanawp_customize_register' ) ) {
    add_action( 'customize_register', 'solanawp_customize_register' );
}
if ( function_exists( 'solanawp_customize_preview_js' ) ) { // For live preview JS
    add_action( 'customize_preview_init', 'solanawp_customize_preview_js' );
}
if ( function_exists( 'solanawp_customizer_css_output' ) ) { // For outputting Customizer CSS to head
    add_action( 'wp_head', 'solanawp_customizer_css_output' );
}

// --- Custom Nav Walker (if used) ---
// Includes the custom navigation walker class.
require get_template_directory() . '/inc/navwalker.php';
// Note: The walker itself needs to be specified in wp_nav_menu() arguments in header.php to be used.

// --- Custom Template Tags ---
// Helper functions primarily for outputting HTML in template files.
require get_template_directory() . '/inc/template-tags.php';

// --- Template Functions ---
// Helper functions that hook into WordPress to modify behavior or add classes.
require get_template_directory() . '/inc/template-functions.php';
if ( function_exists( 'solanawp_body_classes' ) ) {
    add_filter( 'body_class', 'solanawp_body_classes' );
}
if ( function_exists( 'solanawp_pingback_header' ) ) {
    add_action( 'wp_head', 'solanawp_pingback_header' );
}
if ( function_exists( 'solanawp_excerpt_more' ) ) {
    add_filter( 'excerpt_more', 'solanawp_excerpt_more' );
}
if ( function_exists( 'solanawp_custom_excerpt_length' ) ) {
    add_filter( 'excerpt_length', 'solanawp_custom_excerpt_length', 999 );
}

// --- Breadcrumbs Functionality ---
// Includes the breadcrumbs generation function.
require get_template_directory() . '/inc/breadcrumbs.php';
// To display breadcrumbs, call solanawp_breadcrumbs() in your template files.

// --- Optional: WordPress AJAX Handler for Solana Checker ---
// This section would contain the PHP functions to handle AJAX requests from assets/js/main.js
// for fetching and processing Solana address data securely on the server-side.
/*
function solanawp_ajax_check_solana_address() {
    // 1. Verify nonce for security (created with wp_localize_script).
    check_ajax_referer( 'solanawp_solana_checker_nonce', 'nonce' );

    // 2. Get and sanitize the address from the AJAX request.
    $address = isset( $_POST['address'] ) ? sanitize_text_field( wp_unslash( $_POST['address'] ) ) : '';

    if ( empty( $address ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'No address provided.', 'solanawp' ) ) );
    }

    // 3. IMPLEMENT YOUR LOGIC HERE to:
    //    - Call Solana RPC endpoints (e.g., using wp_remote_get or wp_remote_post).
    //    - Call any third-party APIs for risk assessment, token data, etc.
    //    - Process the data.
    //    - IMPORTANT: Store API keys securely (e.g., constants in wp-config.php, not directly in theme files).

    // Example: Simulating a successful data fetch
    // $solana_data = my_custom_solana_api_fetch_function( $address );

    // if ( is_wp_error( $solana_data ) ) {
    //     wp_send_json_error( array( 'message' => $solana_data->get_error_message() ) );
    // } elseif ( empty( $solana_data ) ) {
    //     wp_send_json_error( array( 'message' => esc_html__( 'Could not retrieve data for this address.', 'solanawp' ) ) );
    // } else {
    //     // Structure $solana_data to match what assets/js/main.js expects for populateResults()
    //     wp_send_json_success( $solana_data );
    // }

    // Fallback for simulation if above is not implemented
    $mock_validation_message = ($address && strlen($address) > 30) ? 'Address appears valid (Simulated Server Response)' : 'Invalid address format (Simulated Server Response)';
    $mock_is_valid = ($address && strlen($address) > 30);

    wp_send_json_success( array( //
        'validation' => array(
            'isValid' => $mock_is_valid,
            'address' => esc_html($address),
            'format' => 'Base58 (Simulated Server)',
            'length' => strlen($address) . ' characters (Simulated Server)',
            'type' => 'Wallet Account (Simulated Server)',
            'message' => $mock_validation_message
        ),
        // ... include other mock data sections (balanceHoldings, transactionAnalysis, etc.)
        // if isValid is true, to fully test the JS population.
        // This should mirror the mockData structure in main.js
    ) );

	wp_die(); // This is required to terminate immediately and correctly an AJAX handler.
}
add_action( 'wp_ajax_solanawp_check_solana_address', 'solanawp_ajax_check_solana_address' );       // For logged-in users
add_action( 'wp_ajax_nopriv_solanawp_check_solana_address', 'solanawp_ajax_check_solana_address' ); // For non-logged-in users (visitors)
*/

// Set global content width (if not set in theme-setup.php or if a different context is needed here).
if ( ! isset( $content_width ) ) {
    $content_width = 800; // Example width in pixels for main content area.
}

/**
 * Additional Functions to Add to functions.php
 * Add these to the end of your functions.php file to completely remove default WordPress content
 */

// Remove default WordPress content on front page
add_action('wp_head', 'hide_default_wordpress_content');
function hide_default_wordpress_content() {
    if (is_front_page()) {
        echo '<style>
        .front-page-content-area .hentry,
        .front-page-content-area article.post,
        .front-page-content-area .entry-content p,
        body.home .site-main article.post,
        body.page-template-address-checker .entry-content {
            display: none !important;
        }
        </style>';
    }
}

// Remove default posts from front page query
add_action('pre_get_posts', 'remove_default_posts_from_front_page');
function remove_default_posts_from_front_page($query) {
    if ($query->is_main_query() && is_front_page() && $query->is_posts_page) {
        $query->set('post__in', array(0)); // Show no posts
    }
}

// Additional CSS for perfect center alignment
add_action('wp_head', 'hannisol_center_alignment_css');
function hannisol_center_alignment_css() {
    echo '<style>
    /* Perfect center alignment for analyzer */
    .input-section {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 120px;
    }

    .input-container {
        width: 100%;
        max-width: 800px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 16px;
    }

    /* Ensure content area is properly centered */
    .main-container {
        display: grid;
        grid-template-columns: 300px 1fr 300px;
        gap: 24px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
        align-items: start;
    }

    /* Make sure sidebars are exactly the same height and alignment */
    .sidebar,
    .sidebar-right {
        display: flex;
        flex-direction: column;
        gap: 16px;
        align-items: stretch;
    }

    /* Ensure all ad banners are exactly the same size */
    .sidebar .ad-banner,
    .sidebar-right .ad-banner {
        height: 250px;
        width: 100%;
        box-sizing: border-box;
    }

    .sidebar .ad-banner.small,
    .sidebar-right .ad-banner.small {
        height: 120px;
        width: 100%;
        box-sizing: border-box;
    }

    /* Perfect vertical alignment */
    .content-area {
        align-self: start;
        width: 100%;
    }

    /* Center the results section content */
    .results-section {
        padding: 32px;
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
    }
    </style>';
}

/**
 * Remove the default "Hello World" post if it exists
 * Add this to functions.php to automatically delete the default post
 */
add_action('init', 'remove_default_hello_world_post');
function remove_default_hello_world_post() {
    $hello_world_post = get_posts(array(
        'title' => 'Hello world!',
        'post_status' => 'any',
        'numberposts' => 1
    ));

    if (!empty($hello_world_post)) {
        wp_delete_post($hello_world_post[0]->ID, true);
    }
}

/**
 * CSS for responsive improvements
 * Ensures the layout works perfectly on all screen sizes
 */
add_action('wp_head', 'hannisol_responsive_improvements');
function hannisol_responsive_improvements() {
    echo '<style>
    @media (max-width: 1200px) {
        .main-container {
            grid-template-columns: 250px 1fr 250px;
            gap: 20px;
            padding: 20px;
        }
    }

    @media (max-width: 1024px) {
        .main-container {
            grid-template-columns: 1fr;
            gap: 16px;
            padding: 16px;
        }

        .sidebar,
        .sidebar-right {
            order: -1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .content-area {
            order: 0;
        }

        .ad-banner {
            height: 120px !important;
        }
    }

    @media (max-width: 768px) {
        .sidebar,
        .sidebar-right {
            grid-template-columns: 1fr;
        }

        .main-container {
            padding: 12px;
        }

        .input-section {
            padding: 24px 16px;
        }

        .results-section {
            padding: 24px 16px;
        }
    }
    </style>';
}

/**
 * COMPLETE NAVIGATION MENU REMOVAL
 * Add these functions to the end of your functions.php file
 * This will completely eliminate any navigation menu traces including the '''
 */

// Completely disable navigation menus at the theme level
add_action('after_setup_theme', 'solanawp_disable_navigation_menus', 20);
function solanawp_disable_navigation_menus()
{
    // Remove theme support for menus
    remove_theme_support('menus');

    // Unregister all navigation menus
    global $_wp_registered_nav_menus;
    $_wp_registered_nav_menus = array();
}

// Force hide all navigation elements with CSS
add_action('wp_head', 'solanawp_force_hide_navigation', 999);
function solanawp_force_hide_navigation()
{
    echo '<style>
    /* FORCE HIDE ALL NAVIGATION ELEMENTS */
    .main-navigation,
    .site-navigation,
    #site-navigation,
    .primary-menu,
    #primary-menu,
    .menu,
    nav,
    .nav,
    .navigation,
    .nav-menu,
    .navigation-menu,
    .menu-toggle,
    .menu-item,
    .nav-links,
    .navigation-links,
    ul.menu,
    ol.menu,
    .wp-block-navigation,
    .wp-block-navigation__container,
    .wp-block-navigation-link,
    .has-child .wp-block-navigation__submenu-container,
    [role="navigation"],
    .navbar,
    .nav-bar,
    .top-menu,
    .header-menu,
    .header-navigation,
    .site-header nav,
    .site-header .menu,
    .site-header ul,
    .site-header ol,
    header nav,
    header .menu,
    header ul:not(.no-hide),
    header ol:not(.no-hide),
    .menu-primary-container,
    .menu-header-container,
    .menu-main-container,
    #menu-primary,
    #menu-header,
    #menu-main,
    .primary-navigation,
    .header-nav,
    .main-nav,
    .top-nav {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        width: 0 !important;
        overflow: hidden !important;
        opacity: 0 !important;
        position: absolute !important;
        left: -9999px !important;
        top: -9999px !important;
    }

    /* Remove any list styling that might show as dots or lines */
    .site-header ul,
    .site-header ol,
    header ul,
    header ol {
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Hide any menu-related pseudo-elements */
    .site-header *::before,
    .site-header *::after {
        content: "" !important;
    }

    /* Ensure no menu content appears */
    .site-header .menu *,
    .site-header nav *,
    header .menu *,
    header nav * {
        display: none !important;
    }
    </style>';
}

// Remove menu walker and any menu-related functions
add_filter('wp_nav_menu', '__return_empty_string', 999);
add_filter('wp_nav_menu_args', '__return_empty_array', 999);

// Remove navigation from theme locations
add_action('init', 'solanawp_remove_navigation_locations');
function solanawp_remove_navigation_locations()
{
    // Unregister all navigation menu locations
    unregister_nav_menu('primary');
    unregister_nav_menu('footer');
    unregister_nav_menu('header');
    unregister_nav_menu('main');
    unregister_nav_menu('secondary');
    unregister_nav_menu('social');
}

// Completely remove menu support from customizer
add_action('customize_register', 'solanawp_remove_menu_customizer_section');
function solanawp_remove_menu_customizer_section($wp_customize)
{
    $wp_customize->remove_section('nav');
    $wp_customize->remove_panel('nav_menus');
}

// Hide menu admin pages
add_action('admin_menu', 'solanawp_hide_menu_admin_pages');
function solanawp_hide_menu_admin_pages()
{
    remove_submenu_page('themes.php', 'nav-menus.php');
}

// Enhanced content hiding for front page
add_action('wp_head', 'solanawp_enhanced_content_hiding');
function solanawp_enhanced_content_hiding()
{
    if (is_front_page() || is_page_template('templates/template-address-checker.php')) {
        echo '<style>
        /* ENHANCED CONTENT HIDING */
        .front-page-content-area .hentry,
        .front-page-content-area article,
        .front-page-content-area .post,
        .front-page-content-area .entry-content,
        .front-page-content-area .page-content,
        .address-checker-content .entry-content,
        .address-checker-content .page-content,
        body.home .site-main article,
        body.home .site-main .post,
        body.page-template-address-checker .entry-content,
        body.page-template-address-checker .page-content,
        .page-intro-content,
        .solanawp-page-content .entry-content,
        .wp-block-post-content,
        .entry-summary,
        .post-content,
        .page-content p,
        .entry-content p:first-child,
        article.post,
        article.page,
        .type-post,
        .type-page {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            overflow: hidden !important;
            position: absolute !important;
            left: -9999px !important;
        }

        /* Show only the checker components */
        .input-section,
        .results-section,
        #resultsSection,
        .solanawp-checker-main,
        .checker-input-section,
        .address-checker-content .input-section,
        .address-checker-content .results-section {
            display: block !important;
            visibility: visible !important;
            position: static !important;
            height: auto !important;
            overflow: visible !important;
        }
        </style>';
    }
}

// Remove any WordPress default content on front page query
add_action('pre_get_posts', 'solanawp_remove_front_page_content');
function solanawp_remove_front_page_content($query)
{
    if (!is_admin() && $query->is_main_query()) {
        if (is_front_page() || is_home()) {
            // Don't show any posts
            $query->set('post__in', array(0));
            $query->set('posts_per_page', 0);
        }
    }
}

// Additional CSS for perfect edge-to-edge layout
add_action('wp_head', 'solanawp_edge_to_edge_layout');
function solanawp_edge_to_edge_layout()
{
    echo '<style>
    /* EDGE-TO-EDGE LAYOUT OPTIMIZATION */
    body {
        margin: 0 !important;
        padding: 0 !important;
    }

    .site {
        width: 100vw;
        max-width: 100vw;
        overflow-x: hidden;
    }

    .site-content {
        width: 100%;
        max-width: 100%;
        margin: 0;
        padding: 0;
    }

    /* Ensure main container uses full width */
    .main-container {
        width: 100vw;
        max-width: 100vw;
        margin: 0;
        box-sizing: border-box;
    }

    /* Optimize for very wide screens */
    @media (min-width: 1600px) {
        .main-container {
            grid-template-columns: 320px 1fr 320px;
            gap: 16px;
            padding: 12px;
        }

        .ad-banner {
            height: 160px;
        }

        .ad-banner.small {
            height: 90px;
        }
    }

    /* Optimize for ultra-wide screens */
    @media (min-width: 2000px) {
        .main-container {
            grid-template-columns: 400px 1fr 400px;
            gap: 20px;
            padding: 16px;
        }

        .ad-banner {
            height: 180px;
        }

        .ad-banner.small {
            height: 100px;
        }
    }
    </style>';
}

// Remove the default "Hello World" post and sample page
add_action('wp_loaded', 'solanawp_remove_default_content');
function solanawp_remove_default_content()
{
    // Remove Hello World post
    $hello_post = get_posts(array(
        'title' => 'Hello world!',
        'post_status' => 'any',
        'numberposts' => 1
    ));

    if (!empty($hello_post)) {
        wp_delete_post($hello_post[0]->ID, true);
    }

    // Remove sample page
    $sample_page = get_posts(array(
        'title' => 'Sample Page',
        'post_type' => 'page',
        'post_status' => 'any',
        'numberposts' => 1
    ));

    if (!empty($sample_page)) {
        wp_delete_post($sample_page[0]->ID, true);
    }
}

/**
 * WordPress Dashboard Customizer Integration
 * Add to functions.php to enable customizer icon and ad banner management
 *
 * @package SolanaWP
 * @since SolanaWP 1.0.0
 */

// Add customizer icon to admin bar
add_action( 'admin_bar_menu', 'solanawp_add_customizer_to_admin_bar', 999 );
function solanawp_add_customizer_to_admin_bar( $wp_admin_bar ) {
    if ( ! current_user_can( 'customize' ) ) {
        return;
    }

    $wp_admin_bar->add_node( array(
        'id'    => 'solanawp-customize',
        'title' => '<span class="ab-icon dashicons dashicons-admin-customizer"></span>SolanaWP Options',
        'href'  => admin_url( 'customize.php?autofocus[panel]=solanawp_theme_options_panel' ),
        'meta'  => array(
            'title' => __( 'Customize SolanaWP Theme', 'solanawp' ),
        ),
    ) );
}

// Add customizer link to admin menu
add_action( 'admin_menu', 'solanawp_add_customizer_menu' );
function solanawp_add_customizer_menu() {
    add_theme_page(
        __( 'SolanaWP Theme Options', 'solanawp' ),
        __( 'Theme Options', 'solanawp' ),
        'customize',
        'customize.php?autofocus[panel]=solanawp_theme_options_panel'
    );
}

// Add customizer styles to admin
add_action( 'admin_head', 'solanawp_admin_customizer_styles' );
function solanawp_admin_customizer_styles() {
    echo '<style>
    #wp-admin-bar-solanawp-customize .ab-icon:before {
        content: "\f540";
        color: #3b82f6;
    }
    #wp-admin-bar-solanawp-customize:hover .ab-icon:before {
        color: #1d4ed8;
    }
    .appearance_page_customize #customize-info {
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    }
    </style>';
}

// Dynamic ad banner rendering function
function solanawp_render_customizer_ad_banners( $side = 'left', $count = 6 ) {
    $output = '';

    for ( $i = 1; $i <= $count; $i++ ) {
        $title = get_theme_mod( "solanawp_{$side}_ad_{$i}_title", '' );
        $desc = get_theme_mod( "solanawp_{$side}_ad_{$i}_desc", '' );
        $url = get_theme_mod( "solanawp_{$side}_ad_{$i}_url", '#' );
        $size = get_theme_mod( "solanawp_{$side}_ad_{$i}_size", 'large' );

        // Only render if title is set
        if ( ! empty( $title ) ) {
            $size_class = ( $size === 'small' ) ? 'small' : '';
            $link_attrs = ( $url !== '#' ) ? 'href="' . esc_url( $url ) . '" target="_blank" rel="noopener"' : '';

            $output .= '<div class="ad-banner ' . $size_class . '">';
            if ( $url !== '#' ) {
                $output .= '<a ' . $link_attrs . ' style="text-decoration: none; color: inherit;">';
            }
            $output .= '<div>';

            if ( $size === 'small' ) {
                $output .= '<div style="font-size: 16px; margin-bottom: 4px;">' . esc_html( $title ) . '</div>';
                if ( $desc ) {
                    $output .= '<div style="font-size: 14px;">' . esc_html( $desc ) . '</div>';
                }
            } else {
                $output .= '<div style="font-size: 18px; margin-bottom: 8px;">' . esc_html( $title ) . '</div>';
                if ( $desc ) {
                    $output .= '<div>' . esc_html( $desc ) . '</div>';
                }
            }

            $output .= '</div>';
            if ( $url !== '#' ) {
                $output .= '</a>';
            }
            $output .= '</div>';
        }
    }

    return $output;
}

// Shortcode for ad banners
add_shortcode( 'solanawp_ad_banners', 'solanawp_ad_banners_shortcode' );
function solanawp_ad_banners_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'side' => 'left',
        'count' => 6
    ), $atts );

    return solanawp_render_customizer_ad_banners( $atts['side'], intval( $atts['count'] ) );
}

// Add notice for users to customize ad banners
add_action( 'admin_notices', 'solanawp_customizer_notice' );
function solanawp_customizer_notice() {
    if ( ! current_user_can( 'customize' ) ) {
        return;
    }

    // Show notice only on dashboard and themes page
    $screen = get_current_screen();
    if ( ! in_array( $screen->id, array( 'dashboard', 'themes', 'appearance_page_customize' ) ) ) {
        return;
    }

    // Check if any ad banners are configured
    $has_ads = false;
    for ( $i = 1; $i <= 6; $i++ ) {
        if ( get_theme_mod( "solanawp_left_ad_{$i}_title", '' ) || get_theme_mod( "solanawp_right_ad_{$i}_title", '' ) ) {
            $has_ads = true;
            break;
        }
    }

    if ( ! $has_ads ) {
        ?>
        <div class="notice notice-info is-dismissible">
            <h3><?php _e( 'SolanaWP Theme Configuration', 'solanawp' ); ?></h3>
            <p><?php _e( 'Welcome to SolanaWP! Configure your ad banners and theme options to match your reference design.', 'solanawp' ); ?></p>
            <p>
                <a href="<?php echo admin_url( 'customize.php?autofocus[panel]=solanawp_theme_options_panel' ); ?>" class="button button-primary">
                    <?php _e( 'Customize Theme Options', 'solanawp' ); ?>
                </a>
                <a href="<?php echo admin_url( 'customize.php?autofocus[section]=solanawp_left_ads_section' ); ?>" class="button">
                    <?php _e( 'Configure Ad Banners', 'solanawp' ); ?>
                </a>
            </p>
        </div>
        <?php
    }
}

// Add theme setup hook for customizer registration
add_action( 'customize_register', 'solanawp_customize_register' );
add_action( 'customize_preview_init', 'solanawp_customize_preview_js' );
add_action( 'wp_head', 'solanawp_customizer_css_output' );

// Enhanced customizer CSS output with all new features
if ( ! function_exists( 'solanawp_enhanced_customizer_css_output' ) ) :
    function solanawp_enhanced_customizer_css_output() {
        $header_height = get_theme_mod( 'solanawp_header_height', 100 );
        $banner_distance = get_theme_mod( 'solanawp_banner_edge_distance', 24 );
        $analyzer_width = get_theme_mod( 'solanawp_analyzer_width', 100 );
        $primary_color = get_theme_mod( 'solanawp_primary_accent_color', '#3b82f6' ); // Default to lighter blue
        $secondary_color = get_theme_mod( 'solanawp_secondary_accent_color', '#8b5cf6' );

        $css = '<style type="text/css" id="solanawp-enhanced-customizer-css">';

        // Header height scaling
        if ( $header_height !== 100 ) {
            $scale = $header_height / 100;
            $css .= '
        .site-header .header {
            padding: ' . (16 * $scale) . 'px ' . (24 * $scale) . 'px;
        }
        .custom-logo-link img, .site-header .logo {
            width: ' . (80 * $scale) . 'px;
            height: ' . (80 * $scale) . 'px;
        }
        .site-header .brand-name {
            font-size: ' . (20 * $scale) . 'px;
            margin-bottom: ' . (8 * $scale) . 'px;
        }
        .site-header .logo-h {
            font-size: ' . (36 * $scale) . 'px;
        }
        .logo-container::after {
            width: ' . (60 * $scale) . 'px;
            height: ' . (3 * $scale) . 'px;
        }
        @keyframes logoGlow {
            0% { width: ' . (60 * $scale) . 'px; }
            100% { width: ' . (80 * $scale) . 'px; }
        }';
        }

        // Banner positioning
        if ( $banner_distance !== 24 ) {
            $css .= '
        .main-container {
            padding: ' . ($banner_distance * 4.7) . 'px ' . $banner_distance . 'px ' . $banner_distance . 'px ' . $banner_distance . 'px;
        }
        .sidebar {
            margin-left: ' . (-($banner_distance * 4)) . 'px;
        }
        .sidebar-right {
            margin-right: ' . (-($banner_distance * 4)) . 'px;
        }';
        }

        // Analyzer width
        if ( $analyzer_width !== 100 ) {
            $width_scale = $analyzer_width / 100;
            $side_width = 280 / $width_scale;
            $css .= '
        .main-container {
            grid-template-columns: ' . $side_width . 'px 1fr ' . $side_width . 'px;
        }
        .content-area {
            min-width: ' . (800 * $width_scale) . 'px;
        }
        .input-container {
            max-width: ' . (1000 * $width_scale) . 'px;
        }';
        }

        // Color customization
        $css .= '
    a:hover, .check-btn, .primary-menu li a:hover, .text-blue, .sol-balance-value,
    .address-input:focus {
        color: ' . esc_attr( $primary_color ) . ';
        border-color: ' . esc_attr( $primary_color ) . ';
    }
    .check-btn, .form-submit .submit, .read-more-button {
        background: linear-gradient(135deg, ' . esc_attr( $primary_color ) . ' 0%, ' . esc_attr( $secondary_color ) . ' 100%);
        box-shadow: 0 4px 16px ' . solanawp_hex_to_rgba($primary_color, 0.3) . ';
    }
    .check-btn:hover, .ad-banner:hover {
        border-color: ' . esc_attr( $primary_color ) . ';
        box-shadow: 0 8px 24px ' . solanawp_hex_to_rgba($primary_color, 0.4) . ';
    }
    .address-input:focus {
        box-shadow: 0 0 0 4px ' . solanawp_hex_to_rgba($primary_color, 0.1) . ';
    }
    .logo-container::after {
        background: linear-gradient(90deg, ' . esc_attr( $primary_color ) . ' 0%, ' . esc_attr( $secondary_color ) . ' 100%);
    }';

        $css .= '</style>';

        echo $css;
    }
endif;

// Replace the default CSS output with enhanced version
remove_action( 'wp_head', 'solanawp_customizer_css_output' );
add_action( 'wp_head', 'solanawp_enhanced_customizer_css_output' );

// Helper function for color conversion
if ( ! function_exists( 'solanawp_hex_to_rgba' ) ) :
    function solanawp_hex_to_rgba( $hex, $alpha = 1 ) {
        $hex = str_replace( '#', '', $hex );
        if ( strlen( $hex ) == 3 ) {
            $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
            $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
            $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
        } else {
            $r = hexdec( substr( $hex, 0, 2 ) );
            $g = hexdec( substr( $hex, 2, 2 ) );
            $b = hexdec( substr( $hex, 4, 2 ) );
        }
        return 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $alpha . ')';
    }
endif;

// Add dashboard widget for quick access
add_action( 'wp_dashboard_setup', 'solanawp_add_dashboard_widget' );
function solanawp_add_dashboard_widget() {
    wp_add_dashboard_widget(
        'solanawp_dashboard_widget',
        __( 'SolanaWP Theme Options', 'solanawp' ),
        'solanawp_dashboard_widget_content'
    );
}

function solanawp_dashboard_widget_content() {
    ?>
    <div style="text-align: center; padding: 20px;">
        <h3><?php _e( 'Customize Your Solana Address Checker', 'solanawp' ); ?></h3>
        <p><?php _e( 'Quickly access theme customization options to match your reference design.', 'solanawp' ); ?></p>

        <div style="margin: 20px 0;">
            <a href="<?php echo admin_url( 'customize.php?autofocus[section]=solanawp_layout_section' ); ?>" class="button button-primary" style="margin: 5px;">
                <?php _e( 'Layout & Design', 'solanawp' ); ?>
            </a>
            <a href="<?php echo admin_url( 'customize.php?autofocus[section]=solanawp_left_ads_section' ); ?>" class="button" style="margin: 5px;">
                <?php _e( 'Left Ad Banners', 'solanawp' ); ?>
            </a>
            <a href="<?php echo admin_url( 'customize.php?autofocus[section]=solanawp_right_ads_section' ); ?>" class="button" style="margin: 5px;">
                <?php _e( 'Right Ad Banners', 'solanawp' ); ?>
            </a>
            <a href="<?php echo admin_url( 'customize.php?autofocus[section]=solanawp_colors_section' ); ?>" class="button" style="margin: 5px;">
                <?php _e( 'Colors', 'solanawp' ); ?>
            </a>
        </div>

        <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin-top: 15px;">
            <strong><?php _e( 'Quick Tips:', 'solanawp' ); ?></strong>
            <ul style="text-align: left; margin: 10px 0;">
                <li><?php _e( 'Set Header Height to 50% for compact design', 'solanawp' ); ?></li>
                <li><?php _e( 'Use Banner Edge Distance of 113px for 3cm spacing', 'solanawp' ); ?></li>
                <li><?php _e( 'Increase Analyzer Width to 110% for wider frame', 'solanawp' ); ?></li>
                <li><?php _e( 'Set Primary Color to #3b82f6 for lighter blue', 'solanawp' ); ?></li>
            </ul>
        </div>
    </div>
    <?php
}
