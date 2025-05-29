<script setup>
import { Head, Link } from "@inertiajs/vue3";


defineProps({
    canLogin: { type: Boolean },
    canRegister: { type: Boolean },
    laravelVersion: { type: String, required: true },
    phpVersion: { type: String, required: true },
});
</script>

<template>
    <Head title="Sistema de Inventarios" />
    <div class="min-h-screen bg-gradient-to-b from-slate-100 to-slate-50">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex items-center flex-shrink-0">
                            <a
                                href="/"
                                class="ml-2 text-xl font-semibold text-gray-800"
                                >Sistema</a
                            >
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div v-if="canLogin">
                            <Link
                                v-if="$page.props.auth.user"
                                :href="route('dashboard')"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Inicio
                            </Link>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Iniciar Sesión
                                </Link>

                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="px-4 py-2 ml-3 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Registrarse
                                </Link>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="my-5">
            
            <h1 class="text-4xl text-center font-bold">Sistema</h1>

        </div>

        <!-- <footer class="bg-blue-950 text-white">
            <div class="px-4 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <span>Derechos reservados</span>
            </div>
        </footer> -->
    </div>
</template>
