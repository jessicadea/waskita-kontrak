import './bootstrap';

import Alpine from 'alpinejs';

import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';

window.Alpine = Alpine;

window.initSearchableSelect = function (selector = '.searchable-select', container = document) {
    container.querySelectorAll(selector).forEach((el) => {
        if (el.tomselect || el.classList.contains('tomselected')) return;

        if (el.classList.contains('variantSelect')) return;
        if (el.classList.contains('volumeSelect')) return;

        new TomSelect(el, {
            create: false,
            allowEmptyOption: true,
            sortField: {
                field: 'text',
                direction: 'asc',
            },
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    window.initSearchableSelect();
});

Alpine.start();