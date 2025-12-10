        <nav id="sidebar">
            <div class="custom-menu">
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fa fa-bars"></i>
                    <span class="sr-only">Toggle Menu</span>
                </button>
            </div>
            <h1><a href="{{ route('admin_home') }}" class="logo">Project Name</a></h1>
            <ul class="list-unstyled components mb-5">
                <li class="active">
                    <a href="{{ route('admin_home') }}"><span class="fa fa-home mr-3"></span> Homepage</a>
                </li>
                <li class="active">
                    <a href="{{ route('subject.index') }}"><span class="fa fa-home mr-3"></span> Subject</a>
                </li>
                <li class="active">
                    <a href="{{ route('logout') }}"><span class="fa fa-logout mr-3"></span> Logout</a>
                </li>

            </ul>

        </nav>
