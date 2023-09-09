import { defineStore } from 'pinia'
import { PluginSettings } from '../types'

export const useSettingsStore = defineStore('awesome', {
  state: () => {
    return {
      rows: 5,
      human_date_format: true,
      emails: [],
      loaded: false
    } as PluginSettings
  },
  actions: {
    async setData ({ rows, human_date_format, emails, loaded }: PluginSettings) {
      this.rows = rows
      this.emails = emails
      this.human_date_format = human_date_format
      this.loaded = loaded
    }
  }
})