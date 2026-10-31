<?php
// Memproses autentikasi user saat form dikirim (POST)
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Amankan input dari SQL Injection
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  // Cari user berdasarkan email
  $query = "SELECT * FROM user WHERE email='$email' LIMIT 1";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);

    // Verifikasi password hash
    if (password_verify($password, $data['password'])) {
      $_SESSION['id'] = $data['iduser'];
      $_SESSION['email'] = $data['email'];

      header("Location: dashboard.php");
      exit;
    } else {
      $_SESSION['error'] = "Password salah!";
      header("Location: login.php");
      exit;
    }
  } else {
    $_SESSION['error'] = "Email tidak ditemukan!";
    header("Location: login.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="id" data-theme="nord">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Admin Dashboard</title>

  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-base-200 text-base-content">
  <div class="card w-full max-w-md shadow-2xl bg-base-100 border border-base-300">
    <div class="card-body p-8">
      <h2 class="text-center text-3xl font-extrabold text-primary mb-6 tracking-wide">
        Admin Login
      </h2>

      <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error shadow-sm mb-6">
          <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>
            <?= htmlspecialchars($_SESSION['error']); ?>
          </span>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <form method="POST" class="space-y-5">
        <div class="form-control w-full">
          <label class="label">
            <span class="label-text font-semibold">Email</span>
          </label>
          <input type="email" name="email" class="input input-bordered w-full bg-base-100" required />
        </div>

        <div class="form-control w-full">
          <label class="label">
            <span class="label-text font-semibold">Password</span>
          </label>
          <input type="password" name="password" class="input input-bordered w-full bg-base-100"
            required />
        </div>

        <div class="pt-2">
          <button type="submit" class="btn btn-primary w-full text-primary-content font-bold">
            Login
          </button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>