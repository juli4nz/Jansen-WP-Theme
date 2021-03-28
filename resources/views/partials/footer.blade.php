<footer>
  <div class="container">
    @php dynamic_sidebar('sidebar-footer') @endphp

    @if (has_nav_menu('links_nav'))
    <nav class="nav-links">
      {!! wp_nav_menu([
      'theme_location' => 'links_nav',
      'container_id' => 'linknavi',
      'container_class' => 'navilinie-bottom-top',
      'menu_class' => 'nav',
      ]) !!}
    </nav>
    @endif
    @if (has_nav_menu('main_nav'))
    <nav class="nav-primary">
      {!! wp_nav_menu([
      'theme_location' => 'main_nav',
      'container_id' => 'naviB',
      'depth' => 1 // hides submenus
      ]) !!}
    </nav>
    @endif
  </div>
</footer>