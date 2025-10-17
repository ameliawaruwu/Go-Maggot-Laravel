<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container">
        <div class="text-center mb-4">
            <h1>Stay With us On Social</h1>
            <div class="d-flex justify-content-center align-items-center mt-3">
                <h2 class="me-3 fs-5">FOLLOW US :</h2>
                <div class="fs-4">
                    <i class="ri-instagram-line me-3"></i>
                    <i class="ri-twitter-x-line me-3"></i>
                    <i class="ri-facebook-circle-fill me-3"></i>
                    <i class="ri-whatsapp-line"></i>
                </div>
            </div>
        </div>

        <div class="row g-4 border-top pt-4">
            <div class="col-lg-3 col-md-6">
            <a class="navbar-brand logo text-white" href="{{ url('/home') }}"><span>Go</span>Maggot</a>
            <p class="mt-2">Proactively restore timely alignments after client enviromentals</p>
            <p><i class="ri-phone-fill"></i> +087928364735</p>
            <p><i class="ri-mail-line"></i> gomaggot@gmail.com</p>
        </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase mb-3">Company</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ url('/home') }}" class="text-white text-decoration-none"><i class="ri-arrow-right-s-line"></i> Home</a></li>
                    <li><a href="{{ url('/about') }}" class="text-white text-decoration-none"><i class="ri-arrow-right-s-line"></i> About</a></li>
                    <li><a href="{{ route('product.index') }}" class="text-white text-decoration-none"><i class="ri-arrow-right-s-line"></i> Products</a></li>
                    <li><a href="{{ url('/artikel') }}" class="text-white text-decoration-none"><i class="ri-arrow-right-s-line"></i> Blog</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ url('/contact') }}" class="text-white text-decoration-none"><i class="ri-arrow-right-s-line"></i> Contact Us</a></li>
                    <li><a href="{{ url('/QNA') }}" class="text-white text-decoration-none"><i class="ri-arrow-right-s-line"></i> FAQ's</a></li>
                    <li><a href="{{ url('/help') }}" class="text-white text-decoration-none"><i class="ri-arrow-right-s-line"></i> Help Service</a></li>
                    <li><a href="{{ url('/feedback') }}" class="text-white text-decoration-none"><i class="ri-arrow-right-s-line"></i> Feedback</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase mb-3">Newsletter</h5>
                <p>Subscribe Our NewsLetter</p>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Enter Email" aria-label="Enter Email">
                    <button class="btn btn-success" type="button">Subscribe <i class="ri-arrow-drop-right-line"></i></button>
                </div>
            </div>
        </div>

        <div class="text-center pt-3 border-top mt-3">
            <p class="mb-0">© 2024 GoMaggot. All Right Reserved <a href="#" class="text-info text-decoration-none">maggotinfo.com</a></p>
        </div>
    </div>
</footer>