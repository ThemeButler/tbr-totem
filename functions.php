<?php

// Include Beans
require_once( get_template_directory() . '/lib/init.php' );


// Remove Beans Default Styling
remove_theme_support( 'beans-default-styling' );


// Enqueue uikit assets
beans_add_smart_action( 'beans_uikit_enqueue_scripts', 'totem_enqueue_uikit_assets', 7 );

function totem_enqueue_uikit_assets() {

	// Enqueue uikit extra components
	beans_uikit_enqueue_components( array( 'flex' ) );

	// Enqueue uikit overwrite theme folder
	beans_uikit_enqueue_theme( 'totem', get_stylesheet_directory_uri() . '/assets/less/uikit' );

	// Add the theme style as a uikit fragment to have access to all the variables
	beans_compiler_add_fragment( 'uikit', get_stylesheet_directory_uri() . '/assets/less/style.less', 'less' );

}


// Remove page post type comment support
beans_add_smart_action( 'init', 'totem_post_type_support' );

function totem_post_type_support() {

	remove_post_type_support( 'page', 'comments' );

}


// Setup document fragements, markups and attributes
beans_add_smart_action( 'beans_before_load_document', 'totem_setup_document' );

function totem_setup_document() {

	// Header
	beans_remove_attribute( 'beans_header', 'class', ' uk-block' );
	beans_wrap_inner_markup( 'beans_fixed_wrap_header', 'totem_overlay_navigation', 'div', array( 'class' => 'tm-overlay-navigation uk-clearfix' ) );

	// Breadcrumb
	beans_remove_action( 'beans_breadcrumb' );

	// Navigation
	beans_add_attribute( 'beans_sub_menu_wrap', 'class', 'uk-dropdown-center' );
	beans_remove_attribute( 'beans_menu_item_child_indicator', 'class', 'uk-margin-small-left' );

	// Offcanvas
	beans_add_attribute( 'beans_widget_area_offcanvas_bar', 'class', 'uk-offcanvas-bar-flip' );
	beans_add_attribute( 'beans_primary_menu_offcanvas_button', 'class', 'uk-button-primary' );

	// Post content
	beans_remove_attribute( 'beans_post', 'class', ' uk-panel-box' );
	beans_add_attribute( 'beans_post_content', 'class', 'uk-text-large' );
	beans_add_attribute( 'beans_post_more_link', 'class', 'uk-button uk-button-small' );

	// Post image
	beans_modify_action_hook( 'beans_post_image', 'beans_post_title_before_markup' );

	// Post meta
	beans_remove_action( 'beans_post_meta' );
	beans_remove_action( 'beans_post_meta_tags' );
	beans_remove_action( 'beans_post_meta_categories' );

	// Post read more
	beans_replace_attribute( 'beans_next_icon_more_link', 'class' , 'angle-double-right', 'long-arrow-right' );

	// Posts pagination
	beans_remove_markup( 'beans_previous_icon_posts_pagination' );
	beans_remove_markup( 'beans_next_icon_posts_pagination' );

	// Comment badge
	beans_add_attribute( 'beans_moderator_badge', 'class', 'uk-border-rounded uk-align-right' );
	beans_add_attribute( 'beans_moderation_badge', 'class', 'uk-border-rounded uk-align-right' );

	// Comment meta
	beans_modify_action_priority( 'beans_comment_metadata', 9 );

	// Comment form
	beans_add_attribute( 'beans_comment_form_submit', 'class', 'uk-button-large' );
	beans_replace_attribute( 'beans_comment_fields_wrap', 'class', 'uk-width-medium-1-1', 'uk-width-medium-4-10' );
	beans_replace_attribute( 'beans_comment_form', 'class', 'uk-width-medium-1-3', 'uk-width-medium-1-1' );

	if ( !is_user_logged_in() )
		beans_replace_attribute( 'beans_comment_form_comment', 'class', 'uk-width-medium-1-1', 'uk-width-medium-6-10' );

	// Search
	beans_add_attribute( 'beans_search_title', 'class', 'uk-margin-large-bottom' );

	if ( is_search() )
		beans_remove_action( 'beans_post_image' );

}


// Modify beans layout (filter)
beans_add_smart_action( 'beans_layout_grid_settings', 'totem_layout_grid_settings' );

function totem_layout_grid_settings( $layouts ) {

	return array_merge( $layouts, array(
		'grid' => 10,
		'sidebar_primary' => 2,
		'sidebar_secondary' => 2,
	) );

}


// Modify beans default layout (filter)
beans_add_smart_action( 'beans_default_layout', 'totem_default_layout' );

function totem_default_layout( $layouts ) {

	return 'sp_c_ss';

}


// Modify the categories widget count (filter)
beans_add_smart_action( 'beans_widget_count_output', 'totem_widget_counts' );

function totem_widget_counts() {

	return '$2';

}


// Modify the tags cloud widget (filter)
beans_add_smart_action( 'wp_generate_tag_cloud', 'totem_widget_tags_cloud' );

function totem_widget_tags_cloud( $output ) {

	$output = preg_replace( '#tag-link-#', 'uk-button uk-button-mini tag-link-', $output );
	$output = preg_replace( "#style='font-size:.+pt;'#", '', $output );

	return $output;

}


// Remove comment after note (filter).
beans_add_smart_action( 'comment_form_defaults', 'totem_comment_form_defaults' );

function totem_comment_form_defaults( $args ) {

	$args['comment_notes_after'] = '';

	return $args;

}


// Modify comment title
beans_add_smart_action( 'beans_comment_title_append_markup', 'totem_comment_title_prefix' );

function totem_comment_title_prefix() {

	echo beans_open_markup( 'totem_comment_title_extra', 'span', array(
			'class' => 'uk-margin-small-left',
		) );

		echo beans_output( 'totem_comment_title_extra', __( 'says:', 'tm-totem' ) );

	echo beans_close_markup( 'totem_comment_title_extra', 'span' );

}


// Add avatar uikit circle class (filter)
beans_add_smart_action( 'get_avatar', 'totem_avatar' );

function totem_avatar( $output ) {

	return str_replace( "class='avatar", "class='avatar uk-border-circle", $output ) ;

}


// Add footer content
add_filter( 'beans_footer_credit_right_text_output', 'totem_footer' );

function totem_footer() { ?>

  <a href="http://www.themebutler.com/themes/totem/" target="_blank" title="Totem theme for WordPress">Totem</a> theme for <a href="http://wordpress.org" target="_blank">WordPress</a>.

<? }
