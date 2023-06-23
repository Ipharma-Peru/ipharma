<div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="buttons">
                            @livewire('product.create')
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($products->count())
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>descripción</th>
                                            <th>Tipo</th>
                                            <th>Presentación</th>
                                            <th>laboradorio</th>
                                            <th>Principio Activo</th>
                                            <th>Acción Famacologica</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td class="text-bold-500">Michael Right</td>
                                                <td>$15/hr</td>
                                                <td class="text-bold-500">UI/UX</td>
                                                <td>Remote</td>
                                                <td>Austin,Taxes</td>
                                                <td>Austin,Taxes</td>
                                                <td>Austin,Taxes</td>
                                                <td>
                                                    <a href="#"><svg xmlns="http://www.w3.org/2000/svg"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-mail badge-circle badge-circle-light-secondary font-medium-1">
                                                            <path
                                                                d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                            </path>
                                                            <polyline points="22,6 12,13 2,6"></polyline>
                                                        </svg></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        @else
                            No hay resultados para la busqueda en nuestros registros.
                            {{-- No hay resultados para la busqueda "{{ $search }}" en nuestros registros. --}}

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
