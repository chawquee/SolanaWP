<?php
/**
 * Template part for displaying the "Transaction Analysis" card for the Solana Checker.
 * Called by template-address-checker.php or front-page.php.
 * Structure and classes from hannisolsvelte.html.
 *
 * @package SolanaWP
 * @since SolanaWP 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="card" id="transactionAnalysisCard" style="display:none;"> <?php // Structure & ID from hannisolsvelte.html, initially hidden ?>
    <div class="card-header"> <?php // Class from hannisolsvelte.html ?>
        <svg class="icon text-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"> <?php // Class and SVG from hannisolsvelte.html ?>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
        </svg>
        <h2 class="card-title"><?php esc_html_e( 'Transaction Analysis', 'solanawp' ); ?></h2> <?php // Text from hannisolsvelte.html ?>
    </div>
    <div class="card-content"> <?php // Class from hannisolsvelte.html ?>
        <div class="metrics-grid"> <?php // Class from hannisolsvelte.html ?>
            <div class="metric-card">
                <div class="metric-value text-blue" id="totalTransactions">-</div> <?php // Placeholder for JS, text from hannisolsvelte.html ?>
                <div class="metric-label"><?php esc_html_e( 'Total Transactions', 'solanawp' ); ?></div> <?php // Text from hannisolsvelte.html ?>
            </div>
            <div class="metric-card">
                <div class="metric-value text-green" id="firstActivity">-</div> <?php // Placeholder for JS, text from hannisolsvelte.html ?>
                <div class="metric-label"><?php esc_html_e( 'First Activity', 'solanawp' ); ?></div> <?php // Text from hannisolsvelte.html ?>
            </div>
            <div class="metric-card">
                <div class="metric-value text-purple" id="lastActivity">-</div> <?php // Placeholder for JS, text from hannisolsvelte.html ?>
                <div class="metric-label"><?php esc_html_e( 'Last Activity', 'solanawp' ); ?></div> <?php // Text from hannisolsvelte.html ?>
            </div>
        </div>

        <h4><?php esc_html_e( 'Recent Transactions', 'solanawp' ); ?></h4> <?php // Text from hannisolsvelte.html - h4 styling in main.css ?>
        <div class="recent-transactions-list" id="recentTransactionsList"> <?php // JS Target. Class for styling. ?>
            <?php // Hidden template for a single transaction item, to be cloned by JS ?>
            <div class="recent-transaction-item-template" style="display:none;">
                <div> <?php // Inner flex container from main.css based on HTML structure ?>
                    <div>
                        <div class="tx-type"></div> <?php // JS Target for type ?>
                        <div class="tx-signature"></div> <?php // JS Target for signature ?>
                    </div>
                    <div>
                        <div class="tx-amount"></div> <?php // JS Target for amount ?>
                        <div class="tx-time"></div> <?php // JS Target for time ?>
                    </div>
                </div>
            </div>
            <p class="no-transactions-message" style="display:none;"><?php esc_html_e( 'No recent transactions found or data unavailable.', 'solanawp' ); ?></p>
        </div>
    </div>
</div>
