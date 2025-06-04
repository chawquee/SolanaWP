<?php
/**
 * Template part for displaying the header branding (logo, site title/tagline).
 * Called from header.php.
 *
 * @package SolanaWP
 * @since SolanaWP 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="site-branding"> <?php // Standard WordPress class for this section ?>
    <div class="logo-container"> <?php // Class from hannisolsvelte.html ?>
        <?php
        // Uses the solanawp_get_logo_or_fallback_html() function from inc/template-tags.php
        // to display either the custom logo or the 'H' fallback.
        // The fallback H logo design is from hannisolsvelte.html
        // and aligns with Hannisol branding.
        if ( function_exists( 'solanawp_get_logo_or_fallback_html' ) ) {
            echo solanawp_get_logo_or_fallback_html();
        } elseif ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
            the_custom_logo();
        } else {
            // Fallback if function doesn't exist, directly outputting the structure
            // (though the function is preferred for consistency).
            ?>
            <div class="logo"> <?php // Class from hannisolsvelte.html ?>
                <div class="logo-h">H</div> <?php // Class from hannisolsvelte.html ?>
            </div>
            <?php
        }
        ?>
    </div>

    <div class="site-title-tagline">
        <?php // The .brand-name displays "HANNISOL" as per hannisolsvelte.html & solanacheckerplan.txt ?>
        <?php if ( get_bloginfo( 'name' ) && ( is_front_page() && is_home() ) ) : // Display as H1 on home ?>
            <h1 class="brand-name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html__( 'HANNISOL', 'solanawp' ); ?></a></h1>
        <?php else : // Display as P on other pages ?>
            <p class="brand-name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html__( 'HANNISOL', 'solanawp' ); ?></a></p>
        <?php endif; ?>

        <?php
        // The WordPress site tagline (description) can be displayed if needed.
        // The design in hannisolsvelte.html has a specific .subtitle and .slogan
        // which are part of the main header block in header.php, not typically here.
        // This is for the standard WP tagline if enabled in Customizer.
        $solanawp_description = get_bloginfo( 'description', 'display' );
        if ( $solanawp_description || is_customize_preview() ) :
            ?>
            <p class="site-description screen-reader-text"><?php echo $solanawp_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
        <?php endif; ?>
    </div></div>

<?php
/**
 * Template part for displaying footer widgets if any are active.
 * It's assumed a 'footer-widgets' sidebar is registered in inc/widget-areas.php.
 * This allows for additional content or navigation in the footer, a common theme feature.
 *
 * @package SolanaWP
 * @since SolanaWP 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if the 'footer-widgets' sidebar has any active widgets.
if ( ! is_active_sidebar( 'footer-widgets' ) ) {
    return; // If no widgets, output nothing.
}
?>
<div class="footer-widgets-area widget-area"> <?php // General wrapper for footer widgets section. ?>
    <div class="container footer-widgets-container"> <?php // Optional inner container for layout (you'd define .container style in main.css). ?>
        <div class="footer-widgets-grid"> <?php // Optional grid wrapper for multiple footer widget columns (e.g., 3 or 4 columns). ?>
            <?php dynamic_sidebar( 'footer-widgets' ); // Displays the widgets assigned to 'footer-widgets'. ?>
        </div>
    </div>
</div>
