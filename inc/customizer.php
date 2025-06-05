<?php
/**
 * Enhanced SolanaWP Theme Customizer with Ad Banner Controls
 * Updated to match reference images and add ad customization
 * Version 3: Unified Sidebar Ads, Blue Banner Color Options
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
                    'selector'        => '.site-header .brand-name a',
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
            'description' => __( 'Customize various aspects of the SolanaWP theme.', 'solanawp' ),
        ) );

        // --- Layout & Design Section ---
        $wp_customize->add_section(
            'solanawp_layout_section',
            array(
                'title'    => __( 'Layout & Design', 'solanawp' ),
                'panel'    => 'solanawp_theme_options_panel',
                'priority' => 5,
                'description' => __( 'Control header elements, spacing, and general layout options.', 'solanawp' ),
            )
        );

        // Header Height Control
        $wp_customize->add_setting(
            'solanawp_header_height',
            array(
                'default'           => 100,
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            )
        );
        $wp_customize->add_control(
            'solanawp_header_height',
            array(
                'label'   => __( 'Overall Header Height Scale (%)', 'solanawp' ),
                'description' => __( 'Adjusts overall scaling of some header elements. Specific controls below might override parts of this.', 'solanawp'),
                'section' => 'solanawp_layout_section',
                'type'    => 'range',
                'input_attrs' => array( 'min'  => 30, 'max'  => 150, 'step' => 10, ),
                'priority'    => 10,
            )
        );

        // Logo Size
        $wp_customize->add_setting( 'solanawp_logo_size', array(
            'default'           => 80,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control( 'solanawp_logo_size', array(
            'label'       => __( 'Logo Size (px)', 'solanawp' ),
            'description' => __( 'Set the width and height of the logo.', 'solanawp' ),
            'section'     => 'solanawp_layout_section',
            'type'        => 'number',
            'input_attrs' => array( 'min' => 30, 'max' => 150, 'step' => 1 ),
            'priority'    => 15,
        ));

        // Brand Name Font Size
        $wp_customize->add_setting( 'solanawp_brand_name_font_size', array(
            'default'           => 20,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control( 'solanawp_brand_name_font_size', array(
            'label'       => __( 'Brand Name Font Size (px)', 'solanawp' ),
            'description' => __( 'Set the font size for the HANNISOL brand name.', 'solanawp' ),
            'section'     => 'solanawp_layout_section',
            'type'        => 'number',
            'input_attrs' => array( 'min' => 10, 'max' => 50, 'step' => 1 ),
            'priority'    => 20,
        ));

        // Banner Edge Distance
        $wp_customize->add_setting(
            'solanawp_banner_edge_distance',
            array(
                'default'           => 24,
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            )
        );
        $wp_customize->add_control(
            'solanawp_banner_edge_distance',
            array(
                'label'   => __( 'Banner Edge Distance (px)', 'solanawp' ),
                'description' => __( 'Adjusts padding around the main content grid.', 'solanawp'),
                'section' => 'solanawp_layout_section',
                'type'    => 'number',
                'input_attrs' => array( 'min'  => 0, 'max'  => 150, 'step' => 4, ),
                'priority'    => 25,
            )
        );

        // Analyzer Frame Width
        $wp_customize->add_setting(
            'solanawp_analyzer_width',
            array(
                'default'           => 100,
                'sanitize_callback' => 'absint',
                'transport'         => 'postMessage',
            )
        );
        $wp_customize->add_control(
            'solanawp_analyzer_width',
            array(
                'label'   => __( 'Analyzer Frame Width Scale (%)', 'solanawp' ),
                'description' => __( 'Scales the width of the central content area.', 'solanawp'),
                'section' => 'solanawp_layout_section',
                'type'    => 'range',
                'input_attrs' => array( 'min'  => 80, 'max'  => 120, 'step' => 5, ),
                'priority'    => 30,
            )
        );

        // --- Blue Banner Section ---
        $wp_customize->add_section( 'solanawp_blue_banner_section', array(
            'title'       => __( 'Blue Info Banner', 'solanawp' ),
            'panel'       => 'solanawp_theme_options_panel',
            'priority'    => 15,
            'description' => __( 'Customize the content and appearance of the blue informational banner.', 'solanawp'),
        ));

        $wp_customize->add_setting( 'solanawp_blue_banner_main_text', array(
            'default'           => __( 'Advanced Blockchain Analysis Platform', 'solanawp' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control( 'solanawp_blue_banner_main_text', array(
            'label'    => __( 'Blue Banner - Main Text', 'solanawp' ),
            'section'  => 'solanawp_blue_banner_section',
            'type'     => 'text',
            'priority' => 10,
        ));

        $wp_customize->add_setting( 'solanawp_blue_banner_sub_text', array(
            'default'           => __( 'Real-time validation - Risk assessment - Professional insights', 'solanawp' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control( 'solanawp_blue_banner_sub_text', array(
            'label'    => __( 'Blue Banner - Sub-Text', 'solanawp' ),
            'section'  => 'solanawp_blue_banner_section',
            'type'     => 'text',
            'priority' => 20,
        ));

        // Blue Banner Background Color
        $wp_customize->add_setting( 'solanawp_blue_banner_bg_color', array(
            'default'           => '#1e3a8a', // Default blue
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'solanawp_blue_banner_bg_color', array(
            'label'    => __( 'Blue Banner Background Color', 'solanawp' ),
            'section'  => 'solanawp_blue_banner_section',
            'priority' => 30,
        )));

        // Blue Banner Text Color
        $wp_customize->add_setting( 'solanawp_blue_banner_text_color_customizer', array(
            'default'           => '#ffffff', // Default white
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'solanawp_blue_banner_text_color_customizer', array(
            'label'    => __( 'Blue Banner Text Color', 'solanawp' ),
            'section'  => 'solanawp_blue_banner_section',
            'priority' => 40,
        )));


        // --- Colors Section (Accent Colors) ---
        $wp_customize->add_section(
            'solanawp_colors_section',
            array(
                'title'    => __( 'Theme Accent Colors', 'solanawp' ),
                'panel'    => 'solanawp_theme_options_panel',
                'priority' => 10,
                'description' => __( 'Customize theme accent colors for buttons, links, etc.', 'solanawp' ),
            )
        );

        $wp_customize->add_setting(
            'solanawp_primary_accent_color',
            array(
                'default'           => '#3b82f6',
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
                    'section' => 'solanawp_colors_section',
                )
            )
        );

        $wp_customize->add_setting(
            'solanawp_secondary_accent_color',
            array(
                'default'           => '#8b5cf6',
                'sanitize_callback' => 'sanitize_hex_color',
                'transport'         => 'postMessage',
            )
        );
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'solanawp_secondary_accent_color',
                array(
                    'label'   => __( 'Secondary Accent Color (Gradients)', 'solanawp' ),
                    'section' => 'solanawp_colors_section',
                )
            )
        );


        // --- Unified Sidebar Ad Banners Section ---
        $wp_customize->add_section( 'solanawp_sidebar_ads_section', array(
            'title'       => __( 'Sidebar Ad Banners', 'solanawp' ),
            'panel'       => 'solanawp_theme_options_panel',
            'priority'    => 20,
            'description' => __( 'Configure up to 6 ad banners. These will be used for BOTH left and right sidebars if they are active.', 'solanawp'),
        ));

        for ($i = 1; $i <= 6; $i++) {
            // Title
            $wp_customize->add_setting( "solanawp_sidebar_ad_{$i}_title", array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh', // Refresh needed as structure might change
            ));
            $wp_customize->add_control( "solanawp_sidebar_ad_{$i}_title", array(
                'label'    => sprintf(__('Ad %d - Title', 'solanawp'), $i),
                'section'  => 'solanawp_sidebar_ads_section',
                'type'     => 'text',
            ));

            // Description
            $wp_customize->add_setting( "solanawp_sidebar_ad_{$i}_desc", array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            ));
            $wp_customize->add_control( "solanawp_sidebar_ad_{$i}_desc", array(
                'label'    => sprintf(__('Ad %d - Description', 'solanawp'), $i),
                'section'  => 'solanawp_sidebar_ads_section',
                'type'     => 'text',
            ));

            // URL
            $wp_customize->add_setting( "solanawp_sidebar_ad_{$i}_url", array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'refresh',
            ));
            $wp_customize->add_control( "solanawp_sidebar_ad_{$i}_url", array(
                'label'    => sprintf(__('Ad %d - URL', 'solanawp'), $i),
                'section'  => 'solanawp_sidebar_ads_section',
                'type'     => 'url',
            ));

            // Size
            $wp_customize->add_setting( "solanawp_sidebar_ad_{$i}_size", array(
                'default'           => 'large',
                'sanitize_callback' => 'sanitize_text_field', // Accepts 'large' or 'small'
                'transport'         => 'refresh',
            ));
            $wp_customize->add_control( "solanawp_sidebar_ad_{$i}_size", array(
                'label'    => sprintf(__('Ad %d - Size', 'solanawp'), $i),
                'section'  => 'solanawp_sidebar_ads_section',
                'type'     => 'select',
                'choices'  => array(
                    'large' => __('Large (Default)', 'solanawp'),
                    'small' => __('Small', 'solanawp'),
                ),
            ));

            // Separator
            if ($i < 6) {
                $wp_customize->add_setting( "solanawp_sidebar_ad_{$i}_separator", array(
                    'sanitize_callback' => 'sanitize_text_field', // No real value, just for control
                ));
                $wp_customize->add_control( new WP_Customize_Control( $wp_customize, "solanawp_sidebar_ad_{$i}_separator", array(
                    'section'     => 'solanawp_sidebar_ads_section',
                    'type'        => 'hidden', // Effectively a way to add markup
                    'description' => '<hr style="margin: 20px 0;">',
                )));
            }
        }

        // --- Affiliate Links Section ---
        $wp_customize->add_section( 'solanawp_affiliate_links_section', array(
            'title'       => __( 'Affiliate Links (Checker Page)', 'solanawp' ),
            'panel'       => 'solanawp_theme_options_panel',
            'priority'    => 30,
            'description' => __( 'Configure affiliate URLs for the "Recommended Security Tools" section.', 'solanawp'),
        ));
        $affiliate_items_config = array(
            'ledger' => array('label' => __('Ledger Wallet URL', 'solanawp'), 'default' => '#'),
            'vpn'    => array('label' => __('VPN Service URL', 'solanawp'), 'default' => '#'),
            'guide'  => array('label' => __('Security Guide URL', 'solanawp'), 'default' => '#'),
            'course' => array('label' => __('Crypto Course URL', 'solanawp'), 'default' => '#'),
        );
        $item_priority = 10;
        foreach ( $affiliate_items_config as $key => $config ) {
            $setting_id = "solanawp_affiliate_{$key}_url";
            $wp_customize->add_setting( $setting_id, array(
                'default' => esc_url_raw( $config['default'] ), 'transport' => 'refresh', 'sanitize_callback' => 'esc_url_raw',
            ));
            $wp_customize->add_control( $setting_id, array(
                'label' => $config['label'], 'section' => 'solanawp_affiliate_links_section', 'type' => 'url', 'priority' => $item_priority,
            ));
            $item_priority += 10;
        }

        // --- Footer Settings Section ---
        $wp_customize->add_section('solanawp_footer_settings_section', array(
            'title'    => __('Footer Settings', 'solanawp'),
            'panel'    => 'solanawp_theme_options_panel',
            'priority' => 35,
        ));
        $wp_customize->add_setting('solanawp_footer_copyright_text', array(
            'default'           => sprintf(
                esc_html__( '&copy; %1$s %2$s. All rights reserved. Theme by %3$s.', 'solanawp' ),
                date_i18n('Y'), esc_html( get_bloginfo('name') ),
                '<a href="https://www.worldgpl.com/" target="_blank" rel="noopener noreferrer author">WORLDGPL</a>'
            ),
            'sanitize_callback' => 'wp_kses_post', 'transport' => 'postMessage',
        ));
        $wp_customize->add_control('solanawp_footer_copyright_text', array(
            'label' => __('Footer Copyright Text', 'solanawp'), 'section' => 'solanawp_footer_settings_section', 'type' => 'textarea',
        ));
        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial( 'solanawp_footer_copyright_text', array(
                'selector' => '.site-footer .site-info',
                'render_callback' => 'solanawp_customize_partial_footer_copyright',
                'fallback_refresh' => false,
            ));
        }
    }
endif; // solanawp_customize_register


// Render Callbacks
if ( ! function_exists( 'solanawp_customize_partial_blogname' ) ) :
    function solanawp_customize_partial_blogname() {
        bloginfo( 'name' );
    }
endif;

if ( ! function_exists( 'solanawp_customize_partial_custom_logo_or_fallback' ) ) :
    function solanawp_customize_partial_custom_logo_or_fallback() {
        if ( function_exists( 'solanawp_get_logo_or_fallback_html' ) ) {
            return solanawp_get_logo_or_fallback_html();
        }
        ob_start();
        if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
            the_custom_logo();
        } else {
            echo '<div class="logo"><div class="logo-h">H</div></div>';
        }
        return ob_get_clean();
    }
endif;

if ( ! function_exists( 'solanawp_customize_partial_footer_copyright' ) ) :
    function solanawp_customize_partial_footer_copyright() {
        $default_copyright = sprintf(
            esc_html__( '&copy; %1$s %2$s. All rights reserved. Theme by %3$s.', 'solanawp' ),
            date_i18n('Y'), esc_html( get_bloginfo('name') ),
            '<a href="https://www.worldgpl.com/" target="_blank" rel="noopener noreferrer author">WORLDGPL</a>'
        );
        $copyright_text = get_theme_mod( 'solanawp_footer_copyright_text', $default_copyright );
        echo wp_kses_post( $copyright_text );
    }
endif;

// Preview JavaScript
if ( ! function_exists( 'solanawp_customize_preview_js' ) ) :
    function solanawp_customize_preview_js() {
        wp_enqueue_script(
            'solanawp-customizer-preview',
            get_template_directory_uri() . '/assets/js/customizer.js',
            array( 'customize-preview', 'jquery' ),
            defined('SOLANAWP_VERSION') ? SOLANAWP_VERSION : '1.0.0',
            true
        );

        wp_localize_script('solanawp-customizer-preview', 'solanawpCustomizerPreviewData', array(
            'blueBannerMainTextDefault' => __( 'Advanced Blockchain Analysis Platform', 'solanawp' ),
            'blueBannerSubTextDefault' => __( 'Real-time validation - Risk assessment - Professional insights', 'solanawp' ),
            'blueBannerBgColorDefault' => '#1e3a8a',
            'blueBannerTextColorDefault' => '#ffffff',
        ));
    }
endif;
