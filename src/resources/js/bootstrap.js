import axios from 'axios';
import $ from 'jquery';

import DataTable from 'datatables.net-dt';
import DataTablesCore from 'datatables.net-bs5';
import 'datatables.net-responsive-dt';
 
DataTable.use(DataTablesCore);
 
// Axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// jQuery & Lodash
window.$ = $;
window.jQuery = $;

import Bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';

window.bootstrap = Bootstrap;
window.DataTable = DataTable;
