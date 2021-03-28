<!DOCTYPE html>
<html {!! get_language_attributes() !!}>
@include('partials.head')

<body @php body_class() @endphp>

  <div class="content" role="document">
    <div class="container">
      <div class="row">
        <main class="col-sm-9">
          @php do_action('get_header') @endphp
          @include('partials.header')

          @yield('content')

          @php do_action('get_footer') @endphp
          @include('partials.footer')
          @php wp_footer() @endphp
        </main>
        @if (App\display_sidebar())
        <aside id="rechts" class="sidebar col-sm-3">@include('partials.sidebar')</aside>
        @endif
      </div>
    </div>
  </div>


</body>

</html>