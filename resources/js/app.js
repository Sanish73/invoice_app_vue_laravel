

import { createApp } from 'vue'
import App from './components/app.vue'
import router from './router/index.js';

const app = createApp(App).use(router).mount("#app");