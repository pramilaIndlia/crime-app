<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4">Forgot Password</h2>
            <form id="forgot-password-form">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                </div>
                <button type="button" class="btn btn-primary w-100" onclick="sendResetLink()">Send Reset Link</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function sendResetLink() {

            const email = document.getElementById("email").value;
            const myHeaders = new Headers();
            myHeaders.append("Accept", "application/json");
            myHeaders.append("Content-Type", "application/json");

            const raw = JSON.stringify({
                "email": email
            });
            const requestOptions = {
                method: "POST",
                headers: myHeaders,
                body: raw,
                redirect: "follow"
            };
            fetch("http://127.0.0.1:8000/api/forgot-password", requestOptions)
                .then((response) => response.json())
                .then((result) => {
                    console.log(result);

                    if (result.success) {
                        alert("A reset link has been sent to your email.");
                    } else {
                        alert(result.message || "An error occurred. Please try again.");
                    }
                })
                .catch((error) => console.error("Error:", error));
        }
    </script>

</body>

</html>
