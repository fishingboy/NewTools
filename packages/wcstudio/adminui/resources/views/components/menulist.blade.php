<ul class="c-sidebar-nav" data-drodpown-accordion="true">
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link c-active" href="/adminui">
            <i class="c-sidebar-nav-icon fas fa-tachometer-alt"></i> Dashboard
        </a>
    </li>
    @foreach ($menulist as $layer1)
        <li class="c-sidebar-nav-title" href={{ $layer1['url'] }}>@lang($layer1['menu_name'])</li>
        @foreach ($layer1['sub'] as $layer2)
            @if (count($layer2['sub']) === 0)
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href={{ $layer2['url'] }}>
                        <i class="c-sidebar-nav-icon fas fa-file-alt"></i> @lang($layer2['menu_name'])
                    </a>
                </li>
            @else
                <li class="c-sidebar-nav-dropdown">
                    <a class="c-sidebar-nav-dropdown-toggle" herf={{ $layer2['url'] }}>
                        <i class="c-sidebar-nav-icon fas fa-cubes"></i>@lang($layer2['menu_name'])
                    </a>
                    <ul class="c-sidebar-nav-dropdown-items">
                        @foreach ($layer2['sub'] as $layer3)
                            <li class="c-sidebar-nav-item">
                                <a class="c-sidebar-nav-link" href={{ $layer3['url'] }}> @lang($layer3['menu_name'])</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
        @endforeach
    @endforeach

    <li class="c-sidebar-nav-divider"></li>

    <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
    </div>
    <div class="ps__rail-y" style="top: 0px; height: 651px; right: 0px;">
        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 292px;"></div>
    </div>
</ul>
