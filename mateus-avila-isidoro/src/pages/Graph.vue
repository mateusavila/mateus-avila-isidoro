<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useDataStore } from '../store/data'
import { useSettingsStore } from '../store';
import { useCrud } from '../composables/useCrud'
import { RequestDataFromPlugin, PluginSettings, TranslationWP } from '../types'
import Graphic from '../components/Graphic.vue'
import Loading from '../components/Loading.vue'
import { useCapitalize } from '../composables/useCapitalize'
const dataStore = useDataStore()
const settings = useSettingsStore()

const { toCapitalize } = useCapitalize()

// @ts-ignore
const t: TranslationWP = languages[0]

const graphData = useCrud({
  // @ts-ignore
  nonce: wpRestNonce,
  // @ts-ignore
  baseURL: globalURL
})
const pending = ref<boolean>(true)
onMounted(async () => {
  if (!dataStore.loaded) {
    await graphData.getData({
      url: '/data',
      success(_data: RequestDataFromPlugin) {
        const body = {
          ..._data,
          loaded: true
        }
        dataStore.setData(body)
        pending.value = false
      }
    })
  }
  if (!settings.loaded) {
    await graphData.getData({
      url: '/settings',
      success(_data: {data: PluginSettings}) {
        const body = {
          ..._data.data,
          loaded: true
        }
        settings.setData(body)
        pending.value = false
      }
    })
  }
  if (settings.loaded || dataStore.loaded) {
    pending.value = false
  }
})

</script>

<template>
  <div class="w-full p-20px">
    <h2>{{ toCapitalize(t.graphic) }}</h2>
    <p>{{ t.graph_paragraph }}</p>
    <Loading v-if="pending" />
    <Graphic 
      v-if="!pending && dataStore.data.graph"
      :external-data="dataStore.data.graph" 
      :human-date-format="settings.human_date_format" />
  </div>
</template>