<div class="bild">
    @if(has_post_thumbnail())
        @php the_post_thumbnail() @endphp
    @endif
</div>
<div id="texte">
@php the_content() @endphp
</div>
{!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
