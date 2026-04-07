import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    withCredentials: true,
});

// Attach CSRF token from meta tag on every request
api.interceptors.request.use((config) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) config.headers['X-CSRF-TOKEN'] = token;
    return config;
});

// Normalise errors — always resolve with { data } or reject with { message, errors }
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 419) {
            // CSRF expired — reload to get fresh token
            window.location.reload();
            return Promise.reject(error);
        }
        if (error.response?.status === 401 || error.response?.status === 403) {
            window.location.href = '/exchange/login';
            return Promise.reject(error);
        }
        return Promise.reject(error);
    }
);

export default api;

// ── Rates ────────────────────────────────────────────────────────────────────
export const ratesApi = {
    list:   (officeId)       => api.get(`/offices/${officeId}/rates`),
    store:  (officeId, data) => api.post(`/offices/${officeId}/rates`, data),
    update: (rateId, data)   => api.put(`/rates/${rateId}`, data),
    remove: (rateId)         => api.delete(`/rates/${rateId}`),
};

// ── Special rate ─────────────────────────────────────────────────────────────
export const specialRateApi = {
    update: (officeId, data) => api.put(`/offices/${officeId}/special-rate`, data),
    clear:  (officeId)       => api.delete(`/offices/${officeId}/special-rate`),
};

// ── Payment methods ───────────────────────────────────────────────────────────
export const paymentMethodsApi = {
    update: (officeId, data) => api.put(`/offices/${officeId}/payment-methods`, data),
};

// ── Transfer fee ──────────────────────────────────────────────────────────────
export const transferFeeApi = {
    update: (officeId, data) => api.put(`/offices/${officeId}/transfer-fee`, data),
};

// ── Dashboard stats ───────────────────────────────────────────────────────────
export const dashboardApi = {
    stats: () => api.get('/dashboard/stats'),
};
