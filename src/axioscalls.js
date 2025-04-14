import axios from 'axios'
let g_devurl = ''
if (import.meta.env.DEV) {
    g_devurl = 'https://mygolux.lausanne.ch'    
}

export async function getTypesAffaireListe() {
    const urltl = `${g_devurl}/goeland/gestion_spec/affaire_datainitgestion/axios/typesaffaire_liste.php`
    const response = await axios.get(urltl)
        .catch(function (error) {
            return traiteAxiosError(error)
        })
    return response.data
}

export async function getTypeAffaireInitData(idTypeAffaire) {
    if (idTypeAffaire > 0) {
        const urltd = `${g_devurl}/goeland/gestion_spec/affaire_datainitgestion/axios/typeaffaire_initgestion_data.php`
        const params = new URLSearchParams([['idtypeaffaire', idTypeAffaire]])
        const response = await axios.get(urltd, { params })
            .catch(function (error) {
                return traiteAxiosError(error)
            })
            return response.data
    } else {
        return {}
    }
}

export async function getDicoRoleUnite(idTypeAffaire) {
    if (idTypeAffaire > 0) {
        const urldr = `${g_devurl}/goeland/gestion_spec/affaire_datainitgestion/axios/typeaffaire_dicoroleuo.php`
        const params = new URLSearchParams([['idtypeaffaire', idTypeAffaire]])
        const response = await axios.get(urldr, { params })
            .catch(function (error) {
                return traiteAxiosError(error)
            })
            return response.data
    } else {
        return []
    }
}

export async function getDicoRoleEmploye(idTypeAffaire) {
    if (idTypeAffaire > 0) {
        const urldr = `${g_devurl}/goeland/gestion_spec/affaire_datainitgestion/axios/typeaffaire_dicoroleemp.php`
        const params = new URLSearchParams([['idtypeaffaire', idTypeAffaire]])
        const response = await axios.get(urldr, { params })
            .catch(function (error) {
                return traiteAxiosError(error)
            })
            return response.data
    } else {
        return []
    }
}

export async function getDicoDroitEO() {
    const urldd = `${g_devurl}/goeland/gestion_spec/affaire_datainitgestion/axios/affaire_dicodroiteo.php`
    const response = await axios.get(urldd)
        .catch(function (error) {
            return traiteAxiosError(error)
        })
        return response.data
}

export async function getUnitesOrgListe(jsonCriteres = '{}') {
    const g_pathurluniteorg = '/goeland/uniteorg/axios/'
    const urluol = `${g_devurl}${g_pathurluniteorg}uniteorg_liste.php`
    const params = new URLSearchParams([['jsoncriteres', jsonCriteres]])
    //return jsonCriteres
    const response = await axios.get(urluol, { params })
        .catch(function (error) {
            return traiteAxiosError(error)
        })    
    return response.data
}

export async function getEmployesListe(jsonCriteres) {
    const g_pathurlemploye = '/goeland/employe/axios/'
    const urlel = `${g_devurl}${g_pathurlemploye}employe_liste.php`
    const params = new URLSearchParams([['jsoncriteres', jsonCriteres]])
    //return jsonCriteres
    const response = await axios.get(urlel, { params })
        .catch(function (error) {
            return traiteAxiosError(error)
        })
    return response.data
}

function traiteAxiosError(error) {
    if (error.response) {
        return `${error.response.data}<br>${error.response.status}<br>${error.response.headers}`    
    } else if (error.request.responseText) {
        return error.request.responseText
    } else {
        return error.message
    }
}