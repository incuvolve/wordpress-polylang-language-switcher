<?php
/**
 * Polylang language switcher for WordPress block themes.
 *
 * Add this code to your child theme's functions.php.
 * Replace NAV_BLOCK_REF with your navigation block ID (see README.md Step 1).
 */

define( 'WP_PL_NAV_BLOCK_REF', 261 ); // <-- replace with your nav block ID

// Enqueue child theme stylesheet.
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'wp-pl-child-theme-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );
} );

// Inject Polylang language links into the block navigation.
add_filter( 'render_block_core/navigation', function( $content, $block ) {
    if ( is_admin() ) return $content;
    if ( ( $block['attrs']['ref'] ?? null ) !== WP_PL_NAV_BLOCK_REF ) return $content;
    if ( ! function_exists( 'pll_the_languages' ) ) return $content;

    $languages = pll_the_languages( [ 'raw' => 1, 'hide_if_empty' => 0 ] );
    if ( empty( $languages ) ) return $content;

    $items = '';
    foreach ( $languages as $lang ) {
        $class = 'wp-block-navigation-item menu-item-language' . ( $lang['current_lang'] ? ' is-current-language' : '' );
        $items .= sprintf(
            '<li class="%s"><a class="wp-block-navigation-item__content" href="%s" hreflang="%s" lang="%s">%s</a></li>',
            esc_attr( $class ),
            esc_url( $lang['url'] ),
            esc_attr( $lang['locale'] ),
            esc_attr( $lang['locale'] ),
            esc_html( $lang['name'] )
        );
    }

    $pos = strrpos( $content, '</ul>' );
    if ( false === $pos ) return $content;

    return substr_replace( $content, $items . '</ul>', $pos, 5 );
}, 20, 2 );
