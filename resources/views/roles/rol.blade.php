<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <x-slot name="header">
        <div x-data="{ showModal: false }" x-cloak class="flex items-center gap-5">
            <x-nav-link  :href="route('roles.rol')" 
            class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight no-underline">
                {{ __('Asignar roles') }}
            </x-nav-link>
            <x-nav-link href="{{ route('vendedores.pagosPendientes') }}" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight no-underline">
                {{ __('Pagos pendientes') }}
            </x-nav-link>
        </div>
    </x-slot>
    <div class="py-2">
        <div class="max-w mx-auto px-2 lg:px-20 mb-4">
            <div class="bg-white dark:bg-gray-900 bg-opacity-50 shadow-lg rounded-lg ">

                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-auto">

                    <div class="w-100 bg-white dark:bg-gray-800 border border-gray-300 overflow-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-center whitespace-nowrap">Nombre</th>
                                    <th class="py-2 px-4 border-b text-center whitespace-nowrap">Email</th>
                                    <th class="py-2 px-4 border-b text-center whitespace-nowrap">Rol</th>
                                    <th class="py-2 px-4 border-b text-center whitespace-nowrap">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user as $users)
                                    <tr>
                                        <td class="py-2 px-4 border-b text-center whitespace-nowrap">{{ $users->name }}</td>
                                        <td class="py-2 px-4 border-b text-center whitespace-nowrap">{{ $users->email }}</td>
                                        <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                            <form method="POST" action="{{ route('roles.asignar-rol', ['user' => $users->id]) }}">
                                                @csrf
                                                @method('PUT')
                                                <select name="rol_id">
                                                    @foreach ($roles as $rol)
                                                        <option value="{{ $rol->name }}" {{ $users->hasRole($rol->name) ? 'selected' : '' }}>
                                                            {{ $rol->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                        </td>
                                        <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                            <x-primary-button class="mt-4" type="submit">Agregar rol</x-primary-button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>

@include('layouts.footer')
