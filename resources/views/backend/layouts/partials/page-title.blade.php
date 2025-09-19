<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">@yield('page-title')</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="@yield('breadcrumb-home_route')">@yield('breadcrumb-home_title')</a></li>
                    <li><span>@yield('breadcrumb-current')</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            <div class="user-profile pull-right">
                <img class="avatar user-thumb"
                    src="{{ asset('admin/assets/images/author/avatar.png') }}"
                    alt="avatar">
                <h4 class="user-name dropdown-toggle" data-toggle="dropdown">{{ auth()->user()->name }} <i
                        class="fa fa-angle-down"></i>
                </h4>
                <div class="dropdown-menu">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Log Out
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- page title area end -->
