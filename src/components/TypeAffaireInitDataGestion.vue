<template>
  <div v-if="props.idTypeAffaire > 0">
      <v-container>
        <v-row dense>
          <v-col>Données initiale pour {{ datainitpour }}</v-col>
        </v-row>
        <v-row dense v-for="(pourunite, indexpouruo) in pourunites" :key="indexpouruo" class="d-flex align-center">
          <v-col>
            <v-expansion-panels>
              <v-expansion-panel>
                <v-expansion-panel-title>
                  <span class="d-flex">
                      <span>Pour l'unité&nbsp;:&nbsp;{{ pourunite.unite.libelle }}</span>    
                  </span>    
                </v-expansion-panel-title>
                <v-expansion-panel-text>
                  <v-container>
                    <v-row dense>
                      <v-col>
                        <span class="d-flex">
                        <span class="titreChampSaisie">Nom initial : </span>
                        <v-text-field
                            v-model="pourunites[indexpouruo].unite.nom"
                        ></v-text-field>
                      </span>
                      </v-col>  
                    </v-row>
                    <v-row dense>
                      <v-col>
                        <v-expansion-panels>
                          <v-expansion-panel>
                            <v-expansion-panel-title>
                              <span>Rôles unités&nbsp;({{ pourunite.unite.roleuo.length }})&nbsp;</span>
                            </v-expansion-panel-title>
                            <v-expansion-panel-text>
                              <v-container>
                                <v-row dense v-for="(roleuo, indexroleuo) in pourunite.unite.roleuo" :key="indexroleuo" class="d-flex align-center">
                                  <v-col cols="12" md="1">
                                    <v-tooltip text="supprimer cette unité">
                                      <template v-slot:activator="{ props }">
                                          <v-btn
                                            v-bind="props"
                                            icon="mdi-delete"
                                            variant="text"
                                            @click="supprimeRoleUO(pourunite.unite.id, roleuo.iduo)"
                                          ></v-btn>
                                      </template>        
                                    </v-tooltip>
                                  </v-col>
                                  <v-col>
                                    {{ roleuo.nomuo }}
                                  </v-col>
                                  <v-col>
                                    <v-select
                v-model="pourunites[indexpouruo].unite.roleuo[indexroleuo].idroleuo"  
                :items="dicoRoleUnite"
                item-title="libelle"
                item-value="id"
                placeholder="Sélection du rôle"
                return-object
            ></v-select>                                    
                                  </v-col>  
                                </v-row>  
                              </v-container>  
                            </v-expansion-panel-text>
                          </v-expansion-panel>  
                        </v-expansion-panels>  
                      </v-col>  
                    </v-row>  
                  </v-container>  
                </v-expansion-panel-text>               
              </v-expansion-panel>
            </v-expansion-panels>  
          </v-col>
        </v-row>
      </v-container>
  </div>

</template>

<script setup>
  import { ref, watch } from 'vue'
  import { getTypeAffaireInitData, getDicoRoleUnite } from '@/axioscalls.js'
  const props = defineProps({
    idTypeAffaire: Number,
  })
  const typeAffaireData = ref(null)
  const dicoRoleUnite = ref([])
  const datainitpour = ref('')
  const pourunites = ref([])
  const pouruniteslibelle = ref([])
  
  const loadTypeAffaireData = async (id) => {
    console.log(`TypeAffaireDataGestion.vue loadTypeAffaireData id : ${id}`)
    if (id > 0) {
      typeAffaireData.value = await getTypeAffaireInitData(id)
      typeAffaireData.value.prmsinit.unites.forEach(item => {
        if (item.unite) {
          item.unite.libelle = decodeURIComponent(item.unite.libelle) 
          item.unite.nom = decodeURIComponent(item.unite.nom) 
        }
      })

      datainitpour.value = typeAffaireData.value.prmsinit.datainit_pour
      pourunites.value = typeAffaireData.value.prmsinit.unites
      for (let i=0; i<pourunites.value.length; i++) {
        pouruniteslibelle.value[i] = pourunites.value[i].unite.libelle
      } 

      console.log(typeAffaireData.value)

      dicoRoleUnite.value = await getDicoRoleUnite(id)
      console.log(dicoRoleUnite.value)

    } else {
      typeAffaireData.value = null
    }
  }

  watch(() => props.idTypeAffaire, (newValue, oldValue) => {
    if (newValue > 0) {
      loadTypeAffaireData(newValue)
    }
  })

  const supprimeRoleUO = (iduopour, iduorole) => {
    console.log(`supprimeRoleUO: ${iduopour} ${iduorole}`)
    console.log(pourunites.value)
  }


</script>

<style scoped>
.titreChampSaisie {
    margin-top: 8px !important;
}</style>