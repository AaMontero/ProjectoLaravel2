<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div x-data="{ showModal: false }" x-cloak class="flex items-center gap-5">
                @role('superAdmin')
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
                    <form method="POST" action = "{{ route('hoteles.store') }} " enctype="multipart/form-data">
                        @csrf
                        <label class="mt-0.5 font-bold p-0 ml-4" for="hotel_nombre">Nombre hotel:</label>
                        <input type="text" name="hotel_nombre" id="hotel_nombre"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el nombre del hotel') }}" value="{{ old('hotel_nombre') }}">
                        @error('hotel_nombre')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
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
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="num_camas">Número de camas:</label>
                        <input type="number" name="num_camas" id="num_camas"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el número de camas') }}" value="{{ old('num_camas') }}">
                        @error('num_camas')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="precio">Precio:</label>
                        <input type="number" name="precio" id="precio" step="0.01"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el precio') }}" value="{{ old('precio') }}">
                        @error('precio')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="servicios">Servicios:</label>
                        <input type="text" name="servicios" id="servicios"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa los servicos') }}"
                            value="{{ old('servicios') }}">
                        @error('servicios')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="tipo_alojamiento">Tipo de alojamiento:</label>
                        <select name="tipo_alojamiento" id="tipo_alojamiento" class="form-control mb-2">
                            <option value="Servicio1">Servicio 1</option>
                            <option value="Servicio2">Servicio 2</option>
                            <option value="Servicio3">Servicio 3</option>
                        </select>

                        @error('tipo_alojamiento')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="opiniones">Opiniones:</label>
                        <input type="text" name="opiniones" id="opiniones"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa las opiniones') }}"
                            value="{{ old('opiniones') }}">
                        @error('opiniones')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="imagen_hotel">Imágenes del hotel:</label>
                        <input type="file" name="imagen_hotel[]" id="imagen_hotel" class="form-control mb-2"
                            multiple>
                        <div id="preview-container" class="flex flex-wrap">
                            <!-- Aquí se mostrarán las imágenes -->
                        </div>
                        @error('imagen_hotel')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <x-primary-button class='mt-4'>Agregar nuevo hotel</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
        <script>
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

                console.log("esta dando click en el boton para ocultar");

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




    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    @include('layouts.footer')
</x-app-layout>
