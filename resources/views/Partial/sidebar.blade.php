    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link {{ ($title === "Home") ? 'active' : 'collapsed' }}" href="/">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link {{ ($title === "Akademik") ? 'active' : 'collapsed' }}" href="/akademik">
                    <i class="fa-solid fa-user-nurse"></i>
                    <span>Akademik</span>
                </a>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link {{ ($title === "Sumber Daya Manusia") ? 'active' : 'collapsed' }}" href="/sdm">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Sumber Daya Manusia</span>
                </a>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link {{ ($title === "Penerimaan Taruna Baru") ? 'active' : 'collapsed' }}" href="/ptb">
                    <i class="fa-solid fa-user"></i>
                    <span>Penerimaan Taruna Baru</span>
                </a>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link {{ ($title === "Alumni") ? 'active' : 'collapsed' }}" href="/alumni">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Alumni</span>
                </a>
            </li><!-- End Tables Nav -->

        </ul>

    </aside><!-- End Sidebar-->
