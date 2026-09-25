<?php
// article.php - Standalone Article Detail Page
require_once __DIR__ . '/config/db.php';
$pdo = getDBConnection();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Increment view count
$pdo->prepare("UPDATE news SET views = views + 1 WHERE id = ?")->execute([$id]);

// Fetch article
$stmt = $pdo->prepare("
    SELECT n.*, c.name_np as category_name 
    FROM news n 
    JOIN categories c ON n.category_id = c.id 
    WHERE n.id = ?
");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: index.php');
    exit;
}

// Related news
$relStmt = $pdo->prepare("SELECT id, title_np, image_url, created_at FROM news WHERE category_id = ? AND id != ? ORDER BY created_at DESC LIMIT 3");
$relStmt->execute([$article['category_id'], $id]);
$related = $relStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title_np']) ?> | नगरिक खबर</title>
    <meta name="description" content="<?= htmlspecialchars($article['summary_np']) ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="main-header" style="border-bottom: 2px solid var(--primary-red);">
        <div class="container header-brand-wrap">
            <a href="index.php" class="brand-identity">
                <svg class="emblem-icon" viewBox="0 0 100 100" fill="none">
                    <circle cx="50" cy="50" r="46" fill="#D62828" stroke="#1D3557" stroke-width="4"/>
                    <path d="M50 15 L80 80 L20 80 Z" fill="#FFFFFF"/>
                </svg>
                <div class="brand-text">
                    <h1>नगरिक खबर</h1>
                    <p>नगर पालिका समाचार तथा सूचना पोर्टल</p>
                </div>
            </a>
            <a href="index.php" class="btn-primary" style="width:auto; padding:8px 18px; font-size:0.9rem;">
                <i class="fas fa-arrow-left"></i> गृहपृष्ठमा फर्कनुहोस्
            </a>
        </div>
    </header>

    <main class="container" style="padding: 30px 0; max-width: 900px;">
        <article class="widget" style="padding: 30px;">
            <span class="category-badge" style="position:static; display:inline-block; margin-bottom:12px;"><?= htmlspecialchars($article['category_name']) ?></span>
            <h1 style="font-size: 2.1rem; line-height: 1.3; color: var(--navy-blue); font-weight: 800; margin-bottom: 16px;">
                <?= htmlspecialchars($article['title_np']) ?>
            </h1>

            <div style="display:flex; flex-wrap:wrap; gap:20px; font-size:0.92rem; color:var(--text-muted); border-bottom:1px solid #E2E8F0; padding-bottom:14px; margin-bottom:24px;">
                <span><i class="fas fa-user" style="color:var(--primary-red);"></i> <?= htmlspecialchars($article['author']) ?></span>
                <span><i class="far fa-calendar-alt" style="color:var(--navy-blue);"></i> <?= htmlspecialchars($article['created_at']) ?></span>
                <span><i class="far fa-eye" style="color:var(--accent-gold);"></i> <?= $article['views'] ?> पटक हेरिएको</span>
            </div>

            <?php if ($article['image_url']): ?>
            <div style="width:100%; max-height:450px; overflow:hidden; border-radius:12px; margin-bottom:24px;">
                <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="Article Image" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <?php endif; ?>

            <div style="font-size: 1.15rem; line-height: 1.8; color: #2D3748;">
                <p style="font-weight: 700; font-size: 1.2rem; color: var(--navy-blue); margin-bottom: 18px; background:#F8FAFC; padding:16px; border-left:4px solid var(--primary-red); border-radius:4px;">
                    <?= htmlspecialchars($article['summary_np']) ?>
                </p>
                <div>
                    <?= nl2br(htmlspecialchars($article['content_np'])) ?>
                </div>
            </div>
        </article>

        <!-- RELATED NEWS SECTION -->
        <?php if (!empty($related)): ?>
        <section style="margin-top: 40px;">
            <h3 style="font-size: 1.3rem; color:var(--navy-blue); margin-bottom:16px; border-left:4px solid var(--primary-red); padding-left:10px;">
                सम्बन्धित समाचारहरू
            </h3>
            <div class="news-grid">
                <?php foreach ($related as $rel): ?>
                <div class="news-card">
                    <div class="card-img-wrap">
                        <img src="<?= htmlspecialchars($rel['image_url']) ?>" alt="thumb">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="article.php?id=<?= $rel['id'] ?>"><?= htmlspecialchars($rel['title_np']) ?></a>
                        </h4>
                        <a href="article.php?id=<?= $rel['id'] ?>" class="read-more-btn">पढ्नुहोस् <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <footer class="main-footer">
        <div class="container" style="text-align:center;">
            &copy; <?= date('Y') ?> नगरिक खबर | Nepal Municipality Portal
        </div>
    </footer>
</body>
</html>
