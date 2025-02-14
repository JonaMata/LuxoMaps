<script setup lang="ts">
import {onMounted, ref} from "vue";

const props = defineProps<{
    type: 'signin' | 'signup' | 'use';
}>()

const button = ref();

onMounted(() => {
    google.accounts.id.initialize({
        client_id: import.meta.env.VITE_GOOGLE_CLIENT_ID,
        callback: route('auth.google'),
        autoselect: true,
        prompt_parent_id: 'google',
        context: props.type,
        ux_mode: 'popup',
        itp_support: true,
        hd: 'luxovi.us',

    });
    google.accounts.id.renderButton(button.value, {
        theme: 'outline',
        size: 'large',
        text: props.type === 'signup' ? 'signup_with' : 'signin_with',
        locale: 'nl_NL',
    });
    google.accounts.id.prompt();
})
</script>

<template>
    <div class="mb-4">
        <div id="google" ref="button"></div>
<!--        <div id="g_id_onload"-->
<!--             :data-client_id="googleClientId"-->
<!--             data-context="signin"-->
<!--             data-ux_mode="popup"-->
<!--             :data-login_uri="route('auth.google')"-->
<!--             data-auto_select="true"-->
<!--             data-itp_support="true">-->
<!--        </div>-->

<!--        <div class="g_id_signin"-->
<!--             data-type="standard"-->
<!--             data-shape="rectangular"-->
<!--             data-theme="outline"-->
<!--             data-text="signin_with"-->
<!--             data-size="large"-->
<!--             data-logo_alignment="left">-->
<!--        </div>-->
    </div>
</template>

<style scoped>

</style>
