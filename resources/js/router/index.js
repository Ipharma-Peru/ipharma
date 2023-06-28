import { createRouter, createWebHistory } from 'vue-router';
import BlogList from '../../src/blogs/BlogsList.vue';
import BlogCreate from '../../src/blogs/BlogsCreate.vue';

const routes = [
    {
        path: '/blogs',
        name: 'blogList',
        component: BlogList,
    },
    {
        path: '/blogs/create',
        name: 'blogCreate',
        component: BlogCreate,
    },
];

const router = createRouter({
    history: createWebHistory('/'),
    routes,
});

export default router;
