import { createRouter, createWebHistory } from 'vue-router';
import ventasList from '../../src/ventas/VentasList.vue';
import ventasCreate from '../../src/ventas/VentasCreate.vue';

const routes = [
    {
        path: '/caja/ventas',
        name: 'ventasList',
        component: ventasList,
    },
    {
        path: '/caja/ventas/create',
        name: 'ventasCreate',
        component: ventasCreate,
    },
];

const routerVentas = createRouter({
    history: createWebHistory('/'),
    routes,
});

export default routerVentas;
