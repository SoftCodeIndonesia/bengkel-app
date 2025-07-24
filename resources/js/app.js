import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
// Flowbite
import 'flowbite';

// Jika Anda menggunakan DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi komponen Flowbite
    if (window.Flowbite) {
        window.Flowbite.init();
    }
});