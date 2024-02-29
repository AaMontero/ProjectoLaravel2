<x-app-layout>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Historial Vendedor') }}
            </h2>
        </div>
    </x-slot>

    <body class="bg-gray-100 p-6">

        <div class="max-w-md mx-auto bg-white rounded-md shadow-md overflow-hidden">

            <div class="bg-blue-500 text-white py-4 px-6">
                <h2 class="text-2xl font-bold">Lista de Pagos</h2>
            </div>

            <div class="p-4">

                <!-- Ejemplo de un pago -->
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-lg font-semibold">Pago de Julio</p>
                        <p class="text-sm text-gray-500">Fecha: 2024-02-29</p>
                    </div>
                    <p class="text-lg font-bold">$500.00</p>
                </div>

                <!-- Ejemplo de otro pago -->
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-lg font-semibold">Pago de Agosto</p>
                        <p class="text-sm text-gray-500">Fecha: 2024-03-31</p>
                    </div>
                    <p class="text-lg font-bold">$700.00</p>
                </div>

                <!-- Agrega más divs como este para cada pago -->

            </div>

        </div>

    </body>
</x-app-layout>
