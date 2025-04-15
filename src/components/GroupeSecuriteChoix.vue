<template>
    <v-container>
        <v-row v-if="modeChoix=='multiple'">
            <v-col>
                <v-btn
                    rounded="lg"
                    @click="choixTermine()"
                >Choix terminé</v-btn>
            </v-col>
        </v-row>
        <v-row v-for="(groupeSecurite, index) in groupesSecuriteListe" :key="groupeSecurite.id">
            <v-col cols="12" md="1">
                <v-checkbox
                    density="compact"
                    hide-details                    
                    @click="choix(groupeSecurite)"
                ></v-checkbox>
            </v-col>
            <v-col cols="12" md="5">{{ groupeSecurite.nom }}</v-col>
            <v-col cols="12" md="6">{{ groupeSecurite.commentaire }}</v-col>
        </v-row>
        </v-container>    
</template>

<script setup>
import { ref } from 'vue'
import { getGroupesSecuriteListe } from '@/axioscalls.js'

const props = defineProps({
  modeChoix: String,
})
let modeChoix = ref('unique')
if (props.modeChoix !== undefined) {
  modeChoix = ref(props.modeChoix)
}
const emit = defineEmits(['choixGroupeSecurite'])

const groupesSecuriteListe = await getGroupesSecuriteListe()
for (let i=0; i<groupesSecuriteListe.length; i++) {
    groupesSecuriteListe[i].bcheck = false    
}
//console.log(groupesSecuriteListe)
let groupesSecuriteListeChoisi = []

const choix = (groupeSecurite) => {
    if (modeChoix.value == 'unique') {
        const groupeSecuriteChoisi = {
            id: groupeSecurite.id,
            nom: groupeSecurite.nom,
            description: groupeSecurite.commentaire,
        }
        groupesSecuriteListeChoisi.push(groupeSecuriteChoisi)
        emit('choixGroupeSecurite', JSON.stringify(groupesSecuriteListeChoisi))
        //console.log(JSON.stringify(uoChoisie))
    } else {
        groupeSecurite.bcheck = !groupeSecurite.bcheck
        if (groupeSecurite.bcheck) {
            if (groupesSecuriteListeChoisi.some(objet => objet.id === groupeSecurite.id) === false) {
                const groupeSecuriteChoisi = {
                    id: groupeSecurite.id,
                    nom: groupeSecurite.nom,
                    description: groupeSecurite.commentaire,
                }
                groupesSecuriteListeChoisi.push(groupeSecuriteChoisi)
            }
        } else {
            groupesSecuriteListeChoisi = groupesSecuriteListeChoisi.filter(objet => objet.id !== groupeSecurite.id)    
        }
    }
}

const choixTermine = () => {
    emit('choixGroupeSecurite', JSON.stringify(groupesSecuriteListeChoisi))
    //console.log(JSON.stringify(groupesSecuriteListeChoisi))
}
</script>