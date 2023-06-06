$(() => {
    $('#table1').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
        },
        processing: true,
        serverSide: true,
        ajax: '/ventas',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' }
        ]
    });
})