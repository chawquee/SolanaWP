<?php
/**
 * Updated front-page.php with dynamic customizer ad banners
 * Replace existing front-page.php with this version
 * Implements all reference image improvements
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); // Includes header.php
?>
    <div class="main-container">
        <?php get_sidebar(); // This now uses customizer settings ?>

        <div id="primary" class="content-area front-page-content-area address-checker-content">
            <main id="main" class="site-main solanawp-checker-main" role="main">
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

        <?php
        // Ensure the function exists before calling it.
        // This function is expected to be in functions.php or a sidebar-related include.
        if ( function_exists( 'solanawp_get_right_sidebar' ) ) {
            echo solanawp_get_right_sidebar();
        }
        ?>
    </div>

<?php // Floating front-end customizer button has been removed as per instructions. ?>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebApplication",
            "name": "Hannisol Solana Address Checker",
            "url": "<?php echo esc_url( home_url( '/' ) ); ?>",
            "description": "Comprehensive validation and analysis for Solana addresses. Hannisol's Insight, Navigating Crypto Like Hannibal Crossed the Alps.",
            "applicationCategory": "Cryptocurrency Tool",
            "operatingSystem": "Web Browser",
            "offers": {
                "@type": "Offer",
                "price": "0",
                "priceCurrency": "USD"
            },
            "creator": {
                "@type": "Organization",
                "name": "Hannisol",
                "url": "<?php echo esc_url( home_url( '/' ) ); ?>"
            }
        }
    </script>
<?php
get_footer(); // Includes footer.php
?>
