<nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }} <span class="navbar__beta">beta</span>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    @if (Auth::user())
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="{{ route('home') }}">
                                Dashboard
                            </a>
                        </li>
                    </ul>
                    @endif

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li class="{{ Request::is('register') ? 'active' : '' }}">
                                <a href="{{ route('register') }}">Register</a>
                            </li>
                            <li class="{{ Request::is('login') ? 'active' : '' }}">
                                <a href="{{ route('login') }}">Login</a>
                            </li>
                        @else
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                {{ Auth::user()->name }} <span class="caret"></span></a>
                                </a>
                                <ul class="dropdown-menu">
                                    @if (Auth::user()->hasRole('superadmin'))
                                    <li>
                                        <a class="strong" href="{{ route('admin') }}">Admin Dashboard</a>
                                    </li>
                                    @endif
                                    <li>
                                        <a href="{{ route('user-edit') }}">Update profile</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('user-changepassword') }}">Change password</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('user-subscription') }}">Account Information</a>
                                    </li>
                                     <li>
                                         <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                         </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>