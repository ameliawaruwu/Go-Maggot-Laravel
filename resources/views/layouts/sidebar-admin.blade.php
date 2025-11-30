<section id="sidebar">
    <div class="logo">
        <a href="" class="text-decoration-none">Go<span>Maggot</span></a>
    </div>
    <ul class="side-menu top">
        
        <li class="">
            <a href="/dashboard" class="text-decoration-none">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="/analytics" class="text-decoration-none">
                <i class='bx bx-bar-chart-alt-2'></i> 
                <span class="text">Analitik</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('gallery.*') ? 'active' : '' }}">
            <a href="{{ route('gallery.index') }}" class="text-decoration-none"> 
                <i class='bx bx-images'></i>
                <span class="text">Galeri</span>
            </a>
        </li>

        <li>
            <a href="/publication" class="text-decoration-none">
                <i class='bx bx-library' ></i>
                <span class="text">Publikasi Artikel</span>
            </a>
        </li>

        <li>
            <a href="/manageProduk" class="text-decoration-none">
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">Produk</span>
            </a>
        </li>

        <li>
            <a href="/manageUser" class="text-decoration-none">
                <i class='bx bxs-user'></i>
                <span class="text">Pengguna</span>
            </a>
        </li>

        <li>
            <a href="/manageReview" class="text-decoration-none">
                <i class='bx  bx-edit'></i> 
                <span class="text">Reviews Produk</span>
            </a>
        </li>

        <li>
            <a href="/manageFaq" class="text-decoration-none">
                <i class='bx bxs-message-dots'></i>
                <span class="text">FAQ</span>
            </a>
        </li>
    </ul>
</section>
