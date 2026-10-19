import axios from 'axios'

/**
 * Axios instance khusus untuk memanggil backend Laravel.
 * Semua request otomatis memakai base URL + JSON.
 */
export const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api/v1',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

/**
 * Interceptor: sisipkan token Bearer ke setiap request secara otomatis.
 */
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token')

  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
})

/**
 * Interceptor: saat respons 401, hapus token dan paksa kembali ke login.
 */
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')
    }

    return Promise.reject(error)
  },
)
