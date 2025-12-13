const API_BASE_URL = 'http://localhost:8000/api/v1'

class ApiService {
  async request(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`
    const config = {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers,
      },
      ...options,
    }

    try {
      const response = await fetch(url, config)
      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.message || 'An error occurred')
      }

      return data
    } catch (error) {
      console.error('API Error:', error)
      throw error
    }
  }

  // User endpoints
  getUsers(params = {}) {
    const queryString = new URLSearchParams(params).toString()
    return this.request(`/users${queryString ? `?${queryString}` : ''}`)
  }

  getUser(id) {
    return this.request(`/users/${id}`)
  }

  createUser(data) {
    return this.request('/users', {
      method: 'POST',
      body: JSON.stringify(data),
    })
  }

  updateUser(id, data) {
    return this.request(`/users/${id}`, {
      method: 'PUT',
      body: JSON.stringify(data),
    })
  }

  deleteUser(id) {
    return this.request(`/users/${id}`, {
      method: 'DELETE',
    })
  }

  // Worker endpoints
  getWorkers(params = {}) {
    const queryString = new URLSearchParams(params).toString()
    return this.request(`/workers${queryString ? `?${queryString}` : ''}`)
  }

  getWorker(id) {
    return this.request(`/workers/${id}`)
  }

  // Job Request endpoints
  getJobRequests(params = {}) {
    const queryString = new URLSearchParams(params).toString()
    return this.request(`/job-requests${queryString ? `?${queryString}` : ''}`)
  }

  getJobRequest(id) {
    return this.request(`/job-requests/${id}`)
  }

  createJobRequest(data) {
    return this.request('/job-requests', {
      method: 'POST',
      body: JSON.stringify(data),
    })
  }

  // Services endpoints
  getServices() {
    return this.request('/services')
  }

  // Admin stats endpoints
  getAdminStats() {
    return this.request('/admin/stats')
  }
}

export default new ApiService()
