import { api } from './client'

/**
 * Kumpulan fungsi pemanggilan API CRUD Siswa.
 * Dipisah agar komponen UI tetap bersih dan mudah dibaca.
 */
export const siswaApi = {
  list: (params) => api.get('/siswa', { params }),
  show: (id) => api.get(`/siswa/${id}`),
  store: (payload) => api.post('/siswa', payload),
  update: (id, payload) => api.put(`/siswa/${id}`, payload),
  remove: (id) => api.delete(`/siswa/${id}`),
}

export const authApi = {
  login: (payload) => api.post('/login', payload),
  logout: () => api.post('/logout'),
  me: () => api.get('/me'),
}
