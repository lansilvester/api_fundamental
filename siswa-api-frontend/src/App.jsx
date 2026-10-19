import { useCallback, useEffect, useRef, useState } from 'react'
import { authApi, siswaApi } from './api/siswa'
import LoginPage from './components/LoginPage'
import SiswaTable from './components/SiswaTable'
import SiswaForm from './components/SiswaForm'
import ConfirmDialog from './components/ConfirmDialog'
import Toast from './components/Toast'
import './App.css'

const SEARCH_DELAY = 400

function LoadingRows() {
  return (
    <tbody>
      {Array.from({ length: 5 }).map((_, index) => (
        <tr key={index} className="loading-rows">
          <td><div className="skeleton" /></td>
          <td><div className="skeleton" /></td>
          <td><div className="skeleton" style={{ width: '70%' }} /></td>
          <td><div className="skeleton" style={{ width: '55%' }} /></td>
          <td><div className="skeleton" style={{ width: '80%' }} /></td>
          <td><div className="skeleton" style={{ width: '90px' }} /></td>
        </tr>
      ))}
    </tbody>
  )
}

function EmptyState({ hasSearch }) {
  return (
    <div className="empty-state">
      <div className="empty-icon">📭</div>
      <h3>{hasSearch ? 'Tidak ada hasil pencarian' : 'Belum ada data siswa'}</h3>
      <p>
        {hasSearch
          ? 'Coba kata kunci lain.'
          : 'Klik "Tambah Siswa" untuk menambahkan data pertama.'}
      </p>
    </div>
  )
}

function App() {
  const [user, setUser] = useState(() => {
    const stored = localStorage.getItem('auth_user')
    return stored ? JSON.parse(stored) : null
  })
  const [siswaList, setSiswaList] = useState([])
  const [search, setSearch] = useState('')
  const [loading, setLoading] = useState(Boolean(localStorage.getItem('auth_token')))
  const [modalState, setModalState] = useState({ open: false, editing: null })
  const [deleteTarget, setDeleteTarget] = useState(null)
  const [deleting, setDeleting] = useState(false)
  const [toast, setToast] = useState(null)
  const searchTimer = useRef(null)

  const showToast = useCallback((message, type = 'success') => {
    setToast({ message, type })
  }, [])

  const fetchSiswa = useCallback(
    async (query = '') => {
      setLoading(true)
      try {
        const { data } = await siswaApi.list({ q: query || undefined })
        setSiswaList(data.data)
      } catch {
        showToast('Gagal memuat data siswa.', 'error')
      } finally {
        setLoading(false)
      }
    },
    [showToast],
  )

  // Muat data (initial atau saat pencarian) dengan debounce.
  useEffect(() => {
    if (!user) return undefined

    clearTimeout(searchTimer.current)
    searchTimer.current = setTimeout(() => {
      fetchSiswa(search)
    }, SEARCH_DELAY)

    return () => clearTimeout(searchTimer.current)
  }, [search, user, fetchSiswa])

  // Jika sudah terlogin dari localStorage, efek dihujung akan muat data otomatis.

  const handleLogin = (loggedInUser) => {
    setUser(loggedInUser)
    setLoading(true)
    showToast(`Selamat datang, ${loggedInUser.name}`)
  }

  const handleLogout = async () => {
    try {
      await authApi.logout()
    } catch {
      // Token mungkin sudah kedaluwarsa — tetap lanjut bersihkan sesi lokal.
    } finally {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')
      setUser(null)
      setSiswaList([])
    }
  }

  const handleSave = async (form) => {
    if (modalState.editing) {
      await siswaApi.update(modalState.editing.id, form)
      showToast(`Data ${form.nama} berhasil diperbarui.`)
    } else {
      await siswaApi.store(form)
      showToast(`Data ${form.nama} berhasil ditambahkan.`)
    }

    setModalState({ open: false, editing: null })
    fetchSiswa(search)
  }

  const handleDelete = async () => {
    if (!deleteTarget) return

    setDeleting(true)
    try {
      await siswaApi.remove(deleteTarget.id)
      showToast(`Data ${deleteTarget.nama} berhasil dihapus.`)
      setDeleteTarget(null)
      fetchSiswa(search)
    } catch {
      showToast('Gagal menghapus data.', 'error')
    } finally {
      setDeleting(false)
    }
  }

  if (!user) {
    return <LoginPage onLogin={handleLogin} />
  }

  const totalSiswa = siswaList.length

  return (
    <div className="app-shell">
      <Toast toast={toast} onClose={() => setToast(null)} />

      <header className="topbar">
        <div className="brand">
          <span className="brand-mark">🎓</span>
          <div>
            <strong>Panel Siswa</strong>
            <small>CRUD REST API · Demo</small>
          </div>
        </div>

        <div className="topbar-user">
          <span className="avatar">{user.name.charAt(0).toUpperCase()}</span>
          <span className="user-name">{user.name}</span>
          <button type="button" className="btn-ghost" onClick={handleLogout}>
            Keluar
          </button>
        </div>
      </header>

      <main className="content">
        <section className="hero-banner">
          <div>
            <h1>Data Siswa</h1>
            <p>Kelola data siswa sekolah melalui REST API — Laravel Sanctum + Axios.</p>
          </div>
          <div className="hero-stats">
            <span className="stat-number">{totalSiswa}</span>
            <span className="stat-label">siswa tampil</span>
          </div>
        </section>

        <section className="toolbar">
          <div className="search-box">
            <span className="search-icon">⌕</span>
            <input
              type="search"
              placeholder="Cari nama atau NIS…"
              value={search}
              onChange={(event) => setSearch(event.target.value)}
            />
            {search && (
              <button
                type="button"
                className="search-clear"
                onClick={() => setSearch('')}
                aria-label="Bersihkan pencarian"
              >
                ×
              </button>
            )}
          </div>

          <button
            type="button"
            className="btn-primary"
            onClick={() => setModalState({ open: true, editing: null })}
          >
            + Tambah Siswa
          </button>
        </section>

        <section className="table-card">
          {loading && !siswaList.length ? (
            <table>
              <thead>
                <tr>
                  <th>NIS</th>
                  <th>Nama</th>
                  <th>Kelas</th>
                  <th>Jurusan</th>
                  <th>No. Telp</th>
                  <th className="col-action">Aksi</th>
                </tr>
              </thead>
              <LoadingRows />
            </table>
          ) : siswaList.length ? (
            <SiswaTable
              siswaList={siswaList}
              onEdit={(siswa) => setModalState({ open: true, editing: siswa })}
              onDelete={(siswa) => setDeleteTarget(siswa)}
            />
          ) : (
            <EmptyState hasSearch={Boolean(search)} />
          )}
        </section>
      </main>

      {modalState.open && (
        <SiswaForm
          initialData={modalState.editing}
          onSubmit={handleSave}
          onCancel={() => setModalState({ open: false, editing: null })}
        />
      )}

      <ConfirmDialog
        siswa={deleteTarget}
        busy={deleting}
        onConfirm={handleDelete}
        onCancel={() => setDeleteTarget(null)}
      />
    </div>
  )
}

export default App
