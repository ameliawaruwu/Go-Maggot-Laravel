
<section id="sidebar">
    <div class="logo">
        <a href="{{ url('dashboard') }}">Go<span>Maggot</span></a>
    </div>
    <ul class="side-menu top">
       
        <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="{{ url('dashboard') }}">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li class="{{ Request::is('galery') ? 'active' : '' }}">
            <a href="{{ url('galery') }}">
                <i class='bx bx-images'></i>
                <span class="text">Galery</span>
            </a>
        </li>
        
        <li>
            <a href="{{ url('publication') }}">
                <i class='bx bx-library' ></i>
                <span class="text">Publication</span>
            </a>
        </li>
        <li>
            <a href="{{ url('product') }}">
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">Product</span>
            </a>
        </li>
        <li>
            <a href="{{ url('user') }}">
                <i class='bx bxs-user'></i>
                <span class="text">User</span>
            </a>
        </li>
        <li>
            <a href="{{ url('chat') }}">
                <i class='bx bxs-message-dots'></i>
                <span class="text">Chat</span>
            </a>
        </li>
    </ul>

</section>

