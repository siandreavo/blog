<?php
// Memproses form upload dan penyimpanan artikel ke database
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Escape string untuk mencegah SQL Injection
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $category = mysqli_real_escape_string($conn, $_POST['category']);
  $content = mysqli_real_escape_string($conn, $_POST['content']);

  // Mengelola upload gambar artikel
  $imageName = null;
  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
      mkdir($targetDir, 0777, true);
    }
    $imageName = time() . "_" . basename($_FILES['image']['name']);
    $targetFile = $targetDir . $imageName;
    move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
  }

  // Menyimpan data artikel ke database
  $sql = "INSERT INTO article (title, releasedate, content, category, image)
            VALUES ('$title', NOW(), '$content', '$category', '$imageName')";
  if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Artikel berhasil disimpan!');window.location='dashboard.php';</script>";
  } else {
    echo "<script>alert('Gagal menyimpan artikel: " . mysqli_error($conn) . "');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id" data-theme="nord">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Article</title>

  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-base-200 text-base-content min-h-screen flex flex-col justify-between">
  <nav class="navbar bg-base-100 shadow-md sticky top-0 z-50 px-10">
    <div class="flex-1">
      <a href="dashboard.php" class="font-bold text-2xl text-primary">Admin Dashboard</a>
    </div>
    <div class="flex-none">
      <a href="login.php" class="btn btn-sm btn-primary">Logout</a>
    </div>
  </nav>

  <main class="container mx-auto px-6 lg:px-12 py-12 flex-grow">
    <section class="card bg-base-100 shadow-lg border border-base-300 rounded-xl p-8">
      <h2 class="text-2xl font-bold text-primary mb-6">Add New Article</h2>

      <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Title</span></label>
          <input type="text" name="title" class="input input-bordered w-full" placeholder="Article title" required />
        </div>
        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Category</span></label>
          <input type="text" name="category" class="input input-bordered w-full" placeholder="e.g. coding, culinary"
            required />
        </div>
        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Image</span></label>
          <input type="file" name="image" class="file-input file-input-bordered w-full" />
        </div>
        <div class="form-control">
          <label class="label"><span class="label-text font-semibold">Content</span></label>
          <textarea name="content" class="textarea textarea-bordered w-full h-40"
            placeholder="Write your article here..." required></textarea>
        </div>
        <div class="flex justify-end gap-3 pt-4">
          <a href="dashboard.php" class="btn btn-ghost">Cancel</a>
          <button type="submit" class="btn btn-primary">Save Article</button>
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
</body>

</html>