<?php

namespace App\Widgets;

/**
 * Navigation Menu widget class
 *
 * @since 3.0.0
 */
 class Blog extends \WP_Widget
 {
 	public function __construct()
 	{
 		$widget_ops = [
 			'classname' => 'blog_widget',
 			'description' => __('Add a blog content to your website.')
 		];
 		parent::__construct('blog_widget', __('JB Blog Content'), $widget_ops);
 	}

 	public function widget($args, $instance)
 	{
 		$instance['all_posts'] = !empty($instance['all_posts']) ? true : false;
 		$instance['number'] = !empty($instance['number']) ? absint($instance['number']) : 1;
 		$instance['excerpt_length'] = isset($instance['excerpt_length']) ? absint($instance['excerpt_length']) : null;
 		$instance['readmore_text'] = isset($instance['readmore_text']) ? esc_attr($instance['readmore_text']) : esc_attr('');

 		if ($instance['all_posts']) {
 			$instance['number'] = -1;
 		}

 		$this->instance = $instance;

 		$this->set_filters($instance);

 		echo $args['before_widget'];

 		$query_args = [
 			'post_type' => 'post',
 			'post_status' => 'publish',
 			'posts_per_page' => $instance['number']
 		];
 		$query = new \WP_Query($query_args);

 		while ($query->have_posts()) {
 			$query->the_post();

 			$content = get_the_content();
 			$content = apply_filters('the_content', $content);
 			$excerpt = $this->limit_words($content, $instance['excerpt_length']);
 			$excerpt = $this->close_tags($excerpt);

 			$content_count = str_word_count(strip_tags($content));
 			$excerpt_count = str_word_count(strip_tags($excerpt));

 			$hide_button = $content_count <= $excerpt_count ? true : false;

 			echo \App\template('widgets.blog_render', [
 				'title' => get_the_title(),
 				'thumb' => has_post_thumbnail() ? get_the_post_thumbnail() : '',
 				'excerpt' => $excerpt,
 				'content' => $content,
 				'hide_button' => $hide_button,
 				'readmore_text' => $instance['readmore_text'],
 			]);
 		}

 		echo $args['after_widget'];

 		$this->remove_filters($instance);
 	}

 	public function update($new_instance, $old_instance)
 	{
 		$instance['all_posts'] = $new_instance['all_posts'];
 		$instance['number'] = (int) $new_instance['number'];
 		$instance['excerpt_length'] = (int) $new_instance['excerpt_length'];
 		$instance['readmore_text'] = strip_tags(stripslashes($new_instance['readmore_text']));

 		return $instance;
 	}

 	public function form($instance)
 	{
 		$all_posts = $instance['all_posts'];
 		$number = $instance['number'];
 		$excerpt_length = $instance['excerpt_length'];
 		$readmore_text = $instance['readmore_text'];

 		echo \App\template('widgets.blog_form', [
 			'all_posts' => [
 				'val' => $all_posts,
 				'id' => $this->get_field_id('all_posts'),
 				'name' => $this->get_field_name('all_posts')
 			],
 			'number' => [
 				'val' => $number,
 				'id' => $this->get_field_id('number'),
 				'name' => $this->get_field_name('number')
 			],
 			'excerpt_length' => [
 				'val' => $excerpt_length,
 				'id' => $this->get_field_id('excerpt_length'),
 				'name' => $this->get_field_name('excerpt_length')
 			],
 			'readmore_text' => [
 				'val' => $readmore_text,
 				'id' => $this->get_field_id('readmore_text'),
 				'name' => $this->get_field_name('readmore_text')
 			],
 		]);
 	}

 	private function set_filters($instance)
 	{
 		if (isset($instance['excerpt_length']) && ((int) $instance['excerpt_length']) > 0) {
 			add_filter('excerpt_length', [$this, 'set_excerpt_length']);
 		}
 		if (isset($instance['readmore_text']) && ('' !== ltrim($instance['readmore_text']))) {
 			add_filter('excerpt_more', [$this, 'set_readmore_link']);
 		}
 	}

 	private function remove_filters($instance)
 	{
 		if (has_filter('excerpt_length', [$this, 'set_excerpt_length'])) {
 			remove_filter('excerpt_length', [$this, 'set_excerpt_length']);
 		}
 		if (has_filter('excerpt_more', [$this, 'set_readmore_link'])) {
 			remove_filter('excerpt_more', [$this, 'set_readmore_link']);
 		}
 	}

 	public function set_excerpt_length($length)
 	{
 		if (is_admin()) {
 			return $length;
 		}

 		if (isset($this->instance['excerpt_length']) && $this->instance['excerpt_length'] > 0) {
 			$length = $this->instance['excerpt_length'];
 		}

 		return $length;
 	}

 	public function set_readmore_link($more)
 	{
 		if (is_admin()) {
 			return $more;
 		}

 		return '';
 	}

 	private function limit_words($phrase, $max_words)
 	{
 		$phrase_array = explode(' ', $phrase);
 		if (count($phrase_array) > $max_words && $max_words > 0) {
 			$phrase = implode(' ', array_slice($phrase_array, 0, $max_words)) . '...';
 		}

 		return $phrase;
 	}

 	private function close_tags($html)
 	{
 		preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
 		$openedtags = $result[1];

 		preg_match_all('#</([a-z]+)>#iU', $html, $result);
 		$closedtags = $result[1];
 		$len_opened = count($openedtags);
 		if (count($closedtags) == $len_opened) {
 			return $html;
 		}
 		$openedtags = array_reverse($openedtags);
 		for ($i = 0; $i < $len_opened; $i++) {
 			if (!in_array($openedtags[$i], $closedtags)) {
 				$html .= '</' . $openedtags[$i] . '>';
 			} else {
 				unset($closedtags[array_search($openedtags[$i], $closedtags)]);
 			}
 		}

 		return $html;
 	}
 }
