<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Vendedores - Editar') }}
            </h2>
            
        </div>
    </x-slot>
    <div class="py-2">
        <div id="idAgregarVendedor" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4" style=""> <!--"-->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!--Form para introducir un cliente-->
                    <form method="POST" class ="p-4" enctype="multipart/form-data"
                        action = "{{ route('vendedor.update', $vendedor) }} ">
                        @csrf @method('PUT')
                        <label class="mt-1 p-0 ml-4 font-bold">Nombres:</label>
                        <input type="text" name="nombres"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Ingrese los nombres') }}"
                            value="{{ old('nombres', $vendedor->nombres) }}">
                        <label class="mt-1 p-0 ml-4 font-bold">ROL:</label>
                        <select name="rol"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Seleccione el Rol del Vendedor') }}">
                            <option value="" disabled selected>
                                {{ __('Seleccione el Rol') }}</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol }}" {{ old('provincia', $vendedor->rol) == $rol ? 'selected' : '' }}>
                                    {{ $rol }}
                                </option>
                            @endforeach
                        </select>
                        <label class="mt-1 p-0 ml-4 font-bold">Porcentajes:</label>
                        <select name="porcentaje_ventas"
                            class="mb-2 block w-full rounded-md border-gray-300 bg-white shadow-sm transition-colors duration-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-indigo-300 dark:focus:ring dark:focus:ring-indigo-200 dark:focus:ring-opacity-50"
                            placeholder="{{ __('Seleccione el % del Vendedor') }}">
                            <option value="" disabled selected>
                                {{ __('Seleccione el porcentaje') }}</option>
                            @foreach ($porcentajes as $porcentaje)
                                <option value="{{ $porcentaje }}" {{ old('porcentaje', $vendedor->porcentaje_ventas) == $porcentaje ? 'selected' : '' }}>
                                    {{ $porcentaje }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('message')" />
                        <x-primary-button
                            class='mt-4 bg-gray-800 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded shadow-md transition duration-300 ease-in-out'>Editar Cliente</x-primary-button>
                        <x-input-error :messages="$errors->get('message')" />
                </div>

                </form>
            </div>
        </div>
    </div>
    @include('layouts.footer')


</x-app-layout>
