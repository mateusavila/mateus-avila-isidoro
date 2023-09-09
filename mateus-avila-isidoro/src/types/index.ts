export interface PluginSettings {
  human_date_format: boolean
  rows: number
  emails: {
    email: string
  }[]
  loaded: boolean
}

export interface List {
  email: string
}

export interface GraphData {
  date: number
  value: number | Date
}

export interface RowsTableData {
  id: number
  url: string
  title: string
  pageviews: number
  date: number | Date
}

export interface TableData {
  title: string
  data: {
    headers: string[]
    rows: RowsTableData[]
  }
}

export interface RequestDataFromPlugin {
  data: {
    graph: GraphData[]
    table: TableData
  }
  loaded: boolean
}

export interface GraphBarInterface {
  labels: (number | string)[]
  datasets: {
    label: string
    backgroundColor: string
    data: any
  }[]
}

export interface TranslationWP {
  home: string
  graphic: string
  settings: string
  header_component_paragraph: string
  modal_title: string
  modal_submit: string
  modal_paragraph: string
  form_paragraph: string
  form_label_rows: string
  form_label_rows_helper: string
  form_label_date: string
  form_label_emails: string
  form_save: string
  graph_paragraph: string
  home_paragraph: string
  home_table_id: string
  home_table_url: string
  home_table_title: string
  home_table_pageviews: string
  home_table_date: string
  home_emails: string
  home_emails_paragraphs: string
  form_error_no_duplicates: string
  form_error_email: string
  form_error_rows_min: string
  form_error_rows_max: string
  form_error_rows_string: string
  form_date_activate: string
}