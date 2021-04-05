<article @php post_class() @endphp>
  @if(has_post_thumbnail())
  <div class="bild">
    @php the_post_thumbnail() @endphp
  </div>
  @endif
  <div id="texte">
    <h2 class="entry-title">{!! get_the_title() !!}</h2>
    @include('partials/entry-meta')
    @php the_content() @endphp
  </div>
</article>