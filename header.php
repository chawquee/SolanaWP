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
<html <?php language_attributes(); // Outputs the language attributes for the <html> tag. ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); // Outputs the site charset. ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" /> <?php // Ensures responsive behavior. ?>
    <link rel="profile" href="https://gmpg.org/xfn/11" /> <?php // Link to XFN profile. ?>
    <?php wp_head(); // Crucial WordPress hook. Plugins and themes use this to add scripts, styles, and meta tags. ?>
</head>

<body <?php body_class(); // Outputs dynamic body classes for styling contexts. ?>>
<?php wp_body_open(); // Hook for plugins to add content right after the opening <body> tag. ?>

<div id="page" class="site"> <?php // Main site wrapper. ?>
    <a class="skip-link screen-reader-text" href="#content">
        <?php esc_html_e( 'Skip to content', 'solanawp' ); // Accessibility link. ?>
    </a>

    <header id="masthead" class="site-header" role="banner">
        <?php // This .header div is directly from hannisolsvelte.html ?>
        <div class="header">
            <?php
            // Display site branding (logo, site title/tagline or "HANNISOL" brand name).
            // Uses template-parts/layout/header-branding.php
            get_template_part( 'template-parts/layout/header-branding' );
            ?>

            <?php
            // Main title, subtitle, and slogan for the checker page, based on hannisolsvelte.html.
            // These are typically shown on the front page or the page using the Address Checker template.
            // The specific Hannisol slogan is from solanacheckerplan.txt.
            $display_main_titles = false;
            if ( is_front_page() ) { // Check if it's the site's front page.
                if (is_page_template('templates/template-address-checker.php') || get_page_template_slug(get_option('page_on_front')) === 'templates/template-address-checker.php' || get_post_type() === 'page' && has_block('solana-checker/main-block') /* Example if using a block theme approach */ ) {
                    $display_main_titles = true;
                }
            } elseif ( is_page_template( 'templates/template-address-checker.php' ) ) { // Check if current page uses the checker template.
                $display_main_titles = true;
            }

            if ( $display_main_titles ) :
                ?>
                <h1 class="main-title"><?php echo esc_html__( 'Solana Address Checker', 'solanawp' ); ?></h1> <?php // From hannisolsvelte.html ?>
                <p class="subtitle"><?php echo esc_html__( 'Comprehensive validation and analysis for Solana addresses', 'solanawp' ); ?></p> <?php // From hannisolsvelte.html ?>
                <p class="slogan"><?php echo esc_html__( "Hannisol's Insight, Navigating Crypto Like Hannibal Crossed the Alps.", 'solanawp' ); ?></p> <?php // From solanacheckerplan.txt ?>
            <?php elseif ( !is_singular() && !is_front_page() ) : // For archive pages, could show archive title or a generic title ?>
                <?php /* Archive titles are usually handled in archive.php's page-header */ ?>
            <?php elseif (get_the_title() && !is_front_page()) : // For other single pages/posts ?>
                <h1 class="main-title page-title-header"><?php the_title(); ?></h1>
            <?php endif; ?>

            <?php // Primary navigation menu. Location 'primary' registered in inc/theme-setup.php. ?>
            <?php if ( has_nav_menu( 'primary' ) ) : ?>
            <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'solanawp' ); ?>">
                <?php
                $walker_args = array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu', // ID for the <ul> element.
                    'menu_class'     => 'primary-menu', // Class for the <ul> element, styled in main.css.
                    'container'      => false,          // Don't wrap the <ul> with a <div>.
                );
                // Check if our custom walker exists and use it.
                if ( class_exists( 'SolanaWP_Nav_Walker' ) ) {
                    $walker_args['walker'] = new SolanaWP_Nav_Walker();
                    // Example custom classes for the walker (if your walker uses them)
                    // $walker_args['li_class'] = 'primary-menu__item';
                    // $walker_args['link_class'] = 'primary-menu__link';
                }
                wp_nav_menu( $walker_args );
                ?>
                </nav><?php endif; ?>

        </div></header><?php // The #content div will wrap the main content area in templates like index.php, page.php, etc. ?>
    <div id="content" class="site-content">
