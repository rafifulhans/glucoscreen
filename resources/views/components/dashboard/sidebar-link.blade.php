<li class="sidebar-item">
    <a class="sidebar-link {{ request()->fullUrlIs($link) ? 'active' : '' }}" href="{{ $link }}" aria-expanded="false">
        <i class="{{ $icon }}"></i>
        <span class="hide-menu">{{ $title }}</span>
    </a>
</li>