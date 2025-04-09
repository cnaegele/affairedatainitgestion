<style scoped>
.spnalign {
    padding-left: 23px;
}
</style>
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
        <v-row v-for="(uo, index) in unitesOrgTree" :key="uo.id">
            <v-col>
                <!-- Niveau 0 -->
                <v-expansion-panels v-model="panelN0">
                    <v-expansion-panel>
                        <v-expansion-panel-title>
                            <div class="d-flex align-center">
                                <v-checkbox v-if="modeChoix=='multiple'"
                                    v-model="uo.bcheck"
                                    density="compact"
                                    hide-details
                                    class="mt-0 pt-0 mr-2"
                                    @click.stop="choixMultiple(uo)"
                                ></v-checkbox>
                                <v-checkbox v-else
                                    v-model="uo.bcheck"
                                    density="compact"
                                    hide-details
                                    class="mt-0 pt-0 mr-2"
                                    @click.stop="choix(uo)"
                                ></v-checkbox>
                                <v-tooltip :text="uo.description">
                                    <template v-slot:activator="{ props }">
                                        <v-btn
                                            v-bind:=props
                                            class="text-none text-subtitle-1"
                                            size="small"
                                            variant="flat"
                                        >
                                            {{ uo.nom }}
                                        </v-btn>
                                    </template>
                                </v-tooltip>
                            </div>                                                
                        </v-expansion-panel-title>
                        <v-expansion-panel-text>
                            <!-- Niveau 1 directions -->
                            <div v-for="(uo, index) in uo.enfants" :key="uo.id">
                                <div v-if="uo.enfants.length === 0 && (uo.bcache === 0 || buniteHorVdL)">
                                    <div class="d-flex align-center">
                                        <span class="spnalign"></span>
                                        <v-checkbox  v-if="modeChoix=='multiple'"
                                            v-model="uo.bcheck"
                                            density="compact"
                                            hide-details
                                            class="mt-0 pt-0 mr-2"
                                            @click.stop="choixMultiple(uo)"
                                        ></v-checkbox>
                                        <v-checkbox v-else
                                            v-model="uo.bcheck"
                                            density="compact"
                                            hide-details
                                            class="mt-0 pt-0 mr-2"
                                            @click.stop="choix(uo)"
                                        ></v-checkbox>
                                        <v-tooltip :text="uo.description">
                                            <template v-slot:activator="{ props }">
                                                <v-btn
                                                    v-bind:=props
                                                    class="text-none text-subtitle-1 text-medium-emphasis"
                                                    size="small"
                                                    variant="flat"
                                                >
                                                    {{ uo.nom }}
                                                </v-btn>
                                            </template>
                                        </v-tooltip>                                                
                                    </div>
                                </div>    
                                <div v-else-if="uo.bcache === 0 || buniteHorVdL">
                                    <v-expansion-panels>
                                        <v-expansion-panel>
                                            <v-expansion-panel-title>
                                                <div class="d-flex align-center">
                                                    <v-checkbox v-if="modeChoix=='multiple'"
                                                        v-model="uo.bcheck" 
                                                        density="compact"
                                                        hide-details
                                                        class="mt-0 pt-0 mr-2"
                                                        @click.stop="choixMultiple(uo)"
                                                    ></v-checkbox>
                                                    <v-checkbox v-else
                                                        v-model="uo.bcheck" 
                                                        density="compact"
                                                        hide-details
                                                        class="mt-0 pt-0 mr-2"
                                                        @click.stop="choix(uo)"
                                                    ></v-checkbox>
                                                    <v-tooltip :text="uo.description">
                                                        <template v-slot:activator="{ props }">
                                                            <v-btn
                                                                v-bind:=props
                                                                class="text-none text-subtitle-1"
                                                                size="small"
                                                                variant="flat"
                                                            >
                                                                {{ uo.nom }}
                                                            </v-btn>
                                                        </template>
                                                    </v-tooltip>
                                                </div>                                                
                                            </v-expansion-panel-title>
                                            <v-expansion-panel-text>
                                                <!-- Niveau 2 services -->
                                                <div v-for="(uo, index) in uo.enfants" :key="uo.id">
                                                    <div v-if="uo.enfants.length === 0">
                                                        <div class="d-flex align-center">
                                                            <span class="spnalign"></span>
                                                            <v-checkbox  v-if="modeChoix=='multiple'"
                                                                v-model="uo.bcheck" 
                                                                density="compact"
                                                                hide-details
                                                                class="mt-0 pt-0 mr-2"
                                                                @click.stop="choixMultiple(uo)"
                                                            ></v-checkbox>
                                                            <v-checkbox  v-else
                                                                v-model="uo.bcheck" 
                                                                density="compact"
                                                                hide-details
                                                                class="mt-0 pt-0 mr-2"
                                                                @click.stop="choix(uo)"
                                                            ></v-checkbox>
                                                            <v-tooltip :text="uo.description">
                                                                <template v-slot:activator="{ props }">
                                                                    <v-btn
                                                                        v-bind:=props
                                                                        class="text-none text-subtitle-1 text-medium-emphasis"
                                                                        size="small"
                                                                        variant="flat"
                                                                    >
                                                                        {{ uo.nom }}
                                                                    </v-btn>
                                                                </template>
                                                            </v-tooltip>
                                                        </div>                                                
                                                    </div>    
                                                    <div v-else>
                                                        <v-expansion-panels>
                                                            <v-expansion-panel>
                                                                <v-expansion-panel-title>
                                                                    <div class="d-flex align-center">
                                                                        <v-checkbox v-if="modeChoix=='multiple'"
                                                                            v-model="uo.bcheck" 
                                                                            density="compact"
                                                                            hide-details
                                                                            class="mt-0 pt-0 mr-2"
                                                                            @click.stop="choixMultiple(uo)"
                                                                        ></v-checkbox>
                                                                        <v-checkbox v-else
                                                                            v-model="uo.bcheck" 
                                                                            density="compact"
                                                                            hide-details
                                                                            class="mt-0 pt-0 mr-2"
                                                                            @click.stop="choix(uo)"
                                                                        ></v-checkbox>
                                                                        <v-tooltip :text="uo.description">
                                                                            <template v-slot:activator="{ props }">
                                                                                <v-btn
                                                                                    v-bind:=props
                                                                                    class="text-none text-subtitle-1"
                                                                                    size="small"
                                                                                    variant="flat"
                                                                                >
                                                                                    {{ uo.nom }}
                                                                                </v-btn>
                                                                            </template>
                                                                        </v-tooltip>
                                                                    </div>                                                  
                                                                </v-expansion-panel-title>
                                                                <v-expansion-panel-text>
                                                                    <!-- Niveau 3 sous-services -->
                                                                    <div v-for="(uo, index) in uo.enfants" :key="uo.id">
                                                                        <div v-if="uo.enfants.length === 0">
                                                                            <div class="d-flex align-center">
                                                                                <span class="spnalign"></span>
                                                                                <v-checkbox  v-if="modeChoix=='multiple'"
                                                                                    v-model="uo.bcheck"
                                                                                    density="compact"
                                                                                    hide-details
                                                                                    class="mt-0 pt-0 mr-2"
                                                                                    @click.stop="choixMultiple(uo)"
                                                                                ></v-checkbox>
                                                                                <v-checkbox  v-else
                                                                                    v-model="uo.bcheck"
                                                                                    density="compact"
                                                                                    hide-details
                                                                                    class="mt-0 pt-0 mr-2"
                                                                                    @click.stop="choix(uo)"
                                                                                ></v-checkbox>
                                                                               <v-tooltip :text="uo.description">
                                                                                    <template v-slot:activator="{ props }">
                                                                                        <v-btn
                                                                                            v-bind:=props
                                                                                            class="text-none text-subtitle-1 text-medium-emphasis"
                                                                                            size="small"
                                                                                            variant="flat"
                                                                                        >
                                                                                            {{ uo.nom }}
                                                                                        </v-btn>
                                                                                    </template>
                                                                                </v-tooltip>
                                                                            </div>                                                
                                                                        </div>    
                                                                        <div v-else>
                                                                            <v-expansion-panels>
                                                                                <v-expansion-panel>
                                                                                    <v-expansion-panel-title>
                                                                                        <div class="d-flex align-center">
                                                                                            <v-checkbox v-if="modeChoix=='multiple'"
                                                                                                v-model="uo.bcheck"
                                                                                                density="compact"
                                                                                                hide-details
                                                                                                class="mt-0 pt-0 mr-2"
                                                                                                @click.stop="choixMultiple(uo)"
                                                                                            ></v-checkbox>
                                                                                            <v-checkbox v-else
                                                                                                v-model="uo.bcheck"
                                                                                                density="compact"
                                                                                                hide-details
                                                                                                class="mt-0 pt-0 mr-2"
                                                                                                @click.stop="choix(uo)"
                                                                                            ></v-checkbox>
                                                                                           <v-tooltip :text="uo.description">
                                                                                                <template v-slot:activator="{ props }">
                                                                                                    <v-btn
                                                                                                        v-bind:=props
                                                                                                        class="text-none text-subtitle-1"
                                                                                                        size="small"
                                                                                                        variant="flat"
                                                                                                    >
                                                                                                        {{ uo.nom }}
                                                                                                    </v-btn>
                                                                                                </template>
                                                                                            </v-tooltip>
                                                                                        </div>                                                
                                                                                    </v-expansion-panel-title>
                                                                                    <v-expansion-panel-text>
                                                                                        <!-- Niveau 4 -->
                                                                                        <div v-for="(uo, index) in uo.enfants" :key="uo.id">
                                                                                            <div v-if="uo.enfants.length === 0">
                                                                                                <div class="d-flex align-center">
                                                                                                    <span class="spnalign"></span>
                                                                                                    <v-checkbox  v-if="modeChoix=='multiple'"
                                                                                                        v-model="uo.bcheck"
                                                                                                        density="compact"
                                                                                                        hide-details
                                                                                                        class="mt-0 pt-0 mr-2"
                                                                                                        @click.stop="choixMultiple(uo)"
                                                                                                    ></v-checkbox>
                                                                                                    <v-checkbox  v-else
                                                                                                        v-model="uo.bcheck"
                                                                                                        density="compact"
                                                                                                        hide-details
                                                                                                        class="mt-0 pt-0 mr-2"
                                                                                                        @click.stop="choix(uo)"
                                                                                                    ></v-checkbox>
                                                                                                    <v-tooltip :text="uo.description">
                                                                                                        <template v-slot:activator="{ props }">
                                                                                                            <v-btn
                                                                                                                v-bind:=props
                                                                                                                class="text-none text-subtitle-1 text-medium-emphasis"
                                                                                                                size="small"
                                                                                                                variant="flat"
                                                                                                            >
                                                                                                                {{ uo.nom }}
                                                                                                            </v-btn>
                                                                                                        </template>
                                                                                                    </v-tooltip>
                                                                                                </div>                                                
                                                                                            </div>    
                                                                                            <div v-else>
                                                                                                <v-expansion-panels>
                                                                                                    <v-expansion-panel>
                                                                                                        <v-expansion-panel-title>
                                                                                                            <div class="d-flex align-center">
                                                                                                                <v-checkbox v-if="modeChoix=='multiple'"
                                                                                                                    v-model="uo.bcheck"
                                                                                                                    density="compact"
                                                                                                                    hide-details
                                                                                                                    class="mt-0 pt-0 mr-2"
                                                                                                                    @click.stop="choixMultiple(uo)"
                                                                                                                ></v-checkbox>
                                                                                                                <v-checkbox v-else
                                                                                                                    v-model="uo.bcheck"
                                                                                                                    density="compact"
                                                                                                                    hide-details
                                                                                                                    class="mt-0 pt-0 mr-2"
                                                                                                                    @click.stop="choix(uo)"
                                                                                                                ></v-checkbox>
                                                                                                                <v-tooltip :text="uo.description">
                                                                                                                    <template v-slot:activator="{ props }">
                                                                                                                        <v-btn
                                                                                                                            v-bind:=props
                                                                                                                            class="text-none text-subtitle-1"
                                                                                                                            size="small"
                                                                                                                            variant="flat"
                                                                                                                        >
                                                                                                                            {{ uo.nom }}
                                                                                                                        </v-btn>
                                                                                                                    </template>
                                                                                                                </v-tooltip>
                                                                                                            </div>                                                
                                                                                                        </v-expansion-panel-title>
                                                                                                        <v-expansion-panel-text>
                                                                                                            <!-- Niveau 5 -->
                                                                                                            <div v-for="(uo, index) in uo.enfants" :key="uo.id">
                                                                                                                <div v-if="uo.enfants.length === 0">
                                                                                                                    <div class="d-flex align-center">
                                                                                                                        <span class="spnalign"></span>
                                                                                                                        <v-checkbox  v-if="modeChoix=='multiple'"
                                                                                                                            v-model="uo.bcheck"
                                                                                                                            density="compact"
                                                                                                                            hide-details
                                                                                                                            class="mt-0 pt-0 mr-2"
                                                                                                                            @click.stop="choixMultiple(uo)"
                                                                                                                        ></v-checkbox>
                                                                                                                        <v-checkbox  v-else
                                                                                                                            v-model="uo.bcheck"
                                                                                                                            density="compact"
                                                                                                                            hide-details
                                                                                                                            class="mt-0 pt-0 mr-2"
                                                                                                                            @click.stop="choix(uo)"
                                                                                                                        ></v-checkbox>
                                                                                                                        <v-tooltip :text="uo.description">
                                                                                                                            <template v-slot:activator="{ props }">
                                                                                                                                <v-btn
                                                                                                                                    v-bind:=props
                                                                                                                                    class="text-none text-subtitle-1 text-medium-emphasis"
                                                                                                                                    size="small"
                                                                                                                                    variant="flat"
                                                                                                                                >
                                                                                                                                    {{ uo.nom }}
                                                                                                                                </v-btn>
                                                                                                                            </template>
                                                                                                                        </v-tooltip>
                                                                                                                    </div>                                                
                                                                                                                </div>    
                                                                                                                <div v-else>
                                                                                                                    <v-expansion-panels>
                                                                                                                        <v-expansion-panel>
                                                                                                                            <v-expansion-panel-title>
                                                                                                                                <div class="d-flex align-center">
                                                                                                                                    <v-checkbox v-if="modeChoix=='multiple'"
                                                                                                                                        v-model="uo.bcheck"
                                                                                                                                        density="compact"
                                                                                                                                        hide-details
                                                                                                                                        class="mt-0 pt-0 mr-2"
                                                                                                                                        @click.stop="choixMultiple(uo)"
                                                                                                                                    ></v-checkbox>
                                                                                                                                    <v-checkbox v-else
                                                                                                                                        v-model="uo.bcheck"
                                                                                                                                        density="compact"
                                                                                                                                        hide-details
                                                                                                                                        class="mt-0 pt-0 mr-2"
                                                                                                                                        @click.stop="choix(uo)"
                                                                                                                                    ></v-checkbox>
                                                                                                                                    <v-tooltip :text="uo.description">
                                                                                                                                        <template v-slot:activator="{ props }">
                                                                                                                                            <v-btn
                                                                                                                                                v-bind:=props
                                                                                                                                                class="text-none text-subtitle-1"
                                                                                                                                                size="small"
                                                                                                                                                variant="flat"
                                                                                                                                            >
                                                                                                                                                {{ uo.nom }}
                                                                                                                                            </v-btn>
                                                                                                                                        </template>
                                                                                                                                    </v-tooltip>
                                                                                                                                </div>                                                
                                                                                                                            </v-expansion-panel-title>
                                                                                                                            <v-expansion-panel-text>
                                                                                                                                <!-- Niveau 6 -->
                                                                                                                                <div v-for="(uo, index) in uo.enfants" :key="uo.id">
                                                                                                                                    <div v-if="uo.enfants.length === 0">
                                                                                                                                        <div class="d-flex align-center">
                                                                                                                                            <span class="spnalign"></span>
                                                                                                                                            <v-checkbox  v-if="modeChoix=='multiple'"
                                                                                                                                                v-model="uo.bcheck"
                                                                                                                                                density="compact"
                                                                                                                                                hide-details
                                                                                                                                                class="mt-0 pt-0 mr-2"
                                                                                                                                                @click.stop="choixMultiple(uo)"
                                                                                                                                            ></v-checkbox>
                                                                                                                                            <v-checkbox  v-else
                                                                                                                                                v-model="uo.bcheck"
                                                                                                                                                density="compact"
                                                                                                                                                hide-details
                                                                                                                                                class="mt-0 pt-0 mr-2"
                                                                                                                                                @click.stop="choix(uo)"
                                                                                                                                            ></v-checkbox>
                                                                                                                                            <v-tooltip :text="uo.description">
                                                                                                                                                <template v-slot:activator="{ props }">
                                                                                                                                                    <v-btn
                                                                                                                                                        v-bind:=props
                                                                                                                                                        class="text-none text-subtitle-1 text-medium-emphasis"
                                                                                                                                                        size="small"
                                                                                                                                                        variant="flat"
                                                                                                                                                    >
                                                                                                                                                        {{ uo.nom }}
                                                                                                                                                    </v-btn>
                                                                                                                                                </template>
                                                                                                                                            </v-tooltip>
                                                                                                                                        </div>                                                
                                                                                                                                    </div>    
                                                                                                                                    <div v-else>
                                                                                                                                        <v-expansion-panels>
                                                                                                                                            <v-expansion-panel>
                                                                                                                                                <v-expansion-panel-title>
                                                                                                                                                    <div class="d-flex align-center">
                                                                                                                                                        <v-checkbox v-if="modeChoix=='multiple'"
                                                                                                                                                            v-model="uo.bcheck"
                                                                                                                                                            density="compact"
                                                                                                                                                            hide-details
                                                                                                                                                            class="mt-0 pt-0 mr-2"
                                                                                                                                                            @click.stop="choixMultiple(uo)"
                                                                                                                                                        ></v-checkbox>
                                                                                                                                                        <v-checkbox v-else
                                                                                                                                                            v-model="uo.bcheck"
                                                                                                                                                            density="compact"
                                                                                                                                                            hide-details
                                                                                                                                                            class="mt-0 pt-0 mr-2"
                                                                                                                                                            @click.stop="choix(uo)"
                                                                                                                                                        ></v-checkbox>
                                                                                                                                                        <v-tooltip :text="uo.description">
                                                                                                                                                            <template v-slot:activator="{ props }">
                                                                                                                                                                <v-btn
                                                                                                                                                                    v-bind:=props
                                                                                                                                                                    class="text-none text-subtitle-1"
                                                                                                                                                                    size="small"
                                                                                                                                                                    variant="flat"
                                                                                                                                                                >
                                                                                                                                                                    {{ uo.nom }}
                                                                                                                                                                </v-btn>
                                                                                                                                                            </template>
                                                                                                                                                        </v-tooltip>
                                                                                                                                                    </div>                                                
                                                                                                                                                </v-expansion-panel-title>
                                                                                                                                                <v-expansion-panel-text>
                                                                                                                                                <!-- Niveau 7 -->
                                                                                                                                                <div v-for="(uo, index) in uo.enfants" :key="uo.id">
                                                                                                                                                    <div v-if="uo.enfants.length === 0">
                                                                                                                                                        <div class="d-flex align-center">
                                                                                                                                                            <span class="spnalign"></span>
                                                                                                                                                            <v-checkbox  v-if="modeChoix=='multiple'"
                                                                                                                                                                v-model="uo.bcheck"
                                                                                                                                                                density="compact"
                                                                                                                                                                hide-details
                                                                                                                                                                class="mt-0 pt-0 mr-2"
                                                                                                                                                                @click.stop="choixMultiple(uo)"
                                                                                                                                                            ></v-checkbox>
                                                                                                                                                            <v-checkbox  v-else
                                                                                                                                                                v-model="uo.bcheck"
                                                                                                                                                                density="compact"
                                                                                                                                                                hide-details
                                                                                                                                                                class="mt-0 pt-0 mr-2"
                                                                                                                                                                @click.stop="choix(uo)"
                                                                                                                                                            ></v-checkbox>
                                                                                                                                                            <v-tooltip :text="uo.description">
                                                                                                                                                                <template v-slot:activator="{ props }">
                                                                                                                                                                    <v-btn
                                                                                                                                                                        v-bind:=props
                                                                                                                                                                        class="text-none text-subtitle-1 text-medium-emphasis"
                                                                                                                                                                        size="small"
                                                                                                                                                                        variant="flat"
                                                                                                                                                                    >
                                                                                                                                                                        {{ uo.nom }}
                                                                                                                                                                    </v-btn>
                                                                                                                                                                </template>
                                                                                                                                                            </v-tooltip>
                                                                                                                                                        </div>                                                
                                                                                                                                                    </div>    
                                                                                                                                                    <div v-else>
                                                                                                                                                        <v-expansion-panels>
                                                                                                                                                            <v-expansion-panel>
                                                                                                                                                                <v-expansion-panel-title>
                                                                                                                                                                    <div class="d-flex align-center">
                                                                                                                                                                        <v-checkbox v-if="modeChoix=='multiple'"
                                                                                                                                                                            v-model="uo.bcheck"
                                                                                                                                                                            density="compact"
                                                                                                                                                                            hide-details
                                                                                                                                                                            class="mt-0 pt-0 mr-2"
                                                                                                                                                                            @click.stop="choixMultiple(uo)"
                                                                                                                                                                        ></v-checkbox>
                                                                                                                                                                        <v-checkbox v-else
                                                                                                                                                                            v-model="uo.bcheck"
                                                                                                                                                                            density="compact"
                                                                                                                                                                            hide-details
                                                                                                                                                                            class="mt-0 pt-0 mr-2"
                                                                                                                                                                            @click.stop="choix(uo)"
                                                                                                                                                                        ></v-checkbox>
                                                                                                                                                                        <v-tooltip :text="uo.description">
                                                                                                                                                                            <template v-slot:activator="{ props }">
                                                                                                                                                                                <v-btn
                                                                                                                                                                                    v-bind:=props
                                                                                                                                                                                    class="text-none text-subtitle-1"
                                                                                                                                                                                    size="small"
                                                                                                                                                                                    variant="flat"
                                                                                                                                                                                >
                                                                                                                                                                                    {{ uo.nom }}
                                                                                                                                                                                </v-btn>
                                                                                                                                                                            </template>
                                                                                                                                                                        </v-tooltip>
                                                                                                                                                                    </div>                                                
                                                                                                                                                                </v-expansion-panel-title>
                                                                                                                                                                <v-expansion-panel-text>
                                                                                                                                                                <!-- Niveau 8 -->
                                                                                                                                                                <div v-for="(uo, index) in uo.enfants" :key="uo.id">
                                                                                                                                                                    <div v-if="uo.enfants.length === 0">
                                                                                                                                                                        <div class="d-flex align-center">
                                                                                                                                                                            <span class="spnalign"></span>
                                                                                                                                                                            <v-checkbox  v-if="modeChoix=='multiple'"
                                                                                                                                                                                v-model="uo.bcheck"
                                                                                                                                                                                density="compact"
                                                                                                                                                                                hide-details
                                                                                                                                                                                class="mt-0 pt-0 mr-2"
                                                                                                                                                                                @click.stop="choixMultiple(uo)"
                                                                                                                                                                            ></v-checkbox>
                                                                                                                                                                            <v-checkbox  v-else
                                                                                                                                                                                v-model="uo.bcheck"
                                                                                                                                                                                density="compact"
                                                                                                                                                                                hide-details
                                                                                                                                                                                class="mt-0 pt-0 mr-2"
                                                                                                                                                                                @click.stop="choix(uo)"
                                                                                                                                                                            ></v-checkbox>
                                                                                                                                                                            <v-tooltip :text="uo.description">
                                                                                                                                                                                <template v-slot:activator="{ props }">
                                                                                                                                                                                    <v-btn
                                                                                                                                                                                        v-bind:=props
                                                                                                                                                                                        class="text-none text-subtitle-1 text-medium-emphasis"
                                                                                                                                                                                        size="small"
                                                                                                                                                                                        variant="flat"
                                                                                                                                                                                    >
                                                                                                                                                                                        {{ uo.nom }}
                                                                                                                                                                                    </v-btn>
                                                                                                                                                                                </template>
                                                                                                                                                                            </v-tooltip>
                                                                                                                                                                        </div>                                                
                                                                                                                                                                    </div>    
                                                                                                                                                                    <div v-else>
                                                                                                                                                                        <v-expansion-panels>
                                                                                                                                                                            <v-expansion-panel>
                                                                                                                                                                                <v-expansion-panel-title>
                                                                                                                                                                                    <div class="d-flex align-center">
                                                                                                                                                                                        <v-checkbox v-if="modeChoix=='multiple'"
                                                                                                                                                                                            v-model="uo.bcheck"
                                                                                                                                                                                            density="compact"
                                                                                                                                                                                            hide-details
                                                                                                                                                                                            class="mt-0 pt-0 mr-2"
                                                                                                                                                                                            @click.stop="choixMultiple(uo)"
                                                                                                                                                                                        ></v-checkbox>
                                                                                                                                                                                        <v-checkbox v-else
                                                                                                                                                                                            v-model="uo.bcheck"
                                                                                                                                                                                            density="compact"
                                                                                                                                                                                            hide-details
                                                                                                                                                                                            class="mt-0 pt-0 mr-2"
                                                                                                                                                                                            @click.stop="choix(uo)"
                                                                                                                                                                                        ></v-checkbox>
                                                                                                                                                                                        <v-tooltip :text="uo.description">
                                                                                                                                                                                            <template v-slot:activator="{ props }">
                                                                                                                                                                                                <v-btn
                                                                                                                                                                                                    v-bind:=props
                                                                                                                                                                                                    class="text-none text-subtitle-1"
                                                                                                                                                                                                    size="small"
                                                                                                                                                                                                    variant="flat"
                                                                                                                                                                                                >
                                                                                                                                                                                                    {{ uo.nom }}
                                                                                                                                                                                                </v-btn>
                                                                                                                                                                                            </template>
                                                                                                                                                                                        </v-tooltip>
                                                                                                                                                                                    </div>                                                
                                                                                                                                                                                </v-expansion-panel-title>
                                                                                                                                                                                <v-expansion-panel-text>
                                                                                                                                                                                    !!! Niveau hiérarchique non traité, prévenir le support goéland !!!
                                                                                                                                                                                </v-expansion-panel-text>
                                                                                                                                                                            </v-expansion-panel>   
                                                                                                                                                                    </v-expansion-panels>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                                </v-expansion-panel-text>
                                                                                                                                                            </v-expansion-panel>   
                                                                                                                                                    </v-expansion-panels>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                </v-expansion-panel-text>
                                                                                                                                            </v-expansion-panel>   
                                                                                                                                    </v-expansion-panels>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                           </v-expansion-panel-text>
                                                                                                                        </v-expansion-panel>   
                                                                                                                </v-expansion-panels>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </v-expansion-panel-text>
                                                                                                    </v-expansion-panel>   
                                                                                            </v-expansion-panels>
                                                                                            </div>
                                                                                        </div>
                                                                                    </v-expansion-panel-text>
                                                                                </v-expansion-panel>   
                                                                        </v-expansion-panels>
                                                                        </div>
                                                                    </div>

                                                                </v-expansion-panel-text>
                                                            </v-expansion-panel>   
                                                    </v-expansion-panels>
                                                    </div>
                                                </div>
                                            </v-expansion-panel-text>
                                        </v-expansion-panel>   
                                </v-expansion-panels>
                                </div>
                            </div>
                        </v-expansion-panel-text>
                    </v-expansion-panel>   
                </v-expansion-panels>
            </v-col>
        </v-row>
    </v-container>

</template>
  
<script setup>
import { ref } from 'vue'
import { getUnitesOrgListe } from '@/axioscalls.js'

const props = defineProps({
  modeChoix: String,
  uniteHorsVdL: String,
})
let modeChoix = ref('unique')
if (props.modeChoix !== undefined) {
  modeChoix = ref(props.modeChoix)
}
let buniteHorVdL = ref(false)
if (props.uniteHorsVdL !== undefined) {
    if (props.uniteHorsVdL == 'oui') {
        buniteHorVdL = ref(true)    
    }
}
const emit = defineEmits(['choixUniteOrg'])
const unitesOrgChoisies = ref([])
const unitesOrgTree = ref([])
const unitesOrgListe = await getUnitesOrgListe()
//console.log(unitesOrgListe)

const transforUOListe2UOTree = (unitesOrgListe) => {
    let aDummyTree = []
    let dummyTreeN0 = [], dummyTreeN1 = [], dummyTreeN2 = []
    let dummyTreeN3 = [], dummyTreeN4 = [], dummyTreeN5 = []
    let dummyTreeN6 = [], dummyTreeN7 = [], dummyTreeN8 = []
    let iduoparente

    //niveau0
    dummyTreeN0 = unitesOrgListe.reduce((aels, uniteOrg) => {
        if (uniteOrg.iduoparente === null) {
            aels.push(uniteOrg);
        }
        return aels;
    }, [])

    for (let in0=0; in0<dummyTreeN0.length; in0++) {
        const uoN0 = {
            id: dummyTreeN0[in0].iduniteorg,
            nom: dummyTreeN0[in0].nomuniteorg,
            description: dummyTreeN0[in0].descriptionuniteorg,
            bcache: dummyTreeN0[in0].bcache,
            bcheck: false,
            enfants: []
        }
        aDummyTree.push(uoN0)
        //console.log(aDummyTree)

        iduoparente = dummyTreeN0[in0].iduniteorg
        //niveau1
        dummyTreeN1 = unitesOrgListe.reduce((aels, uniteOrg) => {
            if (uniteOrg.iduoparente == iduoparente) {
                aels.push(uniteOrg);
            }
            return aels;
        }, [])
        //console.log(dummyTreeN1)

        for (let in1=0; in1<dummyTreeN1.length; in1++) {
            const uoN1 = {
                id: dummyTreeN1[in1].iduniteorg,
                nom: dummyTreeN1[in1].nomuniteorg,
                description: dummyTreeN1[in1].descriptionuniteorg,
                bcache: dummyTreeN1[in1].bcache,
                bcheck: false,
                enfants: []
            }
            aDummyTree[in0].enfants.push(uoN1)

            iduoparente = dummyTreeN1[in1].iduniteorg
            //niveau2
            dummyTreeN2 = unitesOrgListe.reduce((aels, uniteOrg) => {
                if (uniteOrg.iduoparente == iduoparente) {
                    aels.push(uniteOrg);
                }
                return aels;
            }, [])
            //console.log(dummyTreeN2)

            for (let in2=0; in2<dummyTreeN2.length; in2++) {
                const uoN2 = {
                    id: dummyTreeN2[in2].iduniteorg,
                    nom: dummyTreeN2[in2].nomuniteorg,
                    description: dummyTreeN2[in2].descriptionuniteorg,
                    bcache: dummyTreeN2[in2].bcache,
                    bcheck: false,
                    enfants: []
                }
                aDummyTree[in0].enfants[in1].enfants.push(uoN2)

                iduoparente = dummyTreeN2[in2].iduniteorg
                //niveau3
                dummyTreeN3 = unitesOrgListe.reduce((aels, uniteOrg) => {
                    if (uniteOrg.iduoparente == iduoparente) {
                        aels.push(uniteOrg);
                    }
                    return aels;
                }, [])
                //console.log(dummyTreeN3)
                
                for (let in3=0; in3<dummyTreeN3.length; in3++) {
                    const uoN3 = {
                        id: dummyTreeN3[in3].iduniteorg,
                        nom: dummyTreeN3[in3].nomuniteorg,
                        description: dummyTreeN3[in3].descriptionuniteorg,
                        bcache: dummyTreeN3[in3].bcache,
                        bcheck: false,
                        enfants: []
                    }
                    aDummyTree[in0].enfants[in1].enfants[in2].enfants.push(uoN3)

                    iduoparente = dummyTreeN3[in3].iduniteorg
                    //niveau4
                    dummyTreeN4 = unitesOrgListe.reduce((aels, uniteOrg) => {
                        if (uniteOrg.iduoparente == iduoparente) {
                            aels.push(uniteOrg);
                        }
                        return aels;
                    }, [])
                    //console.log(dummyTreeN4)

                    for (let in4=0; in4<dummyTreeN4.length; in4++) {
                        const uoN4 = {
                            id: dummyTreeN4[in4].iduniteorg,
                            nom: dummyTreeN4[in4].nomuniteorg,
                            description: dummyTreeN4[in4].descriptionuniteorg,
                            bcache: dummyTreeN4[in4].bcache,
                            bcheck: false,
                            enfants: []
                        }
                        aDummyTree[in0].enfants[in1].enfants[in2].enfants[in3].enfants.push(uoN4)

                        iduoparente = dummyTreeN4[in4].iduniteorg
                        //niveau5
                        dummyTreeN5 = unitesOrgListe.reduce((aels, uniteOrg) => {
                            if (uniteOrg.iduoparente == iduoparente) {
                                aels.push(uniteOrg);
                            }
                            return aels;
                        }, [])
                        //console.log(dummyTreeN5)

                        for (let in5=0; in5<dummyTreeN5.length; in5++) {
                            const uoN5 = {
                                id: dummyTreeN5[in5].iduniteorg,
                                nom: dummyTreeN5[in5].nomuniteorg,
                                description: dummyTreeN5[in5].descriptionuniteorg,
                                bcache: dummyTreeN5[in5].bcache,
                                bcheck: false,
                                enfants: []
                            }
                            aDummyTree[in0].enfants[in1].enfants[in2].enfants[in3].enfants[in4].enfants.push(uoN5)

                            iduoparente = dummyTreeN5[in5].iduniteorg
                            //niveau6
                            dummyTreeN6 = unitesOrgListe.reduce((aels, uniteOrg) => {
                                if (uniteOrg.iduoparente == iduoparente) {
                                    aels.push(uniteOrg);
                                }
                                return aels;
                            }, [])
                            //console.log(dummyTreeN6)

                            for (let in6=0; in6<dummyTreeN6.length; in6++) {
                                const uoN6 = {
                                    id: dummyTreeN6[in6].iduniteorg,
                                    nom: dummyTreeN6[in6].nomuniteorg,
                                    description: dummyTreeN6[in6].descriptionuniteorg,
                                    bcache: dummyTreeN6[in6].bcache,
                                    bcheck: false,
                                    enfants: []
                                }
                                aDummyTree[in0].enfants[in1].enfants[in2].enfants[in3].enfants[in4].enfants[in5].enfants.push(uoN6)

                                iduoparente = dummyTreeN6[in6].iduniteorg
                                //niveau7
                                dummyTreeN7 = unitesOrgListe.reduce((aels, uniteOrg) => {
                                    if (uniteOrg.iduoparente == iduoparente) {
                                        aels.push(uniteOrg);
                                    }
                                    return aels;
                                }, [])
                                //console.log(dummyTreeN7)

                                for (let in7=0; in7<dummyTreeN7.length; in7++) {
                                    const uoN7 = {
                                        id: dummyTreeN7[in7].iduniteorg,
                                        nom: dummyTreeN7[in7].nomuniteorg,
                                        description: dummyTreeN7[in7].descriptionuniteorg,
                                        bcache: dummyTreeN7[in7].bcache,
                                        bcheck: false,
                                        enfants: []
                                    }
                                    aDummyTree[in0].enfants[in1].enfants[in2].enfants[in3].enfants[in4].enfants[in5].enfants[in6].enfants.push(uoN7)

                                    iduoparente = dummyTreeN7[in7].iduniteorg
                                    //niveau8
                                    dummyTreeN8 = unitesOrgListe.reduce((aels, uniteOrg) => {
                                        if (uniteOrg.iduoparente == iduoparente) {
                                            aels.push(uniteOrg);
                                        }
                                        return aels;
                                    }, [])
                                }
                            }
                        }
                    }
                }                
            }
        }
    }

    return aDummyTree
}
unitesOrgTree.value = transforUOListe2UOTree(unitesOrgListe)
const aChoixMultiple = ref([])

const panelN0 = ref([0])
const panelN1 = ref([])

const unitesOrg = unitesOrgTree.value[0]
let unitesOrgListeChoisi = []

const choix = (uniteOrg) => {
    if (modeChoix.value == 'unique') {
        const uoChoisie = {
            id: uniteOrg.id,
            nom: uniteOrg.nom,
            description: uniteOrg.description,
        }
        emit('choixUniteOrg', JSON.stringify(uoChoisie))
        //console.log(JSON.stringify(uoChoisie))
    } else { //choix multiple, on se contente de faire comme quand le clic est fait sur la checkbox
        choixMultiple(uniteOrg)
    }
}

const choixMultiple = (uniteOrg) => {
    uniteOrg.bcheck = !uniteOrg.bcheck
    if (uniteOrg.bcheck) {
        if (unitesOrgListeChoisi.some(objet => objet.id === uniteOrg.id) === false) {
            const uoChoisie = {
                id: uniteOrg.id,
                nom: uniteOrg.nom,
                description: uniteOrg.description,
            }
            unitesOrgListeChoisi.push(uoChoisie)
        }
    } else {
        unitesOrgListeChoisi = unitesOrgListeChoisi.filter(objet => objet.id !== uniteOrg.id)    
    }
}

const choixTermine = () => {
    emit('choixUniteOrg', JSON.stringify(unitesOrgListeChoisi))
    //console.log(JSON.stringify(unitesOrgListeChoisi))
}
</script>