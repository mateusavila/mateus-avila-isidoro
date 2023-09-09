<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useDataStore } from '../store/data'
import { useSettingsStore } from '../store'
import { useCrud } from '../composables/useCrud'
import { useCapitalize } from '../composables/useCapitalize'
import { useDates } from '../composables/useDates'
import { RequestDataFromPlugin, PluginSettings, TranslationWP } from '../types'
import Loading from '../components/Loading.vue'

// @ts-ignore
const t: TranslationWP = languages[0]

const { toCapitalize } = useCapitalize()
const dataStore = useDataStore()
const settings = useSettingsStore()
const { formatDate } = useDates()
const graphData = useCrud({
  // @ts-ignore
  nonce: wpRestNonce,
  // @ts-ignore
  baseURL: globalURL
})

const pending = ref<boolean>(true)
onMounted(async () => {
  if (!settings.loaded) {
    await graphData.getData({
      url: '/settings',
      success(_data: { data: PluginSettings }) {
        const body = {
          ..._data.data,
          loaded: true
        }
        settings.setData(body)
        pending.value = false
      }
    })
  }
  if (!dataStore.loaded) {
    await graphData.getData({
      url: '/data',
      success({ data: { graph, table } }: RequestDataFromPlugin) {
        const body = {
          data: {
            graph,
            table: {
              title: table.title,
              data: {
                headers: table.data.headers,
                rows: table.data.rows.slice(0, settings.rows)
              }
            }
          },
          loaded: true
        }
        dataStore.setData(body)
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
    <h2>{{ toCapitalize(t.home)}}</h2>
    <p>{{t.home_paragraph}}</p>
    <div class="w-full relative min-h-200px">
      <Loading v-if="pending" />
      <div class="w-full m-20-0 box-border relative">
        <table 
          v-if="!pending && dataStore" 
          class="important-box-border important-w-full important-max-w-full wp-list-table widefat striped table-view-list">
          <thead v-if="dataStore?.data?.table?.data">
            <tr>
              <th 
                class="manage-column column-title column-primary" 
                :key="index" 
                v-for="head, index in dataStore.data.table.data.headers">
                {{( t[(`home_table_${head.toLowerCase()}` as keyof(TranslationWP))])}}
              </th>
            </tr>
          </thead>
          <tbody v-if="dataStore?.data?.table?.data">
            <tr 
              id="post-1" 
              class="type-post status-publish format-standard"
              :key="index" 
              v-for="{ id, url, title, pageviews, date}, index in dataStore.data.table.data.rows">
              <td>
                {{ id }}
              </td>
              <td>
                <a :href="url" target="_blank" rel="noopener noreferrer">{{ url }}</a>
              </td>
              <td>
                {{ title }}
              </td>
              <td>
                {{ pageviews }}
              </td>
              <td>
                {{ settings.human_date_format ? formatDate(+date) : date }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="w-full m-20-0 box-border relative" v-if="settings.emails">
        <h2>{{ toCapitalize(t.home_emails) }}</h2>
        <p>{{ t.home_emails_paragraphs }}</p>
        <ul class="list-disc pl-15px">
          <li v-for="{ email }, index in settings.emails" :key="index">{{ email }}</li>
        </ul>
      </div>
    </div>
  </div>
</template>