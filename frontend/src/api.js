const API_BASE_URL = import.meta.env.VITE_API_URL || "http://localhost:4000";

async function request(path, options = {}) {
  const response = await fetch(`${API_BASE_URL}${path}`, {
    headers: { "Content-Type": "application/json", ...(options.headers || {}) },
    ...options,
  });

  if (!response.ok) {
    const payload = await response.json().catch(() => ({}));
    throw new Error(payload.error || `Request failed: ${response.status}`);
  }

  return response.json();
}

export const api = {
  health: () => request("/health"),
  listOffices: () => request("/api/exchanges"),
  getOffice: (id) => request(`/api/exchanges/${id}`),
  getOfficeRates: (id) => request(`/api/offices/${id}/rates`),
  addOfficeRate: (id, body) =>
    request(`/api/offices/${id}/rates`, { method: "POST", body: JSON.stringify(body) }),
};
