import { createApp } from 'vue';
import App from './Components/App.vue';
import vuetify from './plugins/vuetify';
import '@/scss/style.scss';
import PerfectScrollbar from 'vue3-perfect-scrollbar';
import VueTablerIcons from 'vue-tabler-icons';

const app = createApp(App);

app.use(PerfectScrollbar);
app.use(VueTablerIcons);
app.use(vuetify).mount('#vue-ts');
