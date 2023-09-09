<script setup lang="ts">
import { onMounted, ref, watch, toRaw} from 'vue'
import { z } from 'zod'
// @vorms/resolvers/zod has to improve their types.
// @ts-ignore
import { zodResolver } from '@vorms/resolvers/zod'
import { vMaska } from "maska"
import { useForm, useFieldArray } from '@vorms/core'
import { PluginSettings, List, RequestDataFromPlugin, TranslationWP } from '../types'
import { useSettingsStore } from '../store'
import { useDataStore } from '../store/data'
import Loading from '../components/Loading.vue'
import ToggleButton from '../components/ToggleButton.vue'
import ErrorBox from '../components/ErrorBox.vue'
import Modal from '../components/Modal.vue'
import RoundedButton from '../components/RoundedButton.vue'
import { useModal } from '../composables/useModal'
import { useCrud } from '../composables/useCrud'
import { useCapitalize } from '../composables/useCapitalize'

// @ts-ignore
const t: TranslationWP = languages[0]

const { toCapitalize } = useCapitalize()
const settings = useSettingsStore()
const dataStore = useDataStore()
const dataDB = ref<Omit<PluginSettings, 'loaded'>>()
const pending = ref<boolean>(true)
const loading = ref<boolean>(true)
const hasDuplicates = ref<boolean>(false)

// nonce and URL is set in WP layer.
// @ts-ignore
const URL = ref<string>(globalURL)
const { openModal, closeModal } = useModal()

const settingsData = useCrud({
  // @ts-ignore
  nonce: wpRestNonce,
  baseURL: URL.value
})

const { register, errors, handleSubmit } = useForm<Omit<PluginSettings, 'loaded'>>({
  initialValues: {
    rows: 5,
    human_date_format: true,
    emails: []
  },
  validate: zodResolver(
    z.object({
      rows: z.coerce.number().min(1, t.form_error_rows_min).max(5, t.form_error_rows_max),
      human_date_format: z.boolean(),
      emails: z.array(
        z.object({
          email: z.string().email(t.form_error_email)
        }).required()
      ).superRefine((values, { addIssue }) => {
          const emailSet = new Set(values.map(item => item.email))
          hasDuplicates.value = false
          if (emailSet.size !== values.length || values.length === 0) {
            addIssue({
              code: z.ZodIssueCode.custom,
              message: t.form_error_no_duplicates
            })
            hasDuplicates.value = true
          }
        })
    }).required()
  ),
  async onSubmit(body: Omit<PluginSettings, 'loaded'>) {
    loading.value = true
    openModal('modal')
    await settingsData.setData({
      url: '/settings',
      method: 'PUT',
      body,
      async success () {
        pending.value = false
        const loadedBody: PluginSettings = {
          ...body,
          loaded: true
        }
        loading.value = false
        settings.setData(loadedBody)

        // update the table
        await settingsData.getData({
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
      },
      async error() {
        pending.value = false
      }
    })
  }
})

const { value: rows, attrs: rowsAttrs } = register('rows')
const { value: human_date_format } = register('human_date_format')
const { fields, append, remove } = useFieldArray<List>('emails')

const appendData = () => {
  if (fields.value.length < 5) {
    append({
      email: ''
    })
  }
}
const removeData = () => {
  if (fields.value.length > 1) {
    remove(fields.value.length - 1)
  }
}

onMounted(async () => {
  if (!settings.loaded) {
    await settingsData.getData({
      url: '/settings',
      success(_data: { data: PluginSettings }) {
        pending.value = false
        const body = {
          ..._data.data,
          loaded: true
        }
        dataDB.value = _data.data
        settings.setData(body)
      }
    })
  }
  if (settings.loaded) {
    pending.value = false
    dataDB.value = {
      human_date_format: settings.human_date_format,
      rows: +settings.rows,
      emails: settings.emails
    }
  }
})
watch(() => dataDB.value, (d) => {
  if (d) {
    const emails = toRaw(d.emails) 
    remove()
    emails.map((email: List) => {
      append({
        email: email.email
      })
    })
    rows.value = d.rows
    human_date_format.value = d.human_date_format
  }
}, { immediate: true })
</script>
<template>
  <div class="p-20px w-full">
    <h2>{{ toCapitalize(t.settings) }}</h2>
    <p>{{ t.form_paragraph }}</p>
    <div class="w-full relative">
      <Loading v-if="pending"/>
      <form 
        action="#"
        method="post"
        novalidate
        @submit.prevent="handleSubmit"
        class="w-full">
        <div class="bdt-1-#cccccc bdb-1-#cccccc w-full">
          <div class="w-full flex items-start justify-start m-20-0 flex-wrap">
            <label for="rows" class="w-205px pt-3px text-left text-12px m-0">{{ t.form_label_rows }} <br> {{ t.form_label_rows_helper }}</label>
            <div class="wc-100-235">
              <input 
                type="number"
                :min="1"
                :step="1"
                :max="5"
                v-maska
                data-maska="Z"
                data-maska-tokens="{ 'Z': { 'pattern': '[1-5]' }}"
                id="rows"
                v-model="rows"
                required
                aria-required="true"
                v-bind="rowsAttrs"
                :aria-invalid="!!(errors.rows)"
                aria-errormessage="rows-error-box"
                class="w-full h-40px"
                :class="{ 'important-bd-1-#F15757': errors.rows }">
              <ErrorBox 
                id="rows-error-box"
                :show-error="!!errors.rows"
                :error="errors.rows ?? ''" />
            </div>
          </div>
          <div class="w-full flex items-start justify-start m-20-0 flex-wrap">
            <label for="human_date_format" class="w-205px text-left text-12px m-0">{{ t.form_label_date }}</label>
            <div class="wc-100-235">
              <ToggleButton
                id="human_date_format"
                type="checkbox"
                theme="black"
                v-model="human_date_format"
                :value="true">
                {{ t.form_date_activate }}?
              </ToggleButton>
            </div>
          </div>
          <div class="w-full flex items-start justify-start m-20-0 flex-wrap">
            <label for="human_date_format" class="w-205px pt-11px text-left text-12px m-0">{{ t.form_label_emails }}</label>
            <div class="wc-100-235 flex gap-10px flex-wrap">
              <div class="w-full" v-for="field, index in fields"
              :key="field.key">
                <input 
                  type="text"
                  :id="`item-${index}`"
                  name="email"
                  required
                  aria-required="true"
                  v-model="field.value.email"
                  v-bind="field.attrs"
                  :placeholder="`E-mail ${index + 1}`"
                  :aria-errormessage="`item-error-${index}`"
                  class="w-full h-40px"
                  :aria-invalid="!!(field && field.error && field.error.email)"
                  :class="{ 'important-bd-1-#F15757': field && field.error && field.error.email }">
                <ErrorBox
                  :id="`item-error-${index}`"
                  :show-error="!!(field && field.error && field.error.email)"
                  :error="(field && field.error && field.error.email) ?? ''" />
              </div>
              <div class="w-full">
                <ErrorBox 
                  id="no-duplicates"
                  :show-error="hasDuplicates"
                  :error="t.form_error_no_duplicates" />
              </div>
              <div class="flex gap-10px justify-center">
                <RoundedButton
                  :label="t.form_plus"
                  :disabled="fields.length === 5"
                  icon="dashicons-plus" 
                  @action="appendData" />
                <RoundedButton 
                  :label="t.form_minus"
                  :disabled="fields.length === 1"
                  icon="dashicons-minus" 
                  @action="removeData()" />
              </div>
            </div>
          </div>
        </div>
        <div class="w-full mt-20px">
          <button type="submit" class="button button-primary" :aria-label="t.form_accessibility">{{ t.form_save }}</button>
        </div>
      </form>
    </div>
    <Modal
      id="modal" 
      :loading="loading"
      @close="closeModal('modal')" />
  </div>
</template>