<x-maz-sidebar :href="route('dashboard')" :logo="asset('images/logo/logo.png')">

    <!-- Add Sidebar Menu Items Here -->

    <x-maz-sidebar-item name="Dashboard" :link="route('dashboard')" icon="bi bi-grid-fill"></x-maz-sidebar-item>
    <x-maz-sidebar-item name="Blogs" :link="route('blogs')" icon="bi bi-grid-fill"></x-maz-sidebar-item>
    <x-maz-sidebar-item name="Inventario" icon="bi bi-puzzle-fill">
        <x-maz-sidebar-sub-item name="Productos" :link="route('inventario.products')"></x-maz-sidebar-sub-item>
        <x-maz-sidebar-sub-item name="Compras" :link="route('inventario.shopping')"></x-maz-sidebar-sub-item>
    </x-maz-sidebar-item>
    <x-maz-sidebar-item name="Caja" icon="bi bi-cart4">
        <x-maz-sidebar-sub-item name="Nota de Crédito" :link="route('caja.nota')"></x-maz-sidebar-sub-item>
        <x-maz-sidebar-sub-item name="ventas" :link="route('caja.ventas')"></x-maz-sidebar-sub-item>
        <x-maz-sidebar-sub-item name="Lista Ventas" :link="route('caja.list')"></x-maz-sidebar-sub-item>
    </x-maz-sidebar-item>
    {{-- <x-maz-sidebar-item name="Component" icon="bi bi-stack">
        <x-maz-sidebar-sub-item name="Accordion" :link="route('components.accordion')"></x-maz-sidebar-sub-item>
        <x-maz-sidebar-sub-item name="Alert" :link="route('components.alert')"></x-maz-sidebar-sub-item>
    </x-maz-sidebar-item> --}}

</x-maz-sidebar>