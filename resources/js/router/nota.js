import { createRouter, createWebHistory } from 'vue-router';
// import shoppingList from '../../src/shopping/ShoppingList.vue';
// import notaCreate from '../../src/nota/notaCreate.vue';

const routes = [
    // {
    //     path: '/inventario/shopping',
    //     name: 'shoppingList',
    //     component: shoppingList,
    // },
    // {
    //     path: '/caja/nota',
    //     name: 'shoppingCreate',
    //     component: notaCreate,
    // },
];

const routerNota = createRouter({
    history: createWebHistory('/'),
    routes,
});

export default routerNota;
