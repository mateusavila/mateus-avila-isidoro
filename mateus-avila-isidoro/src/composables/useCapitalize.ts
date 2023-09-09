
export function useCapitalize() {
  const toCapitalize = (str: string) => str.charAt(0).toUpperCase() + str.slice(1)
  return {
    toCapitalize
  }
}