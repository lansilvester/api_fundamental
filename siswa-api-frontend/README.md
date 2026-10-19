# Siswa API — Frontend (React + Vite + Axios)

Dashboard CRUD Siswa per il backend Laravel Sanctum (`siswa-api-backend/`).
UI in lingua indonesiana, auth Bearer token via `localStorage`, no stateful cookie.

## Avvio

1. Avvia il backend prima (porta 8000):

   ```bash
   cd ../siswa-api-backend
   php artisan migrate:fresh --seed
   php artisan serve --host=127.0.0.1 --port=8000
   ```

2. Avvia il frontend (porta 5173):

   ```bash
   npm install
   npm run dev
   ```

3. Apri `http://localhost:5173` e fai login con le credenziali demo:
   - **Email:** `admin@sekolah.test`
   - **Password:** `password`

## Struttura

```
src/
├── main.jsx               # Entry point
├── App.jsx               # Stato login, lista, ricerca, modale, toast
├── App.css               # Design system (tema "pro")
├── index.css             # Reset minimale
├── api/
│   ├── client.js         # Istanza axios + interceptor Bearer/401
│   └── siswa.js          # authApi + siswaApi (chiamate REST)
└── components/
    ├── LoginPage.jsx     # Form login (precompilato per demo)
    ├── SiswaTable.jsx    # Tabella dati
    ├── SiswaForm.jsx     # Modal create/edit
    ├── ConfirmDialog.jsx # Conferma eliminazione
    └── Toast.jsx         # Notifiche
```

## Endpoint usati

| Metodo | URL | Uso |
| ------ | --- | --- |
| POST   | `/api/v1/login`  | Login (restituisce token Sanctum) |
| POST   | `/api/v1/logout` | Revoca token |
| GET    | `/api/v1/siswa?q=` | Lista + ricerca (paginated) |
| POST   | `/api/v1/siswa`  | Crea siswa |
| PUT    | `/api/v1/siswa/{id}` | Aggiorna siswa |
| DELETE | `/api/v1/siswa/{id}` | Elimina siswa |

## Script utili

```bash
npm run dev    # server di sviluppo con HMR
npm run build  # build di produzione in dist/
npm run lint   # oxlint
```
