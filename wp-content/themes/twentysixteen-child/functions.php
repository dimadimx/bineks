<?php
/**
 * Twenty Sixteen Child
 */

//hook into the init action
add_action('init', 'create_event_post_type', 0);
add_action('init', 'create_news_post_type', 0);

//create a custom post type
function create_event_post_type() {
	register_post_type(
		'events',
		[
			'labels'      => [
				'name'          => __('Events'),
				'singular_name' => __('Event'),
			],
			'public'      => TRUE,
			'has_archive' => TRUE,
			'rewrite'     => ['slug' => 'events'],
		]
	);
}

//create a custom post type
function create_news_post_type() {
	register_post_type(
		'news',
		[
			'labels'      => [
				'name' => __('News'),
			],
			'public'      => TRUE,
			'has_archive' => TRUE,
			'rewrite'     => ['slug' => 'news'],
		]
	);
}

//check if advanced custom fields exists
add_action('admin_init', 'check_advanced_custom_fields_plugin');
function check_advanced_custom_fields_plugin() {
	if (is_admin() && ! class_exists('ACF')) {
		add_action('admin_notices', 'advanced_custom_fields_notice');
	}
}

//show notice if advanced custom fields not exists
function advanced_custom_fields_notice() {
	printf(
		"<div class=\"error\"><p>%s</p></div>", __(
			'Sorry, but Advanced Custom Fields Plugin is required to use Twenty Sixteen Child Theme.'
		)
	);
}

//check if CKEditor exists
add_action('admin_init', 'check_ckeditor_plugin');
function check_ckeditor_plugin() {
	if (is_admin() && ! class_exists('ckeditor_wordpress')) {
		/** todo: External link / wpLink is not defined / conflict Advanced Custom Fields with CKEditor for WordPress */
		//		add_action('admin_notices', 'ckeditor_wordpress_notice');
	}
}

//show notice if CKEditor not exists
function ckeditor_wordpress_notice() {
	printf(
		"<div class=\"error\"><p>%s</p></div>", __(
			'Sorry, but CKEditor wordpress Plugin is required to use Twenty Sixteen Child Theme.'
		)
	);
}

//add custom filed to Event post type
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group(
		[
			'key'                   => 'group_event',
			'title'                 => 'Event',
			'fields'                => [
				[
					'key'               => 'field_start_date',
					'label'             => 'Start Date',
					'name'              => 'start_date',
					'type'              => 'date_picker',
					'instructions'      => '',
					'required'          => 1,
					'conditional_logic' => 0,
					'wrapper'           => [
						'width' => '',
						'class' => '',
						'id'    => '',
					],
					'display_format'    => 'd/m/Y',
					'return_format'     => 'd/m/Y',
					'first_day'         => 1,
				],
				[
					'key'               => 'field_end_date',
					'label'             => 'End Date',
					'name'              => 'end_date',
					'type'              => 'date_picker',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => [
						'width' => '',
						'class' => '',
						'id'    => '',
					],
					'display_format'    => 'd/m/Y',
					'return_format'     => 'd/m/Y',
					'first_day'         => 1,
				],
				[
					'key'               => 'field_select',
					'label'             => 'Select',
					'name'              => 'select',
					'type'              => 'radio',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => [
						'width' => '',
						'class' => '',
						'id'    => '',
					],
					'choices'           => [
						'News'          => 'News',
						'External link' => 'External link',
					],
					'allow_null'        => 1,
					'other_choice'      => 0,
					'default_value'     => '',
					'layout'            => 'vertical',
					'return_format'     => 'value',
					'save_other_choice' => 0,
				],
				[
					'key'               => 'field_news',
					'label'             => 'News',
					'name'              => 'news',
					'type'              => 'relationship',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => [
						[
							[
								'field'    => 'field_select',
								'operator' => '==',
								'value'    => 'News',
							],
						],
					],
					'wrapper'           => [
						'width' => '',
						'class' => '',
						'id'    => '',
					],
					'post_type'         => [
						0 => 'news',
					],
					'taxonomy'          => '',
					'filters'           => [
						0 => 'search',
					],
					'elements'          => '',
					'min'               => 0,
					'max'               => 1,
					'return_format'     => 'id',
				],
				[
					'key'               => 'field_link',
					'label'             => 'Link',
					'name'              => 'link',
					'type'              => 'link',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => [
						[
							[
								'field'    => 'field_select',
								'operator' => '==',
								'value'    => 'External link',
							],
						],
					],
					'wrapper'           => [
						'width' => '',
						'class' => '',
						'id'    => '',
					],
					'return_format'     => 'array',
				],
			],
			'location'              => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'events',
					],
				],
			],
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => TRUE,
			'description'           => '',
		]
	);
}

//custom sort events on homepage
add_action('pre_get_posts', 'home_events_pre_get_posts');

function home_events_pre_get_posts($query) {
	if (is_admin()) {
		return $query;
	}

	if (isset($query->query_vars['post_type'])
		&& $query->query_vars['post_type'] == 'events'
	) {

		$query->set(
			'meta_query', [
				'relation' => 'AND',
				[
					'relation' => 'OR',
					[
						'key'     => 'end_date',
						'value'   => date("Ymd"),
						'compare' => '>=',
						'type'    => 'DATE',
					],
					[
						'key'   => 'end_date',
						'value' => FALSE,
						'type'  => 'BOOLEAN',
					],
				],
				[
					'key'     => 'start_date',
					'value'   => date("Ymd"),
					'compare' => '<=',
					'type'    => 'DATE',
				],
			]
		);
		$query->set('orderby', 'meta_value');
		$query->set('meta_key', 'start_date');
		$query->set('order', 'ASC');

	}

	return $query;
}

//add_action('wp_enqueue_scripts', 'custom_ajax_pagination_script_load', 100);
function custom_ajax_pagination_script_load() {
	global $wp_query;
	wp_localize_script(
		'twentysixteen-script', 'ajaxPagination', [
			'ajaxUrl'   => admin_url('admin-ajax.php'),
			'queryVars' => json_encode($wp_query->query),
		]
	);
	wp_enqueue_script(
		'custom-ajax-pagination',
		get_stylesheet_directory_uri().'/js/functions.js', ['jquery']
	);
}


//add_action('wp_ajax_ajax_pagination', 'custom_ajax_pagination');
//add_action('wp_ajax_nopriv_ajax_pagination', 'custom_ajax_pagination');

function custom_ajax_pagination() {
	$queryVars = json_decode(stripslashes($_POST['queryVars']), TRUE);

	$events    = new WP_Query(
		[
			'post_type'      => $queryVars['post_type'],
			'posts_per_page' => $queryVars['posts_per_page'],
			'paged'          => $queryVars['page']
				? $queryVars['page'] : 1,
			'meta_query'     => [
				[
					'key'     => $queryVars['post_type'],
					'value'   => '"'.$queryVars['id'].'"',
					'compare' => 'LIKE',
				],
			],
		]
	);
	if ($events->have_posts()) {
		set_query_var('events', $events);
		get_template_part('template-parts/content', 'event');
	}
	die();
}

function newsPagination($custom_query) {

	$total_pages = $custom_query->max_num_pages;
	$big         = 999999999;

	if ($total_pages > 1) {
		echo paginate_links(
			[
				'base'         => str_replace(
					$big, '%#%', get_pagenum_link($big)
				),
				'format'       => '',
				'current'      => max(1, get_query_var('paged')),
				'total'        => $total_pages,
				'add_fragment' => 'ajax-pagination',
			]
		);
	}
}