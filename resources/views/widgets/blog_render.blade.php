<div class="post-item">
    <h3>{!! $title !!}</h3>
    @if(isset($thumb) && $thumb)
    <p class="post-thumb">{!! $thumb !!}</p>
    @endif
    <div class="post-excerpt">{!! $excerpt !!}</div>
    <div class="post-content">{!! $content !!}</div>
    @if(!$hide_button)
    <a class="post-readmore-link" href="#">{!! $readmore_text !!}</a>
    @endif
</div>