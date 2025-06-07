<?php
/**
 * The header for the SolanaWP theme.
 *
 * This is the template that displays all of hte <head> section and everything up until main content.
 * Version 5: Added dynamic rendering for Hero and Analyzer banners.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package SolanaWP
 * @since SolanaWP 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helper function to render a customizable banner.
 *
 * @param string $prefix The Customizer setting prefix (e.g., 'solanawp_hero').
 * @param string $css_class The CSS class for the banner container.
 * @param array $defaults Default text values.
 */
function solanawp_render_customizable_banner($prefix, $css_class, $defaults) {
    $content_type = get_theme_mod("{$prefix}_content_type", 'text');

    echo '<div class="' . esc_attr($css_class) . '">'; // Main container

    switch ($content_type) {
        case 'image':
            $img_url = get_theme_mod("{$prefix}_content_image", '');
            if (!empty($img_url)) {
                echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr__( 'Banner Content Image', 'solanawp' ) . '" style="width:100%; height:auto; display:block;">';
            }
            break;

        case 'slider':
            $slider_shortcode = get_theme_mod("{$prefix}_content_slider", '');
            if (!empty($slider_shortcode) && function_exists('do_shortcode')) {
                echo do_shortcode(wp_kses_post($slider_shortcode));
            }
            break;

        default: // 'text' case
            $main_text = get_theme_mod("{$prefix}_content_text_main", $defaults['main']);
            $sub_text = get_theme_mod("{$prefix}_content_text_sub", $defaults['sub']);

            $container_class = ($prefix === 'solanawp_hero') ? 'hero-sub-banner-container' : 'sca-container';
            $main_text_class = ($prefix === 'solanawp_hero') ? 'hero-sub-banner-main-text' : 'sca-title';
            $sub_text_class = ($prefix === 'solanawp_hero') ? 'hero-sub-banner-sub-text' : 'sca-subtitle';

            echo '<div class="' . esc_attr($container_class) . '">';
            if ($prefix === 'solanawp_hero') {
                echo '<span class="dashicons dashicons-rocke hero-sub-banner-icon"></span>';
            }
            echo '<div class="hero-sub-banner-text-content">'; // Generic text content wrapper
            if (!empty($main_text)) {
                echo '<h2 class="' . esc_attr($main_text_class) . '">' . wp_kses_post($main_text) . '</h2>';
            }
            if (!empty($sub_text)) {
                echo '<div class="' . esc_attr($sub_text_class) . '">' . wp_kses_post($sub_text) . '</div>';
            }
            echo '</div></div>';
            break;
    }

    echo '</div>'; // Close main container
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="profile" href="https://gmpg.org/xfn/11" />
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content">
        <?php esc_html_e( 'Skip to content', 'solanawp' ); ?>
    </a>

    <?php // PART 1: Topmost Header Section (Logo/Brand) - UNCHANGED ?>
    <header id="masthead" class="site-header" role="banner">
        <div class="header">
            <?php
            get_template_part( 'template-parts/layout/header-branding' );
            if ( has_nav_menu( 'primary' ) && function_exists('solanawp_disable_navigation_menus') === false ) :
                ?>
                <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'solanawp' ); ?>">
                    <?php
                    $walker_args = array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'primary-menu',
                        'container'      => false,
                    );
                    if ( class_exists( 'SolanaWP_Nav_Walker' ) ) {
                        $walker_args['walker'] = new SolanaWP_Nav_Walker();
                    }
                    wp_nav_menu( $walker_args );
                    ?>
                </nav>
            <?php endif; ?>
        </div>
    </header>

    <?php // PART 2: Customizable "Platform Banner" ?>
    <?php
    solanawp_render_customizable_banner(
        'solanawp_hero',
        'hero-sub-banner',
        array(
            'main' => __( 'Advanced Blockchain Analysis Platform', 'solanawp' ),
            'sub' => __( 'Real-time validation - Risk assessment - Professional insights', 'solanawp' )
        )
    );
    ?>

    <?php // PART 2.5: Customizable "Analyzer Title Banner" ?>
    <?php
    solanawp_render_customizable_banner(
        'solanawp_analyzer',
        'solana-coins-analyzer-section',
        array(
            'main' => __( 'Solana Coins Analyzer', 'solanawp' ),
            'sub' => '<p>' . __( 'Comprehensive validation and analysis for Solana addresses', 'solanawp' ) . '</p>' .
                '<p>' . __( "Hannisol's Insight, Navigating Crypto Like Hannibal Crossed the Alps.", 'solanawp' ) . '</p>'
        )
    );
    ?>


    <?php // PART 3: White Title Section (Main Title, Subtitle, Slogan for Checker) ?>
    <?php
    $display_main_titles = false;
    // This logic determines if the static "Solana Address Checker" title should be displayed.
    // It is now superseded by the customizable banner above, so we might want to disable it.
    // For now, keeping the logic but it might be redundant.
    if ( is_front_page() ) {
        $front_page_id = get_option('page_on_front');
        if ($front_page_id) {
            if (get_page_template_slug($front_page_id) === 'templates/template-address-checker.php') {
                $display_main_titles = true;
            } elseif (get_post_type($front_page_id) === 'page' && has_shortcode(get_post_field('post_content', $front_page_id), 'solana_checker_form')) { // Example shortcode check
                $display_main_titles = true;
            }
        }
    } elseif ( is_page_template( 'templates/template-address-checker.php' ) ) {
        $display_main_titles = true;
    } elseif ( is_page() && isset($post) && has_shortcode( $post->post_content, 'solana_checker_form' ) ){ // Example shortcode check for any page
        $display_main_titles = true;
    }


    if ( false ) : // Set to false to hide the old static title block, as it's replaced by the customizable banner.
        ?>
        <div class="page-main-title-area"> <?php // New wrapper class for this section ?>
            <h1 class="main-title"><?php echo esc_html__( 'Solana Address Checker', 'solanawp' ); ?></h1>
            <p class="subtitle"><?php echo esc_html__( 'Comprehensive validation and analysis for Solana addresses', 'solanawp' ); ?></p>
            <p class="slogan"><?php echo esc_html__( "Hannisol's Insight, Navigating Crypto Like Hannibal Crossed the Alps.", 'solanawp' ); ?></p>
        </div>
    <?php
    // Standard page/post title for other pages (not the checker with special titles)
    elseif ( is_singular() && !is_front_page() && !$display_main_titles && get_the_title() ) : ?>
        <div class="page-main-title-area standard-page-title-area">
            <h1 class="main-title page-title-header"><?php the_title(); ?></h1>
        </div>
    <?php
    // Archive titles (handled by archive.php typically, but a placeholder if needed here)
    elseif ( !is_singular() && !is_front_page() && !$display_main_titles && !is_404() ) : ?>
        <div class="page-main-title-area archive-title-area">
            <h1 class="main-title page-title-header"><?php the_archive_title(); ?></h1>
            <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
        </div>
    <?php endif; ?>

    <div id="content" class="site-content">
