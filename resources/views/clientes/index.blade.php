<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <x-slot name="header">
        <link rel="shortcut icon" href="#">

        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Clientes Registrados') }}
            </h2>
            <div onclick="abrirVentanaAgregarPaquete()" class="cursor-pointer flex items-center">
                <span class="mr-2">Agregar un nuevo cliente</span>
                <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </div>
        </div>
    </x-slot>
    <div class="py-2">
        <div id="idAgregarCliente" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4"
            style="{{ $errors->any() ? 'display: block;' : 'display: none;' }}"> <!--"-->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!--Form para introducir un cliente-->
                    <form method="POST" class ="p-4" enctype="multipart/form-data"
                        action = "{{ route('clientes.store') }} ">
                        @csrf
                        <!--Campos para Cédula-->
                        <label class="mt-0.5 p-0 ml-4 font-bold">Cédula:</label>
                        <input type="text" name="cedula"
                            class=" block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Ingrese la cédula') }}" value="{{ old('cedula') }}">
                        @error('cedula')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!--Campos para Nombres -->
                        <label class="mt-3 p-0 ml-4 font-bold">Nombres:</label>
                        <input type="text" name="nombres"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Ingrese los nombres') }}" value="{{ old('nombres') }}">
                        @error('nombres')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!--Campos para Apellidos -->
                        <label class="mt-3 p-0 ml-4 font-bold">Apellidos:</label>
                        <input type="text" name="apellidos"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Ingrese los apellidos') }}" value="{{ old('apellidos') }}">
                        @error('apellidos')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!--Campos para Número Telefónico -->
                        <label class="mt-3 p-0 ml-4 font-bold">Número Telefónico:</label>
                        <input type="text" name="numTelefonico"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Ingrese el número telefónico') }}" value="{{ old('numTelefonico') }}">
                        @error('numTelefonico')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!--Campos para Fecha de Nacimiento -->
                        <label class="mt-3 p-0 ml-4 font-bold">Fecha de Nacimiento:</label>
                        <input type="date" name="fecha_nacimiento"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Ingrese su fecha de Nacimiento') }}"
                            value="{{ old('fecha_nacimiento') }}">
                        @error('fecha_nacimiento')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!--Campos para Email -->
                        <label class="mt-3 p-0 ml-4 font-bold">Email:</label>
                        <input type="email" name="email"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Ingrese el correo electrónico') }}" value="{{ old('email') }}">
                        @error('email')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!--Campos para Provincia -->
                        <label class="mt-3 p-0 ml-4 font-bold">Provincia:</label>
                        <select name="provincia"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Seleccione la provincia') }}">
                            <option value="" disabled selected>
                                {{ __('Seleccione la provincia') }}</option>
                            @foreach ($provincias as $provincia)
                                <option value="{{ $provincia }}"
                                    {{ old('provincia') == $provincia ? 'selected' : '' }}>
                                    {{ $provincia }}
                                </option>
                            @endforeach
                        </select>
                        @error('provincia')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!--Campos para Ciudad -->
                        <label class="mt-3 p-0 ml-4 font-bold">Ciudad:</label>
                        <input type="text" name="ciudad"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Ingrese la ciudad') }}" value="{{ old('ciudad') }}">
                        @error('ciudad')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <!-- Agrega los demás campos del cliente según tu estructura -->
                        <x-input-error :messages="$errors->get('message')" />
                        <x-primary-button
                            class='mt-4 bg-gray-800 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out'>Agregar
                            nuevo cliente</x-primary-button>
                        <x-input-error :messages="$errors->get('message')" />
                </div>

                </form>
            </div>
        </div>

    </div>

    <div class="bg-white dark:bg-gray-900 bg-opacity-50 shadow-lg rounded-lg ">
        <div class="p-6 text-gray-900 dark:text-gray-100 overflow-auto">
            <div id="users">
                <input class="search mb-4" placeholder="Buscar" />
                <table class="w-100 bg-[#f8fafc] dark:bg-gray-800 border border-gray-300 " style="overflow-x: auto;">
                    <thead>
                        <tr> <!--Etiquetas de la tabla de clientes-->
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Cedula</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Nombres</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Apellidos</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">Teléfono</th>
                            <th class="py-2 px-4 border-b text-center whitespace-nowrap">F. Nacimiento</th>
                            <th class="py-2 px-4 border-b text-center ">Email</th>
                            <th class="py-2 px-4 border-b text-center ">Provincia</th>
                            <th class="py-2 px-4 border-b text-center ">Ciudad</th>
                            <th class="py-2 px-4 border-b text-center ">Estado</th>
                            @role('Administrador|superAdmin|Host')
                            <th class="py-2 px-4 border-b text-center ">Opciones</th>
                            @endrole
                           
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($clientes as $cliente)
                            <tr> <!--Tabla que muestra los clientes-->
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap cedula">
                                    {{ $cliente->cedula }}
                                </td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap nombres">
                                    {{ $cliente->nombres }}</td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap apellidos">
                                    {{ $cliente->apellidos }}</td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    {{ $cliente->numTelefonico }}</td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    {{ $cliente->fecha_nacimiento }}</td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap email">
                                    {{ $cliente->email }}
                                </td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    {{ $cliente->provincia }}</td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    {{ $cliente->ciudad }}</td>
                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                    @if ($cliente->activo == 1)
                                        Activo
                                    @else
                                        Inactivo
                                    @endif
                                </td>
                                <td class = "text-right pr-6">
                                    @role('Administrador|superAdmin|Host')
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
                                        <x-slot name="content" id="cliente">

                                            <x-dropdown-link :href="route('contrato.agregar', $cliente)">
                                                {{ __('Agregar Contrato') }}
                                            </x-dropdown-link>
                                            @role('Administrador|superAdmin')
                                            <x-dropdown-link :href="route('clientes.edit', $cliente)">
                                                {{ __('Editar Cliente') }}
                                            </x-dropdown-link>
                                            @endrole
                                        </x-slot>
                                    </x-dropdown>
                                    @endrole
                                    
                                </td>
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
  padding: 10px 15px; /* Ajusta el relleno según sea necesario */
  background-color: #4a4a4a; /* Color de fondo del botón */
  color: #fff; /* Color del texto del botón */
  border-radius: 5px; /* Bordes redondeados */
  text-decoration: none; /* Eliminar subrayado del enlace */
  transition: background-color 0.3s ease; /* Efecto de transición al pasar el ratón */
}

.pagination a:hover {
  background-color: #555; /* Cambia el color de fondo al pasar el ratón */
}

</style>
{{-- <div class = "ml-20 mr-20">
    <p class="ml-5 flex justify-center items-center list-none space-x-2">
        {{ $clientes->appends([]) }}
    </p>
</div> --}}

 {{-- buscador --}}
 <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
|<script>
        // buscador
        var options = {
            valueNames: ['nombres', 'apellidos', 'email', 'cedula'],
            page: 10, // Número de elementos por página
        pagination: true // Habilitar paginación
        };

        var userList = new List('users', options);


    function abrirVentanaAgregarPaquete() { // Funcion para desplegar el menú
                var ventanaAgregarPaquete = document.getElementById("idAgregarCliente");

                if (ventanaAgregarPaquete.style.display === 'none') {
                    ventanaAgregarPaquete.style.display = 'block';
                } else {
                    ventanaAgregarPaquete.style.display = 'none';
                }

            }

</script>

</x-app-layout>
@include('layouts.footer')
