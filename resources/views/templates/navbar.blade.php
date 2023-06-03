<nav id="navbar">
    <img src="/assets/logo.png" alt="logo" id="logo">
    <div class="navbar-links-block">
            <img class="menu-toggle" src="/assets/main-menu.png">
            <div id="links">
                    <a href="/home" class='nav-link <?php if ($filename === "home") echo "selected"?>'>Home</a>
                    <a href="/galleria" class="nav-link <?php if ($filename === "galleria") echo "selected"?>">Galleria</a>
                    <a href="/offerte" class="nav-link <?php if ($filename === "offerte") echo "selected"?>">Voli</a>
                    <a href="/prenotazioni" class="nav-link <?php if ($filename === "prenotazioni") echo "selected"?>">Prenotazioni</a>
                    <a href="/profilo" class="nav-link <?php if ($filename === "profilo") echo "selected"?>">Profilo</a>
            </div>
            <img id="plane" src="/assets/plane.png">
    </div>
</nav>