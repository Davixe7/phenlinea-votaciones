export const userService = {
  login,
  logout,
  getAll
}

function login(email, password) {
  return axios.post('/api/login', {email, password})
  .then(handleResponse)
  .then(data => {
    if (data.user.token) {
      localStorage.setItem('user', JSON.stringify(data.user))
    }
    return data.user
  })
}

function logout() {
  localStorage.removeItem('user')
}

function getAll() {
  const requestOptions = {
    headers: authHeader()
  }
  
  return axios.get('/api/users', requestOptions).then(handleResponse)
}

function handleResponse(response) {
  if (!response.data) {
    if (response.status === 401) {
      logout()
      location.reload(true)
    }
    const error = (data && data.message) || response.statusText
    return Promise.reject(error)
  }
  return response.data
}