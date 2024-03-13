@if (session('status'))
    <div class="bg-green-600 text-green-100 text-center text-lg font-bold p-2">{{ session('status') }}</div>
@endif
<link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">


                    @role('admin')
                        <x-nav-link :href="route('chat.chat')" :active="request()->routeIs('chat.*')" class="no-underline">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('paquetes.paquetes')" :active="request()->routeIs('paquetes.*')" class="no-underline">
                            {{ __('Paquetes') }}
                        </x-nav-link>
                        <x-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')" class="no-underline">
                            {{ __('Calendar') }}
                        </x-nav-link>
                        <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')" class="no-underline">
                            {{ __('Clients') }}
                        </x-nav-link>
                        <x-nav-link :href="route('contrato.index')" :active="request()->routeIs('contrato.*')" class="no-underline">
                            {{ __('Contracts') }}
                        </x-nav-link>
                        <x-nav-link :href="route('vendedor.index')" :active="request()->routeIs('vendedor.*')" class="no-underline">
                            {{ __('Vendedor') }}
                        </x-nav-link>
                        <x-nav-link :href="route('roles.rol')" :active="request()->routeIs('roles.*')" class="no-underline">
                            {{ __('Administracion') }}
                        </x-nav-link>
                    @endrole


                    @role('vendedor')
                        <x-nav-link :href="route('vendedor.index')" :active="request()->routeIs('vendedor.*')" class="no-underline">
                            {{ __('Vendedor') }}
                        </x-nav-link>
                    @endrole

                    @role('asesor')
                        <x-nav-link :href="route('paquetes.paquetes')" :active="request()->routeIs('paquetes.*')" class="no-underline">
                            {{ __('Paquetes') }}
                        </x-nav-link>
                        <x-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')" class="no-underline">
                            {{ __('Calendar') }}
                        </x-nav-link>
                        <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')" class="no-underline">
                            {{ __('Clients') }}
                        </x-nav-link>
                        <x-nav-link :href="route('contrato.index')" :active="request()->routeIs('contrato.*')" class="no-underline">
                            {{ __('Contracts') }}
                        </x-nav-link>
                    @endrole
                </div>

            </div>

            <!-- Vista donde se muestra el div de notificaciones -->
            <div>
                <ul class="navbar-nav mr-1">
                    <li class="nav-item dropdown mt-3">
                        <a class="nav-link" href="{{ route('chat.chat') }}">
                            <i class="fas fa-bell text-black"></i>
                            <span id="notification-counter" class="badge badge-danger pending">0</span>
                        </a>
                        <div id="notifications-container" class="dropdown-menu" aria-labelledby="navbarDropdown"
                            style="display: none;">
                            <!-- Aquí se mostrarán las notificaciones en tiempo real -->
                            <div id="notificaciones"></div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Script para escuchar eventos de Pusher y mostrar notificaciones -->
            <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

            <x-slot name="header">
                <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
            </x-slot>
            <script>
                console.log("HOLA COMO VAMOS");
                Pusher.logToConsole = true;
                var pusher = new Pusher('217450e1ce096539fb1c', {
                    cluster: 'sa1'
                });
                console.log("Esta llegando un mensaje");
                var channel = pusher.subscribe('whatsapp-channel');
                channel.bind('whatsapp-event', function() {
                    console.log("Esta llegando");
                    document.getElementById("notification-counter").innerText(5); 
                    // var currentCount = parseInt(counterElement.text());
                    // counterElement.text(currentCount + 1);
                    // $('#notification-link').on('click', function() {
                    //     $('#notification-counter').text(1);
                    // });

                });

                /*
                                    $(document).ready(function() {
                                        var pusher = new Pusher('217450e1ce096539fb1c', {
                                            cluster: 'sa1'
                                        });
                                        var channel = pusher.subscribe('whatsapp-channel');

                                        channel.bind('whatsapp-event', function(data) {
                                            console.log("Esta entrando a las notificaciones");
                                            console.log('Datos recibidos:', data);
                                            // Incrementar el contador de notificaciones
                                            var counterElement = $('#notification-counter');
                                            var currentCount = parseInt(counterElement.text());
                                            counterElement.text(currentCount + 1);

                                            // Mostrar la notificación y el enlace
                                            var notificationDiv = $('<div class="notification"></div>');
                                            var notificationText = $('<span></span>').text(data.mensaje);
                                            var notificationLink = $('<a href="#"></a>').text('Ver notificación');
                                            notificationLink.on('click', function() {
                                                // Redirigir a la nueva ruta
                                                window.location.href = '{{ route('chat.chat') }}';
                                            });
                                            notificationDiv.append(notificationText);
                                            notificationDiv.append(notificationLink);
                                            $('#notificaciones').append(notificationDiv);
                                        });
                                        // Restablecer el contador de notificaciones cuando se abra la nueva ruta
                                        
                                    });*/
            </script>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="no-underline">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" class="no-underline">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                class="no-underline">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @role('admin')
                <x-responsive-nav-link :href="route('chat.chat')" :active="request()->routeIs('chat.*')" class="no-underline">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('paquetes.paquetes')" :active="request()->routeIs('paquetes.*')" class="no-underline">
                    {{ __('Paquetes') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('calendar.index')" :active="request()->routeIs('Calendar.*')" class="no-underline">
                    {{ __('Calendar') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('clientes.index')" :active="request()->routeIs('Clientes.*')" class="no-underline">
                    {{ __('Clients') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('contrato.index')" :active="request()->routeIs('Contrato.*')" class="no-underline">
                    {{ __('Contracts') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('vendedor.index')" :active="request()->routeIs('vendedor.*')" class="no-underline">
                    {{ __('Vendedores') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('roles.rol')" :active="request()->routeIs('roles.*')" class="no-underline">
                    {{ __('Asignar rol') }}
                </x-responsive-nav-link>
            @endrole

            @role('vendedor')
                <x-responsive-nav-link :href="route('vendedor.index')" :active="request()->routeIs('vendedor.*')" class="no-underline">
                    {{ __('Vendedor') }}
                </x-responsive-nav-link>
            @endrole

            @role('asesor')
                <x-responsive-nav-link :href="route('paquetes.paquetes')" :active="request()->routeIs('paquetes.*')" class="no-underline">
                    {{ __('Paquetes') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('calendar.index')" :active="request()->routeIs('Calendar.*')" class="no-underline">
                    {{ __('Calendar') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('clientes.index')" :active="request()->routeIs('Clientes.*')" class="no-underline">
                    {{ __('Clients') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('contrato.index')" :active="request()->routeIs('Contrato.*')" class="no-underline">
                    {{ __('Contracts') }}
                </x-responsive-nav-link>
            @endrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="no-underline">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" class="no-underline">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" class="no-underline"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>

    </div>

</nav>
