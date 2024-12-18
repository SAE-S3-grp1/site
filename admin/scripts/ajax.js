/**
 * The base URL for the server API.
 * @constant {string}
 */
const SERVER_API_URL = '/api';

/**
 * Effectue une requête AJAX avec la méthode spécifiée.
 * @param {string} endpoint - L'endpoint de la requête.
 * @param {string} method - La méthode HTTP (GET, POST, PUT, PATCH, DELETE).
 * @param {Object} data - Les données à envoyer (pour POST, PUT).
 * @param {Object} headers - Les en-têtes HTTP supplémentaires (facultatif).
 * @returns {Promise} - Résout avec les données de la réponse ou rejette avec une erreur.
 */
async function request(endpoint, method = 'GET', data = null, headers = {}) {
    
    // Create url
    if (!endpoint.startsWith('/'))
        endpoint = endpoint + '/';
    const url = SERVER_API_URL + endpoint;
    
    // Fetch
    try {
        // Configuration de l'option de la requête
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
                ...headers
            }
        };

        // Ajouter le corps de la requête pour POST, PUT et PATCH
        if (data) {
            options.body = JSON.stringify(data);
        }

        // Pour les PATCH (fichiers) mettre le Content-Type à multipart/form-data
        if (data instanceof FormData) {
            options.headers['Content-Type'] = 'multipart/form-data';
        }

        // Fetch data
        const response = await fetch(url, options);

        // Récupérer et retourner le résultat en JSON
        const text = await response.text();
        const json = text ? JSON.parse(text) : null;

        // Vérification de la réponse
        if (!response.ok) {
            if (json && json.message)
                throw new Error(json.message);
            else
                throw new Error(`Erreur: ${response.status} ${response.statusText}`);
        }

        return json;

    } catch (error) {
        throw error;
    }
}

/**
 * Effectue une requête GET.
 * @param {string} endpoint - L'endpoint de la requête.
 * @returns {Promise}
 */
function requestGET(endpoint) {
    return request(endpoint, 'GET');
}

/**
 * Effectue une requête POST.
 * @param {string} endpoint - L'endpoint de la requête.
 * @param {Object} data - Les données à envoyer.
 * @returns {Promise}
 */
function requestPOST(endpoint, data) {
    return request(endpoint, 'POST', data);
}


/**
 * Effectue une requête PUT.
 * @param {string} endpoint - L'endpoint de la requête.
 * @param {Object} data - Les données à envoyer.
 * @returns {Promise}
 */
function requestPUT(endpoint, data) {
    return request(endpoint, 'PUT', data);
}

/**
 * Effectue une requête PATCH.
 * @param {string} endpoint - L'endpoint de la requête.
 * @param {Object} data - Les données à envoyer.
 * @returns {Promise}
 */
function requestPATCH(endpoint, data) {
    return request(endpoint, 'PATCH', data);
}

/**
 * Effectue une requête DELETE.
 * @param {string} endpoint - L'endpoint de la requête.
 * @returns {Promise}
 */
function requestDELETE(endpoint) {
    return request(endpoint, 'DELETE');
}

// Export des fonctions
export { request, requestGET, requestPOST, requestPUT, requestPATCH, requestDELETE };
