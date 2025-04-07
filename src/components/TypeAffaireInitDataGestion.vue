<template>
    <div v-if="props.idTypeAffaire > 0">
      data init pour : {{ datainitpour }}
    </div>
</template>

<script setup>
  import { ref, watch } from 'vue'
  import { getTypeAffaireInitData } from '@/axioscalls.js'
  const props = defineProps({
    idTypeAffaire: Number,
  })
  const typeAffaireData = ref(null)
  const datainitpour = ref('')
  
  const loadTypeAffaireData = async (id) => {
    console.log(`TypeAffaireDataGestion.vue loadTypeAffaireData id : ${id}`)
    if (id > 0) {
      typeAffaireData.value = await getTypeAffaireInitData(id)
      datainitpour.value = typeAffaireData.value.prmsinit.datainit_pour

      console.log(typeAffaireData.value)
    } else {
      typeAffaireData.value = null
    }
  }

  watch(() => props.idTypeAffaire, (newValue, oldValue) => {
    if (newValue > 0) {
      loadTypeAffaireData(newValue)
    }
  })




</script>