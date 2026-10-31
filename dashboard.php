<?php
// Mengelola aksi hapus data (artikel & user) serta menampilkan daftar tabel manajemen
session_start();
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'blog';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
  die("Koneksi DB gagal: " . $conn->connect_error);
}

// Penanganan aksi hapus data via GET
if (isset($_GET['action']) && isset($_GET['id'])) {
  $action = $_GET['action'];
  $id = intval($_GET['id']);

  if ($action === 'delete_article') {
    $stmt = $conn->prepare("DELETE FROM article WHERE idarticle = ?");
    if ($stmt) {
      $stmt->bind_param('i', $id);
      $stmt->execute();
      $stmt->close();
    }
    header("Location: dashboard.php");
    exit;
  }

  if ($action === 'delete_user') {
    $stmt = $conn->prepare("DELETE FROM user WHERE iduser = ?");
    if ($stmt) {
      $stmt->bind_param('i', $id);
      $stmt->execute();
      $stmt->close();
    }
    header("Location: dashboard.php");
    exit;
  }
}

// Query mengambil seluruh daftar artikel dan user
$articles = $conn->query("SELECT idarticle, title, releasedate, category, image FROM article ORDER BY releasedate DESC");
$users = $conn->query("SELECT iduser, email FROM user ORDER BY iduser ASC");
?>

<!DOCTYPE html>
<html lang="id" data-theme="nord">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard</title>

  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  <style>
    .table img {
      max-width: 100%;
      height: auto;
    }
  </style>
</head>

<body class="bg-base-200 text-base-content min-h-screen flex flex-col justify-between">

  <header>
    <nav class="navbar bg-base-100 shadow-md sticky top-0 z-50 px-10">
      <div class="flex-1">
        <a href="dashboard.php" class="font-bold text-2xl text-primary">Admin Dashboard</a>
      </div>
      <div class="flex-none">
        <a href="login.php" class="btn btn-sm btn-primary">Logout</a>
      </div>
    </nav>
  </header>

  <main class="container mx-auto px-6 lg:px-12 py-12 space-y-12 flex-grow">
    <section class="card bg-base-100 shadow-lg border border-base-300 rounded-xl">
      <div class="card-body">
        <div class="flex items-center justify-between">
          <h2 class="card-title text-primary text-xl font-bold">Manage Articles</h2>
          <a href="addarticle.php" class="btn btn-sm btn-primary">+ Add Article</a>
        </div>

        <div class="overflow-x-auto mt-6">
          <table class="table table-zebra w-full">
            <thead>
              <tr class="text-center">
                <th>Image</th>
                <th>Title</th>
                <th>Date</th>
                <th>Category</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($articles && $articles->num_rows > 0): ?>
                <?php while ($row = $articles->fetch_assoc()): ?>
                  <?php
                  $img = trim($row['image']);
                  $imgSrc = !empty($img) ? 'uploads/' . $img : 'uploads/error.png';
                  if (!file_exists($imgSrc)) {
                    $imgSrc = 'uploads/error.png';
                  }
                  ?>
                  <tr class="text-center hover:bg-base-200">
                    <td class="w-24">
                      <figure class="inline-block">
                        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($row['title']) ?>"
                          class="w-16 h-16 object-cover rounded-md border border-base-300 mx-auto" />
                      </figure>
                    </td>
                    <td class="text-left px-2">
                      <?= htmlspecialchars($row['title']) ?>
                    </td>
                    <td>
                      <?= htmlspecialchars(date("d F Y H:i", strtotime($row['releasedate']))) ?>
                    </td>
                    <td><span class="badge badge-secondary">
                        <?= htmlspecialchars($row['category']) ?>
                      </span></td>
                    <td class="space-x-1">
                      <a href="editarticle.php?id=<?= (int) $row['idarticle'] ?>" class="btn btn-xs btn-accent">Edit</a>
                      <a href="dashboard.php?action=delete_article&id=<?= (int) $row['idarticle'] ?>"
                        onclick="return confirm('Yakin mau menghapus artikel ini?');"
                        class="btn btn-xs btn-error">Delete</a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center py-6">No Articles Yet</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <section class="card bg-base-100 shadow-lg border border-base-300 rounded-xl">
      <div class="card-body">
        <div class="flex items-center justify-between">
          <h2 class="card-title text-primary text-xl font-bold">Manage Users</h2>
          <a href="adduser.php" class="btn btn-sm btn-primary">+ Add User</a>
        </div>

        <div class="overflow-x-auto mt-6">
          <table class="table table-zebra w-full">
            <thead>
              <tr class="text-center">
                <th>Email</th>
                <th>Password</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($users && $users->num_rows > 0): ?>
                <?php while ($u = $users->fetch_assoc()): ?>
                  <tr class="text-center hover:bg-base-200">
                    <td>
                      <?= htmlspecialchars($u['email']) ?>
                    </td>
                    <td>••••••••</td>
                    <td class="space-x-1">
                      <a href="edituser.php?id=<?= (int) $u['iduser'] ?>" class="btn btn-xs btn-accent">Edit</a>
                      <a href="dashboard.php?action=delete_user&id=<?= (int) $u['iduser'] ?>"
                        onclick="return confirm('Yakin mau menghapus user ini?');" class="btn btn-xs btn-error">Delete</a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="3" class="text-center py-6">No Users Yet</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
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

<?php
$conn->close();
?>