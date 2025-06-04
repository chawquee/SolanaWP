<?php
/**
 * The sidebar containing the main widget area.
 * This template is called by get_sidebar().
 * It displays widgets assigned to 'sidebar-1' (Main Sidebar) and potentially 'ad-banner-sidebar'.
 * The overall structure and ad banner concept are from hannisolsvelte.html
 * and the monetization strategy.
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

// Check if either of the designated sidebar widget areas are active.
// 'sidebar-1' is the main sidebar, 'ad-banner-sidebar' is specifically for ads.
// Both are registered in inc/widget-areas.php.
$is_main_sidebar_active = is_active_sidebar( 'sidebar-1' );
$is_ad_sidebar_active = is_active_sidebar( 'ad-banner-sidebar' );
?>

<?php // The outer <aside> uses the .sidebar class from hannisolsvelte.html for overall sidebar styling. ?>
<aside id="secondary" class="widget-area sidebar" role="complementary">
    <?php
    // Display the "Ad Banner Sidebar" first if it has widgets.
    // This is for dedicated ad slots as seen in hannisolsvelte.html.
    if ( $is_ad_sidebar_active ) :
        ?>
        <div class="ad-banner-widget-area"> <?php // Specific wrapper for ad banner widgets if needed ?>
            <?php dynamic_sidebar( 'ad-banner-sidebar' ); ?>
        </div>
    <?php
    endif;

    // Display the "Main Sidebar" if it has widgets.
    // This is for general purpose widgets like search, categories, recent posts, etc.
    if ( $is_main_sidebar_active ) :
        ?>
        <div class="main-sidebar-widget-area"> <?php // Specific wrapper for main sidebar widgets if needed ?>
            <?php dynamic_sidebar( 'sidebar-1' ); ?>
        </div>
    <?php
    endif;

    // Fallback content if NO widgets are active in ANY sidebar, but you still want to show placeholders
    // as per the visual design of hannisolsvelte.html.
    if ( ! $is_main_sidebar_active && ! $is_ad_sidebar_active ) :
        ?>
        <!-- Ad Banner Placeholders from hannisolsvelte.html -->
        <div class="ad-banner">
            <div>
                <div style="font-size: 18px; margin-bottom: 8px;">🎯 A-ADS</div>
                <div>Crypto Ad Network</div>
                <div style="font-size: 12px; opacity: 0.7;">$0.50-$2.00 CPM</div>
            </div>
        </div>

        <div class="ad-banner">
            <div>
                <div style="font-size: 18px; margin-bottom: 8px;">💰 Coinzilla</div>
                <div>Premium Crypto Ads</div>
                <div style="font-size: 12px; opacity: 0.7;">$1-$5 CPM</div>
            </div>
        </div>

        <div class="ad-banner">
            <div>
                <div style="font-size: 18px; margin-bottom: 8px;">📱 Google AdSense</div>
                <div>Main Revenue Stream</div>
                <div style="font-size: 12px; opacity: 0.7;">$0.20-$2.00 CPC</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">🌐 Media.net</div>
                <div style="font-size: 14px;">Yahoo/Bing Network</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">🛒 Amazon Associates</div>
                <div style="font-size: 14px;">Security Hardware</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">🔐 Ledger Affiliate</div>
                <div style="font-size: 14px;">$20-$28 per sale</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">🛡️ VPN Services</div>
                <div style="font-size: 14px;">$30-$100 signup</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">📚 Crypto Education</div>
                <div style="font-size: 14px;">10-30% commission</div>
            </div>
        </div>

        <div class="ad-banner">
            <div>
                <div style="font-size: 18px; margin-bottom: 8px;">💎 Native Ads</div>
                <div>Content Integration</div>
                <div style="font-size: 12px; opacity: 0.7;">High CTR</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">🏦 Crypto Exchange</div>
                <div style="font-size: 14px;">Trading Platform</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">📊 Portfolio Tracker</div>
                <div style="font-size: 14px;">Investment Tools</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">🎓 DeFi Course</div>
                <div style="font-size: 14px;">Learn DeFi</div>
            </div>
        </div>

        <div class="ad-banner">
            <div>
                <div style="font-size: 18px; margin-bottom: 8px;">🔄 Retargeting Ads</div>
                <div>Visitor Remarketing</div>
                <div style="font-size: 12px; opacity: 0.7;">Higher Conversion</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">⚡ Lightning Wallet</div>
                <div style="font-size: 14px;">Bitcoin Solutions</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">🌟 Staking Rewards</div>
                <div style="font-size: 14px;">Earn Passive Income</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">🔍 Blockchain Explorer</div>
                <div style="font-size: 14px;">Advanced Analytics</div>
            </div>
        </div>

        <div class="ad-banner">
            <div>
                <div style="font-size: 18px; margin-bottom: 8px;">📈 Trading Signals</div>
                <div>Professional Analysis</div>
                <div style="font-size: 12px; opacity: 0.7;">Premium Service</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">🎮 Gaming NFTs</div>
                <div style="font-size: 14px;">Play-to-Earn</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">💳 Crypto Cards</div>
                <div style="font-size: 14px;">Spend Crypto</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">🏅 Audit Services</div>
                <div style="font-size: 14px;">Smart Contract Review</div>
            </div>
        </div>

        <div class="ad-banner">
            <div>
                <div style="font-size: 18px; margin-bottom: 8px;">📰 Crypto News</div>
                <div>Market Updates</div>
                <div style="font-size: 12px; opacity: 0.7;">Daily Insights</div>
            </div>
        </div>

        <div class="ad-banner small">
            <div>
                <div style="font-size: 16px; margin-bottom: 4px;">🎁 Airdrops Alert</div>
                <div style="font-size: 14px;">Free Token Tracker</div>
            </div>
        </div>
    <?php endif; ?>
</aside>
