import React, { useState, useEffect } from 'react'
import api from '../services/api'

const DashboardWorker = () => {
  const [worker, setWorker] = useState(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    // For demo, fetch first worker. In production, use authenticated worker ID
    fetchWorkerData()
  }, [])

  const fetchWorkerData = async () => {
    try {
      setLoading(true)
      const response = await api.getWorkers({ per_page: 1 })
      // Handle paginated response: { success: true, data: { data: [...], ... } }
      const workersList = response.data?.data || (Array.isArray(response.data) ? response.data : [])
      if (workersList.length > 0) {
        const workerId = workersList[0].id
        const workerResponse = await api.getWorker(workerId)
        // Handle single worker response: { success: true, data: {...} }
        setWorker(workerResponse.data?.data || workerResponse.data)
      }
    } catch (error) {
      console.error('Error fetching worker data:', error)
    } finally {
      setLoading(false)
    }
  }

  const stats = worker
    ? [
        {
          label: 'Services',
          value: `${worker.services?.length || 0}`,
          detail: 'Services offered',
          accent: 'text-sky-600',
        },
        {
          label: 'Email',
          value: worker.email ? worker.email.substring(0, 15) + '...' : 'N/A',
          detail: 'Contact email',
          accent: 'text-emerald-600',
        },
        {
          label: 'Phone',
          value: worker.phone || 'N/A',
          detail: 'Contact phone',
          accent: 'text-amber-500',
        },
      ]
    : [
        { label: 'Services', value: '0', detail: 'Loading...', accent: 'text-sky-600' },
        { label: 'Email', value: 'Loading...', detail: 'Loading...', accent: 'text-emerald-600' },
        { label: 'Phone', value: 'Loading...', detail: 'Loading...', accent: 'text-amber-500' },
      ]

  return (
    <div className="min-h-screen bg-[#f7f2f0] text-slate-800">
      <header className="max-w-6xl mx-auto mt-8 flex items-center justify-between gap-6 rounded-full border border-slate-100 bg-white/70 px-6 py-3 shadow-sm backdrop-blur">
        <div className="flex items-center gap-3">
          <div className="h-11 w-11 rounded-full bg-gradient-to-br from-indigo-100 via-sky-100 to-emerald-50 shadow-inner" />
          <div>
            <p className="text-lg font-semibold tracking-tight text-slate-900">Fix Your Home</p>
            <p className="text-xs text-slate-500">Worker dashboard</p>
          </div>
        </div>

        <nav className="flex items-center gap-3">
          {['Home', 'Services', 'History'].map((item) => (
            <button
              key={item}
              className="rounded-full border border-transparent bg-sky-50 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-sky-100"
            >
              {item}
            </button>
          ))}
        </nav>

        <div className="flex items-center gap-3">
          <div className="h-11 w-11 overflow-hidden rounded-full border border-slate-200 bg-gradient-to-br from-amber-100 via-orange-100 to-pink-100 shadow-inner">
            <div className="flex h-full w-full items-center justify-center text-base font-semibold text-slate-700">
              {loading ? '...' : (worker?.name?.[0] || 'W').toUpperCase()}
            </div>
          </div>
          <div>
            <p className="text-sm font-semibold text-slate-900">
              {loading ? 'Loading...' : worker?.name || 'Worker'}
            </p>
            <p className="text-xs text-slate-500">
              {worker?.services?.[0]?.name || 'House Help'}
            </p>
          </div>
        </div>
      </header>

      <main className="max-w-6xl mx-auto mt-10 space-y-8 px-4 pb-12">
        <section className="relative overflow-hidden rounded-3xl border border-slate-100 bg-white px-8 py-10 shadow-lg">
          <div className="absolute inset-x-0 top-0 h-2 bg-gradient-to-r from-sky-200 via-teal-100 to-amber-100" />

          <div className="flex flex-col items-start gap-10 lg:flex-row lg:items-center">
            <div className="relative flex-1 space-y-6">
              <div className="absolute -left-6 -top-6 h-20 w-20 rounded-full bg-sky-100/70 blur-2xl" />
              <h1 className="text-4xl font-extrabold leading-tight tracking-tight text-[#6da7a7] sm:text-5xl">
                Hey! I am {loading ? '...' : worker?.name || 'Worker'}
              </h1>
              <p className="max-w-2xl text-lg leading-relaxed text-slate-600">
                {worker?.services && worker.services.length > 0
                  ? `I specialize in ${worker.services.map(s => s.name).join(', ')}. I'm known for being punctual, respectful, and detail-oriented.`
                  : 'I am a professional worker ready to help with your needs.'}
              </p>
            </div>

            <div className="relative flex-1">
              <div className="absolute -inset-6 rounded-3xl border-2 border-sky-200/70" />
              <div className="overflow-hidden rounded-3xl bg-sky-50 p-4 shadow-inner">
                <img
                  src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=720&q=80"
                  alt="Worker illustration"
                  className="h-full w-full rounded-2xl object-cover"
                  loading="lazy"
                />
              </div>
            </div>
          </div>
        </section>

        <section className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {stats.map((stat) => (
            <div
              key={stat.label}
              className="relative overflow-hidden rounded-2xl border border-slate-100 bg-white px-6 py-5 shadow-md"
            >
              <span className="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                {stat.label}
              </span>
              <p className={`mt-4 text-3xl font-bold ${stat.accent}`}>{stat.value}</p>
              <p className="mt-2 text-sm text-slate-500">{stat.detail}</p>
              <div className="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-sky-100 via-teal-100 to-amber-100" />
            </div>
          ))}
        </section>
      </main>
    </div>
  )
}

export default DashboardWorker
