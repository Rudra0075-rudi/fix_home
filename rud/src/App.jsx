
import React from 'react'
import DashboardWorker from './components/DashboardWorker'
import DashboardAdmin from './components/DashboardAdmin'
import DashboardUser from './components/DashboardUser'

export default function App() {
  const path = window.location.pathname.toLowerCase()

  if (path.includes('/admin')) {
    return <DashboardAdmin />
  }

  if (path.includes('/worker')) {
    return <DashboardWorker />
  }

  // Default: user dashboard (e.g., "/", "/user")
  return <DashboardUser />
}