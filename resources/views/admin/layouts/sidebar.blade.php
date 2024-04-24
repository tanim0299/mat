<nav class="navbar navbar-light navbar-vertical navbar-vibrant navbar-expand-lg">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
      <div class="navbar-vertical-content scrollbar">
        <ul class="navbar-nav flex-column" id="navbarVerticalNav">
          <li class="nav-item">
            <!-- label-->
            <p class="navbar-vertical-label">Apps</p>
            <a class="nav-link" href="{{route('admin.home')}}" role="button" data-bs-toggle="" aria-expanded="false">
                <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="home"></span></span><span class="nav-link-text">Home</span></div>
              </a>
            <a class="nav-link dropdown-indicator collapsed" href="#home" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="home">
              <div class="d-flex align-items-center">
                <div class="dropdown-indicator-icon d-flex flex-center"><span class="fas fa-caret-right fs-0"></span></div><span class="nav-link-icon"><span data-feather="layers"></span></span><span class="nav-link-text">Developer Options</span>
              </div>
            </a>
            <ul class="nav collapse parent" id="home">
              <li class="nav-item"><a class="nav-link" href="index.html" data-bs-toggle="" aria-expanded="false">
                  <div class="d-flex align-items-center"><span class="nav-link-text">Menu Level</span></div>
                </a><!-- more inner pages-->
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="navbar-vertical-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown-link class="btn btn-link border-0 fw-semi-bold d-flex ps-0" :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                <span class="navbar-vertical-footer-icon" data-feather="log-out"></span><span>Sign out</span>
            </x-dropdown-link>

        </form>

    </div>
    </div>
  </nav>
