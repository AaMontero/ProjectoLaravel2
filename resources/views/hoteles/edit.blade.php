<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Hoteles') }}
        </h2>
    </x-slot>
    {{-- <script>
        let listaCaracteristicas = [];

        function agregarCaracteristica() {
            const caracteristicaCiudad = document.getElementById("lugar_caracteristica");
            console.log(caracteristicaCiudad);
            const caracteristicaInput = document.getElementById("caracteristica");
            console.log(caracteristicaInput);
            const caracteristicaTexto = caracteristicaInput.value.trim();
            const caracteristicaCiudadTexto = caracteristicaCiudad.value.trim();
            if (caracteristicaTexto !== "" && caracteristicaCiudadTexto !== "") {
                const caracteristica = {
                    descripcion: caracteristicaTexto,
                    lugar: caracteristicaCiudadTexto
                };

                listaCaracteristicas.push(caracteristica);
                caracteristicaInput.value = "";
                caracteristicaCiudad.value = "";
                // Cambiado a innerHTML para mostrar la lista en un elemento div
                document.getElementById("lista_caracteristicas").value = JSON.stringify(listaCaracteristicas);
                alert("Se ha agregado la característica: " + caracteristicaTexto);
            } else {
                alert("Por favor, ingresa una característica y su lugar válidos.");
            }
            console.log(listaCaracteristicas);


        }
        //Toca cambiar para que si no se da click en uno de los botones regrese la lista sin modificar
        function cambiarCaracteristica(caracteristicaId) {
            event.preventDefault();
            console.log("ID del elemento presionado:", caracteristicaId);
            const caracteristicaElemento = document.getElementById("descripcion" + caracteristicaId).value;
            const lugarElemento = document.getElementById("lugar" + caracteristicaId).value;
            const listaCaracteristicasJson = document.getElementById("lista_caracteristicas").value;
            let listaCaracteristicasObj = JSON.parse(listaCaracteristicasJson);
            let listaCambios = [];
            for (let i = 0; i < listaCaracteristicasObj.length; i++) {
                if (listaCaracteristicasObj[i].id == caracteristicaId) {
                    console.log(listaCaracteristicasObj[i]);
                    listaCaracteristicasObj[i].lugar = lugarElemento;
                    listaCaracteristicasObj[i].descripcion = caracteristicaElemento;
                    listaCambios.push(listaCaracteristicasObj[i]);
                    alert("Los cambios se realizaron");
                    break;
                }
            }
            listaTextoModificada = JSON.stringify(listaCambios);
            document.getElementById("lista_caracteristicas_mod").value = listaTextoModificada;
            //console.log(document.getElementById("lista_caracteristicas_mod").value);  

        }



    </script> --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('hotel.update', $hotel) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <label class="mt-0.5 p-1 ml-4 font-bold" for="hotel_nombre">Nombre del hotel:</label>
                        <input type="text" name="hotel_nombre" id="hotel_nombre"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            value="{{ old('hotel_nombre', $hotel->hotel_nombre) }}">
                        @error('hotel_nombre')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <div class="flex w-full">
                            <div class="mr-2 flex-1">
                                <label class="mt-0.5 font-bold p-0" for="pais">País:</label>
                                <input type="text" name="pais" id="pais"
                                    class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="{{ __('Ingresa el país') }}"
                                    value="{{ old('pais', $hotel->pais) }}">

                            </div>
                            <div class="mr-2 flex-1">
                                <label class="mt-0.5 font-bold p-0" for="provincia">Provincia:</label>
                                <input type="text" name="provincia" id="provincia"
                                    class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="{{ __('Ingresa la provincia') }}"
                                    value="{{ old('provincia', $hotel->provincia) }}">
                            </div>
                            <div class="flex-1">
                                <label class="mt-0.5 font-bold p-0" for="ciudad">Ciudad:</label>
                                <input type="text" name="ciudad" id="ciudad"
                                    class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    placeholder="{{ __('Ingresa la ciudad') }}"
                                    value="{{ old('ciudad', $hotel->ciudad) }}">
                            </div>
                        </div>
                        <label class="mt-3 font-bold p-0 ml-4" for="num_h">Número de habitaciones:</label>
                        <input type="number" name="num_h" id="num_h"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el número de habitaciones') }}"
                            value="{{ old('num_h', $hotel->num_h) }}">
                        @error('num_h')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="num_camas">Número de camas:</label>
                        <input type="number" name="num_camas" id="num_camas"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el número de camas') }}"
                            value="{{ old('num_camas', $hotel->num_camas) }}">
                        @error('num_camas')
                            <small class="text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold p-0 ml-4" for="precio">Precio:</label>
                        <input type="number" name="precio" id="precio" step="0.01"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Ingresa el precio') }}"
                            value="{{ old('precio', $hotel->precio) }}">
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
                                Agregar otro servicio
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
                        {{-- //cargar imagenes del paquete --}}
                        <div class="flex flex-wrap" id="image-preview-container">
                            @if ($hotel->imagen_hotel)
                                @foreach (explode(',', $hotel->imagen_hotel) as $imageName)
                                    <div class="w-1/4 p-2">
                                        <img src="{{ asset($imageName) }}" alt="Imagen actual del paquete"
                                            class="w-full h-auto rounded-lg">
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <label class="mt-3 font-bold ml-4" for="imagen_paquete">Cambiar Imagen:</label>
                        <input type="file" name="imagen_hotel[]" id="imagen_hotel" class="form-control mb-2"
                            multiple>
                        <div>
                            @error('imagen_hotel')
                                <small class="text-red-500 ml-2">{{ $message }}</small>
                                <br>
                            @enderror


                        </div>

                        <x-primary-button class='mt-4'>Actualizar Paquete</x-primary-button>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <style>
        .img {
            max-width: 200px;
            max-height: 200px;
            margin-right: 5px;
        }
    </style>
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

        //previsualisar imagenes
        document.getElementById('imagen_hotel').addEventListener('change', function(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('image-preview-container');
            previewContainer.innerHTML = ''; // Limpiar la vista previa

            for (const file of files) {
                const reader = new FileReader();

                reader.onload = function(event) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.alt = 'Imagen previa del paquete';
                    img.className = 'w-full h-auto rounded-lg';

                    const div = document.createElement('div');
                    div.className = 'w-1/4 p-2';
                    div.appendChild(img);

                    previewContainer.appendChild(div);
                };

                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>
