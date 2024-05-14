<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"></script>
</head>

<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <form method="POST" action="#" id="login-form">
                            @csrf

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    window.onload = function() {
        console.log('Loading')

        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;

            axios.post('/api/auth/login', {
                    email: email,
                    password: password,
                    remember: remember
                })
                .then(function(response) {
                    localStorage.setItem('access_token', response.data.access_token);
                    localStorage.setItem('refresh_token', response.data.refresh_token);

                    redirectToPosts(response.data.access_token);

                })
                .catch(function(error) {
                    alert('Login failed. Please try again.');
                });
        });

        function redirectToPosts(accessToken) {
            axios.get('/api/posts', {
                    headers: {
                        'Authorization': 'Bearer ' + accessToken
                    }
                })
                .then(function(response) {
                    console.log(response.data);
                })
                .catch(function(error) {
                    if (error.response.status === 401) {
                        refreshAccessToken(localStorage.getItem('refresh_token'));
                    } else {
                        console.error(error);
                    }
                });
        }

        function refreshAccessToken(refreshToken) {
            axios.post('/api/auth/refresh', {
                    refresh_token: refreshToken
                })
                .then(function(response) {
                    localStorage.setItem('access_token', response.data.access_token);
                    localStorage.setItem('refresh_token', response.data.refresh_token);

                    redirectToPosts(response.data.access_token);
                })
                .catch(function(error) {
                    console.error(error);
                });
        }
    }
</script>

</html>
