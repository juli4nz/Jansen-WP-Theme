<?php

namespace App;

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
	/** Add page slug if it doesn't exist */
	if (is_single() || is_page() && !is_front_page()) {
		if (!in_array(basename(get_permalink()), $classes)) {
			$classes[] = basename(get_permalink());
		}
	}

	/** Add class if sidebar is active */
	if (display_sidebar()) {
		$classes[] = 'sidebar-primary';
	}

	/** Clean up class names for custom templates */
	$classes = array_map(function ($class) {
		return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
	}, $classes);

	return array_filter($classes);
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
	return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});

/**
 * Template Hierarchy should search for .blade.php files
 */
collect([
	'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
	'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment', 'embed'
])->map(function ($type) {
	add_filter("{$type}_template_hierarchy", __NAMESPACE__ . '\\filter_templates');
});

/**
 * Render page using Blade
 */
add_filter('template_include', function ($template) {
	collect(['get_header', 'wp_head'])->each(function ($tag) {
		ob_start();
		do_action($tag);
		$output = ob_get_clean();
		remove_all_actions($tag);
		add_action($tag, function () use ($output) {
			echo $output;
		});
	});
	$data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
		return apply_filters("sage/template/{$class}/data", $data, $template);
	}, []);
	if ($template) {
		echo template($template, $data);

		return get_stylesheet_directory() . '/index.php';
	}

	return $template;
}, PHP_INT_MAX);

/**
 * Render comments.blade.php
 */
add_filter('comments_template', function ($comments_template) {
	$comments_template = str_replace(
		[get_stylesheet_directory(), get_template_directory()],
		'',
		$comments_template
	);

	$data = collect(get_body_class())->reduce(function ($data, $class) use ($comments_template) {
		return apply_filters("sage/template/{$class}/data", $data, $comments_template);
	}, []);

	$theme_template = locate_template(["views/{$comments_template}", $comments_template]);

	if ($theme_template) {
		echo template($theme_template, $data);

		return get_stylesheet_directory() . '/index.php';
	}

	return $comments_template;
}, 100);

/**
 * Menus
 * Add split menu argument
 */
 add_filter('wp_nav_menu_objects', function ($sorted_menu_items, $args) {
 	if (isset($args->split_menu)) {
 		$root_id = 0;

 		// find the current menu item
 		foreach ($sorted_menu_items as $menu_item) {
 			if ($menu_item->current) {
 				// set the root id based on whether the current menu item has a parent or not
 				$root_id = ($menu_item->menu_item_parent) ? $menu_item->menu_item_parent : $menu_item->ID;
 				break;
 			}
 		}

 		// find the top level parent
 		if (!isset($args->direct_parent)) {
 			$prev_root_id = $root_id;
 			while ($prev_root_id != 0) {
 				foreach ($sorted_menu_items as $menu_item) {
 					if ($menu_item->ID == $prev_root_id) {
 						$prev_root_id = $menu_item->menu_item_parent;
 						// don't set the root_id to 0 if we've reached the top of the menu
 						if ($prev_root_id != 0) {
 							$root_id = $menu_item->menu_item_parent;
 						}
 						break;
 					}
 				}
 			}
 		}

 		$menu_item_parents = [];
 		foreach ($sorted_menu_items as $key => $item) {
 			// init menu_item_parents
 			if ($item->ID == $root_id) {
 				$menu_item_parents[] = $item->ID;
 			}

 			if (in_array($item->menu_item_parent, $menu_item_parents)) {
 				// part of sub-tree: keep!
 				$menu_item_parents[] = $item->ID;
 			} elseif (!(isset($args->show_parent) && in_array($item->ID, $menu_item_parents))) {
 				// not part of sub-tree: away with it!
 				unset($sorted_menu_items[$key]);
 			}
 		}

 		return $sorted_menu_items;
 	} else {
 		return $sorted_menu_items;
 	}
 }, 10, 2);

 /**
  * Add Styles to TinyMCE
  * create a dropdwon for styles
  */
add_filter('mce_buttons_2', function ($buttons) {
	array_unshift($buttons, 'styleselect');

	return $buttons;
});

add_filter('tiny_mce_before_init', function ($init_array) {
	// Define the style_formats array

	$style_formats = [
		/*
* Each array child is a format with it's own settings
* Notice that each array has title, block, classes, and wrapper arguments
* Title is the label which will be visible in Formats menu
* Block defines whether it is a span, div, selector, or inline style
* Classes allows you to define CSS classes
* Wrapper whether or not to add a new block-level element around any selected elements
*/
		[
			'title' => 'Blau',
			'inline' => 'span',
			'classes' => 'blau',
			'wrapper' => false,
		],
		[
			'title' => 'Blau Bold',
			'inline' => 'span',
			'classes' => 'blauBold',
			'wrapper' => true,
		],
		[
			'title' => 'Orange',
			'inline' => 'span',
			'classes' => 'orange',
			'wrapper' => true,
		],
		[
			'title' => 'Orange Bold',
			'inline' => 'span',
			'classes' => 'orangeBold',
			'wrapper' => true,
		],
		[
			'title' => 'Referenz',
			'selector' => 'p',
			'classes' => 'referenz',
			'wrapper' => true,
		],
		[
			'title' => 'List Spaced',
			'selector' => 'ul',
			'classes' => 'spaced',
			'wrapper' => true,
		]
	];
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode($style_formats);

	return $init_array;
});

add_filter('sage/display_sidebar', function ($display) {
	static $display;

	isset($display) || $display = in_array(true, [
		// The sidebar will be displayed if any of the following return true
		is_single(),
	]);

	return true; //workaround

	return $display;
});

/*Contact form 7 remove span*/
add_filter('wpcf7_form_elements', function ($content) {
	$content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);
	$content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);
	$content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-list-item(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);

	$content = str_replace('<br />', '', $content);

	return $content;
});

/* Replce search form */

add_filter('get_search_form', function () {
	return \App\template('widgets.searchform');
});
