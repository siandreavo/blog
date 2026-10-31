<?php
// Memperbarui email dan/atau password pengguna terdaftar
include "db.php";

if (!isset($_GET['id'])) {
  die("User tidak ditemukan.");
}
$id = intval($_GET['id']);

$sql = "SELECT * FROM user WHERE iduser = $id";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) === 0) {
  die("User tidak ada di database.");
}
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password_input = $_POST['password'];

  // Update password hanya jika diisi oleh admin
  if (!empty($password_input)) {
    $password = password_hash($password_input, PASSWORD_DEFAULT);
    $update = "UPDATE user SET email='$email', password='$password' WHERE iduser=$id";
  } else {
    $update = "UPDATE user SET email='$email' WHERE iduser=$id";
  }

  if (mysqli_query($conn, $update)) {
    echo "<script>alert('User berhasil diperbarui!');window.location='dashboard.php';</script>";
    exit;
  } else {
    echo "<script>alert('Gagal update user: " . mysqli_error($conn) . "');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id" data-theme="nord">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit User</title>
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
      <h2 class="text-2xl font-bold text-primary mb-6">Edit User</h2>

      <form method="POST" class="space-y-6">
        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Email</span></label>
          <input type="email" name="email" class="input input-bordered w-full"
            value="<?= htmlspecialchars($user['email']) ?>" required />
        </div>

        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Password</span></label>
          <input type="password" name="password" class="input input-bordered w-full"
            placeholder="Isi jika ingin mengganti password" />
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <a href="dashboard.php" class="btn btn-ghost">Cancel</a>
          <button type="submit" class="btn btn-primary">Update User</button>
        </div>
      </form>
    </section>
  </main>

  <footer class="footer footer-center bg-base-300 p-8 text-base-content shadow-inner mt-20">
    <div>
      <p class="font-bold text-lg">© 2026 Admin Dashboard</p>
      <p class="text-sm opacity-80">All rights reserved</p>
    </div>
  </footer>
</body>

</html>