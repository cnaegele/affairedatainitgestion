<template>
<v-row no-gutters class="d-flex align-center">
    <v-col cols="auto">
        Utilisateur : {{ userInfo.prenom_employe }} {{ userInfo.nom_employe }} / {{ userInfo.login_employe }}
    </v-col>
</v-row>
</template>
<script setup>
    import { ref } from 'vue'
    import { useDataStore } from '@/stores/data.js'
    import { getDataUserInfo } from  '@/axioscalls.js'
    
    const props = defineProps({
        groupeSecurite: {
            type: String,
            required: false
        }
    })
    const lesDatas = useDataStore()
    const userInfo = ref({})
    userInfo.value = await getDataUserInfo(props.groupeSecurite)
    lesDatas.bInGroupe = userInfo.value.bingroupe
    //console.log(lesDatas.bInGroupe)
</script>
