<header id="header">
  <h1 class="logo">
    @if(has_custom_logo())
    @php
    // Get Custom Logo URL
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $custom_logo_width = !empty(get_theme_mod('upload_logo_width')) ? get_theme_mod('upload_logo_width') : 200;
    $custom_logo_data = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    $custom_logo_url = $custom_logo_data[0];
    @endphp
    <a href="{{ esc_url( home_url( '/' ) ) }}" title="{{ esc_attr( get_bloginfo( 'name' ) ) }}" rel="home">
      <img class="logo" width="{{$custom_logo_width}}" src="{{ esc_url( $custom_logo_url ) }}"
        alt="{{ esc_attr( get_bloginfo( 'name' ) ) }}" />
    </a>
    @else
    <div class="site-name">{!! bloginfo( 'name' ) !!}</div>
    @endif
  </h1>
  @if (has_nav_menu('main_nav'))
  <nav class="nav-primary navbar navbar-expand-sm">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navi">
      <span class="navbar-toggler-icon"></span>
    </button>
    {!! wp_nav_menu([
    'theme_location' => 'main_nav',
    'container_id' => 'navi',
    'container_class' => 'collapse navbar-collapse navilinie-bottom',
    'menu_class' => 'navbar-nav',
    'depth' => 1 // hides submenus
    ]) !!}
  </nav>
  <nav class="nav-secondary">
    {!! wp_nav_menu([
    'theme_location' => 'main_nav',
    'container_id' => 'subnavi',
    'container_class' => 'navilinie-bottom',
    'split_menu' => true,
    ]) !!}
  </nav>
  @endif
</header>