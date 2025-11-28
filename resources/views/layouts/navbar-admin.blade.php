

<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom sticky-top">
    <div class="container-fluid">

       
        <i class='bx bx-menu me-3' role="button" id="sidebarToggle"></i> 

        
        <form class="d-none d-md-flex me-auto" action="#">
            <div class="input-group">
                <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
                <button type="submit" class="btn btn-primary"><i class='bx bx-search'></i></button>
            </div>
        </form>
        
       
        <div class="d-flex align-items-center">
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode me-3"></label>
            
            
            <div class="dropdown me-3">
                <a class="nav-link notification" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bxs-bell fs-5'></i>
                    <span class="num position-absolute translate-middle badge rounded-pill bg-danger" style="top: 10px; right: 10px;">5</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow p-3" aria-labelledby="notificationDropdown" style="min-width: 300px;">
                    <h6 class="dropdown-header">Notifications</h6>
                   
                    <a class="dropdown-item d-flex flex-column align-items-start py-2" href="#">
                        <strong class="text-truncate w-100">New Order from Sakira Amirah</strong>
                        <span class="text-muted small">5 minutes ago</span>
                    </a>
                    <a class="dropdown-item text-center mt-2 border-top pt-2" href="#">View All Notifications</a>
                </div>
            </div>

          
            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center p-0" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('images/FOTO Amelia Waruwu.jpg') }}" alt="Admin Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="#">Edit Profile</a></li>
                    <li><a class="dropdown-item" href="{{ url('/home') }}">Home</a></li>
                    <li><a class="dropdown-item" href="{{ url('/managesetting') }}">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                        </a>
                    </li>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </ul>
            </div>
        </div>
    </div>
</nav>