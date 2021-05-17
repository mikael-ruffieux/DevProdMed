import { createApp } from "vue";
import Articles from "./components/Articles.vue";

const dom = document.querySelector("#vue-app");
if (dom) {
  const app = createApp({
    components: {
      Articles
    }
  }).mount(dom);
}