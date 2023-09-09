
import { format } from 'date-fns'
export function useDates() {
  const formatDate = (date: number, formatDateStr : string | undefined = 'MM/dd/yyyy') => format(new Date(date * 1000), formatDateStr)
  return {  
    formatDate
  }
}