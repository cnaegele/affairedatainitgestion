import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useDataStore = defineStore('iddata', () => {
  const bInGroupe = ref(0)

  return {
    bInGroupe,
  };
});
