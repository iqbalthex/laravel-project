<!DOCTYPE html>
<html lang="en-US" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Blog</title>
  </head>

  <body>
    <main class="main">
      <div class="container" data-layout="container">
        <div class="row flex-center min-vh-100 py-6">
          <div class="col-sm-10 col-md-8 col-lg-12 col-xl-5 col-xxl-4"><span class="font-sans-serif fw-bolder fs-5 d-inline-block">Capitasi</span>
            <div class="card">
              <div class="card-body p-4 p-sm-5">
                <div class="row flex-between-center mb-2">
                  <div class="col-auto">
                    <h5>Log in</h5>
                  </div>
                </div>
                <form method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="mb-3">
                    <input class="form-control" type="email" name="email" placeholder="Email address" autofocus />
                  </div>
                  <div class="mb-3">
                    <input class="form-control" type="password" name="password" placeholder="Password" />
                  </div>
                  <div class="row flex-between-center">
                    <div class="col-auto">
                      <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="basic-checkbox" checked="checked" />
                        <label class="form-check-label mb-0" for="basic-checkbox">Remember me</label>
                      </div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">Log in</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </body>
</html>