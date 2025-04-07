<template>
  <v-app>
    <v-main>
      <suspense><TypesAffaireListeChoix @choixTypeAffaire="receptionTypeAffaire" /></suspense>
      <suspense><TypeAffaireInitDataGestion :idTypeAffaire="idTypeAffaire" /></suspense>
    </v-main>
  </v-app>
</template>

<script setup>
import { ref } from 'vue'
import TypeAffaireInitDataGestion from '@/components/TypeAffaireInitDataGestion.vue';
import TypesAffaireListeChoix from '@/components/TypesAffaireListeChoix.vue';

const idTypeAffaire = ref(0)
const receptionTypeAffaire = (idTypeAffaireChoisi) => {
  idTypeAffaire.value = idTypeAffaireChoisi
}
</script>
