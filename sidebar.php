<?php
/**
 * Updated sidebar.php with dynamic ad banners from customizer
 * Replace existing sidebar.php with this version
 *
 * @package SolanaWP
 * @since SolanaWP 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if sidebars are active
$is_main_sidebar_active = is_active_sidebar( 'sidebar-1' );
$is_ad_sidebar_active = is_active_sidebar( 'ad-banner-sidebar' );
?>

    <aside id="secondary" class="widget-area sidebar" role="complementary">
        <?php
        // Display customizer ad banners first
        echo solanawp_render_customizer_ad_banners( 'left', 20 ); // Render up to 20 left side banners

        // Display the "Ad Banner Sidebar" if it has widgets
        if ( $is_ad_sidebar_active ) :
            ?>
            <div class="ad-banner-widget-area">
                <?php dynamic_sidebar( 'ad-banner-sidebar' ); ?>
            </div>
        <?php
        endif;

        // Display the "Main Sidebar" if it has widgets
        if ( $is_main_sidebar_active ) :
            ?>
            <div class="main-sidebar-widget-area">
                <?php dynamic_sidebar( 'sidebar-1' ); ?>
            </div>
        <?php
        endif;

        // Fallback: Show default banners only if no customizer ads are configured AND no widgets are active
        $has_customizer_ads = false;
        for ( $i = 1; $i <= 20; $i++ ) {
            if ( get_theme_mod( "solanawp_left_ad_{$i}_title", '' ) ) {
                $has_customizer_ads = true;
                break;
            }
        }

        if ( ! $has_customizer_ads && ! $is_main_sidebar_active && ! $is_ad_sidebar_active ) :
            ?>
            <!-- Default Ad Banner Placeholders (only shown if no customizer ads or widgets) -->
            <div class="ad-banner">
                <div>
                    <div style="font-size: 18px; margin-bottom: 8px;">🎯 A-ADS</div>
                    <div>Crypto Ad Network</div>
                    <div style="font-size: 12px; opacity: 0.7;">Configure in Customizer</div>
                </div>
            </div>

            <div class="ad-banner">
                <div>
                    <div style="font-size: 18px; margin-bottom: 8px;">💰 Coinzilla</div>
                    <div>Premium Crypto Ads</div>
                    <div style="font-size: 12px; opacity: 0.7;">Configure in Customizer</div>
                </div>
            </div>

            <div class="ad-banner">
                <div>
                    <div style="font-size: 18px; margin-bottom: 8px;">📱 Google AdSense</div>
                    <div>Main Revenue Stream</div>
                    <div style="font-size: 12px; opacity: 0.7;">Configure in Customizer</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">🌐 Media.net</div>
                    <div style="font-size: 14px;">Configure in Customizer</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">🛒 Amazon Associates</div>
                    <div style="font-size: 14px;">Configure in Customizer</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">🔐 Ledger Affiliate</div>
                    <div style="font-size: 14px;">Configure in Customizer</div>
                </div>
            </div>

            <!-- Quick access button to customizer -->
            <?php if ( current_user_can( 'customize' ) ) : ?>
            <div class="ad-banner" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 2px solid #3b82f6;">
                <div>
                    <div style="font-size: 16px; margin-bottom: 8px;">⚙️ Customize Ads</div>
                    <div style="font-size: 14px;">
                        <a href="<?php echo admin_url( 'customize.php?autofocus[section]=solanawp_left_ads_section' ); ?>"
                           style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                            Click to Configure
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php endif; ?>
    </aside>

<?php
/**
 * Right Sidebar for templates that support it
 * This would be called separately in templates that need both sidebars
 */
function solanawp_get_right_sidebar() {
    ob_start();
    ?>
    <aside id="secondary-right" class="widget-area sidebar-right" role="complementary">
        <?php
        // Display customizer ad banners for right side
        echo solanawp_render_customizer_ad_banners( 'right', 20 );

        // Check if there are any customizer ads configured for right side
        $has_right_customizer_ads = false;
        for ( $i = 1; $i <= 20; $i++ ) {
            if ( get_theme_mod( "solanawp_right_ad_{$i}_title", '' ) ) {
                $has_right_customizer_ads = true;
                break;
            }
        }

        // Show default banners if no customizer ads are configured
        if ( ! $has_right_customizer_ads ) :
            ?>
            <!-- Default Right Sidebar Banners -->
            <div class="ad-banner">
                <div>
                    <div style="font-size: 18px; margin-bottom: 8px;">💎 Premium Crypto</div>
                    <div>Exclusive Offers</div>
                    <div style="font-size: 12px; opacity: 0.7;">Configure in Customizer</div>
                </div>
            </div>

            <div class="ad-banner">
                <div>
                    <div style="font-size: 18px; margin-bottom: 8px;">🚀 Launch Platform</div>
                    <div>Token Launches</div>
                    <div style="font-size: 12px; opacity: 0.7;">Configure in Customizer</div>
                </div>
            </div>

            <div class="ad-banner">
                <div>
                    <div style="font-size: 18px; margin-bottom: 8px;">📱 Trading Bot</div>
                    <div>Automated Trading</div>
                    <div style="font-size: 12px; opacity: 0.7;">Configure in Customizer</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">🎯 Smart Contracts</div>
                    <div style="font-size: 14px;">Configure in Customizer</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">💰 Staking Pool</div>
                    <div style="font-size: 14px;">Configure in Customizer</div>
                </div>
            </div>

            <div class="ad-banner small">
                <div>
                    <div style="font-size: 16px; margin-bottom: 4px;">🔒 Cold Storage</div>
                    <div style="font-size: 14px;">Configure in Customizer</div>
                </div>
            </div>

            <!-- Quick access button to customizer for right side -->
            <?php if ( current_user_can( 'customize' ) ) : ?>
            <div class="ad-banner" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 2px solid #3b82f6;">
                <div>
                    <div style="font-size: 16px; margin-bottom: 8px;">⚙️ Customize Ads</div>
                    <div style="font-size: 14px;">
                        <a href="<?php echo admin_url( 'customize.php?autofocus[section]=solanawp_right_ads_section' ); ?>"
                           style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                            Click to Configure
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php endif; ?>
    </aside>
    <?php
    return ob_get_clean();
}
?>
