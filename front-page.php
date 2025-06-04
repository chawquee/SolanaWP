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
        <!-- Left Sidebar with Dynamic Ad Banners -->
        <?php get_sidebar(); // This now uses customizer settings ?>

        <!-- Main Content Area -->
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

        <!-- Right Sidebar with Dynamic Ad Banners -->
        <?php echo solanawp_get_right_sidebar(); ?>
    </div>

    <!-- Quick Customizer Access for Admins -->
<?php if ( current_user_can( 'customize' ) && is_customize_preview() === false ) : ?>
    <div id="solanawp-customizer-quick-access" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); padding: 15px; border-radius: 50%; box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4); cursor: pointer;" onclick="window.open('<?php echo admin_url( 'customize.php?autofocus[panel]=solanawp_theme_options_panel' ); ?>', '_blank');">
            <svg style="width: 24px; height: 24px; fill: white;" viewBox="0 0 24 24">
                <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12A6,6 0 0,0 12,6M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8Z"/>
            </svg>
        </div>
        <div style="background: rgba(0,0,0,0.8); color: white; padding: 8px 12px; border-radius: 6px; font-size: 12px; margin-top: 8px; text-align: center; opacity: 0; transition: opacity 0.3s ease;" id="customizer-tooltip">
            Customize Theme
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quickAccess = document.getElementById('solanawp-customizer-quick-access');
            const tooltip = document.getElementById('customizer-tooltip');

            if (quickAccess && tooltip) {
                quickAccess.addEventListener('mouseenter', function() {
                    tooltip.style.opacity = '1';
                });

                quickAccess.addEventListener('mouseleave', function() {
                    tooltip.style.opacity = '0';
                });
            }
        });
    </script>
<?php endif; ?>

    <!-- Schema.org markup for SEO -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebApplication",
            "name": "Hannisol Solana Address Checker",
            "url": "<?php echo home_url(); ?>",
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
            "url": "<?php echo home_url(); ?>"
        }
    }
    </script>
<?php
get_footer(); // Includes footer.php
?>
