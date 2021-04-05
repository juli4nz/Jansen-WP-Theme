@extends('layouts.app')

@section('content')
<div id="texte">
  <h2>{!! App::title() !!}</h2>
  @if (!have_posts())
  <div class="alert alert-warning">
    <!--{{ __('Sorry, but the page you were trying to view does not exist.', 'sage') }}-->
    {{ __('Entschuldigung, aber die Seite, die Sie anzeigen wollten, existiert nicht.') }}
  </div>

  {!! get_search_form(false) !!}

  @endif
</div>
@endsection