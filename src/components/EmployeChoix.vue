<style scoped>
.listeempnom {
    font-weight: bold;
}
.listeemplogin {
    font-size: small;
}.listeempuo {
    font-size: small;
    font-style: italic;
}
.bactif0 {
    font-style: italic;
    color: rgb(252, 182, 182)
}
.bactif1 {
    font-style: normal;
}
</style>
<template>
    <v-container>
        <v-row  no-gutters>
            <v-col cols="8" md="6" style="text-align: center;">
                {{ descUniteOrgChoix }}  
            </v-col>
            <v-col cols="8" md="2">
                <v-btn
                    rounded="lg"
                    @click="afficheChoixUniteOrg = !afficheChoixUniteOrg"
                    class="text-none text-subtitle-1 text-medium-emphasis"
                >Choix UO</v-btn>
            </v-col>
        </v-row>
        <v-row v-if="afficheChoixUniteOrg" class="border-solid border-1">
            <v-col cols="12" md="12">
                Choisir une unité organisationnelle
                &nbsp;&nbsp;&nbsp;&nbsp;
                <v-btn
                rounded="lg"
                @click="afficheChoixUniteOrg = false"
                >QUITTER</v-btn>
                <Suspense>
                    <UniteOrgChoix 
                        uniteHorsVdL="oui" 
                        modeChoix="unique"
                        @choixUniteOrg="receptionUniteOrg"
                    />
                </Suspense>
            </v-col>
        </v-row>
        <v-row  no-gutters>
            <v-col cols="8" md="6">
                <v-text-field 
                    clearable 
                    v-model="txtCritere"
                    ref="inpTxtCritere"
                    autofocus 
                    label="nom - prénom - login - id"
                    @input="onInputCritere"
                ></v-text-field>  
            </v-col>
            <v-col cols="8" md="2">
                <v-checkbox
                    v-model="bEmployeDesactive" 
                    label="y.c. employes désactivés"
                    @click="onInputCritere">
                </v-checkbox>  
            </v-col>
        </v-row>
        <v-row v-if="messageErreur != ''">
            <v-col cols="8" md="8" v-html="messageErreur"></v-col>  
        </v-row>
        <v-row v-if="modeChoix=='multiple' && employesListeChoisi.length > 0" no-gutters>
        <v-col cols="8" md="8">
            <v-list max-height="400">
            <v-list-subheader>
                Employés choisis ({{ employesListeChoisi.length }})
                &nbsp;&nbsp;&nbsp;&nbsp;
                <v-btn
                rounded="lg"
                @click="choixTermine()"
                >Choix terminé</v-btn>
            </v-list-subheader>
            <v-list-item
                v-for="employe in employesListeChoisi"
                :key="employe.idemploye"
                :value="employe.idemploye"
                :class="`bactif${employe.bactif}`"
            >
                <template v-slot:title>
                    <span class="listeempnom" v-html="`${employe.nom} ${employe.prenom}`"></span>
                    <span class="listeemplogin" v-html="` (${employe.login}).`"></span>
                    <span  class="listeempuo"v-html="` ${employe.unitetree}`"></span>
                </template>
                <template v-slot:append>
                    <v-btn
                        color="grey-lighten-1"
                        icon="mdi-delete"
                        variant="text"
                        @click="supprimeChoix(employe.idemploye)"
                    ></v-btn>
                </template>
            </v-list-item>
            </v-list>
        </v-col>
        </v-row>
        <v-row v-if="modeChoix=='multiple' && employesListeChoisi.length > 0" no-gutters>
            <v-col cols="8" md="8">
                &nbsp;
            </v-col>
        </v-row>
        <v-row no-gutters>
        <v-col cols="12" md="12">
            <v-list max-height="400">
            <v-list-subheader>{{ libelleListe }}</v-list-subheader>
            <v-list-item
                v-for="employe in employesListeSelect"
                :key="employe.idemploye"
                :value="employe.idemploye"
                :class="`bactif${employe.bactif}`"
                @click="choixEmploye(employe)"
            >
                <template v-slot:title>
                    <span class="listeempnom" v-html="`${employe.nom} ${employe.prenom}`"></span>
                    <span class="listeemplogin" v-html="` (${employe.login}).`"></span>
                    <span  class="listeempuo"v-html="` ${employe.unitetree}`"></span>
                </template>
            </v-list-item>
            </v-list>
        </v-col>  
        </v-row>

    </v-container>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import UniteOrgChoix from '@/components/UniteOrgChoix.vue'
import { getEmployesListe } from '@/axioscalls.js'

const props = defineProps({
  nombreMaximumRetour: {
    type: String,
    default() {
        return '1000'
    }
  },
  modeChoix: {
    type: String,
    default() {
        return 'unique'
    }
  }
})

const emit = defineEmits(['choixEmploye'])

const modeChoix = ref(props.modeChoix)
const messageErreur = ref('')
const libelleListe = ref('choix employés (0)')
const idUniteOrgChoix = ref(0)
const descUniteOrgChoix = ref('-unité organisationnelle-')
const afficheChoixUniteOrg = ref(false)
const nombreMaximumRetour = ref(props.nombreMaximumRetour)

const bEmployeDesactive = ref(false)
const txtCritere = ref('')
const inpTxtCritere = ref(null)
const employesListeSelect = ref([])
const employesListeChoisi = ref([])

onMounted(() => {
  nextTick(() => {
    inpTxtCritere.value?.focus()
  })
})

//pour démarrer la recherche seulement si la frappe au clavier a cessé depuis 0.7 secondes
let typingTimer
const typingInterval = 700
const onInputCritere = () => {
  //console.log('oninput')
  clearTimeout(typingTimer)
  
  typingTimer = setTimeout(() => {
    prepareRechercheEmployes()
  }, typingInterval)

  inpTxtCritere.value.$el.querySelector('input').focus()
}

const receptionUniteOrg = (jsonData) => {
  console.log(`Réception unité organisationnelle \njson: ${jsonData}`)
  afficheChoixUniteOrg.value = false
  const oUniteOrgRecu = JSON.parse(jsonData)
  let aoUniteOrgRecu = []
  if (!Array.isArray(oUniteOrgRecu)) {
    aoUniteOrgRecu.push(oUniteOrgRecu)    
  } else {
    aoUniteOrgRecu = oUniteOrgRecu   
  }
  //console.log(aoUniteOrgRecu)
  //Pas de réception multiple dans le cadre de recherche d'employe
  idUniteOrgChoix.value = aoUniteOrgRecu[0].id
  descUniteOrgChoix.value = aoUniteOrgRecu[0].description
  onInputCritere()
}

const prepareRechercheEmployes = () => {
    const critere = txtCritere.value.trim()
    if (idUniteOrgChoix.value > 0) {
        rechercheEmployes(idUniteOrgChoix, critere, nombreMaximumRetour.value)
    } else if (critere.length >= 1) {
        rechercheEmployes(idUniteOrgChoix, critere, nombreMaximumRetour.value)
    }  
}

const rechercheEmployes = async (idUniteOrgChoix, critere, nombreMaximumRetour) => {
    let sbemployedesactive = '0'
    if (bEmployeDesactive.value) {
        sbemployedesactive = '1'
    }
    const oCritere = {
        "iduniteorg" : idUniteOrgChoix.value.toString(),
        "critere" : critere,
        "bemployedesactive" : sbemployedesactive,
        "nombremaximumretour" : nombreMaximumRetour.toString()
    }    
    //console.log (JSON.stringify(oCritere))
    let employesListe = await getEmployesListe(JSON.stringify(oCritere))
    console.log(employesListe)
    if (employesListe.hasOwnProperty('message')) {
        messageErreur.value += employesListe.message + '<br>'
        employesListe = []
    }
    if (employesListe.length < nombreMaximumRetour) {
        libelleListe.value = `Choix employes (${employesListe.length})`
    } else {
        libelleListe.value = `Choix employe (${employesListe.length}). Attention, plus de ${nombreMaximumRetour} employés correspondent aux critères`
    }
    employesListeSelect.value = employesListe
}

const choixEmploye = (employe) => {
    //console.log(employe)
    if (modeChoix.value == 'unique') {
        emit('choixEmploye', employe.idemploye, JSON.stringify(employe))
    } else if (modeChoix.value == 'multiple') {
        if (employesListeChoisi.value.some(objet => objet.idemploye === employe.idemploye) === false) {
            employesListeChoisi.value.push(employe)
        }
    }
}

const supprimeChoix = (idemploye) => {
    employesListeChoisi.value = employesListeChoisi.value.filter(objet => objet.idemploye !== idemploye)  
}

const choixTermine = () => {
  emit('choixEmploye', 0, JSON.stringify(employesListeChoisi.value))
  employesListeChoisi.value = [] 
}
</script>