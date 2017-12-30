<?php

add_action( 'init', function () {
	show_admin_bar( true );
	require( ABSPATH . '/wp-admin/includes/class-wp-screen.php' );
	require( ABSPATH . '/wp-admin/includes/screen.php' );
	require( ABSPATH . '/wp-admin/includes/template.php' );

	add_action( 'wp_enqueue_scripts', function () {
		//gutenberg.test/wp-admin/load-styles.php?c=1&amp;dir=ltr&amp;load%5B%5D=dashicons,admin-bar,common,forms,admin-menu,dashboard,list-tables,edit,revisions,media,themes,about,nav-menus,wp-pointer,widgets&amp;load%5B%5D=,site-icon,l10n,buttons,wp-auth-check&amp;ver=4.9-RC1-42056
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 'common' );
		wp_enqueue_style( 'forms' );
		wp_enqueue_style( 'dashboard' );
		wp_enqueue_style( 'list-tables' );
		wp_enqueue_style( 'edit' );
		wp_enqueue_style( 'revisions' );
		wp_enqueue_style( 'media' );
		wp_enqueue_style( 'admin-menu' );
		wp_enqueue_style( 'admin-bar' );
		wp_enqueue_style( 'themes' );
		wp_enqueue_style( 'about' );
		wp_enqueue_style( 'nav-menus' );
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'widgets' );
		wp_enqueue_style( 'l10n' );
		wp_enqueue_style( 'buttons' );
		/*wp_enqueue_style('');
		wp_enqueue_style('');
		wp_enqueue_style('');*/
	} );
	add_action( 'wp_enqueue_scripts', 'gutenberg_editor_scripts_and_styles' );

	if ( ! is_user_logged_in() ) {
		add_filter( 'wp_insert_post_empty_content', '__return_true', PHP_INT_MAX - 1, 2 );
	}
} );
function caxton_give_permissions( $allcaps, $cap, $args ) {
	if ( is_user_logged_in() ) {
		return $allcaps;
	}
	// give author some permissions
	$allcaps['read']                 = true;
	$allcaps['manage_categories']    = true;
	$allcaps['edit_post']            = true;
	$allcaps['edit_posts']           = true;
	$allcaps['edit_others_posts']    = true;
	$allcaps['edit_published_posts'] = true;

	// better safe than sorry
	$allcaps['edit_pages']        = false;
	$allcaps['switch_themes']     = false;
	$allcaps['edit_themes']       = false;
	$allcaps['edit_pages']        = false;
	$allcaps['activate_plugins']  = false;
	$allcaps['edit_plugins']      = false;
	$allcaps['edit_users']        = false;
	$allcaps['import']            = false;
	$allcaps['unfiltered_html']   = false;
	$allcaps['edit_plugins']      = false;
	$allcaps['unfiltered_upload'] = false;

	return $allcaps;
}

add_filter( 'user_has_cap', 'caxton_give_permissions', 10, 3 );

function caxton_remove_toolbar_node( $wp_admin_bar ) {
	if ( is_user_logged_in() ) {
		return;
	}
	// replace 'updraft_admin_node' with your node id
	$wp_admin_bar->remove_node( 'wpseo-menu' );
	$wp_admin_bar->remove_node( 'new-content' );
	$wp_admin_bar->remove_node( 'comments' );
	$wp_admin_bar->remove_node( 'wp-logo' );
	$wp_admin_bar->remove_node( 'bar-about' );
	$wp_admin_bar->remove_node( 'search' );
	$wp_admin_bar->remove_node( 'wp-logo-external' );
	$wp_admin_bar->remove_node( 'about' );
	$wp_admin_bar->add_menu( array(
		'id'    => 'wp-logo',
		'title' => '<span class="ab-icon"></span>',
		'href'  => home_url(),
		'meta'  => array(
			'class' => 'wp-logo',
			'title' => __( 'Caxton' ),
		),
	) );
	$wp_admin_bar->add_menu( array(
		'id'    => 'caxton',
		'title' => 'Meet Caxton',
		'href'  => home_url(),
		'meta'  => array(
			'title' => __( 'Caxton' ),
		),
	) );


	$wp_admin_bar->add_menu( array(
			'id'    => 'preview',
			'title' => 'Preview',
			'href'  => home_url(),
			'meta'  => array(
				'title' => __( 'Preview' ),
			),
		)
	);

}

add_action( 'admin_bar_menu', 'caxton_remove_toolbar_node', 999 );


add_action( 'wp_ajax_nopriv_query-attachments', 'caxton_wp_ajax_nopriv_query_attachments' );
/**
 * Ajax handler for querying attachments.
 *
 * @since 3.5.0
 */
function caxton_wp_ajax_nopriv_query_attachments() {

	$query = isset( $_REQUEST['query'] ) ? (array) $_REQUEST['query'] : array();
	$keys  = array(
		's',
		'order',
		'orderby',
		'posts_per_page',
		'paged',
		'post_mime_type',
		'post_parent',
		'post__in',
		'post__not_in',
		'year',
		'monthnum'
	);
	foreach ( get_taxonomies_for_attachments( 'objects' ) as $t ) {
		if ( $t->query_var && isset( $query[ $t->query_var ] ) ) {
			$keys[] = $t->query_var;
		}
	}

	$query              = array_intersect_key( $query, array_flip( $keys ) );
	$query['post_type'] = 'attachment';
	if ( MEDIA_TRASH
			 && ! empty( $_REQUEST['query']['post_status'] )
			 && 'trash' === $_REQUEST['query']['post_status'] ) {
		$query['post_status'] = 'trash';
	} else {
		$query['post_status'] = 'inherit';
	}

	// Filter query clauses to include filenames.
	if ( isset( $query['s'] ) ) {
		add_filter( 'posts_clauses', '_filter_query_attachment_filenames' );
	}

	/**
	 * Filters the arguments passed to WP_Query during an Ajax
	 * call for querying attachments.
	 *
	 * @since 3.7.0
	 *
	 * @see WP_Query::parse_query()
	 *
	 * @param array $query An array of query variables.
	 */
	$query = apply_filters( 'ajax_query_attachments_args', $query );
	$query = new WP_Query( $query );

	$posts = array_map( 'wp_prepare_attachment_for_js', $query->posts );
	$posts = array_filter( $posts );

	wp_send_json_success( $posts );
}
