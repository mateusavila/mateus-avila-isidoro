import { defineStore } from 'pinia'
import { GraphData, RequestDataFromPlugin, TableData } from '../types'

export const useDataStore = defineStore('awesome-data', {
  state: () => {
    return {
      data: {
        graph: [] as never as GraphData[],
        table: {} as TableData
      },
      loaded: false,
    } as RequestDataFromPlugin
  },
  actions: {
    async setData ({ data, loaded }: RequestDataFromPlugin) {
      this.data = data
      this.loaded = loaded
    }
  }
})