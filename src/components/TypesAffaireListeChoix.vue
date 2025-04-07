<template>
    <v-card>
        <v-card-text>            
            <v-autocomplete
                v-model="idTypeAffaire"
                label="choix d'un type d'affaire"
                :items="itemsta"
                :custom-filter="customFilter"
            ></v-autocomplete>
            
        </v-card-text>
    </v-card>
</template>

<script setup>
    import { ref, watch } from 'vue'
    import { getTypesAffaireListe } from '@/axioscalls.js'

    const emit = defineEmits(['choixTypeAffaire'])    

    const itemsta = ref([])
    const idTypeAffaire = ref(null)
    const typesAffaire = await getTypesAffaireListe()
    let itemta
    for (let i = 0; i<typesAffaire.length; i++) {
        if (typesAffaire[i].bactif == 1) {
            itemta = {"value" : typesAffaire[i].idtypeaffaire, "title" : typesAffaire[i].typeaffaire}
        } else {
            itemta = {"value" : typesAffaire[i].idtypeaffaire, "title" : `[Désactivée] ${typesAffaire[i].typeaffaire}`}        
        }
        itemsta.value.push(itemta)
    }

    watch(() => idTypeAffaire.value, (newvalue) => {
        emit('choixTypeAffaire', newvalue)
    })

    const removeAccents = str => str.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
    function customFilter(itemTitle, queryText, item) {
        const textLowerCase = item.raw.title.toLowerCase()
        const searchTextLowerCase = queryText.toLowerCase()
        const textUnAccent = removeAccents(item.raw.title.toLowerCase())
        const searchTextUnAccent = removeAccents(queryText.toLowerCase())
        return textLowerCase.indexOf(searchTextLowerCase) > -1
            || textUnAccent.indexOf(searchTextUnAccent) > -1
    }   
</script>