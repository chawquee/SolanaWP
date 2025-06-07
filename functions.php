<?php
/**
 * SolanaWP functions and definitions
 * Version 5: Added dynamic CSS for new banner customization.
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
require get_template_directory() . '/inc/theme-setup.php';
if ( function_exists( 'solanawp_setup' ) ) {
    add_action( 'after_setup_theme', 'solanawp_setup' );
}

// --- Enqueue Scripts and Styles ---
require get_template_directory() . '/inc/enqueue.php';
if ( function_exists( 'solanawp_scripts_styles' ) ) {
    add_action( 'wp_enqueue_scripts', 'solanawp_scripts_styles' );
}
if ( function_exists( 'solanawp_admin_scripts_styles' ) ) {
    add_action( 'admin_enqueue_scripts', 'solanawp_admin_scripts_styles' );
}

// --- Widget Areas (Sidebars) ---
require get_template_directory() . '/inc/widget-areas.php';
if ( function_exists( 'solanawp_widgets_init' ) ) {
    add_action( 'widgets_init', 'solanawp_widgets_init' );
}

// --- Custom Widgets ---
require get_template_directory() . '/inc/custom-widgets.php';
if ( function_exists( 'solanawp_register_custom_widgets' ) ) {
    add_action( 'widgets_init', 'solanawp_register_custom_widgets' );
}

// --- Theme Customizer Additions ---
if (file_exists(get_template_directory() . '/inc/customizer.php')) {
    require get_template_directory() . '/inc/customizer.php';
    if ( function_exists( 'solanawp_customize_register' ) ) {
        add_action( 'customize_register', 'solanawp_customize_register' );
    }
    if ( function_exists( 'solanawp_customize_preview_js' ) ) {
        add_action( 'customize_preview_init', 'solanawp_customize_preview_js' );
    }
}


// --- Custom Nav Walker (if used) ---
require get_template_directory() . '/inc/navwalker.php';

// --- Custom Template Tags ---
require get_template_directory() . '/inc/template-tags.php';

// --- Template Functions ---
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
require get_template_directory() . '/inc/breadcrumbs.php';


if ( ! isset( $content_width ) ) {
    $content_width = 800;
}

// --- Content Hiding and Layout CSS (Existing functions, verified) ---
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

add_action('pre_get_posts', 'remove_default_posts_from_front_page');
function remove_default_posts_from_front_page($query) {
    if ($query->is_main_query() && is_front_page() && isset($query->is_posts_page) && $query->is_posts_page) {
        $query->set('post__in', array(0));
    }
}

add_action('wp_head', 'hannisol_center_alignment_css');
function hannisol_center_alignment_css() {
    echo '<style>
    .input-section { display: flex; justify-content: center; align-items: center; min-height: 120px; }
    .input-container { width: 100%; max-width: 800px; display: flex; justify-content: center; align-items: center; gap: 16px; }
    .main-container { display: grid; grid-template-columns: 300px 1fr 300px; gap: 24px; max-width: 1400px; margin: 0 auto; padding: 24px; align-items: start; }
    .sidebar, .sidebar-right { display: flex; flex-direction: column; gap: 16px; align-items: stretch; }
    .sidebar .ad-banner, .sidebar-right .ad-banner { height: 250px; width: 100%; box-sizing: border-box; }
    .sidebar .ad-banner.small, .sidebar-right .ad-banner.small { height: 120px; width: 100%; box-sizing: border-box; }
    .content-area { align-self: start; width: 100%; }
    .results-section { padding: 32px; width: 100%; max-width: 100%; margin: 0 auto; }
    </style>';
}

add_action('init', 'remove_default_hello_world_post');
function remove_default_hello_world_post() {
    $hello_world_post = get_posts(array('title' => 'Hello world!', 'post_status' => 'any', 'numberposts' => 1));
    if (!empty($hello_world_post)) {
        wp_delete_post($hello_world_post[0]->ID, true);
    }
}

add_action('wp_head', 'hannisol_responsive_improvements');
function hannisol_responsive_improvements() {
    echo '<style>
    @media (max-width: 1200px) { .main-container { grid-template-columns: 250px 1fr 250px; gap: 20px; padding: 20px; } }
    @media (max-width: 1024px) { .main-container { grid-template-columns: 1fr; gap: 16px; padding: 16px; } .sidebar, .sidebar-right { order: -1; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; } .content-area { order: 0; } .ad-banner { height: 120px !important; } }
    @media (max-width: 768px) { .sidebar, .sidebar-right { grid-template-columns: 1fr; } .main-container { padding: 12px; } .input-section { padding: 24px 16px; } .results-section { padding: 24px 16px; } }
    </style>';
}

add_action('after_setup_theme', 'solanawp_disable_navigation_menus', 20);
function solanawp_disable_navigation_menus() {
    remove_theme_support('menus');
    global $_wp_registered_nav_menus;
    $_wp_registered_nav_menus = array();
}

add_action('wp_head', 'solanawp_force_hide_navigation', 999);
function solanawp_force_hide_navigation() {
    echo '<style>
    .main-navigation, .site-navigation, #site-navigation, .primary-menu, #primary-menu, .menu, nav, .nav, .navigation, .nav-menu, .navigation-menu, .menu-toggle, .menu-item, .nav-links, .navigation-links, ul.menu, ol.menu, .wp-block-navigation, .wp-block-navigation__container, .wp-block-navigation-link, .has-child .wp-block-navigation__submenu-container, [role="navigation"], .navbar, .nav-bar, .top-menu, .header-menu, .header-navigation, .site-header nav, .site-header .menu, .site-header ul, .site-header ol, header nav, header .menu, header ul:not(.no-hide), header ol:not(.no-hide), .menu-primary-container, .menu-header-container, .menu-main-container, #menu-primary, #menu-header, #menu-main, .primary-navigation, .header-nav, .main-nav, .top-nav { display: none !important; visibility: hidden !important; height: 0 !important; width: 0 !important; overflow: hidden !important; opacity: 0 !important; position: absolute !important; left: -9999px !important; top: -9999px !important; }
    .site-header ul, .site-header ol, header ul, header ol { list-style: none !important; margin: 0 !important; padding: 0 !important; }
    /* .site-header *::before, .site-header *::after { content: "" !important; } */
    .site-header .menu *, .site-header nav *, header .menu *, header nav * { display: none !important; }
    </style>';
}

add_filter('wp_nav_menu', '__return_empty_string', 999);
add_filter('wp_nav_menu_args', '__return_empty_array', 999);

add_action('init', 'solanawp_remove_navigation_locations');
function solanawp_remove_navigation_locations() {
    unregister_nav_menu('primary'); unregister_nav_menu('footer'); unregister_nav_menu('header'); unregister_nav_menu('main'); unregister_nav_menu('secondary'); unregister_nav_menu('social');
}

add_action('customize_register', 'solanawp_remove_menu_customizer_section', 20);
function solanawp_remove_menu_customizer_section($wp_customize) {
    $wp_customize->remove_section('nav');
    $wp_customize->remove_panel('nav_menus');
}

add_action('admin_menu', 'solanawp_hide_menu_admin_pages', 999);
function solanawp_hide_menu_admin_pages() {
    remove_submenu_page('themes.php', 'nav-menus.php');
}

add_action('wp_head', 'solanawp_enhanced_content_hiding');
function solanawp_enhanced_content_hiding() {
    if (is_front_page() || is_page_template('templates/template-address-checker.php')) {
        echo '<style>
        .front-page-content-area .hentry, .front-page-content-area article, .front-page-content-area .post, .front-page-content-area .entry-content, .front-page-content-area .page-content, .address-checker-content .entry-content, .address-checker-content .page-content, body.home .site-main article, body.home .site-main .post, body.page-template-address-checker .entry-content, body.page-template-address-checker .page-content, .page-intro-content, .solanawp-page-content .entry-content, .wp-block-post-content, .entry-summary, .post-content, .page-content p, .entry-content p:first-child, article.post, article.page, .type-post, .type-page { display: none !important; visibility: hidden !important; height: 0 !important; overflow: hidden !important; position: absolute !important; left: -9999px !important; }
        .input-section, .results-section, #resultsSection, .solanawp-checker-main, .checker-input-section, .address-checker-content .input-section, .address-checker-content .results-section { display: block !important; visibility: visible !important; position: static !important; height: auto !important; overflow: visible !important; }
        </style>';
    }
}

add_action('pre_get_posts', 'solanawp_remove_front_page_content');
function solanawp_remove_front_page_content($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_front_page() || is_home()) {
            $query->set('post__in', array(0));
            $query->set('posts_per_page', 0);
        }
    }
}

add_action('wp_head', 'solanawp_edge_to_edge_layout');
function solanawp_edge_to_edge_layout() {
    echo '<style>
    body { margin: 0 !important; padding: 0 !important; }
    .site { width: 100vw; max-width: 100vw; overflow-x: hidden; }
    .site-content { width: 100%; max-width: 100%; margin: 0; padding: 0; }
    .main-container { width: 100vw; max-width: 100vw; margin: 0; box-sizing: border-box; }
    @media (min-width: 1600px) { .main-container { grid-template-columns: 320px 1fr 320px; gap: 16px; padding: 12px; } .ad-banner { height: 160px; } .ad-banner.small { height: 90px; } }
    @media (min-width: 2000px) { .main-container { grid-template-columns: 400px 1fr 400px; gap: 20px; padding: 16px; } .ad-banner { height: 180px; } .ad-banner.small { height: 100px; } }
    </style>';
}

add_action('wp_loaded', 'solanawp_remove_default_content');
function solanawp_remove_default_content() {
    $hello_post = get_posts(array('title' => 'Hello world!', 'post_status' => 'any', 'numberposts' => 1));
    if (!empty($hello_post)) { wp_delete_post($hello_post[0]->ID, true); }
    $sample_page = get_posts(array('title' => 'Sample Page', 'post_type' => 'page', 'post_status' => 'any', 'numberposts' => 1));
    if (!empty($sample_page)) { wp_delete_post($sample_page[0]->ID, true); }
}

// --- WordPress Dashboard Customizer Integration ---
add_action( 'admin_bar_menu', 'solanawp_add_customizer_to_admin_bar', 999 );
function solanawp_add_customizer_to_admin_bar( $wp_admin_bar ) {
    if ( ! current_user_can( 'customize' ) ) { return; }
    $wp_admin_bar->add_node( array(
        'id'    => 'solanawp-customize',
        'title' => '<span class="ab-icon dashicons dashicons-admin-customizer"></span>SolanaWP Options',
        'href'  => admin_url( 'customize.php?autofocus[panel]=solanawp_theme_options_panel' ),
        'meta'  => array( 'title' => __( 'Customize SolanaWP Theme', 'solanawp' ), ),
    ) );
}

add_action( 'admin_menu', 'solanawp_add_customizer_menu' );
function solanawp_add_customizer_menu() {
    add_theme_page(
        __( 'SolanaWP Theme Options', 'solanawp' ),
        __( 'Theme Options', 'solanawp' ),
        'customize',
        'customize.php?autofocus[panel]=solanawp_theme_options_panel'
    );
}

add_action( 'admin_head', 'solanawp_admin_customizer_styles' );
function solanawp_admin_customizer_styles() {
    echo '<style> #wp-admin-bar-solanawp-customize .ab-icon:before { content: "\f540"; color: #3b82f6; } #wp-admin-bar-solanawp-customize:hover .ab-icon:before { color: #1d4ed8; } .appearance_page_customize #customize-info { background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); } </style>';
}

// --- Dynamic Ad Banner Rendering Function (Updated for Unified Ads) ---
function solanawp_render_customizer_ad_banners( $count = 6 ) {
    $output = '';
    $ad_section_slug = 'solanawp_sidebar_ads_section';

    for ( $i = 1; $i <= $count; $i++ ) {
        $title = get_theme_mod( "solanawp_sidebar_ad_{$i}_title", '' );
        $desc = get_theme_mod( "solanawp_sidebar_ad_{$i}_desc", '' );
        $url = get_theme_mod( "solanawp_sidebar_ad_{$i}_url", '#' );
        $size = get_theme_mod( "solanawp_sidebar_ad_{$i}_size", 'large' );

        if ( ! empty( $title ) ) {
            $size_class = ( $size === 'small' ) ? 'small' : '';
            $link_attrs = ( $url !== '#' && filter_var($url, FILTER_VALIDATE_URL) ) ? 'href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer sponsored"' : '';

            $output .= '<div class="ad-banner ' . esc_attr( $size_class ) . '">';
            if ( $link_attrs ) {
                $output .= '<a ' . $link_attrs . ' style="text-decoration: none; color: inherit; display: block; width: 100%; height: 100%;">';
            }
            $output .= '<div style="padding: 10px;">';

            if ( $size === 'small' ) {
                $output .= '<div style="font-size: 16px; margin-bottom: 4px; font-weight: bold;">' . esc_html( $title ) . '</div>';
                if ( $desc ) { $output .= '<div style="font-size: 14px;">' . esc_html( $desc ) . '</div>'; }
            } else {
                $output .= '<div style="font-size: 18px; margin-bottom: 8px; font-weight: bold;">' . esc_html( $title ) . '</div>';
                if ( $desc ) { $output .= '<div>' . esc_html( $desc ) . '</div>'; }
            }
            $output .= '</div>';
            if ( $link_attrs ) { $output .= '</a>'; }

            if ( current_user_can( 'customize' ) ) {
                $customizer_link = admin_url( 'customize.php?autofocus[section]=' . $ad_section_slug );
                $output .= '<div class="admin-configure-ad-link" style="position: absolute; bottom: 5px; right: 5px; background: rgba(0,0,0,0.5); color: white; padding: 2px 5px; font-size: 10px; border-radius: 3px; line-height:1;">';
                $output .= '<a href="' . esc_url( $customizer_link ) . '" style="color: white; text-decoration: none;" title="' . esc_attr__('Configure Sidebar Ads', 'solanawp') . '">&#9881;</a>';
                $output .= '</div>';
            }
            $output .= '</div>';
        }
    }
    return $output;
}


add_shortcode( 'solanawp_ad_banners', 'solanawp_ad_banners_shortcode' );
function solanawp_ad_banners_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'count' => 6 ), $atts );
    return solanawp_render_customizer_ad_banners( intval( $atts['count'] ) );
}

// --- Admin Notices and Dashboard Widget ---
add_action( 'admin_notices', 'solanawp_customizer_notice' );
function solanawp_customizer_notice() {
    if (!current_user_can('customize')) { return; }
    $screen = get_current_screen();
    if (!in_array($screen->id, array('dashboard', 'themes', 'appearance_page_customize'))) { return; }
    $has_ads = false;
    for ($i = 1; $i <= 6; $i++) {
        if (get_theme_mod("solanawp_sidebar_ad_{$i}_title", '')) {
            $has_ads = true;
            break;
        }
    }
    if (!$has_ads) {
        echo '<div class="notice notice-info is-dismissible"><h3>' . esc_html__('SolanaWP Theme Configuration', 'solanawp') . '</h3><p>' . esc_html__('Welcome to SolanaWP! Configure your ad banners and theme options to match your reference design.', 'solanawp') . '</p><p><a href="' . esc_url(admin_url('customize.php?autofocus[panel]=solanawp_theme_options_panel')) . '" class="button button-primary">' . esc_html__('Customize Theme Options', 'solanawp') . '</a> <a href="' . esc_url(admin_url('customize.php?autofocus[section]=solanawp_sidebar_ads_section')) . '" class="button">' . esc_html__('Configure Sidebar Ad Banners', 'solanawp') . '</a></p></div>';
    }
}

// --- Enhanced Customizer CSS Output ---
if ( ! function_exists( 'solanawp_enhanced_customizer_css_output' ) ) :
    function solanawp_enhanced_customizer_css_output() {
        $header_height_scale = get_theme_mod( 'solanawp_header_height', 100 );
        $logo_size = get_theme_mod( 'solanawp_logo_size', 80 );
        $brand_name_font_size = get_theme_mod( 'solanawp_brand_name_font_size', 20 );
        $banner_distance = get_theme_mod( 'solanawp_banner_edge_distance', 24 );
        $analyzer_width_scale = get_theme_mod( 'solanawp_analyzer_width', 100 );
        $primary_color = get_theme_mod( 'solanawp_primary_accent_color', '#3b82f6' );
        $secondary_color = get_theme_mod( 'solanawp_secondary_accent_color', '#8b5cf6' );

        $css = '<style type="text/css" id="solanawp-enhanced-customizer-css">';

        // --- Loop for Hero and Analyzer Banners ---
        $banners_to_style = array(
            'hero' => array('selector' => '.hero-sub-banner', 'prefix' => 'solanawp_hero'),
            'analyzer' => array('selector' => '.solana-coins-analyzer-section', 'prefix' => 'solanawp_analyzer')
        );

        foreach ($banners_to_style as $key => $config) {
            $bg_type = get_theme_mod("{$config['prefix']}_bg_type", 'color');
            $bg_color = get_theme_mod("{$config['prefix']}_bg_color", ($key === 'hero' ? '#1e3a8a' : '#ffffff'));
            $bg_image = get_theme_mod("{$config['prefix']}_bg_image", '');

            if ($bg_type === 'image' && !empty($bg_image)) {
                $css .= "{$config['selector']} { background-image: url(" . esc_url($bg_image) . "); background-size: cover; background-position: center; }";
            } else {
                $css .= "{$config['selector']} { background-color: " . esc_attr($bg_color) . "; }";
            }

            // Font styles
            $font_family = get_theme_mod("{$config['prefix']}_font_family", 'inherit');
            $font_size = get_theme_mod("{$config['prefix']}_font_size", '');
            $font_color = get_theme_mod("{$config['prefix']}_font_color", ($key === 'hero' ? '#ffffff' : '#111827'));

            $text_selector = "{$config['selector']} h2, {$config['selector']} p, {$config['selector']} div";
            $css .= "{$text_selector} {";
            if ($font_family !== 'inherit') { $css .= "font-family: " . esc_attr($font_family) . " !important;"; }
            if (!empty($font_color)) { $css .= "color: " . esc_attr($font_color) . " !important;"; }
            $css .= "}";

            if (!empty($font_size)) {
                $main_text_selector = "{$config['selector']} h2";
                $sub_text_selector = "{$config['selector']} p, {$config['selector']} div:not([class*='container'])";
                $css .= "{$main_text_selector} { font-size: " . esc_attr($font_size) . "px !important; }";
                $css .= "{$sub_text_selector} { font-size: " . esc_attr(floor($font_size * 0.7)) . "px !important; }";
            }
        }

        // --- General Layout Styles ---
        $default_gap = '20px';
        $css .= '.site-header .header { margin-bottom: ' . $default_gap . '; }';
        $css .= '.hero-sub-banner { margin-bottom: ' . $default_gap . '; }';

        if ( $header_height_scale !== 100 ) {
            $scale = $header_height_scale / 100;
            $css .= '.site-header .header { padding: ' . (16 * $scale) . 'px ' . (24 * $scale) . 'px; }';
            $css .= '.site-header .logo-h { font-size: ' . (36 * $scale) . 'px; }';
            $css .= '.logo-container::after { width: ' . (60 * $scale) . 'px; height: ' . (3 * $scale) . 'px; }';
            $css .= '@keyframes logoGlow { 0% { width: ' . (60 * $scale) . 'px; } 100% { width: ' . (80 * $scale) . 'px; } }';
        }
        if ( $logo_size !== 80 || $header_height_scale !== 100 ) {
            $css .= '.custom-logo-link img, .site-header .logo { width: ' . esc_attr( $logo_size ) . 'px; height: ' . esc_attr( $logo_size ) . 'px; }';
        }
        if ( $brand_name_font_size !== 20 || $header_height_scale !== 100 ) {
            $css .= '.site-header .brand-name, .site-header .brand-name a { font-size: ' . esc_attr( $brand_name_font_size ) . 'px; }';
        }
        if ( $banner_distance !== 24 ) {
            $css .= '.main-container { padding: ' . esc_attr($banner_distance) . 'px; }';
        }
        if ( $analyzer_width_scale !== 100 ) {
            $width_scale_factor = $analyzer_width_scale / 100;
            $css .= '.content-area { min-width: calc(800px * ' . esc_attr($width_scale_factor) . '); }';
            $css .= '.input-container { max-width: calc(1000px * ' . esc_attr($width_scale_factor) . '); }';
        }
        $css .= '
        :root {
            --solanawp-primary-accent-color: ' . esc_attr( $primary_color ) . ';
            --solanawp-secondary-accent-color: ' . esc_attr( $secondary_color ) . ';
        }
        a:hover, .primary-menu li a:hover, .primary-menu .current-menu-item > a, .text-blue, .sol-balance-value,
        .widget-area .widget ul li a:hover, .site-footer .site-info a:hover, .reply .comment-reply-link,
        .solanawp-breadcrumbs a:hover {
            color: var(--solanawp-primary-accent-color);
        }
        .check-btn, .form-submit .submit, .read-more-button,
        .the-posts-pagination .nav-links .page-numbers.current,
        .the-posts-pagination .nav-links .page-numbers:hover,
        .widget-area .widget_search .search-submit {
            background: linear-gradient(135deg, var(--solanawp-primary-accent-color) 0%, var(--solanawp-secondary-accent-color) 100%);
            border-color: var(--solanawp-primary-accent-color);
            color: #ffffff;
            box-shadow: 0 4px 16px ' . solanawp_hex_to_rgba($primary_color, 0.3) . ';
        }
        .check-btn:hover, .form-submit .submit:hover, .read-more-button:hover,
        .widget-area .widget_search .search-submit:hover {
            background: linear-gradient(135deg, ' . solanawp_adjust_brightness($primary_color, -20) . ' 0%, ' . solanawp_adjust_brightness($secondary_color, -20) . ' 100%);
            box-shadow: 0 8px 24px ' . solanawp_hex_to_rgba($primary_color, 0.4) . ';
        }
        .ad-banner:hover {
            border-color: var(--solanawp-primary-accent-color);
            box-shadow: 0 8px 24px ' . solanawp_hex_to_rgba($primary_color, 0.4) . ';
        }
        .address-input:focus,
        .comment-form input[type="text"]:focus, .comment-form input[type="email"]:focus,
        .comment-form input[type="url"]:focus, .comment-form textarea:focus {
            border-color: var(--solanawp-primary-accent-color);
            box-shadow: 0 0 0 4px ' . solanawp_hex_to_rgba($primary_color, 0.1) . ';
        }
        .logo-container::after {
            background: linear-gradient(90deg, var(--solanawp-primary-accent-color) 0%, var(--solanawp-secondary-accent-color) 100%);
        }';
        $css .= '</style>';
        if (str_replace(array('<style type="text/css" id="solanawp-enhanced-customizer-css">', '</style>'), '', trim($css)) !== '') {
            echo $css;
        }
    }
endif;
remove_action( 'wp_head', 'solanawp_customizer_css_output' );
add_action( 'wp_head', 'solanawp_enhanced_customizer_css_output' );

// --- Helper functions for color manipulation ---
if ( ! function_exists( 'solanawp_hex_to_rgba' ) ) :
    function solanawp_hex_to_rgba( $hex, $alpha = 1 ) {
        $hex = str_replace( '#', '', $hex );
        if ( strlen( $hex ) == 3 ) { $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) ); $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) ); $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) ); }
        else { $r = hexdec( substr( $hex, 0, 2 ) ); $g = hexdec( substr( $hex, 2, 2 ) ); $b = hexdec( substr( $hex, 4, 2 ) ); }
        return 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $alpha . ')';
    }
endif;
if ( ! function_exists( 'solanawp_adjust_brightness' ) ) :
    function solanawp_adjust_brightness($hex, $steps) {
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));
        return '#'.str_pad(dechex($r), 2, '0', STR_PAD_LEFT)
            .str_pad(dechex($g), 2, '0', STR_PAD_LEFT)
            .str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
    }
endif;

add_action( 'wp_dashboard_setup', 'solanawp_add_dashboard_widget' );
function solanawp_add_dashboard_widget() {
    wp_add_dashboard_widget('solanawp_dashboard_widget', __('SolanaWP Theme Options', 'solanawp'), 'solanawp_dashboard_widget_content');
}
function solanawp_dashboard_widget_content() {
    echo '<div style="text-align: center; padding: 20px;"><h3>' . esc_html__('Customize Your Solana Address Checker', 'solanawp') . '</h3><p>' . esc_html__('Quickly access theme customization options to match your reference design.', 'solanawp') . '</p><div style="margin: 20px 0;"><a href="' . esc_url(admin_url('customize.php?autofocus[section]=solanawp_layout_section')) . '" class="button button-primary" style="margin: 5px;">' . esc_html__('Layout & Design', 'solanawp') . '</a> <a href="' . esc_url(admin_url('customize.php?autofocus[section]=solanawp_sidebar_ads_section')) . '" class="button" style="margin: 5px;">' . esc_html__('Sidebar Ad Banners', 'solanawp') . '</a> <a href="' . esc_url(admin_url('customize.php?autofocus[section]=solanawp_colors_section')) . '" class="button" style="margin: 5px;">' . esc_html__('Accent Colors', 'solanawp') . '</a><a href="' . esc_url(admin_url('customize.php?autofocus[section]=solanawp_hero_banner_section')) . '" class="button" style="margin: 5px;">' . esc_html__('Platform Banner', 'solanawp') . '</a></div><div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin-top: 15px;"><strong>' . esc_html__('Quick Tips:', 'solanawp') . '</strong><ul style="text-align: left; margin: 10px 0;"><li>' . esc_html__('Set Header Height to 50% for compact design', 'solanawp') . '</li><li>' . esc_html__('Use Banner Edge Distance of 113px for 3cm spacing', 'solanawp') . '</li><li>' . esc_html__('Increase Analyzer Width to 110% for wider frame', 'solanawp') . '</li><li>' . esc_html__('Set Primary Color to #3b82f6 for lighter blue', 'solanawp') . '</li></ul></div></div>';
}

// Function to get the right sidebar (uses unified ad rendering)
if ( ! function_exists( 'solanawp_get_right_sidebar' ) ) {
    function solanawp_get_right_sidebar() {
        ob_start();
        ?>
        <aside id="secondary-right" class="widget-area sidebar-right" role="complementary">
            <?php
            // Display customizer ad banners (unified settings)
            $customizer_ads_output = solanawp_render_customizer_ad_banners( 6 ); // Render up to 6 ads
            echo $customizer_ads_output;

            // Fallback content if no customizer ads are configured AND user can customize.
            // This ensures the placeholder only shows when truly no ads are set up by an admin.
            if ( empty(trim($customizer_ads_output)) && current_user_can( 'customize' ) ) :
                ?>
                <div class="ad-banner" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 2px solid #3b82f6; display:flex; align-items:center; justify-content:center;">
                    <div style="text-align:center;">
                        <div style="font-size: 16px; margin-bottom: 8px;">⚙️ <?php esc_html_e('Setup Sidebar Ads', 'solanawp'); ?></div>
                        <div style="font-size: 14px;">
                            <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=solanawp_sidebar_ads_section' ) ); ?>" style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                                <?php esc_html_e('Click to Configure Unified Ads','solanawp'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php
            endif;
            ?>
        </aside>
        <?php
        return ob_get_clean();
    }
}
?>
