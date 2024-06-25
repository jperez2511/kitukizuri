import axios from 'axios';
import $ from 'jquery';

// Axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// jQuery & Lodash
window.$ = $;
window.jQuery = $;
