    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link {{ ($title === "Home") ? 'active' : 'collapsed' }}" href="/home">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link {{ ($title === "Akademik") ? 'active' : 'collapsed' }}" href="/akademik">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Akademik</span>
                </a>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link {{ ($title === "SDM") ? 'active' : 'collapsed' }}" href="/sdm">
                    <i class="bi bi-journal-text"></i>
                    <span>SDM</span>
                </a>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link {{ ($title === "PTB") ? 'active' : 'collapsed' }}" href="/ptb">
                    <i class="bi bi-layout-text-window-reverse"></i>
                    <span>PTB</span>
                </a>
            </li><!-- End Tables Nav -->

            <li class="nav-item">
                <a class="nav-link {{ ($title === "About") ? 'active' : 'collapsed' }}" href="/about">
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
            </li><!-- End Profile Page Nav -->

        </ul>

    </aside><!-- End Sidebar-->
