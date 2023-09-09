<script setup lang="ts">
import { computed, ref, toRaw } from 'vue'
import { TranslationWP } from '../types'
import { useDates } from '../composables/useDates'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'
import { Bar } from 'vue-chartjs'
import { GraphData, GraphBarInterface } from '../types'
import { useCapitalize } from '../composables/useCapitalize'

// @ts-ignore
const t: TranslationWP = languages[0]

const { toCapitalize } = useCapitalize()

const { formatDate } = useDates()
const { externalData, humanDateFormat } = defineProps<{
  externalData: GraphData[]
  humanDateFormat: boolean
}>()
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)
const options = ref({
  responsive: true,
  maintainAspectRatio: false
})

const labels = computed(() => {
  const data = toRaw(externalData)
  return data.map(({ date }) => humanDateFormat ? formatDate(date) : date)
})

const datasets = computed(() => {
  const data = toRaw(externalData)
  return {
    label: toCapitalize(t.home_table_pageviews),
    backgroundColor: '#f87979',
    data: data.map(({ value }) => value)
  }
})

const data = ref<GraphBarInterface>({
  labels: labels.value,
  datasets: [datasets.value]
})
</script>

<template>
  <div class="w-full bdt-1-#cccccc bdb-1-#cccccc bg-#ffffff" role="figure">
    <Bar :options="options" :data="data" />
  </div>
</template>