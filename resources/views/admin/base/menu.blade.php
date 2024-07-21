<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.home.detail') }}">
                    <i class="nav-icon icon-home"></i> Home
                </a>
            </li>
            <li class="nav-title">Management</li>
            @foreach ($config['menus'] as $menu)
                @if(count($menu['section']) > 1)
                    @if(auth()->user()->hasPermissionIn(array_keys($menu['section'])))
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="javascript:void(0);">
                            {{ $menu['name'] }}
                        </a>
                        <ul class="nav-dropdown-items">
                            @foreach ($menu['section'] as $section => $sectionName) 
                                @if(auth()->user()->hasPermission($section))
                                    @if(is_string($sectionName))
                                        <li class="nav-item">
                                            <a class="nav-link" data-section="{{ $section }}" href="{{ route('admin.' . $section . '.listing') }}">
                                                {{ $sectionName }}
                                            </a>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <a class="nav-link" data-section="{{ $section }}" href="{{ $sectionName['url'] }}">
                                                {{ $sectionName['title'] }}
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </li>
                    @endif
                @else
                    @foreach ($menu['section'] as $section => $sectionName) 
                        @if(auth()->user()->hasPermission($section))
                            @if(is_string($sectionName))
                                <li class="nav-item">
                                    <a class="nav-link" data-section="{{ $section }}" href="{{ route('admin.' . $section . '.listing') }}">
                                        {{ $sectionName }}
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" data-section="{{ $section }}" href="{{ $sectionName['url'] }}">
                                        {{ $sectionName['title'] }}
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endforeach
                @endif
            @endforeach
            <li class="nav-line"></li>
            @if(auth()->user()->hasPermission('translation'))
            <li class="nav-item">
                <a class="nav-link" data-section="translation" href="{{ route('admin.translation.listing') }}">
                    Translation
                </a>
            </li>
            @endif
            @if(auth()->user()->hasPermission('media_library'))
            <li class="nav-item">
                <a class="nav-link" data-section="media_library" href="{{ route('admin.media_library.index') }}">
                    Media Library
                </a>
            </li>
            @endif
            @if(auth()->user()->hasPermission('user'))
            <li class="nav-item">
                <a class="nav-link" data-section="user" href="{{ route('admin.user.listing') }}">
                    User
                </a>
            </li>
            @endif
            @if(auth()->user()->hasPermission('role'))
            <li class="nav-item">
                <a class="nav-link" data-section="role" href="{{ route('admin.role.listing') }}">
                    Role
                </a>
            </li>
            @endif
            @if(auth()->user()->hasPermission('system_setting'))
            <li class="nav-item">
                <a class="nav-link" data-section="system_setting" href="{{ route('admin.system_setting.listing') }}">
                    System Setting
                </a>
            </li>
            @endif

        </ul>
    </nav>
</div>