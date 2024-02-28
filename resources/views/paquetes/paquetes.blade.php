<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Packages') }}
            </h2>
            <div onclick="abrirVentanaAgregarPaquete()" class="cursor-pointer flex items-center">
                <span class="mr-2">Agregar un nuevo paquete</span>
                <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div id="idAgregarPaquete" class="max-w-7xl mx-auto sm:px lg:px-8 pb-2" style="display: none;">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action = "{{ route('paquetes.store') }} " enctype="multipart/form-data">
                        @csrf
                        <p class="mt-1 p-1 ml-4">Nombre del paquete:</p>
                        <input type="text" name="nombre_paquete"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Put your message here') }}" value="{{ old('nombre_paquete') }}">

                        {{-- message --}}
                        <p class="mt-1 p-1 ml-4">Descripción del paquete</p>
                        <input type="text" name="message"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Put your message here') }}" value="{{ old('message') }}">

                        <p class="mt-1 p-1 ml-4">Número de días: </p>
                        <input type="number" name="num_dias"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Put your message here') }}" value="{{ old('num_dias') }}">


                        <p class="mt-1 p-1 ml-4">Número de noches:</p>
                        <input type="number" name="num_noches"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Put your message here') }}" value="{{ old('num_noches') }}">

                        <p class="mt-1 p-1 ml-4">Precio Afiliados:</p>
                        <input type="number" name="precio_afiliado" step="0.01"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Put your message here') }}" value="{{ old('precio_afiliado') }}">

                        <p class="mt-1 p-1 ml-4">Precio no afiliados:</p>
                        <input type="number" name="precio_no_afiliado" step="0.01"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Put your message here') }}" value="{{ old('precio_no_afiliado') }}">
                        <p class="mt-1 p-1 ml-4">Imagen del paquete:</p>
                        <input type="file" name="imagen_paquete" class ="form-control mb-2"
                            value = "{{ old('imagen_paquete') }}">
                        <input type="hidden" id = "lista_caracteristicas" name = "lista_caracteristicas">
                        <div>
                            <p class="mt-1 p-1 ml-4">Agregar Característica</p>
                            <div class="flex">
                                <input type = "text" name="lugar_caracteristica" id ="lugar_caracteristica"
                                    class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                                    placeholder="{{ __('Lugar') }}" value="{{ old('ciudad_caracteristica') }}">
                                <input type="text" name="caracteristica" id="caracteristica"
                                    class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                                    placeholder="{{ __('Ingrese su característica aquí') }}"
                                    value="{{ old('caracteristica') }}">
                                <button type="button" onclick="agregarCaracteristica()"
                                    class="ml-2 h-full bg-blue-500 text-white font-bold py-2 px-4 rounded">
                                    Agregar
                                </button>
                            </div>
                        </div>
                        <script>
                            function abrirVentanaAgregarPaquete() {

                                var ventanaAgregarPaquete = document.getElementById("idAgregarPaquete");
                                console.log(ventanaAgregarPaquete.style.display);
                                if (ventanaAgregarPaquete.style.display === 'none') {
                                    ventanaAgregarPaquete.style.display = 'block';
                                } else {
                                    ventanaAgregarPaquete.style.display = 'none';
                                }

                                console.log("esta dando click en el boton para ocultar");

                            }
                            let listaCaracteristicas = [];

                            function agregarCaracteristica() {
                                const caracteristicaCiudad = document.getElementById("lugar_caracteristica");
                                const caracteristicaInput = document.getElementById("caracteristica");
                                const caracteristicaTexto = caracteristicaInput.value.trim();
                                const caracteristicaCiudadTexto = caracteristicaCiudad.value.trim();
                                if (caracteristicaTexto !== "") {
                                    // Validación para asegurar que caracteristicaCiudadTexto no esté vacía
                                    const caracteristicaCiudadValidada = caracteristicaCiudadTexto !== "" ? caracteristicaCiudadTexto :
                                        "";

                                    const caracteristica = [
                                        caracteristicaTexto, caracteristicaCiudadValidada
                                    ];

                                    listaCaracteristicas.push(caracteristica);
                                    caracteristicaInput.value = "";
                                    caracteristicaCiudad.value = "";
                                    // Cambiado a innerHTML para mostrar la lista en un elemento div
                                    document.getElementById("lista_caracteristicas").value = JSON.stringify(listaCaracteristicas);
                                    alert("Se ha agregado la característica: " + caracteristicaTexto);
                                } else {
                                    alert("Por favor, ingresa una característica válida.");
                                }

                                console.log(listaCaracteristicas);
                            }
                        </script>
                        <x-input-error :messages="$errors->get('message')" />
                        <x-primary-button class='mt-4'>Agregar nuevo paquete</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
        <style>
            .spaninfo {
                color: black;
            }

            .spanTitulo {
                font-weight: bold;
                color: red;
                font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            }

            .spanTituloPaquete {
                font-weight: bold;
                color: blue;
                font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
                font-size: 2rem;
                text-align: center;
            }
        </style>
        <div class="flex items-center w-full ml-10 pt-4">
            <form action="{{ route('paquetes.paquetes') }}" method="GET" class="flex space-x-4 w-full">
                <div class = "w-1/4 flex">
                    <div class ="w-1/2 mr-4">
                        <label for="num_dias" class="block text-sm font-medium text-gray-700">Número de días:</label>
                        <input type="number" name="num_dias" id="num_dias"
                            class="mt-1 p-2 border rounded-md w-full">
                    </div>
                    <div class ="w-1/2 ">
                        <label for="num_noches" class="block text-sm font-medium text-gray-700 ">Número de
                            noches:</label>
                        <input type="number" name="num_noches" id="num_noches"
                            class="mt-1 p-2 border rounded-md w-full">
                    </div>
                </div>
                <div class = "w-1/4 flex">
                    <div class ="w-1/2 mr-4">
                        <label for="precio_min" class="block text-sm font-medium text-gray-700">Precio Mínimo:</label>
                        <input type="number" name="precio_min" id="precio_min"
                            class="mt-1 p-2 border rounded-md w-full">
                    </div>
                    <div class ="w-1/2 mr-4">
                        <label for="precio_max" class="block text-sm font-medium text-gray-700">Precio Máximo:</label>
                        <input type="number" name="precio_max" id="precio_max"
                            class="mt-1 p-2 border rounded-md w-full">
                    </div>
                </div>
                <div class = "w-1/4">
                    <label for="caracteristica"
                        class="block text-sm font-medium text-gray-700">Característica:</label>
                    <input type="text" name="caracteristica" id="caracteristica"
                        class="mt-1 p-2 border rounded-md w-full">
                </div>
                <div class = "w-1/4 flex">
                    <div class ="w-1/2 mr-4">
                        <label for="socios" class="block text-sm font-medium text-gray-700">Socios:</label>
                        <select name="socios" id="socios" class="mt-1 p-2 border rounded-md w-full">
                            <option value="no_socios">No</option>
                            <option value="socios">Sí</option>
                        </select>
                    </div>
                    <div class = "w-1/2">
                        <input type="submit" value="Buscar"
                            class="mt-6 px-4 py-2 bg-blue-500 text-white rounded-md cursor-pointer">
                    </div>
                </div>


            </form>
        </div>

        <div class="mt-4 mr-16 ml-16 bg-white dark:bg-gray-800 shadow-sm rounded-lg divide-y dark:divide-gray-900">
            @foreach ($paquetes as $paquete)
                <div class = "text-center">
                    <span class = "spanTituloPaquete ml-5 ">{{ $paquete->nombre_paquete }}</span></p>
                </div>

                <div
                    class="p-6 bg-transparent flex justify-between items-center border-b border-gray-300 dark:border-gray-700">

                    <div class="w-3/5 h-3/5 text-gray-800 dark:text-gray-200">


                        <div class=" w-90 text-gray-800 dark:text-gray-200">

                            <!-- Mensaje del paquete -->
                            <p><span class="spanTitulo">Descripcion:</span> <span
                                    class="spaninfo">{{ $paquete->message }}</span></p>
                            <!-- Número de días y Número de Noches en la misma fila -->
                            <table width="100%">
                                <tr>
                                    <td style="width: 25%;">
                                        <p><span class="spanTitulo">Número de días:</span> </p>
                                        <p><span class="spanTitulo">Número de Noches:</span> </p>
                                    </td>
                                    <td style="width: 25%;">
                                        <p><span class="spaninfo">{{ $paquete->num_dias }}</span></p>
                                        <p><span class="spaninfo">{{ $paquete->num_noches }}</span></p>
                                    </td>
                                    <td style="width: 25%;">
                                        <p><span class="spanTitulo">Precio Afiliados:</span></p>
                                        <p><span class="spanTitulo">Precio No Afiliados:</span> </p>
                                    </td>
                                    <td style="width: 25%;">
                                        <p><span class="spaninfo">${{ $paquete->precio_afiliado }}</span></p>
                                        <p><span class="spaninfo">${{ $paquete->precio_no_afiliado }}</span></p>
                                    </td>
                                </tr>
                            </table>


                            <!-- Mostrar las características del paquete -->
                            <p class="spanTitulo">Características del paquete</p>
                            <ul class="list-decimal pl-8">
                                @foreach ($paquete->incluye as $caracteristica)
                                    <li class="spaninfo flex items-center">
                                        <img src="{{ asset('images/iconoEtiqueta.png') }}" class="w-4 h-4 mr-2"
                                            alt="Check Circle Icon">
                                        {{ $caracteristica->lugar }} - {{ $caracteristica->descripcion }}
                                    </li>
                                @endforeach
                            </ul>

                        </div>

                    </div>
                    <div class="w-2/5 h-3/5">
                        <img class="w-full h-full rounded-lg" src='uploads/paquetes/{{ $paquete->imagen_paquete }}'
                            alt="Imagen del paquete">
                    </div>


                    <!-- Dropdown para acciones -->
                    <x-dropdown>
                        <x-slot name="trigger">
                            <button>
                                <svg class="ml-5 w-5 h-5 text-gray-400 dark:text-gray-200"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Enlace para editar el paquete -->
                            <x-dropdown-link :href="route('paquetes.edit', $paquete)">
                                {{ __('Editar Paquete') }}
                            </x-dropdown-link>

                            <!-- Formulario para eliminar el paquete -->
                            <form method="POST" action="{{ route('paquetes.destroy', $paquete) }}">
                                @csrf @method('DELETE')
                                <x-dropdown-link :href="route('paquetes.destroy', $paquete)"
                                    onclick="event.preventDefault(); this.closest('form').submit()">
                                    {{ __('Eliminar Paquete') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Separador entre paquetes -->
                <hr class="my-4 border-gray-300 dark:border-gray-700">
            @endforeach
        </div>
        <div class = "ml-20 mr-20">
            <p class="ml-5 flex justify-center items-center list-none space-x-2">
                {{ $paquetes->appends([
                    'num_dias' => $num_dias,
                    'num_noches' => $num_noches,
                    'precio_min' => $precio_min,
                    'precio_max' => $precio_max,
                ]) }}
            </p>
        </div>




    </div>
    </div>

    @include('layouts.footer')
</x-app-layout>
