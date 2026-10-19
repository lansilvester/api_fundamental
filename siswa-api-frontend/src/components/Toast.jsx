import { useEffect } from 'react'

function Toast({ toast, onClose }) {
  useEffect(() => {
    if (!toast) return undefined

    const timer = setTimeout(onClose, 3500)
    return () => clearTimeout(timer)
  }, [toast, onClose])

  if (!toast) return null

  return (
    <div className={`toast toast-${toast.type}`} role="status">
      <span className="toast-icon">{toast.type === 'success' ? '✓' : '!'}</span>
      <span>{toast.message}</span>
      <button type="button" className="toast-close" onClick={onClose} aria-label="Tutup">
        ×
      </button>
    </div>
  )
}

export default Toast
