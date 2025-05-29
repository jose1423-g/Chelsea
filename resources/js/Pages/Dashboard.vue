<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SuccesToast from '@/Components/SuccesToast.vue';
import DangerToast from '@/Components/DangerToast.vue';
import InfoToast from '@/Components/InfoToast.vue';
import Select from '@/Components/Select.vue'
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';

defineProps({
    Documentos: {
        type: Array,
    },
    Estatus: {
        type: Array,
    },
})

const showmodal = ref(false);

const msg = ref('')
const showsucces = ref(false);
const showdanger = ref(false);
const showinfo = ref(false);
const btnload = ref(false);

const pdf_colaboradores_url = ref('');
const pdf_cif_url = ref('');

const openModal = () => {
    showmodal.value = true;
} 

const closeModal = () => {
    showmodal.value = false;
}

const form = useForm({
    id: '',
    rfc: '',
    razon_social: '',
    nombre_comercial: '',
    representante_legal: '',
    antiguedad: '',
    afluencia: '',
    numero_colaboradores: '',
    pdf_colaboradores: '',
    pdf_cif: '',
    status: '',  
});

const submit =  async () => {
    btnload.value  = true;
    try {
        let resp = await axios.post(route('create.admin'), form, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });        
        router.reload({ only: ['Documentos'] });
        form.reset();
        succesToast(resp.data.msg)
        closeModal();
        btnload.value  = false;
        setTimeout(() => {
            showsucces.value = false;
        }, 3000);
    } catch (error) {
        console.log(error);
        closeModal();
        dangerToast(error.response.data.msg)
        setTimeout(() => {            
            showdanger.value = false;
        }, 3000);    
        btnload.value  = false;
    }       
}

const Edit = async (id) => {
    infoToast()

    try {

        let resp = await axios.get(route('edit.admin', id));                
        
        showinfo.value = false;
        openModal();
        form.id = resp.data.id
        form.rfc = resp.data.rfc
        form.razon_social = resp.data.razon_social
        form.nombre_comercial = resp.data.nombre_comercial
        form.representante_legal = resp.data.representante_legal
        form.antiguedad = resp.data.antiguedad
        form.afluencia = resp.data.afluencia
        form.numero_colaboradores = resp.data.numero_colaboradores
        form.pdf_colaboradores = resp.data.pdf_colaboradores
        form.pdf_cif = resp.data.pdf_cif
        form.status = resp.data.status
        pdf_colaboradores_url.value = resp.data.pdf_colaboradores_url;
        pdf_cif_url.value = resp.data.pdf_cif_url;

    } catch (error) {
        console.log(error)
        dangerToast(error.response.data.msg)
        setTimeout(() => {
            showdanger.value = false;
        }, 3000);
    }       
}

const Delete = async (id) => { 
    if (!confirm("¿Estás seguro de que deseas eliminar?")) return;

    infoToast('Elimnando la solicitud.....')
    try {
        let resp = await axios.delete(route('delete.admin', id));       
        showinfo.value = false;
        router.reload({ only: ['Documentos'] });
        succesToast(resp.data.msg)
        setTimeout(() => {
            showsucces.value = false;
        }, 3000);
    } catch (error) {        
        dangerToast(error.response.data.msg)
        setTimeout(() => {
            showdanger.value = false;
        }, 3000);
    }    
}

const succesToast = (text) => {
    msg.value = text;
    showsucces.value = true;
}

const dangerToast = (text) => {
    msg.value = text;
    showdanger.value = true;
}

const infoToast = (text) => {
    msg.value = text;
    showinfo.value = true;
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <SuccesToast 
            :mensaje="msg"
            :hidden="showsucces"
        />

        <DangerToast 
            :mensaje="msg"
            :hidden="showdanger"
        />

        <InfoToast
            :mensaje="msg"
            :hidden="showinfo"
        />

        <Modal :show="showmodal" @close="closeModal">   
             <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    Terms of Service
                </h3>
                <button type="button" @click="closeModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
                <!-- Modal body -->
            <form @submit.prevent="submit">
                <div class="p-4 md:p-5 space-y-4">
                    <div>
                        <InputLabel for="rfc" value="Rfc" />
                        <TextInput
                            id="rfc"
                            type="text"
                            v-model="form.rfc"                                
                            class="mt-1 block w-full"
                        />
                    </div>
                    <div>
                        <InputLabel for="razon_social" value="Razon social" />
                        <TextInput
                            id="razon_social"
                            type="text"
                            v-model="form.razon_social"                                
                            class="mt-1 block w-full"
                        />
                    </div>
                    <div>
                        <InputLabel for="nombre_comercial" value="Nombre comercial" />
                        <TextInput
                            id="nombre_comercial"
                            type="text"
                            v-model="form.nombre_comercial"                                
                            class="mt-1 block w-full"
                        />
                    </div>
                    <div>
                        <InputLabel for="representante_legal" value="Representante legal" />
                        <TextInput
                            id="representante_legal"
                            type="text"
                            v-model="form.representante_legal"                                
                            class="mt-1 block w-full"
                        />
                    </div>
                    <div>
                        <InputLabel for="antiguedad" value="Antiguedad" />
                        <TextInput
                            id="antiguedad"
                            type="text"
                            v-model="form.antiguedad"                                
                            class="mt-1 block w-full"
                        />
                    </div>
                    <div>
                        <InputLabel for="afluencia" value="Afluencia" />
                        <TextInput
                            id="afluencia"
                            type="text"
                            v-model="form.afluencia"                                
                            class="mt-1 block w-full"
                        />
                    </div>
                    <div>
                        <InputLabel for="numero_colaboradores" value="Numero de colaboradores" />
                        <TextInput
                            id="numero_colaboradores"
                            type="number"
                            v-model="form.numero_colaboradores"                                
                            class="mt-1 block w-full"
                        />
                    </div>                        
                    <div>
                        <InputLabel for="pdf_colaboradores" value="Pdf colaboradores" />
                        <input
                            id="pdf_colaboradores"
                            type="file"
                            class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            @change="ChangeColaboradores"
                        />
                        <a v-if="pdf_colaboradores_url" class="text-sm text-blue-500 mt-2" :href="pdf_colaboradores_url" target="_blank" rel="noopener noreferrer">{{pdf_colaboradores_url}}</a>
                    </div>                        
                    <div>
                        <InputLabel for="pdf_cif" value="Pdf cif" />
                        <input
                            id="pdf_cif"
                            type="file"
                            class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            @change="ChangeCif"
                        />
                        <a v-if="pdf_cif_url" class="text-sm text-blue-500 mt-2" :href="pdf_cif_url" target="_blank" rel="noopener noreferrer">{{pdf_cif_url}}</a>
                    </div>
                    <div>
                        <InputLabel for="status" value="Estatus" />
                        <Select
                            id="status"                                
                            v-model="form.status"                                
                            :data="Estatus"
                            :value="'id'"
                            :text="'nombre'"
                            class="mt-1 block w-full" 

                        />
                    </div>
                </div>
                    <!-- Modal footer -->
                    <div class="flex justify-end items-center gap-3 p-4 md:p-5 border-t border-gray-200 rounded-b">
                        <SecondaryButton type="button" @click="closeModal">Cancelar</SecondaryButton>
                        <PrimaryButton type="submit" :btndisabled="btnload">Guardar</PrimaryButton>
                    </div>            
            </form>
        </Modal>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 flex justify-between">
                        <h2 class="text-xl text-semibold">Solicitudes</h2>
                        <PrimaryButton type="button" @click="openModal">Nueva</PrimaryButton>
                    </div>
                    <div>
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            rfc
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Razon social
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Estatus
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            pdf colaboradores
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            pdf cif
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white border-b border-gray-200" v-for="item in Documentos">
                                        <td class="px-6 py-4">
                                            {{ item.rfc }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ item.razon_social }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ item.nombre }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a :href="item.pdf_colaboradores" target="_blank" class="hover:text-blue-500">{{ item.pdf_colaboradores }}</a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a :href="item.pdf_cif" target="_blank" class="hover:text-blue-500">{{ item.pdf_cif }}</a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <PrimaryButton type="buttom" @click="Edit(item.id)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-pen-line-icon lucide-file-pen-line"><path d="m18 5-2.414-2.414A2 2 0 0 0 14.172 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2"/><path d="M21.378 12.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><path d="M8 18h1"/></svg>
                                                </PrimaryButton>
                                                <DangerButton type="button" @click="Delete(item.id)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                </DangerButton>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
