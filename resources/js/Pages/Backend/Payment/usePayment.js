import { find, get } from 'lodash'
import { countries } from './data.js'

export const getCorrespondent = (country, sim) => {
    let countryFound = find(countries, item => item.name == country)
    if (countryFound) {
        let simFound = countryFound.sim.find(item => item.mno == sim)
        return simFound
    }
    return null
}

export const usePayment = () => {

    return {

    }
}