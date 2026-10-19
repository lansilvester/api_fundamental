function SiswaTable({ siswaList, onEdit, onDelete }) {
  return (
    <div className="table-wrap">
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
        <tbody>
          {siswaList.map((siswa) => (
            <tr key={siswa.id}>
              <td>{siswa.nis}</td>
              <td>
                <strong>{siswa.nama}</strong>
              </td>
              <td>
                <span className="badge badge-kelas">{siswa.kelas}</span>
              </td>
              <td>{siswa.jurusan}</td>
              <td>{siswa.no_telp || '—'}</td>
              <td className="col-action">
                <button className="btn-edit" onClick={() => onEdit(siswa)}>
                  Edit
                </button>
                <button className="btn-delete" onClick={() => onDelete(siswa)}>
                  Hapus
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}

export default SiswaTable
