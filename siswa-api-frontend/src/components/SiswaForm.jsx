import { useState } from 'react'

const emptyForm = {
  nis: '',
  nama: '',
  kelas: '',
  jurusan: '',
  alamat: '',
  no_telp: '',
}

function SiswaForm({ initialData, onSubmit, onCancel }) {
  // Modal sempre viene smontato/rimontato quando cambia la modalità,
  // quindi `initialData` non cambia mai mentre il form è visibile.
  const [form, setForm] = useState(() =>
    initialData
      ? {
          nis: initialData.nis,
          nama: initialData.nama,
          kelas: initialData.kelas,
          jurusan: initialData.jurusan,
          alamat: initialData.alamat ?? '',
          no_telp: initialData.no_telp ?? '',
        }
      : { ...emptyForm },
  )
  const [error, setError] = useState('')

  const handleChange = (event) => {
    setForm({ ...form, [event.target.name]: event.target.value })
  }

  const handleSubmit = async (event) => {
    event.preventDefault()
    setError('')

    try {
      await onSubmit(form)
    } catch (err) {
      const errors = err.response?.data?.errors
      setError(errors ? Object.values(errors).flat().join(' ') : 'Gagal menyimpan data.')
    }
  }

  return (
    <div className="modal-backdrop">
      <form className="siswa-form" onSubmit={handleSubmit}>
        <div className="form-head">
          <h2>{initialData ? 'Edit Siswa' : 'Tambah Siswa'}</h2>
          <button type="button" className="btn-close" onClick={onCancel}>
            ×
          </button>
        </div>

        <div className="form-grid">
          <label>
            NIS
            <input
              type="text"
              name="nis"
              value={form.nis}
              onChange={handleChange}
              placeholder="cth: 2024011"
              required
            />
          </label>

          <label>
            Nama Lengkap
            <input
              type="text"
              name="nama"
              value={form.nama}
              onChange={handleChange}
              placeholder="Nama siswa"
              required
            />
          </label>

          <label>
            Kelas
            <input
              type="text"
              name="kelas"
              value={form.kelas}
              onChange={handleChange}
              placeholder="cth: XI-A"
              required
            />
          </label>

          <label>
            Jurusan
            <select
              name="jurusan"
              value={form.jurusan}
              onChange={handleChange}
              required
            >
              <option value="">— Pilih jurusan —</option>
              <option value="RPL">RPL</option>
              <option value="TKJ">TKJ</option>
              <option value="MM">MM</option>
            </select>
          </label>

          <label>
            No. Telepon
            <input
              type="text"
              name="no_telp"
              value={form.no_telp}
              onChange={handleChange}
              placeholder="cth: 0812xxxx"
            />
          </label>

          <label className="span-2">
            Alamat
            <textarea
              name="alamat"
              value={form.alamat}
              onChange={handleChange}
              rows="2"
              placeholder="Alamat tempat tinggal"
            />
          </label>
        </div>

        {error && <p className="form-error">{error}</p>}

        <div className="form-actions">
          <button type="button" className="btn-secondary" onClick={onCancel}>
            Batal
          </button>
          <button type="submit" className="btn-primary">
            {initialData ? 'Simpan Perubahan' : 'Tambah Siswa'}
          </button>
        </div>
      </form>
    </div>
  )
}

export default SiswaForm
