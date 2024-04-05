<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <x-slot name="header">
        <div x-data="{ showModal: false }" x-cloak class="flex items-center gap-5">
            @role('superAdmin')
                <x-nav-link :href="route('roles.rol')"
                    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight no-underline">
                    {{ __('Asignar roles') }}
                </x-nav-link>
                {{-- logs --}}
                <x-nav-link href="{{ route('logs.log') }}"
                    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight no-underline">
                    {{ __('Logs') }}
                </x-nav-link>
            @endrole
            <x-nav-link href="{{ route('vendedores.pagosPendientes') }}"
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight no-underline">
                {{ __('Pagos pendientes') }}
            </x-nav-link>
        </div>
    </x-slot>
    @role('superAdmin')
        <div class="py-2">
            <div class="max-w mx-auto px-2 lg:px-20 mb-4">
                <div class="bg-white dark:bg-gray-900 bg-opacity-50 shadow-lg rounded-lg ">

                    <div class="p-6 text-gray-900 dark:text-gray-100 overflow-auto">

                        <div class="w-100 bg-[#f8fafc] dark:bg-gray-800 border border-gray-300 overflow-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b text-center whitespace-nowrap">Nombre</th>
                                        <th class="py-2 px-4 border-b text-center whitespace-nowrap">Email</th>
                                        <th class="py-2 px-4 border-b text-center whitespace-nowrap">Rol</th>
                                        <th class="py-2 px-4 border-b text-center whitespace-nowrap">Sala</th>
                                        <th class="py-2 px-4 border-b text-center whitespace-nowrap">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <form method="POST"
                                                action="{{ route('roles.asignar-rol', ['user' => $user->id]) }}">
                                                @csrf
                                                @method('PUT')
                                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                                    {{ $user->name }}
                                                </td>
                                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                                    {{ $user->email }}
                                                </td>
                                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">

                                                    <select name="rol_id">
                                                        @foreach ($roles as $rol)
                                                            <option value="{{ $rol->name }}"
                                                                {{ $user->hasRole($rol->name) ? 'selected' : '' }}>
                                                                {{ $rol->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                                    <select name="sala_valor">
                                                        @foreach ($salas as $sala)
                                                            <option value="{{ $sala }}"
                                                                {{ old('sala', $user->sala) == $sala ? 'selected' : '' }}>
                                                                {{ $sala }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="py-2 px-4 border-b text-center whitespace-nowrap">
                                                    <x-primary-button class="mt-4"
                                                        type="submit">Modificar</x-primary-button>

                                                </td>
                                            </form>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endrole
</x-app-layout>

@include('layouts.footer')
