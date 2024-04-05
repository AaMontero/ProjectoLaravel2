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
                    <span class="mr-2">Agregar un nuevo paquete</span>
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
                        <label class="mt-0.5 font-bold p-0 ml-4" for="nombre_paquete">Nombre del paquete:</label>
                        <input type="text" name="nombre_paquete" id="nombre_paquete"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el nombre del paquete') }}" value="{{ old('nombre_paquete') }}">
                        @error('nombre_paquete')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="message">Descripción del paquete:</label>
                        <input type="text" name="message" id="message"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa la descripcion del paquete') }}" value="{{ old('message') }}">
                        @error('message')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="num_dias">Número de días:</label>
                        <input type="number" name="num_dias" id="num_dias"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el número de días') }}" value="{{ old('num_dias') }}">
                        @error('num_dias')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="num_noches">Número de noches:</label>
                        <input type="number" name="num_noches" id="num_noches"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el número de noches') }}" value="{{ old('num_noches') }}">
                        @error('num_noches')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="precio_afiliado">Precio Afiliados:</label>
                        <input type="number" name="precio_afiliado" id="precio_afiliado" step="0.01"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el precio de afiliado') }}"
                            value="{{ old('precio_afiliado') }}">
                        @error('precio_afiliado')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="precio_no_afiliado">Precio no afiliados:</label>
                        <input type="number" name="precio_no_afiliado" id="precio_no_afiliado" step="0.01"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el precio de no afiliados') }}"
                            value="{{ old('precio_no_afiliado') }}">
                        @error('precio_no_afiliado')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="imagen_paquete">Imágenes del paquete:</label>
                        <input type="file" name="imagen_paquete[]" id="imagen_paquete" class="form-control mb-2"
                            multiple>
                        <div id="preview-container" class="flex flex-wrap">
                            <!-- Aquí se mostrarán las imágenes -->
                        </div>
                        @error('imagen_paquete')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <input type="hidden" id="lista_caracteristicas" name="lista_caracteristicas">

                        <div>
                            <label class="mt-1 p-0 ml-4 font-bold" for="lugar_caracteristica">Agregar
                                Característica</label>
                            <div class="flex">
                                <input type="text" name="lugar_caracteristica" id="lugar_caracteristica"
                                    class="mr-2 mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="{{ __('Lugar') }}" value="{{ old('ciudad_caracteristica') }}">

                                <input type="text" name="caracteristica" id="caracteristica"
                                    class=" mr-2 mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="{{ __('Ingrese su característica aquí') }}"
                                    value="{{ old('caracteristica') }}">

                                <button type="button" onclick="agregarCaracteristica()"
                                    class="ml-2 h-full bg-blue-500 text-white font-bold py-2 px-4 rounded">
                                    Agregar
                                </button>
                            </div>
                        </div>

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

                        <x-primary-button class='mt-4'>Agregar nuevo paquete</x-primary-button>
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

        <div id="paquetes">
            <div class="mt-4 mx-auto max-w-lg">
                <input class="search w-full p-2 border rounded-md mt-3" placeholder="Buscar paquete..." />
            </div>

            <div
                class="mt-4 md:mr-16 md:ml-16 bg-white dark:bg-gray-800 shadow-sm rounded-lg divide-y dark:divide-gray-900">
                <div class="list">
                    @foreach ($paquetes as $paquete)
                        <div class="p-6 bg-transparent sm:flex sm:justify-between sm:items-center">
                            <div id="carousel{{ $paquete->id }}" class="carousel slide carousel-fade mx-auto mb-4"
                                data-bs-ride="carousel">
                                <div class = "ml:text-center ">
                                    <span class = "spanTituloPaquete nombres">{{ $paquete->nombre_paquete }}</span>

                                </div>
                                <div style="width: 300px; height: 200px; overflow: hidden;" class="carousel-inner mx-auto">
                                    <!-- Slides -->
                                    @foreach (explode(',', $paquete->imagen_paquete) as $index => $imageName)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <img src="{{ asset('uploads/paquetes/' . $imageName) }}"
                                                class="d-block w-64 h-48 object-cover mx-auto"
                                                alt="Imagen del paquete" loading="lazy">
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Botones de navegación -->
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carousel{{ $paquete->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carousel{{ $paquete->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>


                            <div class="ml:w-3/5 ml:h-3/5 text-gray-800 dark:text-gray-200">
                                <div class=" w-90 text-gray-800 dark:text-gray-200">
                                    <!-- Mensaje del paquete -->
                                    <p><span class="spanTitulo">Descripcion:</span> <span
                                            class="spaninfo">{{ $paquete->message }}</span></p>
                                    <!-- Número de días y Número de Noches en la misma fila -->
                                    <table width="100%">
                                        <tr>
                                            <td style="width: 25%;">
                                                <span class="spanTitulo">Número de días:</span>
                                                <span class="spaninfo">{{ $paquete->num_dias }}</span>
                                            </td>
                                            <td style="width: 25%;">
                                                <span class="spanTitulo">Número de Noches:</span>
                                                <span class="spaninfo">{{ $paquete->num_noches }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">
                                                <span class="spanTitulo">Precio Afiliados:</span>
                                                <span class="spaninfo">${{ $paquete->precio_afiliado }}</span>
                                            </td>
                                            <td style="width: 25%;">
                                                <span class="spanTitulo">Precio No Afiliados:</span>
                                                <span class="spaninfo">${{ $paquete->precio_no_afiliado }}</span>
                                            </td>
                                        </tr>

                                    </table>


                                    <!-- Mostrar las características del paquete -->
                                    <p class="spanTitulo">Características del paquete</p>
                                    <ul class="list-decimal pl-8">
                                        @foreach ($paquete->incluye as $caracteristica)
                                            <li class="spaninfo flex items-center">
                                                <img src="{{ asset('images/iconoEtiqueta.png') }}"
                                                    class="w-4 h-4 mr-2" alt="Check Circle Icon">
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
                                    <form id="deleteForm" method="POST"
                                        action="{{ route('paquetes.destroy', $paquete) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-dropdown-link :href="route('paquetes.destroy', $paquete)" onclick="return confirmDelete(event)">
                                            {{ __('Eliminar Paquete') }}
                                        </x-dropdown-link>
                                    </form>

                                    <script>
                                        function confirmDelete(event) {
                                            Swal.fire({
                                                title: "¿Deseas eliminar este paquete?",
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

    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>

    <script>
        // buscador
        var options = {
            valueNames: ['nombres'],

        };

        var userList = new List('paquetes', options);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('layouts.footer')
</x-app-layout>
