import { useState } from 'react'
import { BASE_URL } from '../config'
import axios from 'axios'
import { useNavigate } from 'react-router-dom'

const Register = () => {
  document.title = 'Register'
  const navigate = useNavigate()
  const [error, setError] = useState({})
  const handleRegister = async e => {
    e.preventDefault()
    const formData = {};
    [...e.target].map(input => {
      if(input.tagName !== 'BUTTON') {
        formData[input.name] = input.value
      }
      
    })

    try {
      const response = await axios.post(`${BASE_URL}/register`, formData)
      const data = await response.data
      localStorage.setItem('token', data.token)
      localStorage.setItem('username', data.username)
      navigate('/')
    } catch (err) {
      setError(err.response.data)
    }
  }
  return (
    <>
      <main className="py-5">
        <section>
            <div className="container">
                {
                  error?.message && (
                    <div className="alert alert-danger text-danger">
                      {error.message}
                    </div>
                  )
                }

                <h3 className="mb-3 text-center">Register</h3>

                <div className="row justify-content-center">
                    <div className="col-md-7">
                        <div className="card mb-4">
                            <div className="card-body">
                                <form onSubmit={handleRegister}>
                                    <div className="form-group mb-2">
                                        <label htmlFor="full_name">Full Name</label>
                                        <input type="text" name="full_name" id="full_name" className={`form-control ${error?.errors?.username ? 'is-invalid' : ''} `} autoFocus/>
                                        {
                                          error?.errors?.username && (
                                            <div className="invalid-feedback">
                                              {error?.errors?.username}
                                            </div>
                                          )
                                        }
                                    </div>
                                    <div className="form-group mb-2">
                                        <label htmlFor="username">Username</label>
                                        <input type="text" name="username" id="username" className={`form-control ${error?.errors?.username ? 'is-invalid' : ''} `}/>
                                        {
                                          error?.errors?.username && (
                                            <div className="invalid-feedback">
                                              {error?.errors?.username}
                                            </div>
                                          )
                                        }
                                    </div>
                                    <div className="form-group mb-2">
                                        <label htmlFor="password">Password</label>
                                        <input type="password" name="password" id="password" className={`form-control ${error?.errors?.username ? 'is-invalid' : ''} `}/>
                                        {
                                          error?.errors?.username && (
                                            <div className="invalid-feedback">
                                              {error?.errors?.username}
                                            </div>
                                          )
                                        }
                                    </div>
                                    <div className="mt-3">
                                        <button type="submit" className="btn btn-primary w-100">Register</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <p className="text-center">You have an account? <a href="/login">Login</a></p>
                    </div>
                </div>

            </div>
        </section>
    </main>
    </>
  )
}

export default Register