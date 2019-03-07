<?php
/**
 * Rêvell'Action theme functions and definitions
 */

/*-----------------------------------------------------------------------------------*/
/* Include the parent theme style.css
/*-----------------------------------------------------------------------------------*/

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}

/*-----------------------------------------------------------------------------------
 * Theme enhancement
 * @author Olivier Jullien <https://github.com/ojullien>
 *-----------------------------------------------------------------------------------*/

 /**
 * Sets up a filter to require that API consumers be authenticated, which effectively prevents anonymous external access.
 * We should not disable the REST API, because doing so would break future WordPress Admin functionality that will depend on the API being active.
 *
 * @link https://developer.wordpress.org/rest-api/using-the-rest-api/frequently-asked-questions/#can-i-disable-the-rest-api
 *
 * @param WP_Error|null|bool WP_Error if authentication error, null if authentication method wasn't used, true if authentication succeeded.
 * @return WP_Error|null|bool WP_Error if the user is not logged in, the $result, otherwise true.
 */
function setAuthenticationForREST( $result ) {
    if( !empty( $result )) {
        // Error from another authentication handler.
        return $result;
    }

    if( !is_user_logged_in()) {
        return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', [ 'status' => 401 ] );
    }
    return $result;
}
add_filter( 'rest_authentication_errors', 'setAuthenticationForREST' );

/**
 * Add more or remove allowed mime types and file extensions..
 *
 * @param array $aMimes The list of the default allowed mime types.
 * @return array
 */
function getCustomAllowedMimeTypes( array $aMimes ): array {
    // Remove a mime type.
    unset( $aMimes['class'] );
    unset( $aMimes['txt|asc|c|cc|h|srt'] );
    // New allowed mime types.
    $aMimes['txt|asc|srt'] = 'text/plain';
    $aMimes['svg'] = 'image/svg+xml';
    $aMimes['webp'] = 'image/webp';
    return $aMimes;
}
add_filter('upload_mimes', 'getCustomAllowedMimeTypes');

/**
 * Removes version parameter from any enqueued scripts.
 *
 * @param string $sSrc The source URL of the enqueued style.
 * @param string $sHandle The style's registered handle.
 * @return string
 */
function filterVersionParameterFromEnqueuedScript( string $sSrc, string $sHandle ) : string {
    if( false !== strpos( $sSrc, 'ver=' )){
        $sSrc = remove_query_arg( 'ver', $sSrc );
    }
    return $sSrc;
}
add_filter( 'style_loader_src', 'filterVersionParameterFromEnqueuedScript', 90,2 );
add_filter( 'script_loader_src', 'filterVersionParameterFromEnqueuedScript', 90,2 );

/**
 * Removes the WordPress generator tags
 */
remove_action( 'wp_head', 'wp_generator');


/**
 * Add configuration to TinyMCE.
 *
 * @param array $aDefaults Array of TinyMCE config.
 * @return array
 */
function getCustomTinyMCE( array $aInit ) : array {
    $sDefault = '"000000", "Black","993300", "Burnt orange","333300", "Dark olive","003300", "Dark green","003366", "Dark azure","000080", "Navy Blue","333399", "Indigo","333333", "Very dark gray","800000", "Maroon","FF6600", "Orange","808000", "Olive","008000", "Green","008080", "Teal","0000FF", "Blue","666699", "Grayish blue","808080", "Gray","FF0000", "Red","FF9900", "Amber","99CC00", "Yellow green","339966", "Sea green","33CCCC", "Turquoise","3366FF", "Royal blue","800080", "Purple","999999", "Medium gray","FF00FF", "Magenta","FFCC00", "Gold","FFFF00", "Yellow","00FF00", "Lime","00FFFF", "Aqua","00CCFF", "Sky blue","993366", "Red violet","FFFFFF", "White","FF99CC", "Pink","FFCC99", "Peach","FFFF99", "Light yellow","CCFFCC", "Pale green","CCFFFF", "Pale cyan","99CCFF", "Light sky blue","CC99FF", "Plum"';
    $sCustom = '"59058D", "Violet (revellaction)", "DA5C05", "Orange (revellaction)"';
    $aInit['textcolor_map'] = '['.$sCustom.','.$sDefault.']';
    $aInit['textcolor_rows'] = 6;
    $aInit['textcolor_cols'] = 7;
    return $aInit;
}
add_filter('tiny_mce_before_init', 'getCustomTinyMCE');

/**
 * Add custom color to gutenber
 */
add_theme_support( 'editor-color-palette', [
    [
        'name' => __( 'Violet (revellaction)', 'revellaction' ),
        'slug' => 'violet-revellaction',
        'color' => '#59058D'
    ],
    [
        'name' => __( 'Orange (revellaction)', 'revellaction' ),
        'slug' => 'orange-revellaction',
        'color' => '#DA5C05'
    ],
    [
        'name' => __( 'Pale pink', 'gutenberg' ),
        'slug' => 'pale-pink',
        'color' => '#f78da7'
    ],
    [
        'name' => __( 'Vivid red', 'gutenberg' ),
        'slug' => 'vivid-red',
        'color' => '#cf2e2e'
    ],
    [
        'name' => __( 'Luminous vivid orange', 'gutenberg' ),
        'slug' => 'luminous-vivid-orange',
        'color' => '#ff6900'
    ],
    [
        'name' => __( 'Luminous vivid amber', 'gutenberg' ),
        'slug' => 'luminous-vivid-amber',
        'color' => '#fcb900'
    ],
    [
        'name' => __( 'Light green cyan', 'gutenberg' ),
        'slug' => 'light-green-cyan',
        'color' => '#7bdcb5'
    ],
    [
        'name' => __( 'Vivid green cyan', 'gutenberg' ),
        'slug' => 'vivid-green-cyan',
        'color' => '#00d084'
    ],
    [
        'name' => __( 'Pale cyan blue', 'gutenberg' ),
        'slug' => 'pale-cyan-blue',
        'color' => '#8ed1fc'
    ],
    [
        'name' => __( 'Vivid cyan blue', 'gutenberg' ),
        'slug' => 'vivid-cyan-blue',
        'color' => '#0693e3'
    ],
    [
        'name' => __( 'Very light gray', 'gutenberg' ),
        'slug' => 'very-light-gray',
        'color' => '#eeeeee'
    ],
    [
        'name' => __( 'Cyan bluish gray', 'gutenberg' ),
        'slug' => 'cyan-bluish-gray',
        'color' => '#abb8c3'
    ],
    [
        'name' => __( 'Very dark gray', 'gutenberg' ),
        'slug' => 'very-dark-gray',
        'color' => '#313131'
    ]
] );
