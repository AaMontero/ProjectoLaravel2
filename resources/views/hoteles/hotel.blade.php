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
                    <form method="POST" action = "{{ route('paquetes.store') }} " enctype="multipart/form-data">
                        @csrf
                        <div class="flex w-full">
                            <div class="mr-2 flex-1">
                                <label class="mt-0.5 font-bold p-0" for="nombre_paquete">País:</label>
                                <input type="text" name="pais" id="pais"
                                    class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="{{ __('Ingresa el país') }}" value="{{ old('pais') }}">
                            </div>
                            <div class="mr-2 flex-1">
                                <label class="mt-0.5 font-bold p-0" for="nombre_paquete">Provincia:</label>
                                <input type="text" name="provincia" id="provincia"
                                    class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="{{ __('Ingresa la provincia') }}" value="{{ old('provincia') }}">
                            </div>
                            <div class="flex-1">
                                <label class="mt-0.5 font-bold p-0" for="nombre_paquete">Ciudad:</label>
                                <input type="text" name="ciudad" id="ciudad"
                                    class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="{{ __('Ingresa la ciudad') }}" value="{{ old('ciudad') }}">
                            </div>
                        </div>

                        @error('nombre_paquete')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="message">Número de habitaciones:</label>
                        <input type="number" name="num_h" id="num_h"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el número de habitaciones') }}" value="{{ old('num_h') }}">
                        @error('message')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="num_dias">Número de camas:</label>
                        <input type="number" name="num_camas" id="num_camas"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el número de camas') }}" value="{{ old('num_camas') }}">
                        @error('num_dias')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="precio_afiliado">Precio:</label>
                        <input type="number" name="precio" id="precio" step="0.01"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el precio') }}" value="{{ old('precio') }}">
                        @error('precio_afiliado')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="precio_no_afiliado">Servicios:</label>
                        <input type="text" name="servicios" id="servicios" step="0.01"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el precio de no afiliados') }}"
                            value="{{ old('servicios') }}">
                        @error('precio_no_afiliado')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="servicios">Tipo de alojamiento:</label>
                        <select name="servicios" id="servicios" class="form-control mb-2">
                            <option value="Servicio1">Servicio 1</option>
                            <option value="Servicio2">Servicio 2</option>
                            <option value="Servicio3">Servicio 3</option>
                        </select>

                        @error('imagen_paquete')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="precio_no_afiliado">Opiniones:</label>
                        <input type="text" name="servicios" id="servicios" step="0.01"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el precio de no afiliados') }}"
                            value="{{ old('servicios') }}">
                        @error('precio_no_afiliado')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                       

                        <script>
                            //previsializar las imagenes antes de subirlas
                            document.getElementById('imagen_paquete').addEventListener('change', function(e) {
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

                        <x-primary-button class='mt-4'>Agregar nuevo hotel</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
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


        {{-- <div class="flex items-center w-full ml-10 pt-4">
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
        </div> --}}

        {{-- <div class="mt-4 mr-16 ml-16 bg-white dark:bg-gray-800 shadow-sm rounded-lg divide-y dark:divide-gray-900">
            @foreach ($paquetes as $paquete)
                <div class = "text-center ">
                    <span class = "spanTituloPaquete ml-5 ">{{ $paquete->nombre_paquete }}</span></p>
                </div>

                <div
                    class="p-6 bg-transparent flex justify-between items-center border-b border-gray-300 dark:border-gray-700">
                    <div style="width: 300px; height: 200px; overflow: hidden;" id="carouselExampleFade"
                        class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <!-- Slides -->
                            @foreach (explode(',', $paquete->imagen_paquete) as $index => $imageName)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('uploads/paquetes/' . $imageName) }}"
                                        class="d-block w-64 h-48 object-cover mx-auto" alt="Imagen del paquete"
                                        loading="lazy">
                                </div>
                            @endforeach
                        </div>
                        <!-- Botones de navegación -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                          </button>
                    </div>


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
                            <form id="deleteForm" method="POST" action="{{ route('paquetes.destroy', $paquete) }}">
                                @csrf
                                @method('DELETE')
                                <x-dropdown-link :href="route('paquetes.destroy', $paquete)" onclick="return confirmDelete(event)">
                                    {{ __('Eliminar Paquete') }}
                                </x-dropdown-link>
                            </form>

                            <script>
                                function confirmDelete(event) {
                                    if (confirm('¿Deseas eliminar este paquete?')) {
                                        event.target.closest('form').submit();
                                    }
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
        <div class = "ml-20 mr-20">
            <p class="ml-5 flex justify-center items-center list-none space-x-2">
                {{ $paquetes->appends([
                    'num_dias' => $num_dias,
                    'num_noches' => $num_noches,
                    'precio_min' => $precio_min,
                    'precio_max' => $precio_max,
                ]) }}
            </p>
        </div> --}}




    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    @include('layouts.footer')
</x-app-layout>
