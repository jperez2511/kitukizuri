import axios from 'axios';
import $ from 'jquery';

// Datatables
import 'datatables.net';
import 'datatables.net-bs4';
import 'datatables.net-buttons-bs4';
import 'datatables.net-responsive';
import 'datatables.net-responsive-bs4';
 
// Axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// jQuery & Lodash
window.$ = $;
window.jQuery = $;

import Bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';

window.bootstrap = Bootstrap;
