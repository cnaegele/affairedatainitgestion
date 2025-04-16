<template>
  <v-app>
    <v-main>
      <AppToper />
      <suspense><TypesAffaireListeChoix @choixTypeAffaire="receptionTypeAffaire" /></suspense>
      <suspense><TypeAffaireInitDataGestion :idTypeAffaire="idTypeAffaire" /></suspense>
    </v-main>
  </v-app>
</template>

<script setup>
import { ref } from 'vue'
import AppToper from '@/components/AppToper.vue';
import TypesAffaireListeChoix from '@/components/TypesAffaireListeChoix.vue';
import TypeAffaireInitDataGestion from '@/components/TypeAffaireInitDataGestion.vue';


const idTypeAffaire = ref(0)
const receptionTypeAffaire = (idTypeAffaireChoisi) => {
  idTypeAffaire.value = idTypeAffaireChoisi
}
</script>
