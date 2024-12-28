import axios from 'axios';
import $ from 'jquery';

// Datatables
import 'datatables.net';
import 'datatables.net-bs4';
import 'datatables.net-buttons-bs4';
import 'datatables.net-responsive';
import 'datatables.net-responsive-bs4';
 
// Plugins adicionales de Datatables
import 'datatables.net-buttons/js/buttons.colVis';
import JSZip from 'jszip';
import pdfMake from 'pdfmake/build/pdfmake';

import 'datatables.net-buttons/js/buttons.html5';
import 'datatables.net-buttons/js/buttons.print';

// Select2
import select2 from 'select2';
import 'select2/dist/css/select2.min.css';


import { loadFontsToBase64 } from './loadFonts';
import { hideElements } from './hideElements';

loadFontsToBase64().catch(error => console.error("Error cargando fuentes:", error));

// Axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// jQuery & Lodash
window.$       = $;
window.jQuery  = $;
window.JSZip   = JSZip;    // Necesario para exportar en Excel
window.pdfMake = pdfMake;  // Necesario para exportar en PDF

// Bootstrap
import Bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';
window.bootstrap    = Bootstrap;
window.hideElements = hideElements;

select2($);
