import { createRouter, createWebHistory } from 'vue-router';
import productList from '../../src/product/ProductsList.vue';
import productCreate from '../../src/product/ProductsCreate.vue';

const routes = [
    {
        path: '/inventario/products',
        name: 'productList',
        component: productList,
    },
    {
        path: '/inventario/products/create',
        name: 'productCreate',
        component: productCreate,
    },
];

const routerProduct = createRouter({
    history: createWebHistory('/'),
    routes,
});

export default routerProduct;
