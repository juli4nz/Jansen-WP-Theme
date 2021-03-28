<p>
    <input class="checkbox" type="checkbox" {!! checked($all_posts['val'], 'on' ) !!} id="{!! $all_posts['id'] !!}"
        name="{!! $all_posts['name'] !!}" />
    <label for="{!! $all_posts['id'] !!}">{!! __('Show all posts:') !!}</label>
</p>
<p>
    <label for="{!! $number['id'] !!}"">{!! __( 'Number of posts to show:') !!}</label>
    <input class=" tiny-text" id="{!! $number['id'] !!}" name="{!! $number['name']!!}" type="number" step="1" min="1"
        value="{!! $number['val'] !!}" size="3" />
</p>
<p>
    <label for="{!! $excerpt_length['id'] !!}"">{!! __( 'Number of words to show per post:') !!}</label>
    <input class=" tiny-text" id="{!! $excerpt_length['id'] !!}" name="{!! $excerpt_length['name']!!}" type="number"
        step="1" min="1" value="{!! $excerpt_length['val'] !!}" size="3" />
</p>
<p>
    <label for="{!! $readmore_text['id'] !!}">{!! __('Read More text:') !!}</label>
    <input type="text" class="widefat" id="{!! $readmore_text['id'] !!}" name="{!! $readmore_text['name'] !!}"
        value="{!!$readmore_text['val']!!}" />
</p>