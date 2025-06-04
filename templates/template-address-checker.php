<?php
/**
 * UPDATED templates/template-address-checker.php
 * Replace the existing template-address-checker.php with this version
 * Includes the new symmetric layout with both sidebars
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); // Includes header.php
?>
    <div class="main-container">
        <!-- Left Sidebar -->
        <?php get_sidebar(); // This will display the left sidebar ?>

        <!-- Main Content Area -->
        <div id="primary" class="content-area address-checker-content">
            <main id="main" class="site-main solanawp-checker-main" role="main">
                <?php
                // Don't display the default page content that might contain "Welcome to WordPress"
                // Comment out or remove this section:
                /*
                while ( have_posts() ) :
                    the_post();
                    // Optional page content is now hidden
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

        <!-- Right Sidebar (New) -->
        <aside id="secondary-right" class="widget-area sidebar-right" role="complementary">
            <?php
            // Right sidebar ad banners - symmetric to left side
            ?>
            <div class="ad-banner">
                <div>
                    <div style="font-size: 18px; margin-bottom: 8px;">💎 Premium Crypto</div>
                    <div>Exclusive Offers</div>
                    <div style="font-size: 12px; opacity: 0.7;">$3-$10 CPM</div>
                </div>
            </div>

            <div class="ad-banner">
                <div>
                    <div style="font-size: 18px; margin-bottom: 8px;">🚀 Launch Platform</div>
                    <div>Token Launches</div>
                    <div style="font-size: 12px; opacity: 0.7;">$100-$500 signup</div>
                </div>
            </div>

            <div class="ad-banner">
                <div>
                    <div style="font-size: 18px; margin-bottom: 8px;">📱 Trading Bot</div>
                    <div>Automated Trading</div>
                    <div style="font-size: 12px; opacity: 0.7;">$25-$75 monthly</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">🎯 Smart Contracts</div>
                    <div style="font-size: 14px;">Audit Services</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">💰 Staking Pool</div>
                    <div style="font-size: 14px;">High Rewards</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">🔒 Cold Storage</div>
                    <div style="font-size: 14px;">Ultimate Security</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">📊 Portfolio Tracker</div>
                    <div style="font-size: 14px;">Real-time Updates</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">🎓 Crypto Academy</div>
                    <div style="font-size: 14px;">Learn & Earn</div>
                </div>
            </div>

            <div class="ad-banner">
                <div>
                    <div style="font-size: 18px; margin-bottom: 8px;">🌐 DeFi Aggregator</div>
                    <div>Best Rates</div>
                    <div style="font-size: 12px; opacity: 0.7;">Compare All DEXs</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">⚡ Instant Swap</div>
                    <div style="font-size: 14px;">Zero Slippage</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">🏆 NFT Marketplace</div>
                    <div style="font-size: 14px;">Exclusive Drops</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">🔍 On-Chain Analytics</div>
                    <div style="font-size: 14px;">Deep Insights</div>
                </div>
            </div>

            <div class="ad-banner">
                <div>
                    <div style="font-size: 18px; margin-bottom: 8px;">🎪 Brand Partnership</div>
                    <div>Sponsored Placements</div>
                    <div style="font-size: 12px; opacity: 0.7;">Premium Visibility</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">🎮 Web3 Gaming</div>
                    <div style="font-size: 14px;">Earn While Playing</div>
                </div>
            </div>
        </aside>
    </div>
<?php
get_footer(); // Includes footer.php
