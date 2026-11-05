
function navigate(page) {
    window.location.hash = page;
}

function router() {
    const page = window.location.hash.substring(1);

    if (page === 'books') {
        renderBooks();
    } else if (page === 'categories') {
        renderCategories();
    } else {
        renderDashboard();
    }
}

window.addEventListener('hashchange', router);

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('menu-dashboard').addEventListener('click', function() {
        navigate('dashboard');
    });

    document.getElementById('menu-books').addEventListener('click', function() {
        navigate('books');
    });

    document.getElementById('menu-categories').addEventListener('click', function() {
        navigate('categories');
    });

    if (!window.location.hash) {
        navigate('dashboard');
    } else {
        router();
    }
});
