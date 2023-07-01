<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Productos</h3>
            </div>
        </div>
    </x-slot>


    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="buttons">
                            <button type="button" class="btn btn-outline-primary block" data-bs-toggle="modal"
                                data-bs-target="#backdrop"><i class="bi bi-pencil"></i> Nuevo
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>NAME</th>
                                        <th>RATE</th>
                                        <th>SKILL</th>
                                        <th>TYPE</th>
                                        <th>LOCATION</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-bold-500">Michael Right</td>
                                        <td>$15/hr</td>
                                        <td class="text-bold-500">UI/UX</td>
                                        <td>Remote</td>
                                        <td>Austin,Taxes</td>
                                        <td>
                                            <a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="feather feather-mail badge-circle badge-circle-light-secondary font-medium-1">
                                                    <path
                                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                    </path>
                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                </svg></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500">Morgan Vanblum</td>
                                        <td>$13/hr</td>
                                        <td class="text-bold-500">Graphic concepts</td>
                                        <td>Remote</td>
                                        <td>Shangai,China</td>
                                        <td>
                                            <a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="feather feather-mail badge-circle badge-circle-light-secondary font-medium-1">
                                                    <path
                                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                    </path>
                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                </svg></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500">Tiffani Blogz</td>
                                        <td>$15/hr</td>
                                        <td class="text-bold-500">Animation</td>
                                        <td>Remote</td>
                                        <td>Austin,Texas</td>
                                        <td>
                                            <a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="feather feather-mail badge-circle badge-circle-light-secondary font-medium-1">
                                                    <path
                                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                    </path>
                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                </svg></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500">Ashley Boul</td>
                                        <td>$15/hr</td>
                                        <td class="text-bold-500">Animation</td>
                                        <td>Remote</td>
                                        <td>Austin,Texas</td>
                                        <td>
                                            <a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="feather feather-mail badge-circle badge-circle-light-secondary font-medium-1">
                                                    <path
                                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                    </path>
                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                </svg></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold-500">Mikkey Mice</td>
                                        <td>$15/hr</td>
                                        <td class="text-bold-500">Animation</td>
                                        <td>Remote</td>
                                        <td>Austin,Texas</td>
                                        <td>
                                            <a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="feather feather-mail badge-circle badge-circle-light-secondary font-medium-1">
                                                    <path
                                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                                    </path>
                                                    <polyline points="22,6 12,13 2,6"></polyline>
                                                </svg></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade text-left" id="backdrop" tabindex="-1" aria-labelledby="myModalLabel4"
        data-bs-backdrop="static" data-bs-keyboard="false" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nuevo Producto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" data-parsley-validate="" novalidate="" id="nuevoproducto">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mandatory">
                                    <label for="codigo" class="form-label">Código</label>
                                    <input type="text" id="codigo" class="form-control" placeholder="Código"
                                        name="codigo" data-parsley-required="true">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <input type="text" id="descripcion" class="form-control"
                                        placeholder="Descripción" name="descripcion" data-parsley-required="true">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mandatory is-valid">
                                    <fieldset>
                                        <label class="form-label">
                                            Tipo de medicamentos
                                        </label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault"
                                                id="comercial" data-parsley-required="true"
                                                data-parsley-multiple="flexRadioDefault" data-parsley-id="21">
                                            <label class="form-check-label form-label" for="comercial">
                                                Comercial
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault"
                                                id="generico" data-parsley-multiple="flexRadioDefault">
                                            <label class="form-check-label form-label" for="generico">
                                                Generico
                                            </label>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-12">
                                <select class="choices multiple-remove" id="colores" multiple>
                                    <option value="1">Opción 1</option>
                                    <option value="2">Opción 2</option>
                                    <option value="3">Opción 3</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="country-floating" class="form-label">Country</label>
                                    <input type="text" id="country-floating" class="form-control"
                                        name="country-floating" placeholder="Country" data-parsley-required="true">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="company-column" class="form-label">Company</label>
                                    <input type="text" id="company-column" class="form-control"
                                        name="company-column" placeholder="Company" data-parsley-required="true">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="email-id-column" class="form-label">Email</label>
                                    <input type="email" id="email-id-column" class="form-control"
                                        name="email-id-column" placeholder="Email" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="guardar">Understood</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
