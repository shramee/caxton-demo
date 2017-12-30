<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */ ?>
	<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">

		<?php wp_head(); ?>
	</head>

	<body
		class="wp-admin wp-core-ui js  gutenberg-editor-page post-new-php auto-fold admin-bar post-type-post branch-4-9 version-4-9 admin-color-fresh locale-en-us customize-support svg">

	<div id="wpwrap">
		<div id="adminmenumain" role="navigation" aria-label="Main menu">
			<a href="#wpbody-content" class="screen-reader-shortcut">Skip to main content</a>
			<a href="#wp-toolbar" class="screen-reader-shortcut">Skip to toolbar</a>
			<div id="adminmenuback"></div>
			<div id="adminmenuwrap">
				<ul id="adminmenu">

					<li>
						<a href="#">
							<div class="wp-menu-image dashicons-before dashicons-archive"><br></div>
							<div class="wp-menu-name">Header</div>
						</a>
					</li>

					<li class="wp-not-current-submenu wp-menu-separator" aria-hidden="true">
						<div class="separator"></div>
					</li>

					<li>
						<a href="#">
							<div class="wp-menu-image dashicons-before dashicons-admin-page"><br></div>
							<div class="wp-menu-name">Page 1</div>
						</a>
					</li>

					<li>
						<a href="#">
							<div class="wp-menu-image dashicons-before dashicons-admin-page"><br></div>
							<div class="wp-menu-name">Page 2</div>
						</a>
					</li>

					<li>
						<a href="#">
							<div class="wp-menu-image dashicons-before dashicons-edit"><br></div>
							<div class="wp-menu-name">Post</div>
						</a>
					</li>

					<li class="wp-not-current-submenu wp-menu-separator" aria-hidden="true">
						<div class="separator"></div>
					</li>

					<li>
						<a href="#">
							<div class="wp-menu-image dashicons-before dashicons-archive" style="transform:rotate(180deg);"><br></div>
							<div class="wp-menu-name">Footer</div>
						</a>
					</li>

				</ul>
			</div>
		</div>
		<div id="wpcontent">
			<div id="wpbody" role="main">
				<div id="wpbody-content" aria-label="Main content" tabindex="0">
					<div class="nvda-temp-fix screen-reader-text">&nbsp;</div>
					<div class="gutenberg">
						<div id="editor" class="gutenberg__editor"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php wp_footer();
