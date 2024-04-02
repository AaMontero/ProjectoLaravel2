<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Vendedores Registrados') }}
            </h2>
            @role('Administrador|superAdmin')
                <div onclick="abrirAgregarVendedor()" class="cursor-pointer flex items-center">
                    <span class="mr-2">Agregar Nuevo Vendedor</span>
                    <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </div>
            @endrole
        </div>
    </x-slot>

    <div class="py-2">
        <div id="idAgregarVendedor" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4"
            style="{{ $errors->any() ? 'display: block;' : 'display: none;' }}">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!--Form para introducir un cliente-->
                    <form method="POST" class ="p-4" enctype="multipart/form-data"
                        action = "{{ route('vendedor.store') }} ">
                        @csrf
                        <label class="mt-0.5 ml-4 font-bold">Nombres:</label>
                        <input type="text" name="nombres"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Ingrese los nombres') }}" value="{{ old('nombres') }}">
                        @error('nombres')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 ml-4 font-bold">Rol:</label>
                        <select name="rol"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Seleccione el Rol del Vendedor') }}">
                            <option value="" disabled selected>
                                {{ __('Seleccione el Rol') }}</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol }}" {{ old('rol') == $rol ? 'selected' : '' }}>
                                    {{ $rol }}
                                </option>
                            @endforeach
                        </select>
                        @error('rol')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror

                        <label class="mt-3 ml-4 font-bold">Usuario Asociado:</label>
                        <select name="user_vend_id"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Seleccione el % del Vendedor') }}">
                            <option value="" disabled selected>
                                {{ __('Seleccione el usuario') }}</option>
                            @foreach ($usuarios as $usuario_asociado)
                                <option value="{{ $usuario_asociado->id }}"
                                    {{ old('usuario_asociado') == $rol ? 'selected' : '' }}>
                                    {{ $usuario_asociado->id }} - {{ $usuario_asociado->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_vend_id')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <x-primary-button
                            class='mt-4 bg-gray-800 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out'>Agregar
                            nuevo vendedor</x-primary-button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-white dark:bg-gray-900 bg-opacity-50 shadow-lg rounded-lg ">
        <div class="p-6 text-gray-900 dark:text-gray-100 overflow-auto">
            <div id="users">
                <input class="search mb-4" placeholder="Buscar" />
                <table class="w-100 bg-[#f8fafc] dark:bg-gray-800 border border-gray-300" style="overflow-x: auto;">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b text-center ">ID</th>
                            <th class="py-2 px-4 border-b text-center ">Nombres</th>
                            <th class="py-2 px-4 border-b text-center ">Rol</th>
                            <th class="py-2 px-4 border-b text-center ">Estado</th>
                            <th class="py-2 px-4 border-b text-center ">Opciones</th>
                        </tr>
                        <!-- IMPORTANT, class="list" have to be at tbody -->
                    <tbody class="list">
                        @foreach ($vendedores as $vendedor)
                            <tr>
                                <td class="py-2 px-4 border-b text-center">{{ $vendedor->id }}</td>
                                <td class="py-2 px-4 border-b text-center nombres">{{ $vendedor->nombres }}</td>
                                <td class="py-2 px-4 border-b text-center rol">{{ $vendedor->rol }}</td>
                                <td class="py-2 px-4 border-b text-center estado">
                                    {{ $vendedor->activo == '1' ? 'Activo' : 'Inactivo' }}</td>
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
                                            <x-dropdown-link :href="route('vendedor.datos_vendedor', $vendedor->id)">
                                                {{ __('Ver ventas') }}
                                            </x-dropdown-link>
                                            @role('Administrador|superAdmin')
                                                <x-dropdown-link :href="route('vendedor.edit', $vendedor)">
                                                    {{ __('Editar Vendedor') }}
                                                </x-dropdown-link>
                                            @endrole
                                            @role('Administrador|superAdmin')
                                                <x-dropdown-link :href="route('vendedor.cambiarActivo', $vendedor)" onclick="return confirmDelete(event)">
                                                    {{ __('Eliminar Vendedor') }}
                                                </x-dropdown-link>
                                            @endrole
                                        </x-slot>
                                    </x-dropdown>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <script>
                    function confirmDelete(event) {
                        event.preventDefault();
                        Swal.fire({
                            title: "¿Deseas eliminar este vendedor?",
                            showDenyButton: true,
                            confirmButtonText: "Sí",
                            denyButtonText: `No`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Continuar con la eliminación
                                window.location.href = event.target.getAttribute('href');
                            } else if (result.isDenied) {
                                // No hacer nada
                            }
                        });
                        return false;
                    }
                </script>
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



    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
    <script>
        // buscador
        var options = {
            valueNames: ['nombres', 'rol', 'estado'],
            page: 10, // Número de elementos por página
            pagination: true // Habilitar paginación
        };

        var userList = new List('users', options);



        function abrirAgregarVendedor() {
            var VentanaAgregarContrato = document.getElementById("idAgregarVendedor");
            if (VentanaAgregarContrato.style.display === 'none') {
                VentanaAgregarContrato.style.display = 'block';
            } else {
                VentanaAgregarContrato.style.display = 'none';
            }
        }
    </script>

</x-app-layout>
@include('layouts.footer')
