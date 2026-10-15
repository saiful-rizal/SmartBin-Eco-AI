# SmartBin Eco-AI ♻️ (React + Vite, siap Vercel)

Dashboard monitoring sampah pintar — hasil migrasi dari Laravel Blade ke **React + Vite**
agar bisa di-hosting statis di **Vercel**.

Fitur sama persis dengan versi Laravel:
- Simulasi AI Sensor: pilih lokasi SmartBin + input berat sampah (gram)
- Poin = berat sampah (1 gram = 1 poin)
- Status kapasitas tiap tong + progress bar (hijau → kuning → merah, warning jika ≥90%)
- Total Impact: total poin & total sampah
- Reset data ke awal (Lobby Utama / Organik, Parkiran Area / Plastik, Cafetaria / Kertas)
- Data disimpan di `localStorage` browser (pengganti MySQL, agar cocok untuk hosting statis)

## Jalankan lokal

```bash
npm install
npm run dev
```

Buka http://localhost:5173

## Build

```bash
npm run build
npm run preview
```

## Deploy ke Vercel

Otomatis via GitHub:
1. Push repo ini ke GitHub
2. Di Vercel: New Project → Import repo ini
3. Framework: **Vite**, Build Command: `npm run build`, Output: `dist`
4. Deploy — file `vercel.json` sudah disiapkan untuk SPA routing.

Atau via CLI:

```bash
npx vercel --prod
```

## Struktur

- `index.html` — entry Vite + Tailwind CDN + font Inter
- `src/main.jsx` — bootstrap React
- `src/App.jsx` — seluruh dashboard (state + localStorage)
- `src/index.css` — style dasar + glass-card
- `vercel.json` — config deploy Vercel
- Folder `app/`, `routes/`, `database/`, `resources/views/` = kode Laravel lama (arsip, tidak dipakai build React).

## Catatan migrasi Laravel → React

| Laravel | React |
|---|---|
| `DashboardController@index` (`Bin::all()`) | `useState(loadBins)` + `localStorage` |
| `addTrash` (tambah berat + poin) | `handleSubmit` di `App.jsx` |
| `reset` (truncate + seed 3 bin) | `handleReset` (reset ke `initialBins`) |
| `dashboard.blade.php` | `src/App.jsx` (JSX, Tailwind sama) |
| MySQL `bins` table | `localStorage key: smartbin-bins-v1` |
