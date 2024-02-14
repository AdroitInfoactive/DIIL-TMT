<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                        class="fas fa-search"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">

        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset(auth()->user()->avatar) }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi,{{ auth()->user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('profile') }}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>
                {{-- <a href="features-activities.html" class="dropdown-item has-icon">
                    <i class="fas fa-bolt"></i> Activities
                </a>
                <a href="features-settings.html" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Settings
                </a> --}}
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                        class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </form>
            </div>
        </li>
    </ul>
</nav>
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">

            <a href="{{ route('dashboard') }}">{{ config('settings.site_name') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}">{{ config('settings.site_name') }}</a>

            <a href="{{ route('dashboard') }}">Home</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">

        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ setSidebarActive(['dashboard']) }}"><a class="nav-link" href="{{ route('dashboard') }}"><i
                        class="fas fa-fire"></i>General Dashboard</a>
            </li>
            <li class="menu-header">Menus</li>
            <li
                class="dropdown {{ setSidebarActive(['size.*', 'brand.*', 'terms-and-conditions.*', 'tax.*', 'collection-tax.*', 'invoice-entity.*', 'charges.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-box"></i>
                    <span>Setup </span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setSidebarActive(['invoice-entity.*']) }}"><a class="nav-link"
                            href="{{ route('invoice-entity.index') }}"><span>Invoice Entity</span></a></li>
                    <li class="{{ setSidebarActive(['size.*']) }}"><a class="nav-link"
                            href="{{ route('size.index') }}"><span>UOM</span></a></li>
                    <li class="{{ setSidebarActive(['brand.*']) }}"><a class="nav-link"
                            href="{{ route('brand.index') }}"><span>Make/Brand</span></a></li>
                    <li class="{{ setSidebarActive(['tax.*', 'collection-tax.*']) }}"><a class="nav-link"
                            href="{{ route('tax.index') }}"><span>Taxes</span></a></li>
                    <li class="{{ setSidebarActive(['charges.*']) }}"><a class="nav-link"
                            href="{{ route('charges.index') }}"><span>Charges</span></a></li>
                    <li class="{{ setSidebarActive(['terms-and-conditions.*']) }}"><a class="nav-link"
                            href="{{ route('terms-and-conditions.index') }}"><span>Terms & Conditions</span></a></li>
                </ul>
            </li>
            @if (Auth::user()->id == 1)
                <li class="{{ setSidebarActive(['user.*', 'user']) }}"><a class="nav-link"
                        href="{{ route('user') }}"><i class="fas fa-users"></i><span>Users</span></a>
                </li>
            @endif
            <li class="{{ setSidebarActive(['product.*']) }}"><a class="nav-link"
                    href="{{ route('product.index') }}"><i class="fas fa-bars"></i><span>Products</span></a>
            </li>
            <li class="{{ setSidebarActive(['client.*']) }}"><a class="nav-link" href="{{ route('client.index') }}"><i
                        class="fas fa-users"></i><span>Clients</span></a>
            </li>
            <li class="dropdown {{ setSidebarActive(['order.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-box"></i>
                    <span>Orders </span></a>
                <ul class="dropdown-menu">
                    <li
                        class="{{ setSidebarActive(['order.*', 'order.index', 'order.create', 'order.edit', 'order.show']) }}">
                        <a class="nav-link" href="{{ route('order.index') }}"><span>All</span></a>
                    </li>
                    {{-- <li class="{{ setSidebarActive(['order.pending']) }}"><a class="nav-link"
                            href="{{ route('order.pending') }}"><span>Pending</span></a></li>
                    <li class="{{ setSidebarActive(['order.accepted']) }}"><a class="nav-link"
                            href="{{ route('order.accepted') }}"><span>Accepted</span></a></li>
                    <li class="{{ setSidebarActive(['order.rejected']) }}"><a class="nav-link"
                            href="{{ route('order.rejected') }}"><span>Rejected</span></a></li> --}}
                    <li class="{{ setSidebarActive(['order.deleted']) }}"><a class="nav-link"
                            href="{{ route('order.deleted') }}"><span>Deleted</span></a></li>

                </ul>
            </li>
            <li class="{{ setSidebarActive(['setting.index']) }}"><a class="nav-link"
                    href="{{ route('setting.index') }}"><i class="fas fa-cogs"></i><span>Settings</span></a></li>
        </ul>

    </aside>
</div>
