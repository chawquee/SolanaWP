<?php
/**
 * The header for the SolanaWP theme.
 *
 * This is the template that displays all of hte <head> section and everything up until main content.
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

    <?php // PART 1: Topmost Header Section (Logo/Brand) ?>
    <header id="masthead" class="site-header" role="banner">
        <div class="header">
            <?php
            // Display site branding (logo, site title/tagline or "HANNISOL" brand name).
            get_template_part( 'template-parts/layout/header-branding' ); //
            ?>
            <?php
            // Primary navigation menu - this was inside .header.
            // If it's part of the topmost bar, it stays. If it's part of a different section, move accordingly.
            // For now, assuming it's part of the topmost bar.
            // However, navigation is currently disabled by functions.php solanawp_disable_navigation_menus and solanawp_force_hide_navigation.
            // If navigation were enabled, this would be its location in the topmost bar.
            if ( has_nav_menu( 'primary' ) && function_exists('solanawp_disable_navigation_menus') === false ) : // Check if it's not disabled
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

    <?php // PART 2: New Blue Banner Section ?>
    <div class="hero-sub-banner">
        <div class="hero-sub-banner-container"> <?php // Added container for better content control ?>
            <span class="dashicons dashicons-rocke hero-sub-banner-icon"></span> <?php // Placeholder Icon ?>
            <div class="hero-sub-banner-text-content">
                <h2 class="hero-sub-banner-main-text">
                    <?php echo esc_html( get_theme_mod( 'solanawp_blue_banner_main_text', __( 'Advanced Blockchain Analysis Platform', 'solanawp' ) ) ); ?>
                </h2>
                <p class="hero-sub-banner-sub-text">
                    <?php echo esc_html( get_theme_mod( 'solanawp_blue_banner_sub_text', __( 'Real-time validation - Risk assessment - Professional insights', 'solanawp' ) ) ); ?>
                </p>
            </div>
        </div>
    </div>

    <?php // PART 2.5: New "Solana Coins Analyzer" Section (Added as per instructions) ?>
    <div class="solana-coins-analyzer-section">
        <h2 class="sca-title"><?php esc_html_e( 'Solana Coins Analyzer', 'solanawp' ); ?></h2>
        <div class="sca-subtitle">
            <p><?php esc_html_e( 'Comprehensive validation and analysis for Solana addresses', 'solanawp' ); ?></p>
            <p><?php esc_html_e( "Hannisol's Insight, Navigating Crypto Like Hannibal Crossed the Alps.", 'solanawp' ); ?></p>
        </div>
    </div>

    <?php // PART 3: White Title Section (Main Title, Subtitle, Slogan for Checker) ?>
    <?php
    $display_main_titles = false;
    // Determine if the main "Solana Address Checker" titles should be displayed.
    // This logic checks if the current page is the front page designated as the checker,
    // or if it's any page using the 'template-address-checker.php' template,
    // or if it's a page containing a specific shortcode (e.g., [solana_checker_form]).
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


    if ( $display_main_titles ) :
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
