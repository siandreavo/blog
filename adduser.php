<?php
// Memproses penambahan pengguna baru dengan enkripsi password
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $pass = $_POST['password'];
  $pass2 = $_POST['password_confirm'];

  // Validasi kesesuaian dan panjang password
  if ($pass !== $pass2) {
    $error = "Password dan konfirmasi password tidak cocok.";
  } elseif (strlen($pass) < 6) {
    $error = "Password minimal 6 karakter.";
  } else {
    // Enkripsi password sebelum disimpan ke DB
    $password_hashed = password_hash($pass, PASSWORD_DEFAULT);
    $sql = "INSERT INTO user (email, password) VALUES ('$email', '$password_hashed')";
    if (mysqli_query($conn, $sql)) {
      echo "<script>alert('User berhasil ditambahkan!');window.location='dashboard.php';</script>";
      exit;
    } else {
      $error = "Gagal menambahkan user: " . mysqli_error($conn);
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id" data-theme="nord">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add User</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-base-200 text-base-content min-h-screen flex flex-col justify-between">
  <nav class="navbar bg-base-100 shadow-md sticky top-0 z-50 px-10">
    <div class="flex-1">
      <a href="dashboard.php" class="font-bold text-2xl text-primary">Dashboard</a>
    </div>
    <div class="flex-none">
      <a href="login.php" class="btn btn-sm btn-primary">Logout</a>
    </div>
  </nav>

  <main class="container mx-auto px-6 lg:px-12 py-12 flex-grow">
    <section class="card bg-base-100 shadow-lg border border-base-300 rounded-xl p-8 max-w-2xl mx-auto">
      <h2 class="text-2xl font-bold text-primary mb-6">Add New User</h2>

      <?php if (!empty($error)): ?>
        <div class="alert alert-error mb-6">
          <span>
            <?php echo htmlspecialchars($error); ?>
          </span>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-6" autocomplete="off">
        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Email</span></label>
          <input type="email" name="email" class="input input-bordered w-full" placeholder="user@example.com" required
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
        </div>

        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Password</span></label>
          <div class="relative">
            <input type="password" name="password" id="password" class="input input-bordered w-full pr-12"
              placeholder="Enter password" required />
            <button type="button" onclick="togglePassword('password')"
              class="btn btn-ghost btn-sm absolute right-1 top-1/2 -translate-y-1/2">
              👁️
            </button>
          </div>
        </div>

        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Confirm Password</span></label>
          <div class="relative">
            <input type="password" name="password_confirm" id="password_confirm"
              class="input input-bordered w-full pr-12" placeholder="Confirm password" required />
            <button type="button" onclick="togglePassword('password_confirm')"
              class="btn btn-ghost btn-sm absolute right-1 top-1/2 -translate-y-1/2">
              👁️
            </button>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <a href="dashboard.php" class="btn btn-ghost">Cancel</a>
          <button type="submit" class="btn btn-primary">Save User</button>
        </div>
      </form>
    </section>
  </main>

  <footer class="footer footer-center bg-base-300 p-8 text-base-content shadow-inner">
    <div>
      <p class="font-bold text-lg">© 2026 Admin Dashboard</p>
      <p class="text-sm opacity-80">All rights reserved</p>
    </div>
  </footer>

  <script>
    function togglePassword(fieldId) {
      const input = document.getElementById(fieldId);
      if (input.type === "password") {
        input.type = "text";
      } else {
        input.type = "password";
      }
    }
  </script>
</body>

</html>