<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import FieldError from '@/Components/FieldError.vue';
import SuccesToast from '@/Components/SuccesToast.vue';
import DangerToast from '@/Components/DangerToast.vue';
import InfoToast from '@/Components/InfoToast.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    Documentos: {
        type: Array,
    },
    Estatus: {
        type: Array,
    },
    EstatusInicial: {
        type: Object,
    }
});

const showmodal = ref(false);

const msg = ref('')
const showsucces = ref(false);
const showdanger = ref(false);
const showinfo = ref(false);
const btnload = ref(false);
const  Errors = ref([]);
const pdf_colaboradores_url = ref('');
const pdf_cif_url = ref('');
const rfc_file_url = ref('');
const razon_social_url = ref('');
const nombre_comercial_url = ref('');
const representante_legal_url = ref('');
const antiguedad_url = ref('');
const afluencia_url = ref('');
const numero_colaboradores_url = ref('');

const openModal = () => {
    form.reset();
    showmodal.value = true;
    rfc_file_url.value = '';
    razon_social_url.value = '';
    nombre_comercial_url.value = '';
    representante_legal_url.value = '';
    antiguedad_url.value = '';
    afluencia_url.value = '';
    numero_colaboradores_url.value = '';
    pdf_colaboradores_url.value = '';
    pdf_cif_url.value = '';
} 

const closeModal = () => {
    showmodal.value = false;
    form.reset();
}

const form = useForm({
    id: '',    
    rfc: '',
    rfc_file: '',
    razon_social: '',
    nombre_comercial: '',
    representante_legal: '',
    antiguedad: '',
    afluencia: '',
    numero_colaboradores: '',
    pdf_colaboradores: '',
    pdf_cif: '',
    status: props.EstatusInicial.id,
});


const ChangeRfc =  (e) => {
    form.rfc_file = e.target.files[0]
}

const ChangeRazonsocial =  (e) => {
    form.razon_social = e.target.files[0]
}

const ChangeNombrecomercial =  (e) => {
    form.nombre_comercial = e.target.files[0]
}

const ChangeReprsentantelegal =  (e) => {
    form.representante_legal = e.target.files[0]
}

const ChangeAntiguedad =  (e) => {
    form.antiguedad = e.target.files[0]
}

const ChangeAfluencia =  (e) => {
    form.afluencia = e.target.files[0]
}

const ChangeNumerocolaboradores =  (e) => {
    form.pdf_cif = e.target.files[0]
}

const ChangeColaboradores =  (e) => {
    form.pdf_colaboradores = e.target.files[0]
}

const ChangeCif =  (e) => {
    form.pdf_cif = e.target.files[0]
}

const submit =  async () => {
    btnload.value  = true;
    try {
        let resp = await axios.post(route('create.cliente'), form, {
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
        console.log(error.response.data.errors);
        Errors.value = error.response.data.errors;
        dangerToast(error.response.data.msg)
        showdanger.value = false;    
        btnload.value  = false;
    }       
}

const Edit = async (id) => {
    infoToast()

    try {

        let resp = await axios.get(route('edit.cliente', id));                
        
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
        
                
        rfc_file_url.value = resp.data.rfc_file_url 
        razon_social_url.value = resp.data.razon_social_url 
        nombre_comercial_url.value = resp.data.nombre_comercial_url 
        representante_legal_url.value = resp.data.representante_legal_url 
        antiguedad_url.value = resp.data.antiguedad_url 
        afluencia_url.value = resp.data.afluencia_url 
        numero_colaboradores_url.value = resp.data.numero_colaboradores_url 
        pdf_colaboradores_url.value = resp.data.pdf_colaboradores_url;
        pdf_cif_url.value = resp.data.pdf_cif_url;

    } catch (error) {
        dangerToast(error.response.data.msg)
        setTimeout(() => {
            showdanger.value = false;
        }, 3000);
        btnload.value  = false;
    }       
}

const Delete = async (id) => { 
    if (!confirm("¿Estás seguro de que deseas eliminar?")) return;

    infoToast('Elimnando la solicitud.....')
    try {
        let resp = await axios.delete(route('delete.cliente', id));        
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
        btnload.value  = false;
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
    <Head title="Solicitud" />

    <AuthenticatedLayout>

        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                solicitudes
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
                    Nueva Solicitud.
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
                    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                        <div>
                            <InputLabel for="rfc" value="Rfc" />
                            <TextInput
                                id="rfc"
                                type="text"
                                v-model="form.rfc"
                                class="mt-1 block w-full"
                            />
                            <FieldError :message="Errors.rfc" />                            
                        </div>
                        <div>
                            <InputLabel for="rfc_file" value="archivo rfc" />
                            <input
                                id="rfc_file"
                                type="file"
                                class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                @change="ChangeRfc"
                            />
                            <a v-if="rfc_file_url" class="text-sm text-blue-500 mt-2" :href="rfc_file_url" target="_blank" rel="noopener noreferrer">{{rfc_file_url}}</a>
                        </div>
                        <div>
                            <InputLabel for="razon_social" value="Razon social" />
                            <input
                                id="razon_social"
                                type="file"
                                class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                @change="ChangeRazonsocial"
                            />
                            <a v-if="razon_social_url" class="text-sm text-blue-500 mt-2" :href="razon_social_url" target="_blank" rel="noopener noreferrer">{{razon_social_url}}</a>
                            <FieldError :message="Errors.razon_social" />
                        </div>
                        <div>
                            <InputLabel for="nombre_comercial" value="Nombre comercial" />
                            <input
                                id="nombre_comercial"
                                type="file"
                                class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                @change="ChangeNombrecomercial"
                            />
                            <a v-if="nombre_comercial_url" class="text-sm text-blue-500 mt-2" :href="nombre_comercial_url" target="_blank" rel="noopener noreferrer">{{nombre_comercial_url}}</a>
                            <FieldError :message="Errors.nombre_comercial" />
                        </div>
                        <div>
                            <InputLabel for="representante_legal" value="Representante legal" />
                            <input
                                id="representante_legal"
                                type="file"
                                class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                @change="ChangeReprsentantelegal"
                            />
                            <a v-if="representante_legal_url" class="text-sm text-blue-500 mt-2" :href="representante_legal_url" target="_blank" rel="noopener noreferrer">{{representante_legal_url}}</a>
                            <FieldError :message="Errors.representante_legal" />
                        </div>
                        <div>
                            <InputLabel for="antiguedad" value="Antiguedad" />
                            <input
                                id="antiguedad"
                                type="file"
                                class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                @change="ChangeAntiguedad"
                            />
                            <a v-if="antiguedad_url" class="text-sm text-blue-500 mt-2" :href="antiguedad_url" target="_blank" rel="noopener noreferrer">{{antiguedad_url}}</a>
                            <FieldError :message="Errors.antiguedad" />
                        </div>
                        <div>
                            <InputLabel for="afluencia" value="Afluencia" />
                            <input
                                id="afluencia"
                                type="file"
                                class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                @change="ChangeAfluencia"
                            />
                            <a v-if="afluencia_url" class="text-sm text-blue-500 mt-2" :href="afluencia_url" target="_blank" rel="noopener noreferrer">{{afluencia_url}}</a>
                            <FieldError :message="Errors.afluencia" />
                        </div>
                        <div>
                            <InputLabel for="numero_colaboradores" value="Numero de colaboradores" />
                            <input
                                id="numero_colaboradores"
                                type="file"
                                class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                @change="ChangeNumerocolaboradores"
                            />
                            <a v-if="numero_colaboradores_url" class="text-sm text-blue-500 mt-2" :href="numero_colaboradores_url" target="_blank" rel="noopener noreferrer">{{numero_colaboradores_url}}</a>
                            <FieldError :message="Errors.numero_colaboradores" />
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
                            <FieldError :message="Errors.pdf_colaboradores" />
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
                            <FieldError :message="Errors.pdf_cif" />
                        </div>
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
                    <div class="p-6 text-gray-900 flex justify-between border-b-gray">
                        <h2 class="text-xl text-semibold">Tus solicitudes</h2>
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
                                            rfc file
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
                                            <a :href="item.rfc_file" target="_blank" class="hover:text-blue-500">{{ item.rfc_file }}</a>
                                        </td>
                                        <td class="px-6 py-4">                                            
                                            <a :href="item.razon_social" target="_blank" class="hover:text-blue-500">{{ item.razon_social }}</a>
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
