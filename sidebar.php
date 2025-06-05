<?php
/**
 * The sidebar (left) containing the main ad banner area.
 * Version 4: Simplified to only use unified Customizer ads or admin placeholder.
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

<aside id="secondary" class="widget-area sidebar" role="complementary">
    <?php
    // Display customizer ad banners (unified settings for both sidebars)
    $customizer_ads_output = '';
    if ( function_exists( 'solanawp_render_customizer_ad_banners' ) ) {
        $customizer_ads_output = solanawp_render_customizer_ad_banners( 6 ); // Render up to 6 ads
        echo $customizer_ads_output;
    }

    // Fallback content if no ads are configured via Customizer AND user can customize.
    // This ensures the placeholder only shows when truly no ads are set up by an admin.
    if ( empty(trim($customizer_ads_output)) && current_user_can('customize') ) :
        ?>
        <div class="ad-banner" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 2px solid #3b82f6; display:flex; align-items:center; justify-content:center;">
            <div style="text-align:center;">
                <div style="font-size: 16px; margin-bottom: 8px;">⚙️ <?php esc_html_e('Setup Sidebar Ads', 'solanawp'); ?></div>
                <div style="font-size: 14px;">
                    <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=solanawp_sidebar_ads_section' ) ); ?>"
                       style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                        <?php esc_html_e('Click to Configure Unified Ads', 'solanawp'); ?>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</aside>
