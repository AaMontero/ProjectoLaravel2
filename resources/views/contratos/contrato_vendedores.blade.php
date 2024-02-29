<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Vendedores del Contrato') }}
            </h2>
        </div>
    </x-slot>
    <div class="py-2">
        <div id="" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4" style=""> <!--"-->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!--Form para introducir un cliente-->
                    <form method="POST" class ="p-4" enctype="multipart/form-data"
                        action = "{{ route('contrato.add_vendedores') }} ">
                        @csrf
                        <input type="hidden" value ="{{$contratoId}}" name = "contratoId">
                        <label class="mt-1 p-0 ml-4 font-bold">Vendedor:</label>
                        <select name="vendedor"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Seleccione el Vendedor') }}">
                            <option value="" disabled selected>
                                {{ __('Seleccione el Vendedor') }}</option>
                            @foreach ($vendedores as $vendedor)
                                <h1>{{$vendedor->nombres}}</h1>
                                <option value="{{$vendedor->id }}"
                                    {{ old('vendedor') == $vendedor ? 'selected' : '' }}>
                                    {{ $vendedor->id. ".- ". $vendedor->nombres }}
                                </option>
                            @endforeach
                        </select>
                        <label class="mt-1 p-0 ml-4 font-bold">Closer 1:</label>
                        <select name="closer1"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Seleccione el Closer') }}">
                            <option value="" disabled selected>
                                {{ __('Seleccione el Closer 1') }}</option>
                            @foreach ($closers as $closer)
                                <option value="{{ $closer->id}}"
                                    {{ old('closer') == $closer ? 'selected' : '' }}>
                                    {{ $vendedor->id. ".- ".  $closer->nombres }}
                                </option>
                            @endforeach
                        </select>
                        <label class="mt-1 p-0 ml-4 font-bold">Closer 2:</label>
                        <select name="closer2"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Seleccione el Closer 2') }}">
                            <option value="" disabled selected>
                                {{ __('Seleccione el Closer 2') }}</option>
                            @foreach ($closers as $closer)
                                <option value="{{ $closer->id }}" {{ old('closer') == $closer ? 'selected' : '' }}>
                                    {{ $vendedor->id. ".- ".  $closer->nombres }}
                                </option>
                            @endforeach
                        </select>
                        <label class="mt-1 p-0 ml-4 font-bold">Jefe de Sala:</label>
                        <select name="jefe_de_sala"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Seleccione el Jefe de Sala') }}">
                            <option value="" disabled selected>
                                {{ __('Seleccione el Jefe de Sala') }}</option>
                            @foreach ($jefes_sala as $jefeSala)
                                <option value="{{$jefeSala->id }}"
                                    {{ old('jefeSala') == $jefeSala ? 'selected' : '' }}>
                                    {{ $vendedor->id. ".- ".  $jefeSala->nombres }}
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

</x-app-layout>
