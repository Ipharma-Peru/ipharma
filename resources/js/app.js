import './dark';
import 'alpinejs';
// import './ventas';
import './form-element-select';
// import './productos';
import { createApp } from "vue";
import Ventas from "../src/ventas/Ventas.vue";
import Blog from "../src/blogs/BlogsIndex.vue";
import Product from "../src/product/ProductsIndex.vue";
import Shopping from "../src/shopping/ShoppingIndex.vue";
import Nota from "../src/nota/NotaIndex.vue";

import router from './router';
import routerProduct from './router/product';
import routerShopping from './router/shopping';
import routerNota from './router/nota';

// const app = createApp({});
if (document.getElementById('ventas')) {
    createApp(Ventas).mount("#ventas");
    // app.component('ventas', Ventas);
    // app.mount("#ventas");
}

if (document.getElementById('blogs')) {
    createApp(Blog).use(router).mount('#blogs');
  
}
if (document.getElementById('products')) {
    createApp(Product).use(routerProduct).mount('#products');
}
if (document.getElementById('nota')) {
    createApp(Nota).use(routerNota).mount('#nota');
}
if (document.getElementById('shopping')) createApp(Shopping).use(routerShopping).mount('#shopping');




