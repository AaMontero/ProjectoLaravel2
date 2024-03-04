<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Vendedores') }}
            </h2>
            <div onclick="abrirAgregarVendedor()" class="cursor-pointer flex items-center">
                <span class="mr-2">Agregar Nuevo Vendedor</span>
                <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div id="idAgregarVendedor" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4" style="display:none">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!--Form para introducir un cliente-->
                    <form method="POST" class ="p-4" enctype="multipart/form-data"
                        action = "{{ route('vendedor.store') }} ">
                        @csrf
                        <label class="mt-1 p-0 ml-4 font-bold">Nombres:</label>
                        <input type="text" name="nombres"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Ingrese los nombres') }}" value="{{ old('nombres') }}">
                        <label class="mt-1 p-0 ml-4 font-bold">ROL:</label>
                        <select name="rol"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Seleccione el Rol del Vendedor') }}">
                            <option value="" disabled selected>
                                {{ __('Seleccione el Rol') }}</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol }}" {{ old('rol') == $rol ? 'selected' : '' }}>
                                    {{ $rol }}
                                </option>
                            @endforeach
                        </select>
                        <label class="mt-1 p-0 ml-4 font-bold">Porcentajes:</label>
                        <select name="porcentaje_ventas"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Seleccione el % del Vendedor') }}">
                            <option value="" disabled selected>
                                {{ __('Seleccione el porcentaje') }}</option>
                            @foreach ($porcentajes as $porcentaje)
                                <option value="{{ $porcentaje }}" {{ old('porcentaje') == $rol ? 'selected' : '' }}>
                                    {{ $porcentaje }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('message')" />
                        <x-primary-button
                            class='mt-4 bg-gray-800 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out'>Agregar
                            nuevo cliente</x-primary-button>
                        <x-input-error :messages="$errors->get('message')" />
                </div>

                </form>
            </div>
        </div>
        <script>
            function abrirVentanaAgregarPaquete() { // Funcion para desplegar el menú
                var ventanaAgregarPaquete = document.getElementById("idAgregarCliente");

                if (ventanaAgregarPaquete.style.display === 'none') {
                    ventanaAgregarPaquete.style.display = 'block';
                } else {
                    ventanaAgregarPaquete.style.display = 'none';
                }

            }
        </script>
    </div>
    <div class="py-2 ">
        <div class="max-w mx-auto px-2 lg:px-20 mb-4">
            <h2 class = "ml-8">Vendedores Registrados</h2>
            <div class="bg-white dark:bg-gray-900 bg-opacity-50 shadow-lg rounded-lg ">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-auto" >
                    <table class="w-100 bg-white dark:bg-gray-800 border border-gray-300"  style="overflow-x: auto;">
                        <thead>
                            <tr> 
                                <th class="py-2 px-4 border-b text-center ">ID</th>
                                <th class="py-2 px-4 border-b text-center ">Nombres</th>
                                <th class="py-2 px-4 border-b text-center ">Rol</th>
                                <th class="py-2 px-4 border-b text-center ">Porcentaje Ventas</th>
                                <th class="py-2 px-4 border-b text-center ">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendedores as $vendedor)
                                <tr> 
                                    <td class="py-2 px-4 border-b text-center">{{ $vendedor->id }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $vendedor->nombres }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $vendedor->rol }}</td>
                                    <td class="py-2 px-4 border-b text-center">{{ $vendedor->porcentaje_ventas }}</td>
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
                                                <?php
                                                
                                                ?>
                                                <x-dropdown-link :href="route('vendedor.edit', $vendedor)">
                                                    {{ __('Editar Vendedor') }}
                                                </x-dropdown-link>


                                            </x-slot>
                                        </x-dropdown>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function abrirAgregarVendedor() {
            var VentanaAgregarContrato = document.getElementById("idAgregarVendedor");
            if (VentanaAgregarContrato.style.display === 'none') {
                VentanaAgregarContrato.style.display = 'block';
            } else {
                VentanaAgregarContrato.style.display = 'none';
            }
        }
    </script>
    @include('layouts.footer')
</x-app-layout>
