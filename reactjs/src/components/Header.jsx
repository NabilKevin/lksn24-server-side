import axios from "axios";
import { BASE_URL } from "../config";
import { useNavigate } from "react-router-dom";

const Header = () => {
  const navigate = useNavigate()
  const token = localStorage.getItem('token');
  const handleLogout = async () => {
    try {
      await axios.post(`${BASE_URL}/logout`, {}, {
        headers: {
          Authorization: 'Bearer ' + token 
        }
      })
      localStorage.clear()
      navigate('/login')
    } catch (err) {
      console.log(err);
      
    }
  }
  return (
    <>
      <nav className="navbar navbar-expand-lg bg-body-tertiary">
        <div className="container">
            <a className="navbar-brand" href="index.html">Web Tech Studio</a>
            <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span className="navbar-toggler-icon"></span>
            </button>
            <div className="collapse navbar-collapse" id="navbarSupportedContent">
                <ul className="navbar-nav ms-auto mb-2 mb-lg-0">
                  {
                    token ? (
                      <>
                      <li className="nav-item">
                        <a className="nav-link" href="#">{localStorage.getItem('username')}</a>
                      </li>
                      <li className="nav-item">
                        <button className="nav-link" onClick={handleLogout}>Logout</button>
                      </li>
                      </>
                    ) : (
                      <>
                      <li className="nav-item">
                          <a className="nav-link" href="login.html">Login</a>
                      </li>
                      <li className="nav-item">
                          <a className="nav-link" href="register.html">Register</a>
                      </li>
                      </>
                    )
                  }
                </ul>
            </div>
        </div>
    </nav>
    </>
  )
}

export default Header