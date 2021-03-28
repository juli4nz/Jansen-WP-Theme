export default {
	init() {
		// JavaScript to be fired on all pages
		$('.post-readmore-link').click((e) => {
			e.preventDefault();
			let post_item = $(e.target).closest('.post-item');
			let excerpt = post_item.children('.post-excerpt');
			let content = post_item.children('.post-content');
			excerpt.toggle();
			content.toggle();
		});
	},
	finalize() {
		// JavaScript to be fired on all pages, after page specific JS is fired
	},
};
