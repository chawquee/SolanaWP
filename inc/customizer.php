<?php
/**
 * SolanaWP Theme Customizer.
 * Adds options to the WordPress Theme Customizer.
 *
 * @package SolanaWP
 * @since SolanaWP 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'solanawp_customize_register' ) ) :
    /**
     * Add settings, controls, and sections to the Theme Customizer.
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    function solanawp_customize_register( $wp_customize ) {

        // --- Site Identity Panel (Standard WordPress panel) ---
        // Enhance live preview for site title and description.
        $wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
        $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial(
                'blogname',
                array(
                    'selector'        => '.site-header .brand-name', // Target our specific brand name element
                    'render_callback' => 'solanawp_customize_partial_blogname',
                    'fallback_refresh' => false, // Important for elements that might be conditionally shown
                )
            );
            // If you use a separate .site-description element for the WP tagline:
            // $wp_customize->selective_refresh->add_partial(
            // 'blogdescription',
            // array(
            // 'selector'        => '.site-description',
            // 'render_callback' => 'solanawp_customize_partial_blogdescription',
            // 'fallback_refresh' => false,
            // )
            // );

            // Selective refresh for custom logo (targets both WP's link and our fallback structure)
            $wp_customize->selective_refresh->add_partial( 'custom_logo', array(
                'selector' => '.custom-logo-link, .site-header .logo-container .logo',
                'render_callback' => 'solanawp_customize_partial_custom_logo_or_fallback',
                'fallback_refresh' => false,
            ));
        }

        // --- SolanaWP Theme Options Panel ---
        // This panel will group all our custom theme sections.
        $wp_customize->add_panel( 'solanawp_theme_options_panel', array(
            'title'    => __( 'SolanaWP Theme Options', 'solanawp' ),
            'priority' => 30, // Adjust priority to position it in the Customizer
            'description' => __( 'Customize various aspects of the SolanaWP theme.', 'solanawp' ),
        ) );


        // --- Colors Section (Inside Theme Options Panel) ---
        // Allows customization of key colors from hannisolsvelte.html.
        $wp_customize->add_section(
            'solanawp_colors_section',
            array(
                'title'    => __( 'Theme Colors', 'solanawp' ),
                'panel'    => 'solanawp_theme_options_panel', // Assign to our custom panel
                'priority' => 10,
                'description' => __( 'Manage primary color schemes for the theme.', 'solanawp' ),
            )
        );

        // Setting: Primary Accent Color
        // Used for elements like .check-btn gradient start and link hovers.
        $wp_customize->add_setting(
            'solanawp_primary_accent_color',
            array(
                'default'           => '#7c3aed', // From .check-btn in hannisolsvelte.html
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage', // For live preview via JS
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'solanawp_primary_accent_color',
                array(
                    'label'   => __( 'Primary Accent Color', 'solanawp' ),
                    'description' => __( 'Main accent color for buttons, active links, and highlights.', 'solanawp'),
                    'section' => 'solanawp_colors_section',
                )
            )
        );

        // Setting: Secondary Accent Color
        // Used for elements like .check-btn gradient end.
        $wp_customize->add_setting(
            'solanawp_secondary_accent_color',
            array(
                'default'           => '#a855f7', // From .check-btn in hannisolsvelte.html
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'refresh', // Gradients are typically refreshed, not easily live-updated with simple postMessage.
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'solanawp_secondary_accent_color',
                array(
                    'label'   => __( 'Secondary Accent Color (Gradients)', 'solanawp' ),
                    'description' => __( 'Used as the secondary color in gradients, like the main "Check Address" button.', 'solanawp'),
                    'section' => 'solanawp_colors_section',
                )
            )
        );

        // --- Affiliate Links Section (Inside Theme Options Panel) ---
        // Manages links for the "Recommended Security Tools" section.
        // This supports the affiliate monetization strategy.
        $wp_customize->add_section( 'solanawp_affiliate_links_section', array(
            'title'       => __( 'Affiliate Links (Checker Page)', 'solanawp' ),
            'panel'       => 'solanawp_theme_options_panel', // Assign to our custom panel
            'priority'    => 20,
            'description' => __( 'Configure URLs for the "Recommended Security Tools" section displayed on the address checker page. These are used in template-parts/checker/results-affiliate.php.', 'solanawp'),
        ));

        $affiliate_items_config = array( // Based on items in hannisolsvelte.html and categories from solanacheckerplan.txt
            'ledger' => array(
                'label' => __('Ledger Wallet URL', 'solanawp'),
                'default' => '#' // Default placeholder URL
            ),
            'vpn'    => array(
                'label' => __('VPN Service URL', 'solanawp'),
                'default' => '#'
            ),
            'guide'  => array(
                'label' => __('Security Guide URL', 'solanawp'),
                'default' => '#'
            ),
            'course' => array(
                'label' => __('Crypto Course URL', 'solanawp'),
                'default' => '#'
            ),
        );

        $item_priority = 10;
        foreach ( $affiliate_items_config as $key => $config ) {
            $setting_id = "solanawp_affiliate_{$key}_url";
            $wp_customize->add_setting( $setting_id, array(
                'default'           => esc_url_raw( $config['default'] ),
                'transport'         => 'refresh', // Refresh is fine for URLs
                'sanitize_callback' => 'esc_url_raw', // Stores a raw URL
            ));
            $wp_customize->add_control( $setting_id, array(
                'label'    => $config['label'],
                'section'  => 'solanawp_affiliate_links_section',
                'type'     => 'url', // URL input field
                'priority' => $item_priority,
            ));
            $item_priority += 10;
        }

        // --- Footer Settings Section (Inside Theme Options Panel) ---
        $wp_customize->add_section('solanawp_footer_settings_section', array(
            'title'    => __('Footer Settings', 'solanawp'),
            'panel'    => 'solanawp_theme_options_panel',
            'priority' => 30,
        ));

        // Setting: Footer Copyright Text
        $wp_customize->add_setting('solanawp_footer_copyright_text', array(
            'default'           => sprintf( // Default copyright text
                esc_html__( '&copy; %1$s %2$s. All rights reserved. Theme by %3$s.', 'solanawp' ),
                date_i18n('Y'), // Current year, localized
                esc_html( get_bloginfo('name') ), // Site name
                '<a href="https://www.worldgpl.com/" target="_blank" rel="noopener">WORLDGPL</a>' // Author credit
            ),
            'sanitize_callback' => 'wp_kses_post', // Allows some HTML like links
            'transport'         => 'postMessage', // For live preview via JS
        ));
        $wp_customize->add_control('solanawp_footer_copyright_text', array(
            'label'       => __('Footer Copyright Text', 'solanawp'),
            'section'     => 'solanawp_footer_settings_section',
            'type'        => 'textarea', // Textarea for potentially longer text with HTML
        ));
        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial( 'solanawp_footer_copyright_text', array(
                'selector'        => '.site-footer .site-info', // Target the .site-info div in footer.php
                'render_callback' => 'solanawp_customize_partial_footer_copyright',
                'fallback_refresh' => false,
            ));
        }
    }
endif;

// --- Render Callbacks for Selective Refresh ---

if ( ! function_exists( 'solanawp_customize_partial_blogname' ) ) :
    /** Render the site title for the selective refresh partial. */
    function solanawp_customize_partial_blogname() {
        // In this theme, the .brand-name is hardcoded "HANNISOL" but could be made dynamic.
        // If you want the WP Site Title to be displayed and refresh, use bloginfo('name').
        // For now, let's assume .brand-name shows "HANNISOL" but if custom logo is off, it might show site title.
        // This callback needs to match what header.php outputs for the .brand-name or .site-title element.
        // The current header.php shows "HANNISOL" hardcoded in .brand-name.
        // If the site title from WP settings is what you want in .brand-name when no logo:
        // if (!has_custom_logo()) { bloginfo( 'name' ); } else { /* nothing or specific handling for logo */ }
        // Given current header.php, this partial might be more for a generic .site-title element
        bloginfo( 'name' );
    }
endif;

if ( ! function_exists( 'solanawp_customize_partial_blogdescription' ) ) :
    /** Render the site tagline for the selective refresh partial. */
    function solanawp_customize_partial_blogdescription() {
        bloginfo( 'description' );
    }
endif;

if ( ! function_exists( 'solanawp_customize_partial_custom_logo_or_fallback' ) ) :
    /** Render the custom logo or fallback for selective refresh. */
    function solanawp_customize_partial_custom_logo_or_fallback() {
        // This function must output the same HTML structure as in header.php's logo section
        // or template-parts/layout/header-branding.php
        ob_start();
        if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
            the_custom_logo();
        } else {
            // Fallback to the "H" logo structure from hannisolsvelte.html
            ?>
            <div class="logo">
                <div class="logo-h">H</div>
            </div>
            <?php
        }
        return ob_get_clean();
    }
endif;

if ( ! function_exists( 'solanawp_customize_partial_footer_copyright' ) ) :
    /** Render the footer copyright text for selective refresh. */
    function solanawp_customize_partial_footer_copyright() {
        $copyright_text = get_theme_mod( 'solanawp_footer_copyright_text', sprintf(
            esc_html__( '&copy; %1$s %2$s. All rights reserved. Theme by %3$s.', 'solanawp' ),
            date_i18n('Y'),
            esc_html( get_bloginfo('name') ),
            '<a href="https://www.worldgpl.com/" target="_blank" rel="noopener">WORLDGPL</a>'
        ) );
        // This needs to output the exact HTML structure within the .site-info div in footer.php for accurate refresh.
        // For simplicity, just echoing the mod. footer.php would need to be structured to only contain this.
        // A more robust way is to have footer.php call a function that gets this mod.
        echo wp_kses_post( $copyright_text );
    }
endif;


if ( ! function_exists( 'solanawp_customize_preview_js' ) ) :
    /**
     * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
     * Enqueues assets/js/customizer.js
     */
    function solanawp_customize_preview_js() {
        wp_enqueue_script(
            'solanawp-customizer-preview', // Handle
            get_template_directory_uri() . '/assets/js/customizer.js', // Path to customizer.js
            array( 'customize-preview', 'jquery' ), // Dependencies
            SOLANAWP_VERSION, // Version
            true // Load in footer
        );
    }
endif;

// --- Helper: Output Customizer CSS ---
if ( ! function_exists( 'solanawp_customizer_css_output' ) ) :
    /**
     * Generates and outputs CSS based on Theme Customizer settings.
     * This is hooked into wp_head.
     */
    function solanawp_customizer_css_output() {
        $primary_accent_color = get_theme_mod( 'solanawp_primary_accent_color', '#7c3aed' );
        $secondary_accent_color = get_theme_mod( 'solanawp_secondary_accent_color', '#a855f7' );
        // Add more theme_mod getters here for other color options if needed.

        // Start CSS output
        $css = '<style type="text/css" id="solanawp-customizer-css">';

        // Primary Accent Color applications
        if ( $primary_accent_color !== '#7c3aed' ) { // Only output if changed from default
            $css .= 'a, .primary-menu li a:hover, .primary-menu .current-menu-item > a, .primary-menu .current-menu-ancestor > a, .entry-title a:hover, .comment-metadata a:hover, .widget-area .widget ul li a:hover, .site-footer .site-info a:hover, .text-purple { color: ' . esc_attr( $primary_accent_color ) . '; }';
            $css .= '.check-btn, .form-submit .submit, .read-more-button { background: linear-gradient(135deg, ' . esc_attr( $primary_accent_color ) . ' 0%, ' . esc_attr( $secondary_accent_color ) . ' 100%); }'; // Gradient update
            $css .= '.check-btn:focus, .address-input:focus { border-color: ' . esc_attr( $primary_accent_color ) . '; box-shadow: 0 0 0 4px ' . esc_attr( solanawp_hex_to_rgba($primary_accent_color, 0.1) ) . '; }';
            $css .= '.the-posts-pagination .nav-links .page-numbers:hover, .the-posts-pagination .nav-links .page-numbers.current { background-color: ' . esc_attr( $primary_accent_color ) . '; border-color: ' . esc_attr( $primary_accent_color ) . '; }';
            $css .= '.reply .comment-reply-link:hover { background-color: ' . esc_attr( $primary_accent_color ) . '; border-color: ' . esc_attr( $primary_accent_color ) . '; }';
            $css .= '.comment-form input[type="text"]:focus, .comment-form input[type="email"]:focus, .comment-form input[type="url"]:focus, .comment-form textarea:focus { border-color: ' . esc_attr( $primary_accent_color ) . '; box-shadow: 0 0 0 3px ' . esc_attr( solanawp_hex_to_rgba($primary_accent_color, 0.1) ) . '; }';
            // Example for logo-h::before - more complex, might need specific handling if colors are dynamic
            // $css .= '.site-header .logo-h::before { background: linear-gradient(90deg, #00bcd4 0%, ' . esc_attr( $primary_accent_color ) . ' 100%); }';
        }

        // Secondary Accent Color applications (mostly for gradients with primary)
        if ( $secondary_accent_color !== '#a855f7' && $primary_accent_color === '#7c3aed' ) { // If only secondary changed
            $css .= '.check-btn, .form-submit .submit, .read-more-button { background: linear-gradient(135deg, #7c3aed 0%, ' . esc_attr( $secondary_accent_color ) . ' 100%); }';
        }


        // Add more dynamic CSS rules here for other Customizer settings
        // Example:
        // $header_bg_color = get_theme_mod('solanawp_header_bg_color');
        // if ($header_bg_color) {
        //     $css .= '.site-header .header { background-color: ' . esc_attr($header_bg_color) . '; }';
        // }

        $css .= '</style>';

        // Only output if there's custom CSS to add.
        if ( str_replace(array('<style type="text/css" id="solanawp-customizer-css">', '</style>'), '', $css) !== '' ) {
            echo $css; // Already escaped where needed
        }
    }
endif;

// Helper function to convert hex to rgba (used for box-shadow with opacity)
if ( ! function_exists( 'solanawp_hex_to_rgba' ) ) :
    function solanawp_hex_to_rgba( $hex, $alpha = 1 ) {
        $hex      = str_replace( '#', '', $hex );
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


// Action hooks for Customizer registration, JS preview, and CSS output.
// These will be called from the main SolanaWP/functions.php file.
// add_action( 'customize_register', 'solanawp_customize_register' );
// add_action( 'customize_preview_init', 'solanawp_customize_preview_js' );
// add_action( 'wp_head', 'solanawp_customizer_css_output' );
