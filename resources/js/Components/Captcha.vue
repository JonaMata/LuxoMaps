<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faRotateRight} from "@fortawesome/free-solid-svg-icons";
import axios from "axios";

const checkValue = ref<"pik" | "bal">();

const checked = ref<boolean[]>(Array(9).fill(false));

const pikbals = ref<number[]>([]);

const loading = ref<boolean>(false);

const overlay = ref<boolean>(false);

onMounted(() => {
    getData();
})

const getData = () => {
    overlay.value = false;
    result.value = null;
    loading.value = false;
    checked.value = Array(9).fill(false);
    fetch(route('captcha.get')).then(response => response.json()).then(data => {
        checkValue.value = data.check;
        pikbals.value = data.pikBals;
    });
}

const result = ref<boolean | null>(null);

const checkCaptcha = () => {
    result.value = null;
    overlay.value = true;
    setTimeout(() => loading.value = true, 0);
    axios.post(route('captcha.check'), {
        check: checkValue.value,
        selected: pikbals.value.filter((_, index) => checked.value[index]),
        pikbals: pikbals.value,
    }).then(response => {
        console.log(response.data)
        setTimeout(() => {
            loading.value = false;
            result.value = response.data.success;
            if (!response.data.success) {
                setTimeout(() => overlay.value = false, 100);
            }
        }, 2000);
    });
}

</script>

<template>
    <div class="relative rounded-sm w-80 border border-gray-200 bg-white">
        <div v-if="overlay" class="absolute w-full h-full z-10">
            <div class="absolute w-full h-full bg-black opacity-50"></div>
            <div class="absolute w-full h-full flex justify-center items-center">
                <div
                    class="w-32 h-32 rounded-full border-8 border-gray-300 border-solid border-t-blue-500 transition-all animate-spin small"
                    :class="loading ? 'big' : ''"
                ></div>
            </div>
            <div class="absolute w-full h-full flex justify-center items-center">
                <div @click="getData" class="w-32 h-32 rounded-full bg-blue-500 text-white text-8xl flex justify-center items-center transition-all small"
                     :class="result ? 'big' : ''">✓</div>
            </div>
        </div>
        <div class="bg-blue-500 text-white m-2 p-4">
            <h2 class="text-sm">Selecteer alle afbeeldingen met</h2>
            <h1 class="text-3xl font-bold">een {{ checkValue }}</h1>
        </div>
        <div class="grid grid-cols-3 grid-rows-3 m-2">
            <template v-for="(pikbal, index) in pikbals">
                <input type="checkbox" :id="pikbal.toString()" v-model="checked[index]" :value="pikbal"/>
                <label :for="pikbal.toString()">
                    <img :src="route('captcha.image', {pikbal: pikbal})" class="w-20 h-20 cursor-pointer"/>
                </label>
            </template>
        </div>
        <hr/>
        <div class="flex justify-between items-center m-2">
            <button @click="getData" class="ms-2 text-xl text-gray-700 hover:bg-gray-200 rounded-full w-10">
                <FontAwesomeIcon :icon="faRotateRight"/>
            </button>
            <div v-if="result !== null && !result">
                <div class="text-sm text-red-500">ff beter je best doen</div>
            </div>
            <button class="rounded-sm bg-blue-500 text-white font-bold px-4 py-2" @click="checkCaptcha">Check</button>
        </div>
    </div>
</template>

<style scoped>
.small {
    scale: 0;
}

.big {
    scale: 1;
}

label:before {
    position: absolute;
    width: 8%;
    aspect-ratio: 1/1;
    border-radius: 50%;
    background-color: black;
    color: white;
    font-size: 100%;
    text-align: center;
    content: '✓';
    z-index: 1;
    scale: 0;
    transition: 100ms;
}

:checked + label:before {
    scale: 1;
}

:checked + label img {
    scale: .8;
}

input[type=checkbox] {
    display: none;
}

label {
    margin: 10px;
}

label img {
    scale: 1;
    transition: 100ms;
}

</style>
