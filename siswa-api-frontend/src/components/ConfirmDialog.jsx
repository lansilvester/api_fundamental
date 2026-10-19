function ConfirmDialog({ siswa, busy, onConfirm, onCancel }) {
  if (!siswa) return null

  return (
    <div className="modal-backdrop" role="dialog" aria-modal="true" aria-label="Konfirmasi hapus">
      <div className="confirm-dialog">
        <div className="confirm-icon">🗑️</div>
        <h2>Hapus data siswa?</h2>
        <p>
          Data <strong>{siswa.nama}</strong> (NIS {siswa.nis}) akan dihapus
          permanen. Tindakan ini tidak dapat dibatalkan.
        </p>
        <div className="form-actions">
          <button type="button" className="btn-secondary" onClick={onCancel} disabled={busy}>
            Batal
          </button>
          <button type="button" className="btn-danger" onClick={onConfirm} disabled={busy}>
            {busy ? 'Menghapus…' : 'Ya, Hapus'}
          </button>
        </div>
      </div>
    </div>
  )
}

export default ConfirmDialog
