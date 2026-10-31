<?php
// Menampilkan statistik singkat, artikel terbaru, serta pagination daftar seluruh artikel
include "db.php";

// Hitung total kategori dan total artikel
$total_category = mysqli_query($conn, "SELECT COUNT(DISTINCT category) AS total FROM article")->fetch_assoc()['total'];
$total_article = mysqli_query($conn, "SELECT COUNT(*) AS total FROM article")->fetch_assoc()['total'];

// Ambil 3 artikel terbaru
$new_articles = mysqli_query($conn, "SELECT * FROM article ORDER BY releasedate DESC LIMIT 3");

// Konfigurasi pagination
$limit = 6;
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$all_articles = mysqli_query($conn, "SELECT * FROM article ORDER BY releasedate DESC LIMIT $limit OFFSET $offset");
$total_pages = ceil($total_article / $limit);
?>

<!DOCTYPE html>
<html lang="id" data-theme="nord">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Blog tentang Lorem Ipsum, artikel terbaru, dan informasi lainnya." />
  <title>Blog</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-base-200 text-base-content min-h-screen flex flex-col justify-between">
  <header>
    <nav class="navbar bg-base-100 shadow-md sticky top-0 z-50 px-10">
      <div class="flex-1">
        <a href="index.php" class="font-bold text-2xl text-primary">Lorem Ipsum</a>
      </div>
    </nav>

    <section class="hero min-h-[400px] pt-10 bg-base-300/40">
      <div class="hero-content text-center">
        <div class="max-w-xl">
          <h1 class="mb-5 text-5xl font-extrabold text-primary">Lorem Ipsum</h1>
          <p class="mb-6 text-lg opacity-80 leading-relaxed">
            Selamat datang di portal informasi terbaru. Temukan beragam artikel menarik seputar pemrograman, kuliner,
            dan topik populer lainnya.
          </p>
        </div>
      </div>
    </section>
  </header>

  <main class="container mx-auto px-6 lg:px-12 py-16 space-y-16 flex-grow" id="articles">
    <section>
      <div class="stats bg-base-100 shadow-lg rounded-xl p-6 w-full border border-base-300">
        <div class="stat text-center">
          <h2 class="stat-title font-bold text-xl text-primary">Total Category</h2>
          <p class="stat-value text-5xl text-secondary">
            <?= $total_category ?>
          </p>
        </div>
        <div class="stat text-center">
          <h2 class="stat-title font-bold text-xl text-primary">Total Article</h2>
          <p class="stat-value text-5xl text-accent">
            <?= $total_article ?>
          </p>
        </div>
      </div>
    </section>

    <section>
      <h2 class="text-center text-3xl font-bold mb-10 text-primary tracking-wide">New Article</h2>
      <ul class="space-y-6 max-w-3xl mx-auto">
        <?php while ($row = mysqli_fetch_assoc($new_articles)): ?>
          <li>
            <article
              class="flex items-start gap-5 border border-base-300 bg-base-100 p-6 rounded-xl shadow-md hover:shadow-xl transition">
              <figure class="shrink-0">
                <img class="size-28 rounded-lg object-cover"
                  src="<?= !empty($row['image']) ? 'uploads/' . htmlspecialchars($row['image']) : 'uploads/error.png' ?>"
                  alt="Thumbnail artikel" />
              </figure>
              <div class="flex-1">
                <a href="article.php?id=<?= $row['idarticle'] ?>" class="block space-y-2">
                  <h3 class="uppercase font-bold text-lg hover:text-primary transition">
                    <?= htmlspecialchars($row['title']) ?>
                  </h3>
                  <p class="text-xs uppercase font-semibold text-secondary">
                    <?= htmlspecialchars($row['category']) ?> |
                    <?= date("d M Y", strtotime($row['releasedate'])) ?>
                  </p>
                  <p class="text-sm leading-relaxed opacity-80">
                    <?= substr(strip_tags($row['content']), 0, 120) ?>...
                  </p>
                </a>
              </div>
            </article>
          </li>
        <?php endwhile; ?>
      </ul>
    </section>

    <section>
      <h2 class="text-center text-3xl font-bold my-10 text-primary tracking-wide">All Article</h2>
      <ul class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php while ($row = mysqli_fetch_assoc($all_articles)): ?>
          <li class="h-full">
            <article
              class="flex flex-col h-full border border-base-300 bg-base-100 rounded-xl p-5 shadow-md hover:shadow-xl transition">
              <figure>
                <img class="w-full h-48 object-cover rounded-lg"
                  src="<?= !empty($row['image']) ? 'uploads/' . htmlspecialchars($row['image']) : 'uploads/error.png' ?>"
                  alt="Thumbnail artikel" />
              </figure>
              <div class="mt-4 flex flex-col flex-1">
                <a href="article.php?id=<?= $row['idarticle'] ?>" class="block space-y-2">
                  <h3 class="uppercase font-bold text-lg hover:text-primary transition">
                    <?= htmlspecialchars($row['title']) ?>
                  </h3>
                  <p class="text-xs uppercase font-semibold text-secondary">
                    <?= htmlspecialchars($row['category']) ?> |
                    <?= date("d M Y", strtotime($row['releasedate'])) ?>
                  </p>
                  <p class="text-sm leading-relaxed opacity-80 line-clamp-3">
                    <?= strip_tags($row['content']) ?>
                  </p>
                </a>
              </div>
            </article>
          </li>
        <?php endwhile; ?>
      </ul>

      <div class="join flex justify-center mt-12">
        <?php if ($page > 1): ?>
          <a href="?page=<?= $page - 1 ?>" class="join-item btn btn-base-100">«</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <a href="?page=<?= $i ?>" class="join-item btn <?= ($i == $page) ? 'btn-primary' : 'btn-base-100' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <a href="?page=<?= $page + 1 ?>" class="join-item btn btn-base-100">»</a>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <footer class="footer footer-center bg-base-300 p-8 text-base-content shadow-inner mt-20">
    <div>
      <p class="font-bold text-lg">Lorem Ipsum Media.</p>
      <p class="text-sm opacity-80">© 2026 - All rights reserved</p>
    </div>
  </footer>
</body>

</html>