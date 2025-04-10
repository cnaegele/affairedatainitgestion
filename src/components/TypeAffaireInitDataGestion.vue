<template>
  <div v-if="props.idTypeAffaire > 0">
      <v-container>
        <v-row dense>
          <v-col>
            <span class="d-flex">
              <span>
                <span class="titreChampSaisie">Données initiale pour&nbsp;:&nbsp;</span>
                <span v-if="datainitpour != 'tous'">
                  <br>
                  <v-btn size="small" rounded="xl" class="text-none" @click.stop="choixUnitePour()">+ unité</v-btn>
                </span>
              </span>
              <v-select
                v-model="datainitpour"  
                :items="['tous','unité','service','direction']"
                placeholder="Données initiales pour"
              ></v-select>                                    
            </span>
          </v-col>
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
                              <span>Rôles unités&nbsp;({{ pourunite.unite.roleuo.length }})&nbsp;&nbsp;&nbsp;&nbsp;</span>
                              <v-btn size="small" rounded="xl" class="text-none" @click.stop="choixUniteRole(indexpouruo, pourunite.unite.id)">+ unité</v-btn>
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

  <v-dialog max-width="1280">
    <template v-slot:activator="{ props: activatorProps }">
      <div style="display: none;">
        <v-btn
          id="btnActiveCardChoixUniteOrg"
          v-bind="activatorProps"
        ></v-btn>
      </div>
    </template>

    <template v-slot:default="isActive">
      <v-card>
        <v-card-actions>
            <span class="cardTitre"><h3>Choix d'une unité organisationnelle</h3> (cliquez sur le nom pour sélectionner)</span>
            <v-spacer></v-spacer>
          <v-btn
            text="Fermer"
            variant="tonal"
            @click="closeCardUniteOrgChoix"
          ></v-btn>
        </v-card-actions>
        <v-card-text>
            <div>
                <Suspense>
                    <UniteOrgChoix 
                        uniteHorsVdL="non" 
                        :modeChoix="'unique'"
                        @choixUniteOrg="receptionUnitesOrg"
                    />
                </Suspense>
            </div>
       </v-card-text>
      </v-card>
    </template>
  </v-dialog> 

</template>

<script setup>
  import { ref, watch } from 'vue'
  import { getTypeAffaireInitData, getDicoRoleUnite } from '@/axioscalls.js'
  import UniteOrgChoix from '@/components/UniteOrgChoix.vue'
  const props = defineProps({
    idTypeAffaire: Number,
  })
  let ctrl_load_datainitpour = false
  let ctrl_load_pourunites = false
  let ctrl_unitepour_index = -1
  let ctrl_unitepour_id = -1
  let ctrl_choixunite_mode = ''
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
      ctrl_load_datainitpour = true
      ctrl_load_pourunites = true
    }
  })

  watch(() => datainitpour.value, (newValue, oldValue) => {
    if (ctrl_load_datainitpour) {
      ctrl_load_datainitpour = false  
    } else {
      //Modification
      console.log(`datainitpour ${oldValue} -> ${newValue}`)
    }
  })

  watch(() => pourunites.value,() => {
    if (ctrl_load_pourunites) {
      ctrl_load_pourunites = false  
    } else {
      //Modification
      console.log(pourunites.value)
    }
  }, { deep: true })

  
  const choixUnitePour = () => {
    ctrl_choixunite_mode = 'pour'
    document.getElementById("btnActiveCardChoixUniteOrg").click() 
  }

  const choixUniteRole = (indexpouruo, idunitepour) => {
    ctrl_choixunite_mode = 'role'
    ctrl_unitepour_index = indexpouruo
    ctrl_unitepour_id = idunitepour
    document.getElementById("btnActiveCardChoixUniteOrg").click() 
  }

  const supprimeRoleUO = (iduopour, iduorole) => {
    console.log(`supprimeRoleUO: ${iduopour} ${iduorole}`)
    console.log(pourunites.value)
    for (let iunite=0; iunite<pourunites.value.length; iunite++) {
      if (pourunites.value[iunite].unite.id == iduopour) {
        for (let iroleuo=0; iroleuo<pourunites.value[iunite].unite.roleuo.length; iroleuo++) {
          if (pourunites.value[iunite].unite.roleuo[iroleuo].iduo == iduorole) {
            pourunites.value[iunite].unite.roleuo.splice(iroleuo, 1)
            break;
          }
        }
        break;
      }
    }
  }

  const receptionUnitesOrg = (jsonData) => {
    console.log(`Réception unité organisationnelle, mode: ${ctrl_choixunite_mode} \njson: ${jsonData}`)
    const oUniteOrg = JSON.parse(jsonData)
    const aUnitesOrg = []
    if (Array.isArray(oUniteOrg)) {
        aUnitesOrg = oUniteOrg    
    } else {
        aUnitesOrg.push(oUniteOrg)   
    }
    for (let iretuo=0; iretuo<aUnitesOrg.length; iretuo++) {
      console.log(aUnitesOrg[iretuo])
      const idUnite = aUnitesOrg[iretuo].id
      const libelleUnite = aUnitesOrg[iretuo].description
      let btrouve = false
      if (ctrl_choixunite_mode == 'pour') {
        //tester si existe déjà
        for (let i=0; i<pourunites.value.length; i++) {
          if (pourunites.value[i].unite.id === idUnite) {
            btrouve = true
            break  
          }
        }
        if (!btrouve) {
          const oUnite = {
            "unite" : {
              "id" : idUnite,
              "libelle" : libelleUnite,
              "nom" : '',
              "roleemp" : [],
              "roleuo" : [],
              "droiteemp" : [
                {
                  "idemp" : 0,
                  "nomemp" : "Créateur",
                  "uoemp" : "",                
                  "bactifemp" : 1,
                  "droitemp" : "Contrôle total",
                  "iddroitemp" : 1,
                }
              ],
              "droituo" : [],
              "droitgrpsec" : [],
            }
          }
          pourunites.value.push(oUnite)
        }
  
      } else if (ctrl_choixunite_mode == 'role') {
        //tester si existe déjà
        const indexunitepour = ctrl_unitepour_index
        for (let i=0; i<pourunites.value[indexunitepour].unite.roleuo.length; i++) {
          if (pourunites.value[indexunitepour].unite.roleuo[i].iduo === idUnite) {
            btrouve = true
            break  
          }
        }
        if (!btrouve) {
          const oUnite = {
            "iduo" : idUnite,
            "nomuo" : libelleUnite,
            "idroleuo" : 1,
            "roleuo" : 'Participe',
            "bactifuo" : 1,
          }
          pourunites.value[indexunitepour].unite.roleuo.push(oUnite)
        }
  
      } else if (ctrl_choixunite_mode == 'droit') {
        //tester si existe déjà
  
      }
    }

    closeCardUniteOrgChoix()
    ctrl_choixunite_mode = ''
    console.log(pourunites.value)
  }
  const closeCardUniteOrgChoix = () => {
    document.getElementById("btnActiveCardChoixUniteOrg").click()    
  }


</script>

<style scoped>
.titreChampSaisie {
    margin-top: 8px !important;
}</style>