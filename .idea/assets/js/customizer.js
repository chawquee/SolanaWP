/**
 * SolanaWP Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 * Used when a Customizer setting has 'transport' => 'postMessage'.
 * Works in conjunction with settings defined in inc/customizer.php.
 */

( function( $ ) {

    // Site Title (Blogname) - For selective refresh, its render_callback in PHP handles this.
    // If you still want JS control for direct text update for some reason:
    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            // This would target an element like <p class="site-title"><a href="...">...</a></p>
            // Or the .brand-name if it's meant to display bloginfo('name')
            // The PHP selective refresh partial is usually preferred for this.
            // $( '.site-header .brand-name a' ).text( to ); // If .brand-name shows site title
        } );
    } );

    // Site Tagline (Blogdescription) - For selective refresh, its render_callback in PHP handles this.
    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            // $( '.site-description' ).text( to ); // If you have an element with this class
        } );
    } );

    // Primary Accent Color (solanawp_primary_accent_color)
    // Defined in inc/customizer.php with 'transport' => 'postMessage'
    wp.customize( 'solanawp_primary_accent_color', function( value ) {
        value.bind( function( newval ) {
            // 1. Update elements directly that use this specific color if they don't use CSS variables.
            // Example:
            // $( 'a' ).not('.wp-block-button__link').css( 'color', newval );
            // $( '.text-purple' ).css( 'color', newval + '!important' );
            // $( '.sol-balance-value' ).css( 'color', newval );

            // 2. OR, a more flexible way: Update CSS Variables.
            //    Your main.css would then use these variables, e.g., color: var(--solanawp-primary-accent-color);
            //    The inline <style> block generated by solanawp_customizer_css_output() in PHP
            //    will define these variables initially. This JS updates them for live preview.
            var primaryColorVar = '--solanawp-primary-accent-color';
            var secondaryColorVar = '--solanawp-secondary-accent-color'; // Get current secondary for gradient
            var currentSecondary = wp.customize('solanawp_secondary_accent_color') ? wp.customize('solanawp_secondary_accent_color').get() : '#a855f7';


            // Create or update an inline style tag for dynamic CSS variable changes.
            var $dynamicStyle = $('#solanawp-customizer-preview-styles');
            if (!$dynamicStyle.length) {
                $('head').append('<style id="solanawp-customizer-preview-styles"></style>');
                $dynamicStyle = $('#solanawp-customizer-preview-styles');
            }

            var customCSS = `
				:root {
					${primaryColorVar}: ${newval};
				}
				/* Re-apply styles that use this variable or related gradients */
                a:hover,
                .primary-menu li a:hover,
                .primary-menu .current-menu-item > a,
                .primary-menu .current-menu-ancestor > a,
                .entry-title a:hover,
                .comment-metadata a:hover,
                .widget-area .widget ul li a:hover,
                .site-footer .site-info a:hover,
                .text-purple, /* from hannisolsvelte.html */
                .sol-balance-value, /* from hannisolsvelte.html */
                .reply .comment-reply-link,
                .widget-area .widget_search .search-submit:hover /* Assuming secondary accent is used for hover */
                {
                    color: ${newval} !important; /* Important may be needed to override existing */
                }

                .check-btn, .form-submit .submit, .read-more-button,
                .the-posts-pagination .nav-links .page-numbers:hover,
                .the-posts-pagination .nav-links .page-numbers.current,
                .reply .comment-reply-link:hover,
                .widget-area .widget_search .search-submit
                 {
                    background: linear-gradient(135deg, ${newval} 0%, ${currentSecondary} 100%) !important;
                    border-color: ${newval} !important; /* For buttons that also have borders */
                    color: #fff !important; /* Ensure text remains white on colored buttons */
                }
                 .check-btn:hover, .form-submit .submit:hover, .read-more-button:hover {
                    /* Assuming hover might use the secondary color or a darker shade of primary */
                 }


                .address-input:focus,
                .comment-form input[type="text"]:focus,
                .comment-form input[type="email"]:focus,
                .comment-form input[type="url"]:focus,
                .comment-form textarea:focus {
                    border-color: ${newval} !important;
                    box-shadow: 0 0 0 3px ${solanawp_hex_to_rgba_js(newval, 0.1)} !important;
                }
			`;
            $dynamicStyle.html(customCSS);
        } );
    } );

    // Secondary Accent Color (solanawp_secondary_accent_color) - for gradients
    // This is set to 'refresh' in PHP, but if you changed to 'postMessage':
    wp.customize( 'solanawp_secondary_accent_color', function( value ) {
        value.bind( function( newval ) {
            var primaryColorVar = '--solanawp-primary-accent-color';
            var secondaryColorVar = '--solanawp-secondary-accent-color';
            var currentPrimary = wp.customize('solanawp_primary_accent_color') ? wp.customize('solanawp_primary_accent_color').get() : '#7c3aed';

            var $dynamicStyle = $('#solanawp-customizer-preview-styles');
            if (!$dynamicStyle.length) {
                $('head').append('<style id="solanawp-customizer-preview-styles"></style>');
                $dynamicStyle = $('#solanawp-customizer-preview-styles');
            }
            // Update only the secondary color part of styles, assuming primary is stable or also live updated
            var customCSS = `
				:root {
					${secondaryColorVar}: ${newval};
				}
                .check-btn, .form-submit .submit, .read-more-button {
                    background: linear-gradient(135deg, ${currentPrimary} 0%, ${newval} 100%) !important;
                }
                .read-more-button:hover, .widget-area .widget_search .search-submit:hover {
                    background: ${newval} !important; /* Example: hover uses secondary */
                }
			`;
            // Append or update existing styles
            var existingCSS = $dynamicStyle.html();
            var rootRuleRegex = /:root\s*{[^}]*}/;
            if (rootRuleRegex.test(existingCSS)) {
                existingCSS = existingCSS.replace(rootRuleRegex, function(match) {
                    if (match.includes(secondaryColorVar + ':')) {
                        return match.replace(new RegExp(secondaryColorVar + ':[^;]*;'), secondaryColorVar + ': ' + newval + ';');
                    } else {
                        return match.substring(0, match.length - 1) + `    ${secondaryColorVar}: ${newval};\n}`;
                    }
                });
            } else {
                existingCSS += `\n:root { ${secondaryColorVar}: ${newval}; }`;
            }
            // This is simplistic for updating gradients; more robust solution might be needed
            // or simply rely on 'refresh' for gradient-affecting colors.
            $dynamicStyle.html(existingCSS + '\n' + customCSS.replace(/:root\s*{[^}]*}/, '')); // Avoid duplicate :root
        } );
    } );


    // Footer Copyright Text (solanawp_footer_copyright_text)
    // This uses selective refresh (partial) via PHP, so JS here is for direct DOM update if preferred over render_callback.
    wp.customize( 'solanawp_footer_copyright_text', function( value ) {
        value.bind( function( newHtml ) {
            // Target the specific element where copyright text is displayed in footer.php
            // Ensure footer.php has an element with class .site-info or similar unique selector.
            $( '.site-footer .site-info' ).html( newHtml ); // Assuming wp_kses_post in PHP, so newHtml is safe.
        } );
    } );

    // Helper function for JS equivalent of PHP hex_to_rgba
    function solanawp_hex_to_rgba_js(hex, alpha) {
        var r, g, b;
        hex = hex.replace('#', '');
        if (hex.length === 3) {
            r = parseInt(hex.substring(0, 1) + hex.substring(0, 1), 16);
            g = parseInt(hex.substring(1, 2) + hex.substring(1, 2), 16);
            b = parseInt(hex.substring(2, 3) + hex.substring(2, 3), 16);
        } else if (hex.length === 6) {
            r = parseInt(hex.substring(0, 2), 16);
            g = parseInt(hex.substring(2, 4), 16);
            b = parseInt(hex.substring(4, 6), 16);
        } else {
            return ''; // Invalid hex
        }
        return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
    }

} )( jQuery );
