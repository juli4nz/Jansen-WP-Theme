<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
	wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, null);
	wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);

	if (is_single() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	// Register Contact Form 7 JavaScript & Css files on contact page
	if (is_page(148)) {
		if (function_exists('wpcf7_enqueue_scripts')) {
			wpcf7_enqueue_scripts();
		}

		if (function_exists('wpcf7_enqueue_styles')) {
			wpcf7_enqueue_styles();
		}
	} else {
		wp_dequeue_script('google-recaptcha');
		wp_dequeue_script('wpcf7-recaptcha');
	}
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
	/**
	 * Enable features from Soil when plugin is activated
	 * @link https://roots.io/plugins/soil/
	 */
	add_theme_support('soil-clean-up');
	add_theme_support('soil-jquery-cdn');
	add_theme_support('soil-nav-walker');
	add_theme_support('soil-nice-search');
	add_theme_support('soil-relative-urls');

	/**
	 * Enable plugins to manage the document title
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
	 */
	add_theme_support('title-tag');
	add_theme_support('custom-logo');

	/**
	 * Register navigation menus
	 * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
	 */
	register_nav_menus([
		'main_nav' => __('Main Navigation', 'sage'),
		'links_nav' => __('Links Navigation', 'sage'),
	]);

	/**
	 * Enable post thumbnails
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	/**
	 * Enable HTML5 markup support
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
	 */
	add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

	/**
	 * Enable selective refresh for widgets in customizer
	 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
	 */
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Use main stylesheet for visual editor
	 * @see resources/assets/styles/layouts/_tinymce.scss
	 */
	add_editor_style(asset_path('styles/main.css'));
}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
	$config = [
		'before_widget' => '<section class="widget %1$s %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	];
	register_sidebar([
		'name' => __('Primary', 'sage'),
		'id' => 'sidebar-primary'
	] + $config);

	register_widget('App\Widgets\Blog');
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
	sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
	/**
	 * Add JsonManifest to Sage container
	 */
	sage()->singleton('sage.assets', function () {
		return new JsonManifest(config('assets.manifest'), config('assets.uri'));
	});

	/**
	 * Add Blade to Sage container
	 */
	sage()->singleton('sage.blade', function (Container $app) {
		$cachePath = config('view.compiled');
		if (!file_exists($cachePath)) {
			wp_mkdir_p($cachePath);
		}
		(new BladeProvider($app))->register();

		return new Blade($app['view']);
	});

	/**
	 * Create @asset() Blade directive
	 */
	sage('blade')->compiler()->directive('asset', function ($asset) {
		return '<?= ' . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
	});
});

/**
 * Send second mail if checkbox is activated
 */
add_action('wpcf7_before_send_mail', function ($cf7) {
	//get CF7's mail and posted_data objects
	$submission = \WPCF7_Submission::get_instance();
	if ($submission) {
		$posted_data = $submission->get_posted_data();
	}
	//get mail props
	$mail = $cf7->prop('mail');

	if ($posted_data['ruck'][0]) { //if Checkbox checked
		$mail2 = $cf7->prop('mail_2'); //get CF7's mail_2 object
		//now set sender's email address to mail2's recipient
		$mail2['recipient'] = $posted_data['email'];
		$cf7->set_properties(['mail_2' => $mail2]);
	} else {
		$cf7->set_properties(['mail_2' => []]);
	}

	return $cf7;
});
