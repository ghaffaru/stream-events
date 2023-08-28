import './bootstrap';
import {createApp} from "vue";
import Events from './components/Events.vue';

const app = createApp(Events);

app.mount("#app");
