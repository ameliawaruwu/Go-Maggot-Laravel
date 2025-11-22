<section id="sidebar">
    <div class="logo">
        <a href="{{ url('dashboard') }}" class="text-decoration-none">Go<span>Maggot</span></a>
    </div>
    <ul class="side-menu top">
        
        <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="{{ url('dashboard') }}" class="text-decoration-none">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="" class="text-decoration-none">
                <i class='bx bx-bar-chart-alt-2'></i> 
                <span class="text">Analytics</span>
            </a>
        </li>

        {{-- 
        <li class="{{ Request::routeIs('gallery.index') ? 'active' : '' }}">
            <a href="{{ route('gallery.index') }}" class="text-decoration-none">
                <i class='bx bx-images'></i>
                <span class="text">Galleries</span>
            </a>
        </li>
        --}}

        <li>
            <a href="" class="text-decoration-none">
                <i class='bx bx-library' ></i>
                <span class="text">Publications</span>
            </a>
        </li>

        <li>
            <a href="/manageProduk" class="text-decoration-none">
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">Manajemen Produk</span>
            </a>
        </li>

        <li>
            <a href="" class="text-decoration-none">
                <i class='bx bxs-user'></i>
                <span class="text">Users</span>
            </a>
        </li>

        <li>
            <a href="{{ url('managereview') }}" class="text-decoration-none">
                <i class='bx  bx-edit'></i> 
                <span class="text">Reviews</span>
            </a>
        </li>

        <li>
            <a href="" class="text-decoration-none">
                <i class='bx bxs-message-dots'></i>
                <span class="text">FAQS</span>
            </a>
        </li>
    </ul>
</section>
