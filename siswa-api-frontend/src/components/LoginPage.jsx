import { useState } from 'react'
import { authApi } from '../api/siswa'

function LoginPage({ onLogin }) {
  const [form, setForm] = useState({
    email: 'admin@sekolah.test',
    password: 'password',
  })
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')

  const handleChange = (event) => {
    setForm({ ...form, [event.target.name]: event.target.value })
  }

  const handleSubmit = async (event) => {
    event.preventDefault()
    setLoading(true)
    setError('')

    try {
      const { data } = await authApi.login(form)

      localStorage.setItem('auth_token', data.token)
      localStorage.setItem('auth_user', JSON.stringify(data.user))
      onLogin(data.user)
    } catch (err) {
      setError(err.response?.data?.message ?? 'Login gagal. Coba lagi.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="auth-page">
      <div className="auth-card">
        <div className="auth-head">
          <span className="auth-badge">Demo REST API</span>
          <h1>Masuk ke Panel Siswa</h1>
          <p>Laravel Sanctum · React + Axios</p>
        </div>

        <form onSubmit={handleSubmit}>
          <label className="form-field">
            <span>Email</span>
            <input
              type="email"
              name="email"
              value={form.email}
              onChange={handleChange}
              autoComplete="email"
              required
            />
          </label>

          <label className="form-field">
            <span>Password</span>
            <input
              type="password"
              name="password"
              value={form.password}
              onChange={handleChange}
              autoComplete="current-password"
              required
            />
          </label>

          {error && (
            <p className="form-error" role="alert">
              {error}
            </p>
          )}

          <button type="submit" className="btn-primary" disabled={loading}>
            {loading ? 'Memproses…' : 'Login'}
          </button>
        </form>

        <div className="auth-note">
          <strong>Akun demo:</strong>
          <br />
          <code>admin@sekolah.test</code> · <code>password</code>
        </div>
      </div>
    </div>
  )
}

export default LoginPage
