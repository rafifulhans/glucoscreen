<li class="sidebar-item">
    <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
            <span class="d-flex">
                <i class="{{ $icon }}"></i>
            </span>
            <span class="hide-menu">{{ $title }}</span>
        </div>

    </a>
    <ul aria-expanded="false" class="collapse first-level">
        @foreach ($submenus as $menu)
            <li class="sidebar-item">
                <a class="sidebar-link justify-content-between" href="{{ $menu['link'] }}">
                    <div class="d-flex align-items-center gap-3">
                        <div class="round-16 d-flex align-items-center justify-content-center">
                            <i class="{{ $menu['icon'] }}"></i>
                        </div>
                        <span class="hide-menu">{{  $menu['title'] }}</span>
                    </div>
                </a>
            </li>
        @endforeach
    
    </ul>
</li>