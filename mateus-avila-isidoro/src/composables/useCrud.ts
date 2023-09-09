import { ofetch } from 'ofetch'

interface RequestOptionsComposable {
  baseURL: string
  nonce: string
}

interface RequestOptions<T> {
  method?: 'GET' | 'POST' | 'PUT' | 'DELETE'
  success?: Function
  error?: Function
  body?: any
  url: string
}

export function useCrud(options: RequestOptionsComposable) {
  
  const { baseURL, nonce } = options

  const getData = async <T>(options: RequestOptions<T>) => {
    const { success, error, url } = options
    if (!url) {
      throw new Error("URL value is missing. Should be a string")
    }
    await ofetch<T>(url, {
      baseURL,
      headers: {
        // @ts-ignore
        'X-WP-Nonce': nonce
      },
      onResponse({ response: { _data } }) {
        success && success(_data)
      },
      onResponseError({ response: { _data } }) {
        error && error(_data)
      }
    })
  }

  const setData = async <T>(options: RequestOptions<T>) => {
    const { success, error, method, body, url } = options
    if (!method) {
      throw new Error("Method value is missing. Should be 'GET' | 'POST' | 'PUT' | 'DELETE'")
    }
    if (!url) {
      throw new Error("URL value is missing. Should be a string")
    }
    if (!body) {
      throw new Error("Body value is missing.")
    }
    await ofetch<T>(url, {
      baseURL,
      method,
      body,
      headers: {
        // @ts-ignore
        'X-WP-Nonce': nonce
      },
      onResponse({ response: { _data } }) {
        success && success(_data)
      },
      onResponseError({ response: { _data } }) {
        error && error(_data)
      }
    })
  }

  return {  
    getData,
    setData
  }
}