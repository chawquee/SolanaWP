<?php
/**
 * Enhanced SolanaWP Theme Customizer with Ad Banner Controls
 * Updated to match reference images and add ad customization
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

        // --- Enhanced Site Identity Panel ---
        $wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
        $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial(
                'blogname',
                array(
                    'selector'        => '.site-header .brand-name',
                    'render_callback' => 'solanawp_customize_partial_blogname',
                    'fallback_refresh' => false,
                )
            );

            $wp_customize->selective_refresh->add_partial( 'custom_logo', array(
                'selector' => '.custom-logo-link, .site-header .logo-container .logo',
                'render_callback' => 'solanawp_customize_partial_custom_logo_or_fallback',
                'fallback_refresh' => false,
            ));
        }

        // --- SolanaWP Theme Options Panel ---
        $wp_customize->add_panel( 'solanawp_theme_options_panel', array(
            'title'    => __( 'SolanaWP Theme Options', 'solanawp' ),
            'priority' => 30,
            'description' => __( 'Customize all aspects of your Solana address checker theme.', 'solanawp' ),
        ) );

        // --- Layout & Design Section ---
        $wp_customize->add_section(
            'solanawp_layout_section',
            array(
                'title'    => __( 'Layout & Design', 'solanawp' ),
                'panel'    => 'solanawp_theme_options_panel',
                'priority' => 5,
                'description' => __( 'Control header height, spacing, and layout options.', 'solanawp' ),
            )
        );

        // Header Height Control
        $wp_customize->add_setting(
            'solanawp_header_height',
            array(
                'default'           => '100',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            )
        );
        $wp_customize->add_control(
            'solanawp_header_height',
            array(
                'label'   => __( 'Header Height (%)', 'solanawp' ),
                'description' => __( 'Reduce to 50% for compact header as shown in reference.', 'solanawp'),
                'section' => 'solanawp_layout_section',
                'type'    => 'range',
                'input_attrs' => array(
                    'min'  => 30,
                    'max'  => 150,
                    'step' => 10,
                ),
            )
        );

        // Banner Edge Distance
        $wp_customize->add_setting(
            'solanawp_banner_edge_distance',
            array(
                'default'           => '24',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            )
        );
        $wp_customize->add_control(
            'solanawp_banner_edge_distance',
            array(
                'label'   => __( 'Banner Edge Distance (px)', 'solanawp' ),
                'description' => __( 'Distance of ad banners from screen edge (3cm ≈ 113px).', 'solanawp'),
                'section' => 'solanawp_layout_section',
                'type'    => 'number',
                'input_attrs' => array(
                    'min'  => 8,
                    'max'  => 150,
                    'step' => 4,
                ),
            )
        );

        // Analyzer Frame Width
        $wp_customize->add_setting(
            'solanawp_analyzer_width',
            array(
                'default'           => '100',
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            )
        );
        $wp_customize->add_control(
            'solanawp_analyzer_width',
            array(
                'label'   => __( 'Analyzer Frame Width (%)', 'solanawp' ),
                'description' => __( 'Make analyzer frame wider as requested.', 'solanawp'),
                'section' => 'solanawp_layout_section',
                'type'    => 'range',
                'input_attrs' => array(
                    'min'  => 80,
                    'max'  => 120,
                    'step' => 5,
                ),
            )
        );

        // --- Colors Section ---
        $wp_customize->add_section(
            'solanawp_colors_section',
            array(
                'title'    => __( 'Theme Colors', 'solanawp' ),
                'panel'    => 'solanawp_theme_options_panel',
                'priority' => 10,
                'description' => __( 'Customize theme colors including lighter blue option.', 'solanawp' ),
            )
        );

        // Primary Accent Color (with lighter blue option)
        $wp_customize->add_setting(
            'solanawp_primary_accent_color',
            array(
                'default'           => '#7c3aed', // Can be changed to lighter blue
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'solanawp_primary_accent_color',
                array(
                    'label'   => __( 'Primary Accent Color', 'solanawp' ),
                    'description' => __( 'Use lighter blue (#3b82f6) as suggested in reference.', 'solanawp'),
                    'section' => 'solanawp_colors_section',
                )
            )
        );

        // Secondary Accent Color
        $wp_customize->add_setting(
            'solanawp_secondary_accent_color',
            array(
                'default'           => '#a855f7',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'refresh',
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'solanawp_secondary_accent_color',
                array(
                    'label'   => __( 'Secondary Accent Color', 'solanawp' ),
                    'section' => 'solanawp_colors_section',
                )
            )
        );

        // --- Left Sidebar Ad Banners Section ---
        $wp_customize->add_section( 'solanawp_left_ads_section', array(
            'title'       => __( 'Left Sidebar Ad Banners', 'solanawp' ),
            'panel'       => 'solanawp_theme_options_panel',
            'priority'    => 20,
            'description' => __( 'Customize ad banners in the left sidebar.', 'solanawp'),
        ));

        // Left Ad Banner 1
        for ($i = 1; $i <= 6; $i++) {
            // Ad Title
            $wp_customize->add_setting( "solanawp_left_ad_{$i}_title", array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ));
            $wp_customize->add_control( "solanawp_left_ad_{$i}_title", array(
                'label'    => sprintf(__('Left Ad %d - Title', 'solanawp'), $i),
                'section'  => 'solanawp_left_ads_section',
                'type'     => 'text',
            ));

            // Ad Description
            $wp_customize->add_setting( "solanawp_left_ad_{$i}_desc", array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ));
            $wp_customize->add_control( "solanawp_left_ad_{$i}_desc", array(
                'label'    => sprintf(__('Left Ad %d - Description', 'solanawp'), $i),
                'section'  => 'solanawp_left_ads_section',
                'type'     => 'text',
            ));

            // Ad URL
            $wp_customize->add_setting( "solanawp_left_ad_{$i}_url", array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'postMessage',
            ));
            $wp_customize->add_control( "solanawp_left_ad_{$i}_url", array(
                'label'    => sprintf(__('Left Ad %d - URL', 'solanawp'), $i),
                'section'  => 'solanawp_left_ads_section',
                'type'     => 'url',
            ));

            // Ad Size
            $wp_customize->add_setting( "solanawp_left_ad_{$i}_size", array(
                'default'           => 'large',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ));
            $wp_customize->add_control( "solanawp_left_ad_{$i}_size", array(
                'label'    => sprintf(__('Left Ad %d - Size', 'solanawp'), $i),
                'section'  => 'solanawp_left_ads_section',
                'type'     => 'select',
                'choices'  => array(
                    'large' => __('Large (250px)', 'solanawp'),
                    'small' => __('Small (120px)', 'solanawp'),
                ),
            ));

            // Separator
            if ($i < 6) {
                $wp_customize->add_setting( "solanawp_left_ad_{$i}_separator", array(
                    'sanitize_callback' => 'sanitize_text_field',
                ));
                $wp_customize->add_control( new WP_Customize_Control( $wp_customize, "solanawp_left_ad_{$i}_separator", array(
                    'label'       => '',
                    'section'     => 'solanawp_left_ads_section',
                    'type'        => 'hidden',
                    'description' => '<hr style="margin: 20px 0;">',
                )));
            }
        }

        // --- Right Sidebar Ad Banners Section ---
        $wp_customize->add_section( 'solanawp_right_ads_section', array(
            'title'       => __( 'Right Sidebar Ad Banners', 'solanawp' ),
            'panel'       => 'solanawp_theme_options_panel',
            'priority'    => 25,
            'description' => __( 'Customize ad banners in the right sidebar.', 'solanawp'),
        ));

        // Right Ad Banners (similar structure)
        for ($i = 1; $i <= 6; $i++) {
            // Ad Title
            $wp_customize->add_setting( "solanawp_right_ad_{$i}_title", array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ));
            $wp_customize->add_control( "solanawp_right_ad_{$i}_title", array(
                'label'    => sprintf(__('Right Ad %d - Title', 'solanawp'), $i),
                'section'  => 'solanawp_right_ads_section',
                'type'     => 'text',
            ));

            // Ad Description
            $wp_customize->add_setting( "solanawp_right_ad_{$i}_desc", array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ));
            $wp_customize->add_control( "solanawp_right_ad_{$i}_desc", array(
                'label'    => sprintf(__('Right Ad %d - Description', 'solanawp'), $i),
                'section'  => 'solanawp_right_ads_section',
                'type'     => 'text',
            ));

            // Ad URL
            $wp_customize->add_setting( "solanawp_right_ad_{$i}_url", array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'postMessage',
            ));
            $wp_customize->add_control( "solanawp_right_ad_{$i}_url", array(
                'label'    => sprintf(__('Right Ad %d - URL', 'solanawp'), $i),
                'section'  => 'solanawp_right_ads_section',
                'type'     => 'url',
            ));

            // Ad Size
            $wp_customize->add_setting( "solanawp_right_ad_{$i}_size", array(
                'default'           => 'large',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ));
            $wp_customize->add_control( "solanawp_right_ad_{$i}_size", array(
                'label'    => sprintf(__('Right Ad %d - Size', 'solanawp'), $i),
                'section'  => 'solanawp_right_ads_section',
                'type'     => 'select',
                'choices'  => array(
                    'large' => __('Large (250px)', 'solanawp'),
                    'small' => __('Small (120px)', 'solanawp'),
                ),
            ));

            if ($i < 6) {
                $wp_customize->add_setting( "solanawp_right_ad_{$i}_separator", array(
                    'sanitize_callback' => 'sanitize_text_field',
                ));
                $wp_customize->add_control( new WP_Customize_Control( $wp_customize, "solanawp_right_ad_{$i}_separator", array(
                    'label'       => '',
                    'section'     => 'solanawp_right_ads_section',
                    'type'        => 'hidden',
                    'description' => '<hr style="margin: 20px 0;">',
                )));
            }
        }

        // --- Affiliate Links Section ---
        $wp_customize->add_section( 'solanawp_affiliate_links_section', array(
            'title'       => __( 'Affiliate Links (Checker Page)', 'solanawp' ),
            'panel'       => 'solanawp_theme_options_panel',
            'priority'    => 30,
            'description' => __( 'Configure affiliate URLs in the checker results.', 'solanawp'),
        ));

        $affiliate_items_config = array(
            'ledger' => array(
                'label' => __('Ledger Wallet URL', 'solanawp'),
                'default' => '#'
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
                'transport'         => 'refresh',
                'sanitize_callback' => 'esc_url_raw',
            ));
            $wp_customize->add_control( $setting_id, array(
                'label'    => $config['label'],
                'section'  => 'solanawp_affiliate_links_section',
                'type'     => 'url',
                'priority' => $item_priority,
            ));
            $item_priority += 10;
        }

        // --- Footer Settings Section ---
        $wp_customize->add_section('solanawp_footer_settings_section', array(
            'title'    => __('Footer Settings', 'solanawp'),
            'panel'    => 'solanawp_theme_options_panel',
            'priority' => 35,
        ));

        // Footer Copyright Text
        $wp_customize->add_setting('solanawp_footer_copyright_text', array(
            'default'           => sprintf(
                esc_html__( '&copy; %1$s %2$s. All rights reserved. Theme by %3$s.', 'solanawp' ),
                date_i18n('Y'),
                esc_html( get_bloginfo('name') ),
                '<a href="https://www.worldgpl.com/" target="_blank" rel="noopener">WORLDGPL</a>'
            ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('solanawp_footer_copyright_text', array(
            'label'       => __('Footer Copyright Text', 'solanawp'),
            'section'     => 'solanawp_footer_settings_section',
            'type'        => 'textarea',
        ));

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial( 'solanawp_footer_copyright_text', array(
                'selector'        => '.site-footer .site-info',
                'render_callback' => 'solanawp_customize_partial_footer_copyright',
                'fallback_refresh' => false,
            ));
        }
    }
endif;

// Render Callbacks
if ( ! function_exists( 'solanawp_customize_partial_blogname' ) ) :
    function solanawp_customize_partial_blogname() {
        bloginfo( 'name' );
    }
endif;

if ( ! function_exists( 'solanawp_customize_partial_custom_logo_or_fallback' ) ) :
    function solanawp_customize_partial_custom_logo_or_fallback() {
        ob_start();
        if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
            the_custom_logo();
        } else {
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
    function solanawp_customize_partial_footer_copyright() {
        $copyright_text = get_theme_mod( 'solanawp_footer_copyright_text', sprintf(
            esc_html__( '&copy; %1$s %2$s. All rights reserved. Theme by %3$s.', 'solanawp' ),
            date_i18n('Y'),
            esc_html( get_bloginfo('name') ),
            '<a href="https://www.worldgpl.com/" target="_blank" rel="noopener">WORLDGPL</a>'
        ) );
        echo wp_kses_post( $copyright_text );
    }
endif;

// CSS Output Function
if ( ! function_exists( 'solanawp_customizer_css_output' ) ) :
    function solanawp_customizer_css_output() {
        $header_height = get_theme_mod( 'solanawp_header_height', 100 );
        $banner_distance = get_theme_mod( 'solanawp_banner_edge_distance', 24 );
        $analyzer_width = get_theme_mod( 'solanawp_analyzer_width', 100 );
        $primary_color = get_theme_mod( 'solanawp_primary_accent_color', '#7c3aed' );
        $secondary_color = get_theme_mod( 'solanawp_secondary_accent_color', '#a855f7' );

        $css = '<style type="text/css" id="solanawp-customizer-css">';

        // Header height adjustment
        if ( $header_height !== 100 ) {
            $scale = $header_height / 100;
            $css .= '.site-header .header { padding: ' . (8 * $scale) . 'px ' . (16 * $scale) . 'px; }';
            $css .= '.custom-logo-link img, .site-header .logo { width: ' . (100 * $scale) . 'px; height: ' . (100 * $scale) . 'px; }';
            $css .= '.site-header .brand-name { font-size: ' . (24 * $scale) . 'px; margin-bottom: ' . (4 * $scale) . 'px; }';
        }

        // Banner edge distance
        if ( $banner_distance !== 24 ) {
            $css .= '.main-container { padding: ' . $banner_distance . 'px; }';
        }

        // Analyzer width
        if ( $analyzer_width !== 100 ) {
            $width_scale = $analyzer_width / 100;
            $css .= '.content-area { max-width: ' . (100 * $width_scale) . '%; }';
        }

        // Colors
        if ( $primary_color !== '#7c3aed' ) {
            $css .= 'a:hover, .check-btn, .primary-menu li a:hover { color: ' . esc_attr( $primary_color ) . '; }';
            $css .= '.check-btn { background: linear-gradient(135deg, ' . esc_attr( $primary_color ) . ' 0%, ' . esc_attr( $secondary_color ) . ' 100%); }';
        }

        $css .= '</style>';

        if ( str_replace(array('<style type="text/css" id="solanawp-customizer-css">', '</style>'), '', $css) !== '' ) {
            echo $css;
        }
    }
endif;

// Preview JavaScript
if ( ! function_exists( 'solanawp_customize_preview_js' ) ) :
    function solanawp_customize_preview_js() {
        wp_enqueue_script(
            'solanawp-customizer-preview',
            get_template_directory_uri() . '/assets/js/customizer.js',
            array( 'customize-preview', 'jquery' ),
            '1.0.0',
            true
        );
    }
endif;
