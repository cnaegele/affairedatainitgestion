<template>
    <v-app-bar
        color="primary"
        prominent
        density="compact"
        app
    >
        <v-toolbar-title>Gestion des données initiales des affaires</v-toolbar-title>
        <span v-if="lesDatas.bInGroupe == 0" class="alerte"><br>Sauvegarde impossible car réservée aux administrateurs goéland</span>
        <v-spacer></v-spacer>
        <div style="position: absolute; right: 16px;">
            <Suspense><UserInformation groupeSecurite="GoelandManager"></UserInformation></Suspense>
        </div>
    </v-app-bar>

</template>

<script setup>
    import { useDataStore } from '@/stores/data.js'
    import UserInformation from '@/components/UserInformation.vue';
    const lesDatas = useDataStore()
</script>

<style scoped>
    .custom-item {
    font-size: 15px;
    }
    .alerte {
        color: rgb(32, 238, 14);
    }
</style>