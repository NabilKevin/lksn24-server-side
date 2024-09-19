const Course = () => {
  return (
    <>
      <main className="py-5">
        <section>
            <div className="container">
                <div className="d-flex align-items-center justify-content-between mb-3">
                    <h3 className="mb-0">[Course name]</h3>
                    <a href="detail-course-registered.html" className="btn btn-primary">Register to this course</a>
                </div>

                <p className="mb-5">
                    [Course description]
                </p>

                <div className="mb-4">
                    <h4 className="mb-3">Outline</h4>
                    <div className="row">
                        <div className="col-md-12">
                            <div className="card mb-3">
                                <div className="card-body">
                                    <h5 className="mb-0">1. [Set name]</h5>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-12">
                            <div className="card mb-3">
                                <div className="card-body">
                                    <h5 className="mb-0">2. [Set name]</h5>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-12">
                            <div className="card mb-3">
                                <div className="card-body">
                                    <h5 className="mb-0">3. [Set name]</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>
    </>
  )
}

export default Course