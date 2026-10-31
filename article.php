<?php
// Menampilkan detail artikel berdasarkan parameter ID di URL dan artikel terkait lainnya
include "db.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 1;

// Mengambil artikel utama
$sql = "SELECT * FROM article WHERE idarticle = $id";
$result = mysqli_query($conn, $sql);
$article = mysqli_fetch_assoc($result);

if (!$article) {
  echo "<!DOCTYPE html><html data-theme='nord'><body class='bg-base-200 text-base-content p-10'><h1 class='text-2xl font-bold text-error'>Artikel tidak ditemukan.</h1><p class='mt-4'><a href='index.php' class='btn btn-primary'>Kembali ke Beranda</a></p></body></html>";
  exit;
}

// Mengambil 2 artikel terkait secara acak / terbaru (selain artikel yang sedang dibuka)
$sql2 = "SELECT * FROM article WHERE idarticle != $id ORDER BY releasedate DESC LIMIT 2";
$related = mysqli_query($conn, $sql2);
?>

<!DOCTYPE html>
<html lang="id" data-theme="nord">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    <?= htmlspecialchars($article['title']); ?>
  </title>
  <meta name="description" content="<?= htmlspecialchars(substr(strip_tags($article['content']), 0, 150)); ?>" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-base-200 text-base-content min-h-screen flex flex-col justify-between">
  <header>
    <nav class="navbar bg-base-100 shadow-md sticky top-0 z-50 px-10">
      <div class="flex-1">
        <a href="index.php" class="font-bold text-2xl text-primary">
          Lorem Ipsum
        </a>
      </div>
    </nav>
  </header>

  <main class="container max-w-3xl mx-auto px-6 py-12 flex-grow">
    <div class="mb-8">
      <a href="index.php" class="btn btn-outline btn-primary btn-sm">
        ← Back
      </a>
    </div>

    <article class="prose max-w-none">
      <h1 class="text-4xl md:text-5xl font-extrabold text-primary mb-4">
        <?= htmlspecialchars($article['title']); ?>
      </h1>
      <p class="text-sm opacity-70 mb-6">
        <span class="font-semibold text-secondary">
          <?= htmlspecialchars($article['category']); ?>
        </span> |
        <?= date("d F Y", strtotime($article['releasedate'])); ?>
      </p>

      <figure class="mb-8">
        <img
          src="<?= !empty($article['image']) ? 'uploads/' . htmlspecialchars($article['image']) : 'uploads/error.png'; ?>"
          alt="<?= htmlspecialchars($article['title']); ?>"
          class="w-full rounded-lg shadow-lg border border-base-300" />
        <figcaption class="text-xs text-center opacity-60 mt-2">
          <?= htmlspecialchars($article['title']); ?>
        </figcaption>
      </figure>

      <div class="space-y-5 text-justify leading-relaxed">
        <?= nl2br(htmlspecialchars($article['content'])); ?>
      </div>

      <div class="mt-10 flex flex-wrap gap-3">
        <span class="badge badge-outline badge-lg badge-primary">
          #
          <?= htmlspecialchars($article['category']); ?>
        </span>
      </div>
    </article>

    <section class="mt-16 border-t border-base-300 pt-10">
      <h2 class="text-2xl font-bold mb-8 text-primary">Another Article</h2>
      <div class="grid md:grid-cols-2 gap-6">
        <?php while ($row = mysqli_fetch_assoc($related)): ?>
          <a href="article.php?id=<?= $row['idarticle']; ?>"
            class="flex items-center gap-4 p-4 border border-base-300 rounded-xl shadow-sm hover:shadow-md transition bg-base-100">
            <img src="<?= !empty($row['image']) ? 'uploads/' . htmlspecialchars($row['image']) : 'uploads/error.png'; ?>"
              alt="<?= htmlspecialchars($row['title']); ?>" class="w-28 h-20 object-cover rounded-md" />
            <div>
              <h3 class="font-bold text-lg hover:text-primary transition line-clamp-2">
                <?= htmlspecialchars($row['title']); ?>
              </h3>
              <p class="text-sm opacity-60 mt-1">
                Diposting
                <?= date("d F Y", strtotime($row['releasedate'])); ?>
              </p>
            </div>
          </a>
        <?php endwhile; ?>
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