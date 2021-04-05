@if(has_post_thumbnail())
<div class="bild">
    @php the_post_thumbnail() @endphp
</div>
@endif
<div id="texte">
    @php the_content() @endphp
</div>