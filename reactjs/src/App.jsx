import { Route, Routes, useNavigate } from 'react-router-dom'
import './css/index.css'
import { Login, Register, Home, Course, NotFound } from './pages/'
import Header from './components/Header'
import { useEffect } from 'react'

function App() {
  const navigate = useNavigate()

  useEffect(() => {
    const token = localStorage.getItem('token')
    const path = window.location.pathname.split('/')[1].toLowerCase()
    if(!token && path !== 'login' && path !== 'register') {
      navigate('/login')
    }
    if(token && (path === 'login' || path === 'register')) {
      navigate('/')
    }

  })

  return (
    <>
    <Header />
    <Routes>
      <Route path='/login' element={<Login />}/>
      <Route path='/register' element={<Register />} />
      <Route path='/' element={<Home />} />
      <Route path='/{:course:slug}' element={<Course />} />
      <Route path='/*' element={<NotFound />} />
    </Routes>
    </>
  )
}

export default App
