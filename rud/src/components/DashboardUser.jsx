import React, { useState, useEffect } from 'react'
import api from '../services/api'

const DashboardUser = () => {
  const [jobRequests, setJobRequests] = useState([])
  const [workers, setWorkers] = useState([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState('')
  const [selectedWorker, setSelectedWorker] = useState(null)
  const [showWorkerProfile, setShowWorkerProfile] = useState(false)

  useEffect(() => {
    fetchData()
  }, [])

  const fetchData = async () => {
    try {
      setLoading(true)
      
      // Fetch workers - handle errors gracefully
      let workersData = []
      try {
        const workersResponse = await api.getWorkers({ per_page: 10 })
        // Handle paginated responses - Laravel returns { success: true, data: { data: [...], ... } }
        workersData = workersResponse.data?.data || (Array.isArray(workersResponse.data) ? workersResponse.data : [])
      } catch (error) {
        console.error('Error fetching workers:', error)
      }
      
      // Fetch job requests - handle errors gracefully (may not exist in simplified schema)
      let jobRequestsData = []
      try {
        const requestsResponse = await api.getJobRequests({ per_page: 5 })
        jobRequestsData = requestsResponse.data?.data || (Array.isArray(requestsResponse.data) ? requestsResponse.data : [])
      } catch (error) {
        console.error('Error fetching job requests:', error)
        // Job requests may not exist in simplified schema, so this is expected
      }
      
      setWorkers(workersData)
      setJobRequests(jobRequestsData)
    } catch (error) {
      console.error('Error fetching data:', error)
    } finally {
      setLoading(false)
    }
  }

  const quickActions = [
    { title: 'Request Work', desc: 'Post a new task with budget & timing', action: 'Create Request' },
    { title: 'Find Worker', desc: 'Search vetted workers by skill & rating', action: 'Search Now' },
    { title: 'Urgent Help', desc: 'Nearest available worker quickly', action: 'Request Urgent' },
  ]

  const formatDate = (dateString) => {
    if (!dateString) return 'Not scheduled'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' })
  }

  const formatPrice = (price) => {
    return price ? `$${parseFloat(price).toFixed(2)}` : 'N/A'
  }

  return (
    <div className="min-h-screen bg-gradient-to-b from-[#f7f2f0] via-white to-[#f0f7f9] text-slate-800">
      <header className="max-w-6xl mx-auto mt-8 flex flex-col gap-4 rounded-3xl border border-slate-100 bg-white/80 px-6 py-4 shadow-md backdrop-blur sm:flex-row sm:items-center sm:justify-between">
        <div>
          <p className="text-xs font-semibold uppercase tracking-[0.2em] text-sky-500">Welcome back</p>
          <h1 className="text-2xl font-bold text-slate-900">User Dashboard</h1>
          <p className="text-sm text-slate-500">Manage requests, browse workers, track progress.</p>
        </div>
        <div className="flex w-full flex-wrap items-center gap-3 sm:w-auto">
          <input
            type="search"
            placeholder="Search workers or services..."
            className="w-full min-w-[240px] rounded-full border border-slate-200 px-4 py-2 text-sm shadow-inner focus:border-sky-300 focus:outline-none sm:w-64"
          />
          <button className="rounded-full bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-sky-600">
            Request Work
          </button>
        </div>
      </header>

      <main className="max-w-6xl mx-auto mt-8 space-y-8 px-4 pb-12">
        <section className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {quickActions.map((item) => (
            <div
              key={item.title}
              className="flex h-full flex-col justify-between rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
            >
              <div className="space-y-2">
                <p className="text-sm font-semibold text-slate-500">{item.title}</p>
                <p className="text-sm text-slate-600">{item.desc}</p>
              </div>
              <button className="mt-4 w-fit rounded-full bg-sky-50 px-4 py-2 text-xs font-semibold text-sky-700 transition hover:bg-sky-100">
                {item.action}
              </button>
            </div>
          ))}
        </section>

        <section className="grid gap-6 lg:grid-cols-3">
          <div className="lg:col-span-2">
            <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
              <h2 className="text-lg font-semibold text-slate-900">Recent Requests</h2>
              <button className="text-sm font-semibold text-sky-600 hover:underline">View all</button>
            </div>
            <div className="mt-4 space-y-4">
              {loading ? (
                <div className="text-center py-8 text-slate-500">Loading...</div>
              ) : jobRequests.length === 0 ? (
                <div className="text-center py-8 text-slate-500">No job requests found</div>
              ) : (
                jobRequests.map((req) => (
                  <div
                    key={req.id}
                    className="flex flex-col gap-3 rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-sm sm:flex-row sm:items-center sm:justify-between"
                  >
                    <div>
                      <p className="text-sm font-semibold text-slate-900">{req.title}</p>
                      <p className="text-xs text-slate-500">
                        {req.scheduled_at ? formatDate(req.scheduled_at) : 'Not scheduled'}
                      </p>
                    </div>
                    <div className="flex flex-wrap items-center gap-3">
                      <span className="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 capitalize">
                        {req.status}
                      </span>
                      <span className="text-sm font-semibold text-slate-800">
                        {formatPrice(req.final_price || req.budget)}
                      </span>
                      <button className="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-700 hover:border-sky-200 hover:text-sky-700">
                        Details
                      </button>
                    </div>
                  </div>
                ))
              )}
            </div>
          </div>

          <div className="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <h2 className="text-lg font-semibold text-slate-900">Recommended Workers</h2>
            <p className="text-xs text-slate-500">Based on your recent requests</p>
            <div className="mt-4 space-y-3">
              {loading ? (
                <div className="text-center py-4 text-slate-500 text-sm">Loading...</div>
              ) : workers.length === 0 ? (
                <div className="text-center py-4 text-slate-500 text-sm">No workers found</div>
              ) : (
                workers.map((worker) => (
                  <div
                    key={worker.id}
                    onClick={() => {
                      setSelectedWorker(worker)
                      setShowWorkerProfile(true)
                    }}
                    className="flex cursor-pointer items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-3 py-2 transition hover:border-sky-300 hover:bg-sky-50"
                  >
                    <div>
                      <p className="text-sm font-semibold text-slate-900">{worker.name || 'Unknown'}</p>
                      <p className="text-xs text-slate-500">
                        {worker.services?.slice(0, 2).map(s => s.name).join(', ') || 'Worker'}
                      </p>
                    </div>
                    <div className="text-right">
                      <p className="text-xs text-slate-500">{worker.services?.length || 0} services</p>
                    </div>
                  </div>
                ))
              )}
            </div>
            <button className="mt-4 w-full rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-600">
              Book a worker
            </button>
          </div>
        </section>

        {/* Worker Profile Modal */}
        {showWorkerProfile && selectedWorker && (
          <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" onClick={() => setShowWorkerProfile(false)}>
            <div className="w-full max-w-2xl rounded-2xl border border-slate-200 bg-white p-6 shadow-xl" onClick={(e) => e.stopPropagation()}>
              <div className="mb-4 flex items-center justify-between">
                <h2 className="text-2xl font-bold text-slate-900">{selectedWorker.name}</h2>
                <button
                  onClick={() => setShowWorkerProfile(false)}
                  className="rounded-full p-2 text-slate-500 hover:bg-slate-100"
                >
                  ✕
                </button>
              </div>
              
              <div className="space-y-4">
                <div>
                  <p className="text-sm font-semibold text-slate-500">Email</p>
                  <p className="text-slate-900">{selectedWorker.email}</p>
                </div>
                <div>
                  <p className="text-sm font-semibold text-slate-500">Phone</p>
                  <p className="text-slate-900">{selectedWorker.phone}</p>
                </div>
                {selectedWorker.services && selectedWorker.services.length > 0 && (
                  <div>
                    <p className="text-sm font-semibold text-slate-500 mb-2">Services Offered</p>
                    <div className="flex flex-wrap gap-2">
                      {selectedWorker.services.map((service) => (
                        <span
                          key={service.id}
                          className="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700"
                        >
                          {service.name}
                        </span>
                      ))}
                    </div>
                  </div>
                )}
                <div className="flex gap-3 pt-4">
                  <button className="flex-1 rounded-full bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600">
                    Book This Worker
                  </button>
                  <button
                    onClick={() => setShowWorkerProfile(false)}
                    className="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                  >
                    Close
                  </button>
                </div>
              </div>
            </div>
          </div>
        )}
      </main>
    </div>
  )
}

export default DashboardUser
