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

                    <!-- employés rôles-->
                    <v-row dense>
                      <v-col>
                        <v-expansion-panels>
                          <v-expansion-panel>
                            <v-expansion-panel-title>
                              <span>Rôles employés&nbsp;({{ pourunite.unite.roleemp.length }})&nbsp;&nbsp;&nbsp;&nbsp;</span>
                              <v-btn size="small" rounded="xl" class="text-none" @click.stop="choixEmployeRole(indexpouruo, pourunite.unite.id, 'unique')">+ employé</v-btn>
                              <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                              <v-btn size="small" rounded="xl" class="text-none" @click.stop="choixEmployeRole(indexpouruo, pourunite.unite.id, 'multiple')">+n employés</v-btn>
                            </v-expansion-panel-title>
                            <v-expansion-panel-text>
                              <v-container>
                                <v-row dense v-for="(roleemp, indexroleemp) in pourunite.unite.roleemp" :key="indexroleemp" class="d-flex align-center">
                                  <v-col cols="12" md="1">
                                    <v-tooltip text="supprimer cet employé">
                                      <template v-slot:activator="{ props }">
                                          <v-btn
                                            v-bind="props"
                                            icon="mdi-delete"
                                            variant="text"
                                            @click="supprimeRoleEmp(pourunite.unite.id, roleemp.idemp)"
                                          ></v-btn>
                                      </template>        
                                    </v-tooltip>
                                  </v-col>
                                  <v-col>
                                    {{ roleemp.nomemp }}
                                  </v-col>
                                  <v-col>
                                    <v-select
                                      v-model="pourunites[indexpouruo].unite.roleemp[indexroleemp].idroleemp"  
                                      :items="dicoRoleEmploye"
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

                    <!-- unités rôles-->
                    <v-row dense>
                      <v-col>
                        <v-expansion-panels>
                          <v-expansion-panel>
                            <v-expansion-panel-title>
                              <span>Rôles unités&nbsp;({{ pourunite.unite.roleuo.length }})&nbsp;&nbsp;&nbsp;&nbsp;</span>
                              <v-btn size="small" rounded="xl" class="text-none" @click.stop="choixUniteRole(indexpouruo, pourunite.unite.id, 'unique')">+ unité</v-btn>
                              <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                              <v-btn size="small" rounded="xl" class="text-none" @click.stop="choixUniteRole(indexpouruo, pourunite.unite.id, 'multiple')">+n unités</v-btn>
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

                    <!-- employés droits-->
                    <v-row dense>
                      <v-col>
                        <v-expansion-panels>
                          <v-expansion-panel>
                            <v-expansion-panel-title>
                              <span>Droits employés&nbsp;({{ pourunite.unite.droitemp.length }})&nbsp;&nbsp;&nbsp;&nbsp;</span>
                              <v-btn size="small" rounded="xl" class="text-none" @click.stop="choixEmployeDroit(indexpouruo, pourunite.unite.id, 'unique')">+ employé</v-btn>
                              <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                              <v-btn size="small" rounded="xl" class="text-none" @click.stop="choixEmployeDroit(indexpouruo, pourunite.unite.id, 'multiple')">+n employés</v-btn>
                            </v-expansion-panel-title>
                            <v-expansion-panel-text>
                              <v-container>
                                <v-row dense v-for="(droitemp, indexdroitemp) in pourunite.unite.droitemp" :key="indexdroitemp" class="d-flex align-center">
                                  <v-col cols="12" md="1">
                                    <v-tooltip text="supprimer cet employé">
                                      <template v-slot:activator="{ props }">
                                          <v-btn
                                            v-bind="props"
                                            icon="mdi-delete"
                                            variant="text"
                                            @click="supprimeDroitEmp(pourunite.unite.id, droitemp.idemp)"
                                          ></v-btn>
                                      </template>        
                                    </v-tooltip>
                                  </v-col>
                                  <v-col>
                                    {{ droitemp.nomemp }}
                                  </v-col>
                                  <v-col>
                                    <v-select
                                      v-model="pourunites[indexpouruo].unite.droitemp[indexdroitemp].iddroitemp"  
                                      :items="dicoDroitEO"
                                      item-title="libelle"
                                      item-value="id"
                                      placeholder="Sélection du droit"
                                    ></v-select>                                    
                                  </v-col>  
                                </v-row>  
                              </v-container>  
                            </v-expansion-panel-text>
                          </v-expansion-panel>  
                        </v-expansion-panels>  
                      </v-col>  
                    </v-row>

                    <!-- unités droits-->
                    <v-row dense>
                      <v-col>
                        <v-expansion-panels>
                          <v-expansion-panel>
                            <v-expansion-panel-title>
                              <span>Droits unités&nbsp;({{ pourunite.unite.droituo.length }})&nbsp;&nbsp;&nbsp;&nbsp;</span>
                              <v-btn size="small" rounded="xl" class="text-none" @click.stop="choixUniteDroit(indexpouruo, pourunite.unite.id, 'unique')">+ unité</v-btn>
                              <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                              <v-btn size="small" rounded="xl" class="text-none" @click.stop="choixUniteDroit(indexpouruo, pourunite.unite.id, 'multiple')">+n unités</v-btn>
                            </v-expansion-panel-title>
                            <v-expansion-panel-text>
                              <v-container>
                                <v-row dense v-for="(droituo, indexdroituo) in pourunite.unite.droituo" :key="indexdroituo" class="d-flex align-center">
                                  <v-col cols="12" md="1">
                                    <v-tooltip text="supprimer cette unité">
                                      <template v-slot:activator="{ props }">
                                          <v-btn
                                            v-bind="props"
                                            icon="mdi-delete"
                                            variant="text"
                                            @click="supprimeDroitUO(pourunite.unite.id, droituo.iduo)"
                                          ></v-btn>
                                      </template>        
                                    </v-tooltip>
                                  </v-col>
                                  <v-col>
                                    {{ droituo.nomuo }}
                                  </v-col>
                                  <v-col>
                                    <v-select
                                      v-model="pourunites[indexpouruo].unite.droituo[indexdroituo].iddroituo"  
                                      :items="dicoDroitEO"
                                      item-title="libelle"
                                      item-value="id"
                                      placeholder="Sélection du droit"
                                    ></v-select>                                    
                                  </v-col>  
                                </v-row>  
                              </v-container>  
                            </v-expansion-panel-text>
                          </v-expansion-panel>  
                        </v-expansion-panels>  
                      </v-col>  
                    </v-row>

                    <!-- groupes sécurité droits-->
                    <v-row dense>
                      <v-col>
                        <v-expansion-panels>
                          <v-expansion-panel>
                            <v-expansion-panel-title>
                              <span>Droits groupes de sécurité&nbsp;({{ pourunite.unite.droitgrpsec.length }})&nbsp;&nbsp;&nbsp;&nbsp;</span>
                              <v-btn size="small" rounded="xl" class="text-none" @click.stop="choixGrpSecDroit(indexpouruo, pourunite.unite.id, 'unique')">+ groupe sécurité</v-btn>
                              <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                              <v-btn size="small" rounded="xl" class="text-none" @click.stop="choixGrpSecDroit(indexpouruo, pourunite.unite.id, 'multiple')">+n groupes sécurité</v-btn>
                            </v-expansion-panel-title>
                            <v-expansion-panel-text>
                              <v-container>
                                <v-row dense v-for="(droitgrpsec, indexdroitgrpsec) in pourunite.unite.droitgrpsec" :key="indexdroitgrpsec" class="d-flex align-center">
                                  <v-col cols="12" md="1">
                                    <v-tooltip text="supprimer ce groupe de sécurité">
                                      <template v-slot:activator="{ props }">
                                          <v-btn
                                            v-bind="props"
                                            icon="mdi-delete"
                                            variant="text"
                                            @click="supprimeDroitGrpSec(pourunite.unite.id, droitgrpsec.idgrpsec)"
                                          ></v-btn>
                                      </template>        
                                    </v-tooltip>
                                  </v-col>
                                  <v-col>
                                    {{ droitgrpsec.nomgrpsec }}
                                  </v-col>
                                  <v-col>
                                    <v-select
                                      v-model="pourunites[indexpouruo].unite.droitgrpsec[indexdroitgrpsec].iddroitgrpsec"  
                                      :items="dicoDroitEO"
                                      item-title="libelle"
                                      item-value="id"
                                      placeholder="Sélection du droit"
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
                        :modeChoix="ctrl_choixunite_mode"
                        @choixUniteOrg="receptionUnitesOrg"
                    />
                </Suspense>
            </div>
       </v-card-text>
      </v-card>
    </template>
  </v-dialog> 

  <v-dialog max-width="1280">
    <template v-slot:activator="{ props: activatorProps }">
      <div style="display: none;">
        <v-btn
          id="btnActiveCardChoixEmploye"
          v-bind="activatorProps"
        ></v-btn>
      </div>
    </template>

    <template v-slot:default="isActive">
      <v-card>
        <v-card-actions>
            <span class="cardTitre"><h3>Choix d'un employé</h3> (cliquez sur le nom pour sélectionner)</span>
            <v-spacer></v-spacer>
          <v-btn
            text="Fermer"
            variant="tonal"
            @click="closeCardEmployeChoix"
          ></v-btn>
        </v-card-actions>
        <v-card-text>
            <div>
                <Suspense>
                    <EmployeChoix 
                        uniteHorsVdL="non" 
                        :modeChoix="ctrl_choixemploye_mode"
                        @choixEmploye="receptionEmploye"
                    />
                </Suspense>
            </div>
       </v-card-text>
      </v-card>
    </template>
  </v-dialog> 

  <v-dialog max-width="1280">
    <template v-slot:activator="{ props: activatorProps }">
      <div style="display: none;">
        <v-btn
          id="btnActiveCardChoixGroupeSecurite"
          v-bind="activatorProps"
        ></v-btn>
      </div>
    </template>

    <template v-slot:default="isActive">
      <v-card>
        <v-card-actions>
            <span class="cardTitre"><h3>Choix d'un groupe de sécurité</h3></span>
            <v-spacer></v-spacer>
          <v-btn
            text="Fermer"
            variant="tonal"
            @click="closeCardGroupeSecuriteChoix"
          ></v-btn>
        </v-card-actions>
        <v-card-text>
            <div>
                <Suspense>
                  <GroupeSecuriteChoix 
                      :modeChoix="ctrl_choixgrpsec_mode"
                      @choixGroupeSecurite="receptionGroupesSecurite"
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
  import { getTypeAffaireInitData, getDicoRoleUnite, getDicoRoleEmploye, getDicoDroitEO} from '@/axioscalls.js'
  import EmployeChoix from '@/components/EmployeChoix.vue'
  import UniteOrgChoix from '@/components/UniteOrgChoix.vue'
  import GroupeSecuriteChoix from '@/components/GroupeSecuriteChoix.vue'
  const props = defineProps({
    idTypeAffaire: Number,
  })
  let ctrl_load_datainitpour = false
  let ctrl_load_pourunites = false
  let ctrl_unitepour_index = -1
  let ctrl_unitepour_id = -1
  let ctrl_choixunite_concerne = ''
  const ctrl_choixunite_mode = ref('unique')
   let ctrl_choixemploye_concerne = ''
  const ctrl_choixemploye_mode = ref('unique')
  const ctrl_choixgrpsec_mode = ref('unique')
  const typeAffaireData = ref(null)
  const dicoRoleEmploye = ref([])
  const dicoRoleUnite = ref([])
  const dicoDroitEO = ref([])
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

      dicoRoleEmploye.value = await getDicoRoleEmploye(id)
      console.log(dicoRoleEmploye.value)
      dicoRoleUnite.value = await getDicoRoleUnite(id)
      console.log(dicoRoleUnite.value)
      dicoDroitEO.value = await getDicoDroitEO()
      console.log(dicoDroitEO.value)

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
    ctrl_choixunite_concerne = 'pour'
    ctrl_choixunite_mode.value = 'unique'
    document.getElementById("btnActiveCardChoixUniteOrg").click() 
  }

  const choixEmployeRole = (indexpouruo, idunitepour, mode) => {
    ctrl_choixemploye_concerne = 'role'
    ctrl_choixemploye_mode.value = mode
    ctrl_unitepour_index = indexpouruo
    ctrl_unitepour_id = idunitepour
    document.getElementById("btnActiveCardChoixEmploye").click() 
  }

  const choixUniteRole = (indexpouruo, idunitepour, mode) => {
    ctrl_choixunite_concerne = 'role'
    ctrl_choixunite_mode.value = mode
    ctrl_unitepour_index = indexpouruo
    ctrl_unitepour_id = idunitepour
    document.getElementById("btnActiveCardChoixUniteOrg").click() 
  }

  const choixEmployeDroit = (indexpouruo, idunitepour, mode) => {
    ctrl_choixemploye_concerne = 'droit'
    ctrl_choixemploye_mode.value = mode
    ctrl_unitepour_index = indexpouruo
    ctrl_unitepour_id = idunitepour
    document.getElementById("btnActiveCardChoixEmploye").click() 
  }

  const choixUniteDroit = (indexpouruo, idunitepour, mode) => {
    ctrl_choixunite_concerne = 'droit'
    ctrl_choixunite_mode.value = mode
    ctrl_unitepour_index = indexpouruo
    ctrl_unitepour_id = idunitepour
    document.getElementById("btnActiveCardChoixUniteOrg").click() 
  }

  const choixGrpSecDroit = (indexpouruo, idunitepour, mode) => {
    ctrl_choixgrpsec_mode.value = mode
    ctrl_unitepour_index = indexpouruo
    ctrl_unitepour_id = idunitepour
    document.getElementById("btnActiveCardChoixGroupeSecurite").click() 
  }

  const supprimeRoleEmp = (iduopour, idemprole) => {
    console.log(`supprimeRoleEmp: ${iduopour} ${idemprole}`)
    console.log(pourunites.value)
    for (let iunite=0; iunite<pourunites.value.length; iunite++) {
      if (pourunites.value[iunite].unite.id == iduopour) {
        for (let iroleemp=0; iroleemp<pourunites.value[iunite].unite.roleemp.length; iroleemp++) {
          if (pourunites.value[iunite].unite.roleemp[iroleemp].idemp == idemprole) {
            pourunites.value[iunite].unite.roleemp.splice(iroleemp, 1)
            break;
          }
        }
        break;
      }
    }
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

  const supprimeDroitEmp = (iduopour, idempdroit) => {
    console.log(`supprimeDroitEmp: ${iduopour} ${idempdroit}`)
    console.log(pourunites.value)
    for (let iunite=0; iunite<pourunites.value.length; iunite++) {
      if (pourunites.value[iunite].unite.id == iduopour) {
        for (let idroitemp=0; idroitemp<pourunites.value[iunite].unite.droitemp.length; idroitemp++) {
          if (pourunites.value[iunite].unite.droitemp[idroitemp].idemp == idempdroit) {
            pourunites.value[iunite].unite.droitemp.splice(idroitemp, 1)
            break;
          }
        }
        break;
      }
    }
  }

  const supprimeDroitUO = (iduopour, iduodroit) => {
    console.log(`supprimeDroitUO: ${iduopour} ${iduodroit}`)
    console.log(pourunites.value)
    for (let iunite=0; iunite<pourunites.value.length; iunite++) {
      if (pourunites.value[iunite].unite.id == iduopour) {
        for (let idroituo=0; idroituo<pourunites.value[iunite].unite.droituo.length; idroituo++) {
          if (pourunites.value[iunite].unite.droituo[idroituo].iduo == iduodroit) {
            pourunites.value[iunite].unite.droituo.splice(idroituo, 1)
            break;
          }
        }
        break;
      }
    }
  }

  const supprimeDroitGrpSec  = (iduopour, idgrpsecdroit) => {
    console.log(`supprimeDroitGrpSec: ${iduopour} ${idgrpsecdroit}`)
  }

  const receptionEmploye = (idemp, jsonData) => {
    console.log(`Réception employé, mode: ${ctrl_choixemploye_concerne} \njson: ${jsonData}`)
    const oEmploye = JSON.parse(jsonData)
    let aEmployes = []
    if (Array.isArray(oEmploye)) {
      aEmployes = oEmploye    
    } else {
      aEmployes.push(oEmploye)   
    }
    for (let iretemp=0; iretemp<aEmployes.length; iretemp++) {
      const idEmploye = aEmployes[iretemp].idemploye
      const libelleEmploye = `${aEmployes[iretemp].nom} ${aEmployes[iretemp].prenom}`
      const bactifemp = aEmployes[iretemp].bactif
      const uoemp = aEmployes[iretemp].unite
      const indexunitepour = ctrl_unitepour_index
      let btrouve = false
      if (ctrl_choixemploye_concerne == 'role') {
        //tester si existe déjà
        for (let i=0; i<pourunites.value[indexunitepour].unite.roleemp.length; i++) {
          if (pourunites.value[indexunitepour].unite.roleemp[i].idemp === idEmploye) {
            btrouve = true
            break  
          }
        }
        if (!btrouve) {
          const oEmploye = {
            "idemp" : idEmploye,
            "nomemp" : libelleEmploye,
            "idroleemp" : 1,
            "roleemp" : 'Participe',
            "uoemp" : uoemp,
            "bactifemp" : bactifemp,
          }
          pourunites.value[indexunitepour].unite.roleemp.push(oEmploye)
        }  
      } else if (ctrl_choixemploye_concerne == 'droit') {
        //tester si existe déjà
        for (let i=0; i<pourunites.value[indexunitepour].unite.droitemp.length; i++) {
          if (pourunites.value[indexunitepour].unite.droitemp[i].idemp === idEmploye) {
            btrouve = true
            break  
          }
        }
        if (!btrouve) {
          const oEmploye = {
            "idemp" : idEmploye,
            "nomemp" : libelleEmploye,
            "iddroitemp" : 3,
            "droitemp" : 'Ajout suivis et documents',
            "uoemp" : uoemp,
            "bactifemp" : bactifemp,
          }
          pourunites.value[indexunitepour].unite.droitemp.push(oEmploye)
        }  
      }
    }

    closeCardEmployeChoix()
    ctrl_choixemploye_concerne = ''
    console.log(pourunites.value)
  }
 

  const receptionUnitesOrg = (jsonData) => {
    console.log(`Réception unité organisationnelle, mode: ${ctrl_choixunite_concerne} \njson: ${jsonData}`)
    const indexunitepour = ctrl_unitepour_index
    const oUniteOrg = JSON.parse(jsonData)
    let aUnitesOrg = []
    if (Array.isArray(oUniteOrg)) {
        aUnitesOrg = oUniteOrg    
    } else {
        aUnitesOrg.push(oUniteOrg)   
    }
    for (let iretuo=0; iretuo<aUnitesOrg.length; iretuo++) {
      const idUnite = aUnitesOrg[iretuo].id
      const libelleUnite = aUnitesOrg[iretuo].description
      let btrouve = false
      if (ctrl_choixunite_concerne == 'pour') {
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
  
      } else if (ctrl_choixunite_concerne == 'role') {
        //tester si existe déjà
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
  
      } else if (ctrl_choixunite_concerne == 'droit') {
        //tester si existe déjà
        for (let i=0; i<pourunites.value[indexunitepour].unite.droituo.length; i++) {
          if (pourunites.value[indexunitepour].unite.droituo[i].iduo === idUnite) {
            btrouve = true
            break  
          }
        }
        if (!btrouve) {
          const oUnite = {
            "iduo" : idUnite,
            "nomuo" : libelleUnite,
            "iddroituo" : 3,
            "droituo" : 'Ajout suivis et documents',
            "bactifuo" : 1,
          }
          pourunites.value[indexunitepour].unite.droituo.push(oUnite)
        }  
  
      }
    }

    closeCardUniteOrgChoix()
    ctrl_choixunite_concerne = ''
    console.log(pourunites.value)
  }

  const receptionGroupesSecurite = (jsonData) => {
    console.log(`Réception groupe sécurité \njson: ${jsonData}`)
    /*
    const aGroupesSecuriteDC = JSON.parse(jsonData)
    for (let i=0; i<aGroupesSecuriteDC.length; i++) {
        let bTrouve = false
        for (let j=0; j<lesDatas.document.groupesSecuriteDroitConsultation.length; j++) {
            if (aGroupesSecuriteDC[i].id == lesDatas.document.groupesSecuriteDroitConsultation[j].id) {
                bTrouve= true
                break
            }
        }
        if (!bTrouve) {
            const oGroupeSecuriteDCPlus = {
                "id": aGroupesSecuriteDC[i].id,
                "nom": aGroupesSecuriteDC[i].nom,
                "description": aGroupesSecuriteDC[i].description,
            }
            lesDatas.document.groupesSecuriteDroitConsultation.push(oGroupeSecuriteDCPlus)
        }
    }
    */
    closeCardGroupeSecuriteChoix()
    console.log(pourunites.value)
  }

  const closeCardEmployeChoix = () => {
    document.getElementById("btnActiveCardChoixEmploye").click()    
  }

  const closeCardUniteOrgChoix = () => {
    document.getElementById("btnActiveCardChoixUniteOrg").click()    
  }

  const closeCardGroupeSecuriteChoix = () => {
    document.getElementById("btnActiveCardChoixGroupeSecurite").click()    
  }
  


</script>

<style scoped>
.titreChampSaisie {
    margin-top: 8px !important;
}</style>