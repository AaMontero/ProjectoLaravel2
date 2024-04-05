<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Paquetes') }}
        </h2>
    </x-slot>
    <script>
        let listaCaracteristicas = [];

        function agregarCaracteristica() {
            const caracteristicaCiudad = document.getElementById("lugar_caracteristica");
            const caracteristicaInput = document.getElementById("caracteristica");
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


        }
        //Toca cambiar para que si no se da click en uno de los botones regrese la lista sin modificar
        function cambiarCaracteristica(caracteristicaId) {
            event.preventDefault();
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

        }



    </script>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('paquetes.update', $paquete) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <label class="mt-0.5 p-1 ml-4 font-bold" for="nombre_paquete">Nombre del paquete:</label>
                        <input type="text" name="nombre_paquete" id="nombre_paquete"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Coloca tu mensaje aquí') }}"
                            value="{{ old('nombre_paquete', $paquete->nombre_paquete) }}">
                        @error('nombre_paquete')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold ml-4" for="message">Descripción del paquete:</label>
                        <textarea name="message" id="message"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Pon aquí lo que quieras añadir') }}">{{ old('message', $paquete->message) }}</textarea>
                        @error('message')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror

                        <input type="hidden" id="lista_caracteristicas" name="lista_caracteristicas"
                            value="{{ $listaJson }}">

                        <label class="mt-3 font-bold ml-4" for="num_dias">Número de días: </label>
                        <input type="number" name="num_dias" id="num_dias"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Coloca tu mensaje aquí') }}"
                            value="{{ old('num_dias', $paquete->num_dias) }}">
                        @error('num_dias')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold ml-4" for="num_noches">Número de noches:</label>
                        <input type="number" name="num_noches" id="num_noches"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Coloca tu mensaje aquí') }}"
                            value="{{ old('num_noches', $paquete->num_noches) }}">
                        @error('num_noches')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold ml-4" for="precio_afiliado">Precio Afiliados:</label>
                        <input type="number" name="precio_afiliado" id="precio_afiliado" step="0.01"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Coloca tu mensaje aquí') }}"
                            value="{{ old('precio_afiliado', $paquete->precio_afiliado) }}">
                        @error('precio_afiliado')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        <label class="mt-3 font-bold ml-4" for="precio_no_afiliado">Precio no afiliados:</label>
                        <input type="number" name="precio_no_afiliado" id="precio_no_afiliado" step="0.01"
                            class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="{{ __('Coloca tu mensaje aquí') }}"
                            value="{{ old('precio_no_afiliado', $paquete->precio_no_afiliado) }}">
                        @error('precio_no_afiliado')
                            <small class = "text-red-500 ml-2">{{ $message }}</small>
                            <br>
                        @enderror
                        {{-- //cargar imagenes del paquete --}}
                        <div class="flex flex-wrap" id="image-preview-container">
                            @if ($paquete->imagen_paquete)
                                @foreach (explode(',', $paquete->imagen_paquete) as $imageName)
                                    <div class="w-1/4 p-2">
                                        <img src="{{ asset('uploads/paquetes/' . $imageName) }}"
                                            alt="Imagen actual del paquete" class="w-full h-auto rounded-lg">
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <label class="mt-3 font-bold ml-4" for="imagen_paquete">Cambiar Imagen:</label>
                        <input type="file" name="imagen_paquete[]" id="imagen_paquete" class="form-control mb-2" multiple>
                        <input type="hidden" id="lista_caracteristicas_mod" name="lista_caracteristicas_mod">
                        <div>
                            <label class="mt-3 font-bold ml-4 font-bold">Características Actuales</label>

                            <div class="ml-10 mb-15 space-y-4">
                                @foreach ($paquete->incluye as $caracteristica)
                                    <div class="flex">
                                        <input type="text" id="lugar{{ $caracteristica->id }}" name="lugar"
                                            class="w-1/6 mr-4 rounded-md border border-gray-300 p-2"
                                            value="{{ old('caracteristica_paquete', $caracteristica->lugar) }}">
                                        <input type="text" id="descripcion{{ $caracteristica->id }}"
                                            name="descripcion" class="w-5/6 mr-4 rounded-md border border-gray-300 p-2"
                                            value="{{ old('caracteristica_paquete', $caracteristica->descripcion) }}">
                                        <button onclick="cambiarCaracteristica('{{ $caracteristica->id }}')"
                                            class="w-10 p-2 bg-blue-500 text-white rounded-md">
                                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" class="text-black dark:text-white">
                                                <path
                                                    d="M18.4721 16.7023C17.3398 18.2608 15.6831 19.3584 13.8064 19.7934C11.9297 20.2284 9.95909 19.9716 8.25656 19.0701C6.55404 18.1687 5.23397 16.6832 4.53889 14.8865C3.84381 13.0898 3.82039 11.1027 4.47295 9.29011C5.12551 7.47756 6.41021 5.96135 8.09103 5.02005C9.77184 4.07875 11.7359 3.77558 13.6223 4.16623C15.5087 4.55689 17.1908 5.61514 18.3596 7.14656C19.5283 8.67797 20.1052 10.5797 19.9842 12.5023M19.9842 12.5023L21.4842 11.0023M19.9842 12.5023L18.4842 11.0023"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <label class="mt-3 font-bold ml-4">Agregar Nueva caracteristica</label>
                        <div class="flex mt-0">

                            <input type="text" name="lugar_caracteristica" id="lugar_caracteristica"
                                class="block w-2/6 mr-4 rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                                placeholder="{{ __('Lugar') }}" value="{{ old('ciudad_caracteristica') }}">

                            <input type="text" name="caracteristica" id="caracteristica"
                                class="block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                                placeholder="{{ __('Ingrese su característica aquí') }}"
                                value="{{ old('caracteristica') }}">
                            <button type="button" onclick="agregarCaracteristica()"
                                class="ml-2 h-full bg-blue-500 text-white font-bold py-2 px-4 rounded">
                                Agregar
                            </button>
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
        document.getElementById('imagen_paquete').addEventListener('change', function(event) {
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
