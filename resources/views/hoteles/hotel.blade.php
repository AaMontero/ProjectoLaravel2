<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div x-data="{ showModal: false }" x-cloak class="flex items-center gap-5">
                @role('Administrador|superAdmin')
                    <x-nav-link :href="route('paquetes.paquetes')"
                        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight no-underline">
                        {{ __('Paquetes') }}
                    </x-nav-link>
                    {{-- hoteles --}}
                    <x-nav-link href="{{ route('hotel') }}"
                        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight no-underline">
                        {{ __('Hoteles') }}
                    </x-nav-link>
                @endrole
            </div>

            @role('Administrador|superAdmin')
                <div onclick="abrirVentanaAgregarPaquete()" class="cursor-pointer flex items-center">
                    <span class="mr-2">Agregar un nuevo hotel</span>
                    <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </div>
            @endrole
        </div>
    </x-slot>

    <div class="py-2">
        <div id="idAgregarPaquete" class="max-w-7xl mx-auto sm:px lg:px-8 pb-2"
            style="{{ $errors->any() ? 'display: block;' : 'display: none;' }}">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('hoteles.store') }} " enctype="multipart/form-data">
                        @csrf
                        <label class="mt-0.5 font-bold p-0 ml-4" for="hotel_nombre">Nombre hotel:</label>
                        <input type="text" name="hotel_nombre" id="hotel_nombre"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el nombre del hotel') }}" value="{{ old('hotel_nombre') }}">
                        @error('hotel_nombre')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <div class="flex w-full">
                            <div class="mr-2 flex-1">
                                <label class="mt-0.5 font-bold p-0" for="pais">País:</label>
                                <input type="text" name="pais" id="pais"
                                    class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="{{ __('Ingresa el país') }}" value="{{ old('pais') }}">
                            </div>
                            <div class="mr-2 flex-1">
                                <label class="mt-0.5 font-bold p-0" for="provincia">Provincia:</label>
                                <input type="text" name="provincia" id="provincia"
                                    class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="{{ __('Ingresa la provincia') }}" value="{{ old('provincia') }}">
                            </div>
                            <div class="flex-1">
                                <label class="mt-0.5 font-bold p-0" for="ciudad">Ciudad:</label>
                                <input type="text" name="ciudad" id="ciudad"
                                    class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="{{ __('Ingresa la ciudad') }}" value="{{ old('ciudad') }}">
                            </div>
                        </div>


                        <label class="mt-3 font-bold p-0 ml-4" for="num_h">Número de habitaciones:</label>
                        <input type="number" name="num_h" id="num_h"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el número de habitaciones') }}" value="{{ old('num_h') }}">
                        @error('num_h')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="num_camas">Número de camas:</label>
                        <input type="number" name="num_camas" id="num_camas"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el número de camas') }}" value="{{ old('num_camas') }}">
                        @error('num_camas')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="precio">Precio:</label>
                        <input type="number" name="precio" id="precio" step="0.01"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el precio') }}" value="{{ old('precio') }}">
                        @error('precio')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <div>
                            <label class="mt-3 font-bold p-0 ml-4" for="servicios[]">Servicios:</label>
                            <input type="text" name="servicios[]"
                                class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="{{ __('Ingresa un servicio') }}">
                        </div>

                        <div id="servicios-container" class="m-3">
                            <!-- nuevo input de opinion -->
                        </div>

                        <div>
                            <button type="button" id="agregar-servicio-btn"
                                class="mt-1 bg-blue-950 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline">
                                Agregar otra servicio
                            </button>

                        </div>
                        @error('servicios')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="tipo_alojamiento">Tipo de alojamiento:</label>
                        <select name="tipo_alojamiento" id="tipo_alojamiento" class="form-control mb-2">
                            <option value="Servicio1">Servicio 1</option>
                            <option value="Servicio2">Servicio 2</option>
                            <option value="Servicio3">Servicio 3</option>
                        </select>

                        @error('tipo_alojamiento')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <div>
                            <label class="mt-3 font-bold p-0 ml-4" for="opiniones[]">Opiniones:</label>
                            <input type="text" name="opiniones[]"
                                class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                placeholder="{{ __('Ingresa una opinión') }}">
                        </div>

                        <div id="opiniones-container" class="m-3">
                            <!-- nuevo input de opinion -->
                        </div>

                        <div>
                            <button type="button" id="agregar-opinion-btn"
                                class="mt-1 bg-blue-950 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline">
                                Agregar otra opinión
                            </button>

                        </div>

                        @error('opiniones')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="imagen_hotel">Imágenes del hotel:</label>
                        <input type="file" name="imagen_hotel[]" id="imagen_hotel" class="form-control mb-2"
                            multiple>
                        <div id="preview-container" class="flex flex-wrap">
                            <!-- Aquí se mostrarán las imágenes -->
                        </div>
                        @error('imagen_hotel')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <x-primary-button class='mt-4'>Agregar nuevo hotel</x-primary-button>
                    </form>
                </div>
            </div>
        </div>


        <script>
            //agregar un input dinamico en las opiniones
            document.addEventListener("DOMContentLoaded", function() {
                var opinionesContainer = document.getElementById('opiniones-container');
                var agregarOpinionBtn = document.getElementById('agregar-opinion-btn');

                agregarOpinionBtn.addEventListener('click', function() {
                    var nuevaOpinionInput = document.createElement('input');
                    nuevaOpinionInput.type = 'text';
                    nuevaOpinionInput.name = 'opiniones[]';
                    nuevaOpinionInput.className =
                        'mt-3 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50';
                    nuevaOpinionInput.placeholder = 'Ingresa otra opinión';

                    // Insertar el nuevo campo antes del botón "Agregar otra opinión"
                    agregarOpinionBtn.parentNode.insertBefore(nuevaOpinionInput, agregarOpinionBtn);
                });
            });

            //agregar un input dinamico en los servicios
            document.addEventListener("DOMContentLoaded", function() {
                var opinionesContainer = document.getElementById('servicios-container');
                var agregarOpinionBtn = document.getElementById('agregar-servicio-btn');

                agregarOpinionBtn.addEventListener('click', function() {
                    var nuevaOpinionInput = document.createElement('input');
                    nuevaOpinionInput.type = 'text';
                    nuevaOpinionInput.name = 'servicios[]';
                    nuevaOpinionInput.className =
                        'mt-3 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50';
                    nuevaOpinionInput.placeholder = 'Ingresa otro servicio';

                    // Insertar el nuevo campo antes del botón "Agregar otra opinión"
                    agregarOpinionBtn.parentNode.insertBefore(nuevaOpinionInput, agregarOpinionBtn);
                });
            });
            //previsializar las imagenes antes de subirlas
            document.getElementById('imagen_hotel').addEventListener('change', function(e) {
                var files = e.target.files;
                var previewContainer = document.getElementById('preview-container');
                previewContainer.innerHTML = '';

                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('preview-image');
                        previewContainer.appendChild(img);
                    }

                    reader.readAsDataURL(file);
                }
            });

            function abrirVentanaAgregarPaquete() {

                var ventanaAgregarPaquete = document.getElementById("idAgregarPaquete");
                console.log(ventanaAgregarPaquete.style.display);
                if (ventanaAgregarPaquete.style.display === 'none') {
                    ventanaAgregarPaquete.style.display = 'block';
                } else {
                    ventanaAgregarPaquete.style.display = 'none';
                }

            }
        </script>

        <style>
            .preview-image {
                max-width: 200px;
                max-height: 200px;
                margin-right: 5px;
            }

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

            .imagen-insertada {
                width: 300px;
                /* Ancho fijo */
                height: 200px;
                /* Alto fijo */
            }
        </style>

        <div id="hotels">
            <div class="mt-4 mx-auto max-w-lg">
                <input class="search w-full p-2 border rounded-md mt-3" placeholder="Buscar hotel..." />
            </div>

            <div
                class="mt-4 md:mr-16 md:ml-16 bg-white dark:bg-gray-800 shadow-sm rounded-lg divide-y dark:divide-gray-900">
                <div class="list">
                    @foreach ($hotel as $hoteles)
                        <div class="p-6 bg-transparent sm:flex sm:justify-between sm:items-center">
                            <div id="carousel{{ $hoteles->id }}" class="carousel slide carousel-fade mx-auto mb-4"
                                data-bs-ride="carousel">
                                <div class = "ml:text-center">
                                    <span class = "spanTituloPaquete nombres">{{ $hoteles->hotel_nombre }}</span>
                                </div>
                                <div style="width: 300px; height: 200px; overflow: hidden;"
                                    class="carousel-inner mx-auto">
                                    <!-- Slides -->
                                    @foreach (explode(',', $hoteles->imagen_hotel) as $index => $imageName)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <img src="{{ asset($imageName) }}"
                                                class="d-block w-64 h-48 object-cover mx-auto" alt="Imagen del hotel"
                                                loading="lazy">
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Botones de navegación -->
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carousel{{ $hoteles->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carousel{{ $hoteles->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <div class="ml:w-3/5 ml:h-3/5 text-gray-800 dark:text-gray-200">
                                <div class=" w-90 text-gray-800 dark:text-gray-200">
                                    <p>Entre las caracteristicas mas importantes de nuestro hotel <span
                                            class="font-bold">{{ $hoteles->hotel_nombre }}</span>, tenemos las siguientes: </p>
                                    <table class="w-full">
                                        <tr>
                                            <td><span class="spanTitulo">País:</span></td>
                                            <td><span class="spaninfo">{{ $hoteles->pais }}</span></td>
                                            <td><span class="spanTitulo">Tipo de servicio:</span></td>
                                            <td><span class="spaninfo">{{ $hoteles->tipo_alojamiento }}</span></td>

                                        </tr>
                                        <tr>
                                            <td><span class="spanTitulo whitespace-no-wrap">Provincia:</span></td>
                                            <td><span class="spaninfo">{{ $hoteles->provincia }}</span></td>
                                            <td><span class="spanTitulo">Ciudad:</span></td>
                                            <td><span class="spaninfo">{{ $hoteles->ciudad }}</span></td>
                                        </tr>
                                        <tr>

                                            <td><span class="spanTitulo">Num de habitaciones:</span></td>
                                            <td><span class="spaninfo">{{ $hoteles->num_h }}</span></td>
                                            <td><span class="spanTitulo">Num de camas:</span></td>
                                            <td><span class="spaninfo">{{ $hoteles->num_camas }}</span></td>
                                        </tr>
                                    </table>

                                    <table width = "100%">
                                        <tr>
                                            <td style="width: 50%;">
                                                <!-- Mostrar los servicios -->
                                                <p class="spanTitulo">Servicios</p>
                                                <ul class="list-decimal pl-8">
                                                    @foreach (explode(',', $hoteles->servicios) as $servicio)
                                                        <li class="spaninfo flex items-center">
                                                            <img src="{{ asset('images/iconoEtiqueta.png') }}"
                                                                class="w-4 h-4 mr-2" alt="Check Circle Icon">
                                                            {{ $servicio }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td style="width: 50%;">
                                                <p class="spanTitulo">Comentarios</p>
                                                <ul class="list-decimal pl-8">
                                                    @foreach (explode(',', $hoteles->opiniones) as $opinion)
                                                        <li class="spaninfo flex items-center">
                                                            <img src="{{ asset('images/iconoEtiqueta.png') }}"
                                                                class="w-4 h-4 mr-2 whitespace-no-wrap"
                                                                alt="Check Circle Icon">
                                                            {{ $opinion }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>

                                        </tr>
                                    </table>
                                </div>

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
                                    <x-dropdown-link :href="route('hotel.edit', $hoteles)">
                                        {{ __('Editar hotel') }}
                                    </x-dropdown-link>

                                    <!-- Formulario para eliminar el paquete -->
                                    <form id="deleteForm" method="POST"
                                        action="{{ route('hotel.destroy', $hoteles) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-dropdown-link :href="route('hotel.destroy', $hoteles)" onclick="return confirmDelete(event)">
                                            {{ __('Eliminar Hotel') }}
                                        </x-dropdown-link>
                                    </form>


                                    <script>
                                        function confirmDelete(event) {
                                            Swal.fire({
                                                title: "¿Deseas eliminar este hotel?",
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
                                    </script>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <!-- Separador entre paquetes -->
                        <hr class="my-4 border-gray-300 dark:border-gray-700">
                    @endforeach
                </div>
            </div>
        </div>


    </div>

    </div>
    </div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
    |
    <script>
        // buscador
        var options = {
            valueNames: ['nombres', 'pais'],
        };

        var userList = new List('hotels', options);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('layouts.footer')
</x-app-layout>
