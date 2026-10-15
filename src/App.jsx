import { useEffect, useMemo, useState } from 'react';

const STORAGE_KEY = 'smartbin-bins-v1';

const initialBins = [
  { id: 1, name: 'Lobby Utama', type: 'Organik', current_weight: 0, max_capacity: 5000, total_points: 0 },
  { id: 2, name: 'Parkiran Area', type: 'Plastik', current_weight: 0, max_capacity: 5000, total_points: 0 },
  { id: 3, name: 'Cafetaria', type: 'Kertas', current_weight: 0, max_capacity: 5000, total_points: 0 },
];

function loadBins() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) return initialBins;
    const parsed = JSON.parse(raw);
    if (!Array.isArray(parsed) || parsed.length === 0) return initialBins;
    return parsed;
  } catch {
    return initialBins;
  }
}

function binColor(percentage) {
  if (percentage >= 90) return 'bg-red-500';
  if (percentage > 50) return 'bg-yellow-500';
  return 'bg-emerald-500';
}

export default function App() {
  const [bins, setBins] = useState(loadBins);
  const [binId, setBinId] = useState(1);
  const [weight, setWeight] = useState('');
  const [notif, setNotif] = useState('');

  useEffect(() => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(bins));
    if (bins.length > 0 && !bins.find((b) => b.id === Number(binId))) {
      setBinId(bins[0].id);
    }
  }, [bins]);

  const totalPoints = useMemo(() => bins.reduce((s, b) => s + (b.total_points || 0), 0), [bins]);
  const totalWeight = useMemo(() => bins.reduce((s, b) => s + (b.current_weight || 0), 0), [bins]);

  const handleSubmit = (e) => {
    e.preventDefault();
    const w = parseInt(weight, 10);
    if (!w || w < 1) {
      setNotif('Berat sampah minimal 1 gram.');
      return;
    }
    setBins((prev) =>
      prev.map((b) =>
        b.id === Number(binId)
          ? { ...b, current_weight: b.current_weight + w, total_points: b.total_points + w }
          : b
      )
    );
    setNotif(`Berhasil membuang sampah! Kamu dapat +${w} poin.`);
    setWeight('');
    setTimeout(() => setNotif(''), 4000);
  };

  const handleReset = () => {
    setBins(initialBins);
    setBinId(1);
    setWeight('');
    setNotif('Data berhasil di-reset ke awal.');
    setTimeout(() => setNotif(''), 3000);
  };

  return (
    <div className="text-gray-800 min-h-screen">
      {/* Navbar */}
      <nav className="bg-emerald-600 shadow-lg">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-16">
            <div className="flex items-center gap-3">
              <div className="bg-white p-2 rounded-full text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </div>
              <span className="text-white font-bold text-xl tracking-wide">
                SmartBin <span className="text-emerald-200">Eco-AI</span>
              </span>
            </div>
            <div className="flex items-center gap-4">
              <span className="hidden sm:inline text-emerald-100 text-sm">React • Vercel Ready</span>
              <button onClick={handleReset} className="text-emerald-100 hover:text-white text-sm font-medium transition">
                Reset Data
              </button>
            </div>
          </div>
        </div>
      </nav>

      {/* Main */}
      <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {notif && (
          <div className="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
            <p className="font-bold">Sukses!</p>
            <p>{notif}</p>
          </div>
        )}

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Kiri: Simulasi */}
          <div className="lg:col-span-1 space-y-6">
            <div className="glass-card rounded-2xl shadow-xl p-6">
              <h2 className="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span className="bg-blue-100 text-blue-600 p-1 rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fillRule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clipRule="evenodd" />
                  </svg>
                </span>
                Simulasi AI Sensor
              </h2>
              <p className="text-sm text-gray-500 mb-6">
                Masukkan data seolah-olah sensor AI mendeteksi sampah yang masuk.
              </p>

              <form onSubmit={handleSubmit} className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Pilih Lokasi SmartBin
                  </label>
                  <select
                    value={binId}
                    onChange={(e) => setBinId(Number(e.target.value))}
                    className="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5 transition"
                  >
                    {bins.map((bin) => (
                      <option key={bin.id} value={bin.id}>
                        {bin.name} ({bin.type})
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Berat Sampah (Gram)
                  </label>
                  <input
                    type="number"
                    min="1"
                    value={weight}
                    onChange={(e) => setWeight(e.target.value)}
                    placeholder="Contoh: 250"
                    className="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5 transition"
                    required
                  />
                </div>

                <button
                  type="submit"
                  className="w-full text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition shadow-md transform hover:-translate-y-0.5"
                >
                  Proses Data Sampah
                </button>
              </form>
            </div>

            <div className="glass-card rounded-2xl shadow-xl p-6 bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
              <h3 className="text-lg font-semibold mb-2">Total Impact</h3>
              <div className="flex justify-between items-end">
                <div>
                  <p className="text-sm opacity-80">Poin Terkumpul</p>
                  <p className="text-3xl font-bold">{totalPoints}</p>
                </div>
                <div className="text-right">
                  <p className="text-sm opacity-80">Total Sampah</p>
                  <p className="text-xl font-bold">{totalWeight}g</p>
                </div>
              </div>
            </div>

            <p className="text-xs text-gray-400 leading-relaxed">
              Migrasi dari Laravel Blade ke React. Data disimpan di localStorage browser
              (pengganti database MySQL) agar bisa di-hosting statis di Vercel.
            </p>
          </div>

          {/* Kanan: Status */}
          <div className="lg:col-span-2">
            <h2 className="text-2xl font-bold text-gray-800 mb-6">Status Tong Sampah</h2>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              {bins.length === 0 && (
                <div className="col-span-2 text-center py-10 bg-white rounded-xl shadow-sm">
                  <p className="text-gray-500">
                    Belum ada data SmartBin.{' '}
                    <button onClick={handleReset} className="text-emerald-600 font-bold hover:underline">
                      Klik Reset untuk inisialisasi awal.
                    </button>
                  </p>
                </div>
              )}

              {bins.map((bin) => {
                const percentage = bin.max_capacity > 0 ? (bin.current_weight / bin.max_capacity) * 100 : 0;
                const isFull = percentage >= 90;
                const barColor = binColor(percentage);
                return (
                  <div
                    key={bin.id}
                    className={`bg-white rounded-2xl shadow-md hover:shadow-lg transition duration-300 p-6 border-t-4 ${isFull ? 'border-red-500' : 'border-emerald-500'}`}
                  >
                    <div className="flex justify-between items-start mb-4">
                      <div>
                        <h3 className="font-bold text-lg text-gray-800">{bin.name}</h3>
                        <span className="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full mt-1 capitalize">
                          {bin.type}
                        </span>
                      </div>
                      {isFull && (
                        <div className="bg-red-100 text-red-600 p-2 rounded-full animate-pulse">
                          <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                          </svg>
                        </div>
                      )}
                    </div>

                    <div className="mb-4">
                      <div className="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Kapasitas</span>
                        <span>{percentage.toFixed(1)}%</span>
                      </div>
                      <div className="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                        <div
                          className={`${barColor} h-2.5 rounded-full transition-all duration-1000 ease-out`}
                          style={{ width: `${Math.min(percentage, 100)}%` }}
                        ></div>
                      </div>
                    </div>

                    <div className="grid grid-cols-2 gap-4 mt-6 pt-4 border-t border-gray-100">
                      <div className="text-center">
                        <p className="text-xs text-gray-400">Berat Saat Ini</p>
                        <p className="font-semibold text-gray-700">
                          {bin.current_weight} / {bin.max_capacity} g
                        </p>
                      </div>
                      <div className="text-center border-l border-gray-100">
                        <p className="text-xs text-gray-400">Poin Lokasi</p>
                        <p className="font-semibold text-emerald-600">{bin.total_points} pts</p>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </main>
    </div>
  );
}
