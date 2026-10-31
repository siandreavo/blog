<?php
// Memperbarui informasi artikel yang sudah ada berdasarkan ID
include "db.php";

if (!isset($_GET['id'])) {
  die("Artikel tidak ditemukan.");
}
$id = intval($_GET['id']);

$sql = "SELECT * FROM article WHERE idarticle = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
  die("Artikel tidak ada di database.");
}
$article = mysqli_fetch_assoc($result);

// Memproses perubahan data ketika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $category = mysqli_real_escape_string($conn, $_POST['category']);
  $content = mysqli_real_escape_string($conn, $_POST['content']);

  // Cek apakah ada penggantian gambar baru
  if (!empty($_FILES['image']['name'])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
      mkdir($targetDir, 0777, true);
    }
    $imageName = time() . "_" . basename($_FILES['image']['name']);
    $targetFile = $targetDir . $imageName;
    move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

    $imageSQL = ", image='$imageName'";
  } else {
    $imageSQL = "";
  }

  $update = "UPDATE article 
               SET title='$title', category='$category', content='$content' $imageSQL 
               WHERE idarticle=$id";

  if (mysqli_query($conn, $update)) {
    echo "<script>alert('Artikel berhasil diperbarui!');window.location='dashboard.php';</script>";
    exit;
  } else {
    echo "<script>alert('Gagal update artikel: " . mysqli_error($conn) . "');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id" data-theme="nord">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Article</title>
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
    <section class="card bg-base-100 shadow-lg border border-base-300 rounded-xl p-8">
      <h2 class="text-2xl font-bold text-primary mb-6">Edit Article</h2>

      <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Title</span></label>
          <input type="text" name="title" class="input input-bordered w-full"
            value="<?php echo htmlspecialchars($article['title']); ?>" required />
        </div>

        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Category</span></label>
          <input type="text" name="category" class="input input-bordered w-full"
            value="<?php echo htmlspecialchars($article['category']); ?>" required />
        </div>

        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Image</span></label>
          <?php if (!empty($article['image'])): ?>
            <p class="mb-2">Current: <img src="uploads/<?php echo htmlspecialchars($article['image']); ?>" alt="Image"
                class="h-24 rounded border border-base-300"></p>
          <?php endif; ?>
          <input type="file" name="image" class="file-input file-input-bordered w-full" />
        </div>

        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Content</span></label>
          <textarea name="content"
            class="textarea textarea-bordered w-full h-40"><?php echo htmlspecialchars($article['content']); ?></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <a href="dashboard.php" class="btn btn-ghost">Cancel</a>
          <button type="submit" class="btn btn-primary">Update Article</button>
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