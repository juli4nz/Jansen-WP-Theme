@extends('layouts.app')

@section('content')
<div id="bild"></div>
<div id="texte">
  <h2>{!! App::title() !!}</h2>
  <h3>{{ __('Ihre Suche ergab folgende Treffer') }}</h3>

  @if (!have_posts())
  <div class="alert alert-warning">
    <!--{{ __('Sorry, no results were found.', 'sage') }}-->
    {{ __('Leider konnte keine Übereinstimmung mit Ihrem Suchbegriff gefunden werden.') }}
  </div>
  {!! get_search_form(false) !!}
  @else

  <ul>
    @while(have_posts())
    @php the_post() @endphp
    @include('partials.content-search')
    @endwhile
  </ul>

  @endif
</div>
@endsection