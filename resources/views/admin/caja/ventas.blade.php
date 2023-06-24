<x-app-layout>
    <x-slot name="header">
        <div class="row mb-2">
            <div class="col-6 order-md-1 order-first">
                <div class="badges">
                    <span class="badge bg-light-primary">24/06/2023</span>
                    <span class="badge bg-light-primary"">TC 1$ = S/4.00</span>
                </div>
            </div>
            <div class="col-6 order-md-2 order-last d-flex justify-content-end">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Delivery</label>
                </div>
            </div>
        </div>
    </x-slot>


    <section class="section">
        <div class="row">
            <div class="col-12 col-sm-12 col-lg-6" id="ventasDetail">
                <div class="card">
                    <div
                        class="card-header d-flex flex-column flex-sm-row justify-content-sm-between align-items-center">
                        <div class="custom-switch col-12 col-sm-6">
                            <div class="btn-custom"></div>
                            <button type="button" class="toggle-btn active">Boleta</button>
                            <button type="button" class="toggle-btn">Factura</button>
                        </div>
                        <h4 class="card-title col-12 col-sm-6 text-center text-sm-end">BO19819081098</h4>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Cliente</h4>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control me-2" id="basicInput" placeholder="DNI | RUC"
                                    aria-label="Recipient's username" aria-describedby="button-addon2">

                                <a href="#" class="btn icon btn-primary"><i class="bi bi-pencil"></i></a>
                            </div>
                            <div class="form-group has-icon-left mt-2">
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="first-name-horizontal-icon" disabled>
                                    <div class="form-control-icon">
                                        <i class="bi bi-pin-map-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Artículo</th>
                                        <th>Laboratorio</th>
                                        <th>PVPx</th>
                                        <th>PVPX blister</th>
                                        <th>PVPx fracción</th>
                                        <th>Pol. Dcto. add.</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                        <th>Incentivo</th>
                                        <th>Presentación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-bold-700">1111111</td>
                                        <td>$15/hr</td>
                                        <td class="text-bold-500">UI/UX</td>
                                        <td>Remote</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-700">1111111</td>
                                        <td>$15/hr</td>
                                        <td class="text-bold-500">UI/UX</td>
                                        <td>Remote</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-700">1111111</td>
                                        <td>$15/hr</td>
                                        <td class="text-bold-500">UI/UX</td>
                                        <td>Remote</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                        <td>Austin,Taxes</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="detail-sales d-flex flex-column justify-content-end align-items-end pt-3    ">
                            <div class="sub-total d-flex">
                                <label for="">Sub total</label>
                                <h5 class="ps-5">S/ 0.00</h5>
                            </div>
                            <div class="igv d-flex">
                                <label for="">I.G.V </label>
                                <h5 class="ps-5">S/ 0.00</h5>
                            </div>
                            <div class="otro d-flex">
                                <label for="">Otro tributo</label>
                                <h5 class="ps-5">S/ 0.00</h5>
                            </div>
                            <div class="sub-total d-flex">
                                <h5 for="">Total</h5>
                                <h5 class="ps-5">S/ 0.00</h5>
                            </div>
                            <div class="otro d-flex">
                                <label for="">Redondeo</label>
                                <h5 class="ps-5">S/ 0.00</h5>
                            </div>
                            <div class="sub-total d-flex">
                                <h5 for="">Total a cobrar</h5>
                                <h5 class="ps-5">S/ 0.00</h5>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
            <div class="col-12 col-sm-12 col-lg-6" id="ventasProducts">
                {{-- <livewire:caja.venta /> --}}
                <h1>Hola</h1>
                <div class="" id="ventas"></div>
                {{-- @vite('resources/js/app.js') --}}
            </div>
        </div>
        </div>
        </div>
    </section>
</x-app-layout>
