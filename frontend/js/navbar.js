(function () {


    const user = JSON.parse(localStorage.getItem('user') || 'null');
    const role = user ? (user.employee_role || '').toLowerCase() : '';

    const isEmployee = role === 'employee';
    const isChief = role === 'chief';
    const isIT = role === 'it';


    const path = window.location.pathname.split('/').pop() || 'index.html';

    function isActive(page) {
        return path === page ? 'active' : '';
    }


    const employeePages = ['stocks.html', 'compte.html', 'employes.html', 'employes-new.html'];
    const isEmployeeSpaceActive = employeePages.includes(path) ? 'active' : '';


    function navLink(href, label, active) {
        return `<a href="${href}" class="nav-link px-3 py-1 rounded-pill ${active} text-white text-decoration-none">${label}</a>`;
    }


    const employeMenu = user ? (() => {


        let items = `
            <li><a class="dropdown-item ${isActive('stocks.html')}" href="stocks.html">Stock Management</a></li>
            <li><a class="dropdown-item ${isActive('compte.html')}" href="compte.html">My Account</a></li>`;


        if (isChief) {
            items += `
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item ${isActive('employes.html')}" href="employes.html">My Store Employees</a></li>`;
        }


        if (isIT) {
            items += `
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item ${isActive('employes.html')}" href="employes.html">All Employees</a></li>`;
        }

        return `
        <div class="dropdown">
            <a class="nav-link px-3 py-1 rounded-pill text-white text-decoration-none dropdown-toggle ${isEmployeeSpaceActive}"
               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Employee Space
            </a>
            <ul class="dropdown-menu dropdown-menu-dark shadow">
                ${items}
            </ul>
        </div>`;
    })() : '';


    const profileBtn = user
        ? `<button onclick="logoutUser()" class="btn btn-outline-danger btn-sm rounded-pill px-3 ms-2">
               Logout
           </button>`
        : `<a href="index.html" class="btn btn-outline-light btn-sm rounded-pill px-3 ms-2">
               Login
           </a>`;


    const html = `
        <nav class="navbar navbar-dark bg-dark shadow-sm px-4 d-flex align-items-center">

            <a class="navbar-brand fw-bold fs-5 d-flex align-items-center gap-2" href="index.html">
                <img src="images/logo.png" alt="Logo" width="60" height="60" >
            </a>   

            <div class="d-flex align-items-center gap-1 ms-auto">
                ${navLink('accueil.html', 'Home', isActive('accueil.html'))}
                ${navLink('boutique.html', 'Shop', isActive('boutique.html'))}
                ${employeMenu}
                ${profileBtn}
            </div>

        </nav>`;


    const placeholder = document.getElementById('site-navbar');
    if (placeholder) {
        placeholder.outerHTML = html;
    } else {
        document.body.insertAdjacentHTML('afterbegin', html);
    }


    const style = document.createElement('style');
    style.textContent = `
        #site-navbar, nav[class*="navbar"] { min-height: 58px; }
        .navbar .nav-link { font-size: .9rem; font-weight: 500; transition: background .15s; }
        .navbar .nav-link:hover { background: rgba(255,255,255,.08); }
        .navbar .nav-link.active { background: rgba(13,110,253,.25) !important; color: #4da3ff !important; }
        .dropdown-item.active { background-color: #0d6efd; color: white !important; }
    `;
    document.head.appendChild(style);


    if (!window.bootstrap) {
        const bsScript = document.createElement('script');
        bsScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
        document.head.appendChild(bsScript);
    }

})();


function logoutUser() {
    localStorage.removeItem('user');
    window.location.href = 'index.html';
}