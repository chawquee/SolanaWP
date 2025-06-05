<?php
/**
 * UPDATED templates/template-address-checker.php
 * Replace the existing template-address-checker.php with this version
 * Includes the new symmetric layout with both sidebars (right sidebar is now dynamic)
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); // Includes header.php
?>
    <div class="main-container">
        <!-- Left Sidebar -->
        <?php get_sidebar(); // This will display the left sidebar (dynamically rendered from Customizer) ?>

        <!-- Main Content Area -->
        <div id="primary" class="content-area address-checker-content">
            <main id="main" class="site-main solanawp-checker-main" role="main">
                <?php
                // Page content (like from the WordPress editor for this page)
                // is intentionally not displayed for the checker template by default
                // to keep the focus on the checker tool.
                // If you need to show content from the WP editor, you can uncomment the loop:
                /*
                while ( have_posts() ) :
                    the_post();
                    the_content();
                endwhile;
                wp_reset_postdata();
                */
                ?>

                <?php // --- Input Section for the address checker --- ?>
                <?php get_template_part( 'template-parts/checker/input-section' ); ?>

                <?php // --- Results Section container --- ?>
                <div class="results-section" id="resultsSection">
                    <?php get_template_part( 'template-parts/checker/results-validation' ); ?>
                    <?php get_template_part( 'template-parts/checker/results-balance' ); ?>
                    <?php get_template_part( 'template-parts/checker/results-transactions' ); ?>

                    <div id="accountAndSecurityOuterGrid" class="account-security-grid-wrapper" style="display:none;">
                        <?php get_template_part( 'template-parts/checker/results-account-details' ); ?>
                        <?php get_template_part( 'template-parts/checker/results-security' ); ?>
                    </div>

                    <?php get_template_part( 'template-parts/checker/results-rugpull' ); ?>
                    <?php get_template_part( 'template-parts/checker/results-community' ); ?>
                    <?php get_template_part( 'template-parts/checker/results-affiliate' ); ?>
                    <?php get_template_part( 'template-parts/checker/results-final' ); ?>
                </div>
            </main>
        </div>

        <!-- Right Sidebar (Updated to be dynamic) -->
        <?php
        // Ensure the function exists before calling it.
        // This function is defined in functions.php and renders ads from the Customizer
        // for the right sidebar, mirroring the left sidebar's functionality.
        if ( function_exists( 'solanawp_get_right_sidebar' ) ) {
            echo solanawp_get_right_sidebar();
        } else {
            // Fallback if the function is somehow not available
            // You could output a placeholder or an error message here.
            // For example, a simple placeholder:
            echo '<aside id="secondary-right" class="widget-area sidebar-right" role="complementary">';
            echo '<p>Right sidebar content is unavailable.</p>';
            echo '</aside>';
        }
        ?>
    </div>
<?php
get_footer(); // Includes footer.php
