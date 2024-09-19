import axios from "axios"
import { useEffect, useState } from "react"
import { BASE_URL } from "../config"

const Home = () => {
  document.title = 'Home'
  const token = localStorage.getItem('token')
  const [courses, setCourses] = useState([])
  const getCourses = async () => {
    const response = await axios.get(`${BASE_URL}/courses`, {
      headers: {
        Authorization: 'Bearer ' + token
      }
    })
    const data = response.data.data.courses
    setCourses(data)
  }

  useEffect(() => {
    getCourses()
  }, [])
  return (
    <>
      <main className="py-5">
        <section className="my-courses">
            <div className="container">
                <h4 className="mb-3">My courses</h4>
                <div className="courses d-flex flex-column gap-3">
                    <a href="detail-course-registered.html" className="card text-decoration-none bg-body-tertiary">
                        <div className="card-body">
                            <h5 className="mb-2">[Course title]</h5>
                            <p className="text-muted mb-0">[Course description]</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section className="other-courses mt-4">
            <div className="container">
                <h4 className="mb-3">Other courses</h4>
                <div className="d-flex flex-column gap-3">
                {
                  courses.map(course => (
                      <a href={`/${course.slug}`} className="card text-decoration-none bg-body-tertiary">
                          <div className="card-body">
                              <h5 className="mb-2">{course.name}</h5>
                              <p className="text-muted mb-0">{course.description}</p>
                          </div>
                      </a>
                  ))
                }
                </div>
            </div>
        </section>
    </main>
    </>
  )
}

export default Home