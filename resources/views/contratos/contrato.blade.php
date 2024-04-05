<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Contratos Registrados') }}
            </h2>
            <div onclick="abrirVentanaAgregarContrato()" class="cursor-pointer flex items-center">
                <span class="mr-2">Agregar un nuevo contrato</span>
                <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </div>
        </div>
    </x-slot>

    <?php
    $nombres = $email = $apellidos = $ciudad = $provincia = $ubicacionSala = $cedula = $contrato = $formasPago = $pagareText = $montoCuotaPagare = '';
    $aniosContrato = $montoContrato = $numCuotas = $valor_pagare = 0;
    $bonoQory = $bonoQoryInt = $pagareBoolean = $otroFormaPagoBoolean = $tarjertaCredito = $contienePagare = $contieneCreditoDirecto = false;
    date_default_timezone_set('America/Guayaquil');
    $fechaActual = $fechaVencimiento = $fechaInicioCredDir = date('Y-m-d');
    
    ?>


    {{-- Formualrio --}}
    <div class="py-2">
        <div id="idAgregarContrato" class="max-w mx-auto sm:px-6 lg:px-20 mb-4 "
            style="{{ $errors->any() ? 'display: block;' : 'display: none;' }}">
            <!-- -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg ">
                <div class="p-6 text-gray-900 dark:text-gray-100 ">
                    <form action="{{ route('contrato.store') }}" method="POST" class="p-4 ">

                        @csrf
                        <!-- Hidden -->
                        <input type="hidden" id="formas_pago" name="formas_pago">
                        <input type="hidden" id="pagare_monto_info" name="pagare_monto_info">
                        <input type="hidden" id="pagare_fecha_info" name="pagare_fecha_info">
                        <input type="hidden" id="contiene_pagare" name="contiene_pagare">
                        <input type="hidden" id="contiene_credito_directo" name="contiene_credito_directo">
                        <input type="hidden" id="cred_dir_fecha_inicio" name="cred_dir_fecha_inicio">
                        <input type="hidden" id="cred_dir_num_cuotas" name="cred_dir_num_cuotas">
                        <input type="hidden" id="cred_dir_valor" name="cred_dir_valor">
                        <input type="hidden" id="cred_dir_abono" name="cred_dir_abono">

                        <!-- Nombres -->

                        <label for="nombres" class="mt-0.5 p-0 ml-4 font-bold">Nombres</label>
                        <input type="text" id="nombres" name="nombres" value="{{ old('nombres') }}"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50">
                        @error('nombres')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!-- Apellidos -->

                        <label for="apellidos" class="mt-3 p-0 ml-4 font-bold">Apellidos</label>
                        <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos') }}"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50">
                        @error('apellidos')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror

                        <!-- Cédula -->

                        <label for="cedula" class="mt-3 p-0 ml-4 font-bold">Cédula</label>
                        <input type="text" id="cedula" name="cedula" value="{{ old('cedula') }}"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50">
                        @error('cedula')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror

                        <!-- Email -->

                        <label for="email" class="mt-3 p-0 ml-4 font-bold">Email</label>
                        <input type="text" id="email" name="email" value="{{ old('email') }}"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50">
                        @error('email')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror

                        <!-- Ciudad -->

                        <label for="ciudad" class="mt-3 p-0 ml-4 font-bold">Ciudad</label>
                        <input type="text" id="ciudad" name="ciudad" value="{{ old('ciudad') }}"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50">
                        @error('ciudad')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror

                        <!-- Provincia -->
                        <?php
                        $provincias = ['Azuay', 'Bolívar', 'Cañar', 'Carchi', 'Chimborazo', 'Cotopaxi', 'El Oro', 'Esmeraldas', 'Galápagos', 'Guayas', 'Imbabura', 'Loja', 'Los Ríos', 'Manabí', 'Morona Santiago', 'Napo', 'Orellana', 'Pastaza', 'Pichincha', 'Santa Elena', 'Santo Domingo', 'Sucumbíos', 'Tungurahua', 'Zamora Chinchipe'];
                        ?>

                        <label for="provincia" class="mt-3 p-0 ml-4 font-bold">Provincia</label>
                        <select id="provincia" name="provincia"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50">
                            @foreach ($provincias as $p)
                                <option value="{{ $p }}" {{ $p === $provincia ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                        @error('provincia')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror

                        <!-- Ubicacion de la sala -->

                        <label for="ubicacion_sala" class="mt-3 p-0 ml-4 font-bold">Ubicación de la sala</label>
                        <input type="text" id="ubicacion_sala" name="ubicacion_sala"
                            value="{{ old('ubicacion_sala') }}"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50">

                        @error('ubicacion_sala')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!-- Años del contrato -->

                        <label for="anios_contrato" class="mt-3 p-0 ml-4 font-bold">Años del contrato</label>
                        <input type="number" id="anios_contrato" name="anios_contrato"
                            value="{{ old('anios_contrato') }}"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50">
                        @error('anios_contrato')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror

                        <!-- Monto del contrato -->

                        <label for="monto_contrato" class="mt-3 p-0 ml-4 font-bold">Monto del contrato</label>
                        <input type="number" id="monto_contrato" name="monto_contrato"
                            value="{{ old('monto_contrato') }}"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50">
                        @error('monto_contrato')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-1 p- ml-4 font-bold">Forma de pago:</label>
                        <!-- Forma de pago (añadir más de una) -->
                        <div class="mt-2 mb-2 ml-8">

                            <div class="mt-2 italic">
                                <input type="checkbox" name="forma_pago" value="{{ $pagareBoolean }}"
                                    id="pagareCheckbox" class="mr-2 "> Pagaré
                            </div>

                            <div id="divPagareCheckbox" class="hidden mt-1 mb-4">
                                <label for="valor" class="mr-2 mt-1 p-0 ml-4 font-bold">Valor:</label>
                                <input type="number" id="valor_pagare" name="valor_pagare"
                                    placeholder="Ingrese el valor" class="border rounded-md px-3 py-2 mr-2">
                                <label for="fechaPago" class="mr-2 mt-1 p-0 ml-4 font-bold">Fecha de Pago:</label>
                                <input type="date" id="fecha_pago_pagare" name="fechaPago"
                                    class="border rounded-md px-3 py-2 mr-2">
                                <button onclick="functionAgregarPagare()"
                                    class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">+</button>
                            </div>

                            <div class="mt-2 italic">
                                <input type="checkbox" value="{{ $pagareBoolean }}" id="creditoDirectoCheckbox"
                                    class="mr-2 ">
                                Crédito Directo
                            </div>
                            <div id="divCreditoDirectoCheckBox" class="hidden mt-1 mb-4">
                                <label for="montoCredDir" class="mr-2 mt-1 p-0 ml-4 font-bold">Valor:</label>
                                <input type="number" id="monto_credito_directo" name="montoCredDir"
                                    placeholder="Valor" class="border rounded-md px-3 py-2 mr-2 w-15">
                                <label for="abonoCredDir" class="mr-2 mt-1 p-0 ml-4 font-bold">Abono:</label>
                                <input type="number" id="abono_credito_directo" name="abonoCredDir"
                                    placeholder="Abono" class="border rounded-md px-3 py-2 mr-2 w-15">
                                <label for="mesesCredDir" class="mr-2 py-2 mt-1 p-0 ml-4 font-bold"># Meses: </label>
                                <select id="meses_credito_directo" name="mesesCredDir"
                                    class="border rounded-md px-3 py-2 mr-2 w-20">
                                    <option value="12">12</option>
                                    <option value="24">24</option>
                                    <option value="36">36</option>
                                </select>
                                <label for="fechaInicioCredDir" class="mr-2 mt-1 p-0 ml-4 font-bold">Fecha de
                                    Inicio:</label>
                                <input type="date" id="fecha_inicio_cred_dir" name="fechaInicioCredDir"
                                    class="border rounded-md px-3 py-2 mr-2">
                                <button onclick="functionAgregarCreditoDirecto()"
                                    class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">+</button>
                            </div>

                            <div class="mt-2 italic">
                                <input type="checkbox" value="{{ $otroFormaPagoBoolean }}" id="otroCheckbox"
                                    class="mr-2 "> Tarjeta de débito
                            </div>
                            <div id="divOtrosCheckbox" class="hidden mt-1 mb-4">
                                <label for="monto" class="mr-2 mt-1 p-0 ml-4 font-bold">Valor:</label>
                                <input type="number" id="monto_forma_pago" name="monto_forma_pago"
                                    placeholder="Ingrese el valor" class="border rounded-md px-3 py-2 mr-2 w-15">
                                <label for="formaPago" class="mr-2 mt-1 p-0 ml-4 font-bold">Forma:</label>
                                <input type="text" id="forma_pago" name="forma_pago"
                                    class="border rounded-md px-3 py-2 mr-2 w-1/2">
                                <button onclick="functionAgregar()"
                                    class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">+</button>
                            </div>

                            <div class="mt-2 italic">
                                <input type="checkbox" value="{{ $tarjertaCredito }}" id="tarjetaCreditoCheckbox"
                                    class="mr-2 "> Tarjeta de crédito
                            </div>
                            <div class="flex">
                                <div id="tarjetaCreditoCheck" class="hidden mt-1 mb-4 ">
                                    <label for="monto" class="mr-2 mt-1 p-0 ml-4 font-bold">Valor:</label>
                                    <input type="number" id="monto" name="monto"
                                        placeholder="Ingrese el valor" class="border rounded-md px-3 py-2 mr-2 w-15">
                                    {{-- tarjeta --}}
                                    <label for="formaPago" class="mr-2 mt-1 p-0 ml-4 font-bold">Tarjeta:</label>
                                    <select id="formaPago" name="formaPago"
                                        class="border rounded-md px-3 py-2 mr-2 w-20">
                                        <option value="Visa">Visa</option>
                                        <option value="MasterCard">MasterCard</option>
                                        <option value="DinnersClub">Dinners Club</option>
                                    </select>
                                    {{-- banco --}}
                                    <label for="banco" class="mr-2 mt-1 p-0 ml-4 font-bold">Banco:</label>
                                    <input type="text" id="banco" name="banco"
                                        placeholder="Ingrese el banco" class="border rounded-md px-3 py-2 mr-2 w-15">
                                    {{-- meses de diferimiento --}}
                                    <label for="mesesCredDir" class="mr-2 mt-1 p-0 ml-4 font-bold py-2"># Meses:
                                    </label>
                                    <select id="meses_credito" name="mesesCredDir"
                                        class="border rounded-md px-3 py-2 mr-2 w-20">
                                        <option value="12">12</option>
                                        <option value="24">24</option>
                                        <option value="36">36</option>
                                    </select>
                                    <button onclick="functionAgregarTC()"
                                        class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">+</button>
                                </div>
                            </div>

                        </div>
                        <ul id="listaFormasPagoUl"></ul>
                        @error('formas_pago')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!-- Bono hospedaje Qory Loyalty -->
                        <div class="mb-2">
                            <label class="inline-flex items-center mt-1 p-0  font-bold">Bono hospedaje Qory
                                Loyalty</label>
                            <input type="checkbox" name="bono_hospedaje" id="bono_hospedaje_checkbox">
                        </div>
                        @error('bono_hospedaje')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!-- Bono de hospedaje internacional Qory Loyalty -->
                        <div class="mb-2">
                            <label class="inline-flex items-center mt-1 p-0 font-bold">Certificado
                                Vacacional</label>
                            <input type="checkbox" name="bono_hospedaje_internacional"
                                id="bono_hospedaje_internacional_checkbox" class="ml-2">
                        </div>
                        @error('bono_hospedaje_internacional')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!-- Bono de hospedaje internacional Qory Loyalty -->
                        <div class="mb-2">
                            <label class="inline-flex items-center mt-1 p-0 font-bold">
                                Bono Vacacional Internacional
                            </label>
                            <input type="checkbox" name="bono_certificado_vacacional_internacional"
                                id="bono_certificado_vacacional_internacional_checkbox" class="ml-2"
                                onchange="toggleVisibility()">
                        </div>
                        @error('bono_certificado_vacacional_internacional')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!-- Bono de hospedaje internacional Qory Loyalty -->
                        <div class="mb-2 flex ">
                            <div class = "flex">
                                <label class="inline-flex items-center mt-1 p-0 font-bold">Bono de Semana
                                    Internacional</label>
                                <input type="checkbox" name="bono_semana_internacional"
                                    id="bono_semana_internacional_checkbox" class="ml-3 mt-2"
                                    onchange="toggleVisibility()">

                            </div>
                            <div id = "destino_personas_internacional" style = "display:none">
                                <!-- Agregado margen a la derecha -->
                                <div class="flex ml-10"> <!-- Agregado margen a la izquierda -->
                                    <label class="inline-flex items-center mt-1 p-0 font-bold">Destino: </label>
                                    <input type="text" name="lugar_bono_semana_internacional"
                                        id="lugar_bono_semana_internacional" class="ml-2">
                                </div>
                                @error('lugar_bono_semana_internacional')
                                    <small class="text-red-500 ml-2">{{ $message }}</small>
                                    <br>
                                @enderror
                                <div class="flex ml-10">
                                    <label class="inline-flex items-center mt-1 p-0 font-bold"># Personas: </label>
                                    <input type="text" name="personas_bono_semana_internacional"
                                        id="personas_bono_semana_internacional"
                                        value="{{ old('personas_bono_semana_internacional') }}" class="ml-2">
                                </div>
                                @error('personas_bono_semana_internacional')
                                    <small class="text-red-500 ml-2">{{ $message }}</small>
                                    <br>
                                @enderror
                            </div>

                        </div>
                        @error('bono_semana_internacional')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!-- Aquí está el botón para ejecutar el código -->
                        <button type="submit"
                            class="mt-4 bg-gray-800 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out">Generar
                            Contrato</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- tablas --}}

    <div class="bg-white dark:bg-gray-900 bg-opacity-50 shadow-lg rounded-lg ">
        <div class="p-6 text-gray-900 dark:text-gray-100 overflow-auto">
            <div id="users">
                <input class="search " placeholder="Buscar" />
                <table class="w-100 bg-[#f8fafc] dark:bg-gray-800 border border-gray-300 " style="overflow-x: auto;">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Contrato ID</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Ubicacion Sala</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Años Cont.</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Monto Cont.</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Valor del Credito </th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Abono</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap"># Meses</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Valor del Pagare</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Fecha Fin</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Otro</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Id Cliente</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Vendedor</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Closer 1 </th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Closer 2</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Jefe Sala</th>
                            @role('Administrador|superAdmin')
                                <th class="py-2 px-4 border-b text-center whitespace-nowrap">Opciones</th>
                            @endrole
                            <div class="py-2">
                                <div class="max-w mx-auto px-2 lg:px-20 mb-4">


                        </tr>
                    </thead>
                    <!-- IMPORTANT, class="list" have to be at tbody -->
                    <tbody class="list">
                        @foreach ($contratos as $contrato)
                            <tr>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap contrato_id">
                                    {{ $contrato->contrato_id }}</td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap ubicacion">
                                    {{ $contrato->ubicacion_sala }}</td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap años">
                                    {{ $contrato->anios_contrato }}</td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    ${{ $contrato->monto_contrato }}</td>
                                <!-- Credito Directo-->
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    {{ $contrato->valor_total_credito_directo ? "$" . $contrato->valor_total_credito_directo : 'NO' }}
                                </td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    {{ $contrato->abono_credito_directo ? "$" . $contrato->abono_credito_directo : 'NO' }}
                                </td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    {{ $contrato->meses_credito_directo ? $contrato->meses_credito_directo : 'NO' }}
                                </td>
                                <!-- Valor Pagare-->
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    {{ $contrato->valor_pagare ? "$" . $contrato->valor_pagare : 'NO' }}</td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    {{ $contrato->fecha_fin_pagare ? $contrato->fecha_fin_pagare : 'NO' }}</td>
                                <!-- Otros metodos de Pago-->
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    {!! $contrato->otro_comentario
                                        ? str_replace(']', ']</br>', str_replace('"', '', $contrato->otro_comentario))
                                        : 'NO' !!}
                                </td>

                                {{-- modal dinamico --}}
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#exampleModalCliente{{ $contrato->id }}">
                                        {{ $contrato->cliente_id }}
                                    </button>

                                    <!-- Modal Cliente ID -->
                                    <div class="modal fixed inset-0 z-50 overflow-auto flex justify-center items-center bg-black bg-opacity-50"
                                        id="exampleModalCliente{{ $contrato->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog flex items-center justify-center h-screen bottom-20">
                                            <div class="modal-content bg-white rounded-lg shadow-lg">
                                                <div
                                                    class="flex justify-between modal-header bg-sky-700 text-white px-4 py-2 rounded-t-lg">
                                                    <h5 class="modal-title text-lg font-bold flex-grow text-center"
                                                        id="exampleModalLabel">
                                                        Información del Usuario
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body ml-4 py-4 text-left">
                                                    @foreach ($clientes as $cliente)
                                                        @if ($cliente->id == $contrato->cliente_id)
                                                            <p><strong>Nombres:
                                                                </strong><span>{{ $cliente->nombres }}</span></p>
                                                            <p><strong>Apellidos: </strong>
                                                                <span>{{ $cliente->apellidos }}</span>
                                                            </p>
                                                            <p><strong>Numero de telefono: </strong>
                                                                <span>{{ $cliente->numTelefonico }}</span>
                                                            </p>
                                                            <p><strong>Nacimiento: </strong>
                                                                <span>{{ $cliente->fecha_nacimiento }}</span>
                                                            </p>
                                                            <p><strong>Email: </strong>
                                                                <span>{{ $cliente->email }}</span>
                                                            </p>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                {{-- --- --}}
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#exampleModalVendedor{{ $contrato->id }}">
                                        {{ $contrato->vendedor_id }}
                                    </button>

                                    <!-- Modal  Vendedor-->
                                    <div class="modal fixed inset-0 z-50 overflow-auto flex items-center justify-center h-screen bg-black bg-opacity-50"
                                        id="exampleModalVendedor{{ $contrato->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog flex items-center justify-center h-screen bottom-20">
                                            <div class="modal-content bg-white rounded-lg shadow-lg">
                                                <div
                                                    class="flex justify-between modal-header bg-sky-700 text-white px-4 py-2 rounded-t-lg">
                                                    <h5 class="modal-title text-lg font-bold flex-grow text-center"
                                                        id="exampleModalLabel">
                                                        Información del Vendedor
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                @foreach ($vendedores as $vendedor)
                                                    @if ($vendedor->id == $contrato->vendedor_id)
                                                        <div class="modal-body ml-4 py-4 text-left">
                                                            <p><strong>Nombre:
                                                                </strong><span>{{ $vendedor->nombres }}</span>
                                                            </p>
                                                            <p><strong>Rol: </strong><span>{{ $vendedor->rol }}</span>
                                                            </p>
                                                            <p><strong>Estado:
                                                                </strong><span>{{ $vendedor->activo == 1 ? 'Activo' : 'Inactivo' }}</span>
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                </td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#exampleModalCloser1{{ $contrato->id }}">
                                        {{ $contrato->closer_id }}
                                    </button>
                                    <!-- Modal Closer 1 -->
                                    <div class="modal fixed inset-0 z-50 overflow-auto flex justify-center items-center bg-black bg-opacity-50 "
                                        id="exampleModalCloser1{{ $contrato->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog flex items-center justify-center h-screen bottom-20">
                                            <div class="modal-content bg-white rounded-lg shadow-lg">
                                                <div
                                                    class="flex justify-between modal-header bg-sky-700 text-white px-4 py-2 rounded-t-lg">
                                                    <h5 class="modal-title text-lg font-bold flex-grow text-center"
                                                        id="exampleModalLabel">
                                                        Información de Closer 1
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                @foreach ($vendedores as $vendedor)
                                                    @if ($vendedor->id == $contrato->closer_id)
                                                        <div class="modal-body ml-4 py-4 text-left">
                                                            <p><strong>Nombre:
                                                                </strong><span>{{ $vendedor->nombres }}</span>
                                                            </p>
                                                            <p><strong>Rol: </strong><span>{{ $vendedor->rol }}</span>
                                                            </p>
                                                            <p><strong>Estado:
                                                                </strong><span>{{ $vendedor->activo == 1 ? 'Activo' : 'Inactivo' }}</span>
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#exampleModalCloser2{{ $contrato->id }}">
                                        {{ $contrato->closer2_id }}
                                    </button>
                                    <!-- Modal Closer 2-->
                                    <div class="modal fixed inset-0 z-50 overflow-auto flex justify-center items-center bg-black bg-opacity-50"
                                        id="exampleModalCloser2{{ $contrato->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog flex items-center justify-center h-screen bottom-20">
                                            <div class="modal-content bg-white rounded-lg shadow-lg">
                                                <div
                                                    class="flex justify-between modal-header bg-sky-700 text-white px-4 py-2 rounded-t-lg">
                                                    <h5 class="modal-title text-lg font-bold flex-grow text-center"
                                                        id="exampleModalLabel">
                                                        Información del Closer 2
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                @foreach ($vendedores as $vendedor)
                                                    @if ($vendedor->id == $contrato->closer2_id)
                                                        <div class="modal-body ml-4 py-4 text-left">
                                                            <p><strong>Nombre:
                                                                </strong><span>{{ $vendedor->nombres }}</span>
                                                            </p>
                                                            <p><strong>Rol: </strong><span>{{ $vendedor->rol }}</span>
                                                            </p>
                                                            <p><strong>Estado:
                                                                </strong><span>{{ $vendedor->activo == 1 ? 'Activo' : 'Inactivo' }}</span>
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach


                                            </div>
                                        </div>
                                    </div>

                                </td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#exampleModalJefeSala{{ $contrato->id }}">
                                        {{ $contrato->jefe_sala_id }}
                                    </button>
                                    <!-- Modal Jefe de Sala-->
                                    <div class="modal fixed inset-0 z-50 overflow-auto flex justify-center items-center bg-black bg-opacity-50"
                                        id="exampleModalJefeSala{{ $contrato->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog flex items-center justify-center h-screen bottom-20">
                                            <div class="modal-content bg-white rounded-lg shadow-lg">
                                                <div
                                                    class="flex justify-between modal-header bg-sky-700 text-white px-4 py-2 rounded-t-lg">
                                                    <h5 class="modal-title text-lg font-bold flex-grow text-center"
                                                        id="exampleModalLabel">
                                                        Información del Jefe de Sala
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                @foreach ($vendedores as $vendedor)
                                                    @if ($vendedor->id == $contrato->jefe_sala_id)
                                                        <div class="modal-body ml-4 py-4 text-left">
                                                            <p><strong>Nombre:
                                                                </strong><span>{{ $vendedor->nombres }}</span>
                                                            </p>
                                                            <p><strong>Rol: </strong><span>{{ $vendedor->rol }}</span>
                                                            </p>
                                                            <p><strong>Estado:
                                                                </strong><span>{{ $vendedor->activo == 1 ? 'Activo' : 'Inactivo' }}</span>
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                </td>
                                @role('Administrador|superAdmin')
                                    <td class = "text-right pr-6">
                                        <x-dropdown class="origin-top absolute ">
                                            <x-slot name="trigger">
                                                <button>
                                                    <svg class="ml-5 w-5 h-5 text-gray-400 dark:text-gray-200"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                                    </svg>
                                                </button>
                                            </x-slot>
                                            <x-slot name="content">
                                                <form action="{{ route('eliminar.contrato', $contrato->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-dropdown-link type="submit" onclick="return confirmDelete(event)"
                                                        class="text-red-500">{{ __('Eliminar Contrato') }}</x-dropdown-link>
                                                </form>
                                            </x-slot>
                                        </x-dropdown>
                                    </td>
                                @endrole
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination flex justify-center mt-2">

                    <div class="pagination "></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .pagination li {
            display: inline-block;
            padding: 5px;
        }

        .pagination a {
            display: inline-block;
            padding: 10px 15px;
            /* Ajusta el relleno según sea necesario */
            background-color: #4a4a4a;
            /* Color de fondo del botón */
            color: #fff;
            /* Color del texto del botón */
            border-radius: 5px;
            /* Bordes redondeados */
            text-decoration: none;
            /* Eliminar subrayado del enlace */
            transition: background-color 0.3s ease;
            /* Efecto de transición al pasar el ratón */
        }

        .pagination a:hover {
            background-color: #555;
            /* Cambia el color de fondo al pasar el ratón */
        }
    </style>

    {{-- buscador --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
    <script>
        if (document.getElementById("bono_semana_internacional_checkbox").checked) {
            document.getElementById("bono_semana_internacional").value = true;
        }
        if (document.getElementById("bono_certificado_vacacional_internacional_checkbox").checked) {
            document.getElementById("bono_certificado_vacacional_internacional").value = true;
        }
        if (document.getElementById("bono_hospedaje_internacional_checkbox").checked) {
            document.getElementById("bono_hospedaje_internacional").value = true;
        }
        if (document.getElementById("bono_hospedaje_checkbox").checkek) {
            document.getElementById("bono_hospedaje").value = true;
        }

        function toggleVisibility() {
            var bono_hospedaje_checkbox = document.getElementById("bono_certificado_vacacional_internacional_checkbox");
            var bono_semana_internacional_checkbox = document.getElementById("bono_semana_internacional_checkbox");
            var bono_semana_internacional_div = document.getElementById("destino_personas_internacional");

            if (bono_hospedaje_checkbox.checked || bono_semana_internacional_checkbox.checked) {
                bono_semana_internacional_div.style.display = "flex";
            } else {
                bono_semana_internacional_div.style.display = "none";
            }
        }
        //confimacion de eliminar contrato
        function confirmDelete(event) {
            Swal.fire({
                title: "¿Deseas eliminar este contrato?",
                showDenyButton: true,
                confirmButtonText: "Sí",
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                } else if (result.isDenied) {

                }
            });
            return false;
        }

        // buscador
        var options = {
            valueNames: ['contrato_id', 'ubicacion', 'años'],
            page: 10, // Número de elementos por página
            pagination: true // Habilitar paginación
        };

        var userList = new List('users', options);
        var listaFormasPago = [];
        var pagareBoolean = false;
        var creditoDirectoBoolean = false;


        function functionAgregar() {
            event.preventDefault();
            const valor = document.getElementById("monto_forma_pago");
            const forma = document.getElementById("forma_pago");
            const formaValue = forma.value;
            const valorValue = valor.value;
            if (valorValue === "" || formaValue === "") {
                alert("Por favor, complete todos los campos antes de agregar una forma de pago.");
            } else {
                var cadena = "$" + valorValue + " con " + formaValue + "." + " (Tarjeta de débito) ";
                listaFormasPago.push(cadena);
                valor.value = "";
                forma.value = "";
                document.getElementById("formas_pago").value = JSON.stringify(listaFormasPago);
                alert("Se agregó: " + cadena);
            }
        }

        function functionAgregarTC() {
            event.preventDefault();
            const valor = document.getElementById("monto");
            const forma = document.getElementById("formaPago");
            const banco = document.getElementById("banco");
            const meses = document.getElementById("meses_credito");
            const valorValue = valor.value;
            const formaValue = forma.value;
            const bancoValue = banco.value;
            const mesesValue = meses.value;

            if (valorValue === "" || formaValue === "" || bancoValue === "" || mesesValue === "") {
                alert("Por favor, complete todos los campos antes de agregar una forma de pago.");
            } else {
                var cadena =
                    "$" + valorValue + " con " + formaValue + " del banco " + bancoValue +
                    " a " + mesesValue + " meses." + " (Tarjeta de crédito) ";

                listaFormasPago.push(cadena);
                valor.value = "";
                forma.value = "";
                banco.value = "";
                meses.value = "";
                document.getElementById("formas_pago").value = JSON.stringify(listaFormasPago);
                alert("Se agregó: " + cadena);
            }
        }


        function functionAgregarPagare() {
            if (pagareBoolean == true || creditoDirectoBoolean == true) {
                alert("Ya se agrego un Pagaré previamente");
            } else {
                event.preventDefault();
                const valor = document.getElementById("valor_pagare");
                const fecha = document.getElementById("fecha_pago_pagare");
                const valorValue = valor.value;
                const fechaValue = fecha.value;
                if (valorValue === "" || fechaValue === "") {
                    alert("Por favor, complete todos los campos antes de agregar el Pagaré.");
                } else {
                    document.getElementById("pagare_monto_info").value = JSON.stringify(valorValue);
                    document.getElementById("pagare_fecha_info").value = JSON.stringify(fechaValue);
                    var cadena = "$" + valorValue + " con Pagaré Fecha: " + fechaValue;
                    listaFormasPago.push(cadena);
                    valor.value = "";
                    fecha.value = "";
                    document.getElementById("formas_pago").value = JSON.stringify(listaFormasPago);
                    document.getElementById("contiene_pagare").value = "true";
                    pagareBoolean = true;
                }
            }
        }

        function functionAgregarCreditoDirecto() {
            event.preventDefault();
            if (pagareBoolean == true || creditoDirectoBoolean == true) {
                alert("Ya se ha agregado otra forma de pago");
            } else {
                const creditoDirectoValor = document.getElementById("monto_credito_directo");
                const creditoDirectoFecha = document.getElementById("fecha_inicio_cred_dir");
                const creditoDirectoNumCuotas = document.getElementById("meses_credito_directo");
                const creditoDirectoAbono = document.getElementById("abono_credito_directo");
                const CDValor = creditoDirectoValor.value;
                const CDFechaIni = creditoDirectoFecha.value;
                const CDNumCuotas = creditoDirectoNumCuotas.value;
                const CDAbono = creditoDirectoAbono.value;

                if (CDValor == "" || CDFechaIni == "" || CDNumCuotas == "") {
                    alert("Por favor complete todos los campos del Credito Directo");
                } else {
                    document.getElementById("cred_dir_fecha_inicio").value = JSON.stringify(CDFechaIni);
                    document.getElementById("cred_dir_num_cuotas").value = JSON.stringify(CDNumCuotas);
                    document.getElementById("cred_dir_valor").value = JSON.stringify(CDValor);
                    document.getElementById("cred_dir_abono").value = JSON.stringify(CDAbono);
                    listaFormasPago.push("Se inserto un Credito Directo");
                    document.getElementById("contiene_credito_directo").value = "true";
                    document.getElementById("formas_pago").value = JSON.stringify(listaFormasPago);
                    creditoDirectoValor.value = "";
                    creditoDirectoFecha.value = "";
                    creditoDirectoNumCuotas.value = "";
                    creditoDirectoAbono.value = "";
                    alert("Se agrego un Credito Directo");
                    creditoDirectoBoolean = true;
                }
            }
        }

        function abrirVentanaAgregarContrato() {
            var VentanaAgregarContrato = document.getElementById("idAgregarContrato");
            if (VentanaAgregarContrato.style.display === 'none') {
                VentanaAgregarContrato.style.display = 'block';
            } else {
                VentanaAgregarContrato.style.display = 'none';
            }
        }


        document.addEventListener("DOMContentLoaded", function() {
            const pagareCheckbox = document.getElementById("pagareCheckbox");
            const otroCheckbox = document.getElementById("otroCheckbox");
            const credDirectoCheckBox = document.getElementById("creditoDirectoCheckbox");
            const pagareFields = document.getElementById("divPagareCheckbox");
            const otroFields = document.getElementById("divOtrosCheckbox");
            const creditoDirectoFields = document.getElementById("divCreditoDirectoCheckBox");
            const tarjetaCreditoCheckbox = document.getElementById("tarjetaCreditoCheckbox");
            const divTarjetaCredito = document.getElementById("tarjetaCreditoCheck")
            pagareCheckbox.addEventListener("change", function() {
                if (pagareCheckbox.checked) {
                    pagareFields.style.display = "flex";
                    pagareFields.style.alignItems = "center";
                } else {
                    pagareFields.style.display = "none";
                }
            });
            otroCheckbox.addEventListener("change", function() {
                if (otroCheckbox.checked) {
                    otroFields.style.display = "flex";
                    otroFields.style.alignItems = "center";
                } else {
                    otroFields.style.display = "none";
                }
            });
            credDirectoCheckBox.addEventListener("change", function() {
                if (credDirectoCheckBox.checked) {
                    creditoDirectoFields.style.display = "flex";
                    creditoDirectoFields.style.alignItems = "center";
                } else {
                    creditoDirectoFields.style.display = "none";
                }
            });
            tarjetaCreditoCheckbox.addEventListener("change", function() {
                if (tarjetaCreditoCheckbox.checked) {
                    divTarjetaCredito.style.display = "flex";
                    divTarjetaCredito.style.alignItems = "center";
                } else {
                    divTarjetaCredito.style.display = "none";
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el contenedor del modal
            const modalContainer = document.querySelector('[x-data="{ mostrarModal: false }"]');


        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</x-app-layout>
@include('layouts.footer')
