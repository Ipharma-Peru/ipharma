import { createRouter, createWebHistory } from 'vue-router';
import shoppingList from '../../src/shopping/ShoppingList.vue';
import shoppingCreate from '../../src/shopping/ShoppingCreate.vue';

const routes = [
    {
        path: '/inventario/shopping',
        name: 'shoppingList',
        component: shoppingList,
    },
    {
        path: '/inventario/shopping/create',
        name: 'shoppingCreate',
        component: shoppingCreate,
    },
];

const routerShopping = createRouter({
    history: createWebHistory('/'),
    routes,
});

export default routerShopping;
