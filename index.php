<?php
// index.php - Main Portal Homepage
require_once __DIR__ . '/config/db.php';
$pdo = getDBConnection();

// Fetch Breaking News for Ticker
$tickerStmt = $pdo->query("SELECT id, title_np FROM news WHERE is_breaking = 1 ORDER BY created_at DESC LIMIT 5");
$breakingNews = $tickerStmt->fetchAll();

// Fetch Featured Main News
$featuredStmt = $pdo->query("SELECT n.*, c.name_np as category_name FROM news n JOIN categories c ON n.category_id = c.id WHERE n.is_featured = 1 ORDER BY n.created_at DESC LIMIT 1");
$mainFeatured = $featuredStmt->fetch();

// Fetch Sub-Featured News
$subStmt = $pdo->query("SELECT n.*, c.name_np as category_name FROM news n JOIN categories c ON n.category_id = c.id ORDER BY n.created_at DESC LIMIT 3");
$subFeatured = $subStmt->fetchAll();

// Fetch Categories
$catStmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $catStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>नगरिक खबर | नगर पालिका समाचार तथा सूचना पोर्टल</title>
    <meta name="description" content="नेपाल नगर पालिका आधिकारिक समाचार, सार्वजनिक सूचना, विकास निर्माण तथा वडा अपडेट पोर्टल।">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- TOP EMERGENCY & GOVT HEADER BAR -->
    <div class="top-header">
        <div class="container top-header-inner">
            <div class="header-meta">
                <div class="header-meta-item">
                    <i class="far fa-calendar-alt"></i>
                    <span id="live-nepali-date">सोमबार, १५ असार २०८१ (BS)</span>
                </div>
                <div class="header-meta-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>नगरपालिका हटलाइन: ०१-४५६७८९०</span>
                </div>
                <div class="header-meta-item">
                    <i class="fas fa-ambulance"></i>
                    <span>एम्बुलेन्स: १०२ | दमकल: १०१</span>
                </div>
            </div>
            <div class="header-controls">
                <div class="font-scaler">
                    <span style="font-size: 0.8rem; margin-right: 4px;">अक्षर आकार:</span>
                    <button class="font-btn" id="font-increase" title="अक्षर ठूलो बनाउनुहोस्">A+</button>
                    <button class="font-btn" id="font-reset" title="सामान्य">A</button>
                    <button class="font-btn" id="font-decrease" title="अक्षर सानो बनाउनुहोस्">A-</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN BRANDING HEADER -->
    <header class="main-header">
        <div class="container header-brand-wrap">
            <div class="brand-identity">
                <!-- SVG Emblem -->
                <svg class="emblem-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="46" fill="#D62828" stroke="#1D3557" stroke-width="4"/>
                    <path d="M50 15 L80 80 L20 80 Z" fill="#FFFFFF"/>
                    <path d="M50 28 L70 75 L30 75 Z" fill="#1D3557"/>
                    <circle cx="50" cy="52" r="10" fill="#F77F00"/>
                </svg>
                <div class="brand-text">
                    <h1>नगरिक खबर - समाचार पोर्टल</h1>
                    <p>हाम्रो नगरपालिका, राम्रो नगरपालिका | Official News & Information Portal</p>
                </div>
            </div>
            <div class="brand-quote">
                <i class="fas fa-quote-left"></i>
                <span>"जननी जन्मभूमिश्च स्वर्गादपि गरीयसी"</span>
            </div>
        </div>
    </header>

    <!-- BREAKING NEWS MARQUEE TICKER -->
    <?php if (!empty($breakingNews)): ?>
    <div class="ticker-wrap">
        <div class="ticker-title">
            <i class="fas fa-bullhorn"></i> मुख्य सूचना (Breaking):
        </div>
        <div class="ticker-content">
            <div class="ticker-marquee">
                <?php foreach ($breakingNews as $item): ?>
                    <span class="ticker-item">
                        <a href="javascript:void(0)" onclick="openNewsModal(<?= $item['id'] ?>)">
                            🔥 <?= htmlspecialchars($item['title_np']) ?>
                        </a>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- NAVIGATION BAR -->
    <nav class="navbar">
        <div class="container nav-inner">
            <button class="mobile-nav-toggle" id="mobile-nav-toggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="nav-links">
                <a href="index.php" class="active"><i class="fas fa-home"></i> गृहपृष्ठ</a>
                <a href="#news-section"><i class="fas fa-newspaper"></i> ताजा समाचार</a>
                <a href="#citizen-tip-section"><i class="fas fa-paper-plane"></i> समाचार पठाउनुहोस्</a>
                <a href="admin/index.php" target="_blank"><i class="fas fa-user-shield"></i> एडमिन लगइन</a>
            </div>
            <div class="nav-right-actions">
                <button class="search-trigger">
                    <i class="fas fa-search"></i> खोज्नुहोस्...
                </button>
            </div>
        </div>
    </nav>

    <!-- HERO FEATURED SECTION -->
    <section class="container hero-section">
        <div class="hero-grid">
            <!-- MAIN FEATURED STORY -->
            <?php if ($mainFeatured): ?>
            <div class="main-featured-card">
                <div class="featured-img-wrap">
                    <img src="<?= htmlspecialchars($mainFeatured['image_url']) ?>" alt="<?= htmlspecialchars($mainFeatured['title_np']) ?>">
                    <span class="category-badge"><?= htmlspecialchars($mainFeatured['category_name']) ?></span>
                </div>
                <div class="featured-content">
                    <div>
                        <div class="featured-meta">
                            <span><i class="far fa-calendar-alt"></i> <?= htmlspecialchars(date('Y-m-d', strtotime($mainFeatured['created_at']))) ?></span>
                            <span><i class="far fa-user"></i> <?= htmlspecialchars($mainFeatured['author']) ?></span>
                        </div>
                        <h2 class="featured-title">
                            <a href="javascript:void(0)" onclick="openNewsModal(<?= $mainFeatured['id'] ?>)">
                                <?= htmlspecialchars($mainFeatured['title_np']) ?>
                            </a>
                        </h2>
                        <p class="featured-excerpt"><?= htmlspecialchars($mainFeatured['summary_np']) ?></p>
                    </div>
                    <button class="read-more-btn" onclick="openNewsModal(<?= $mainFeatured['id'] ?>)">
                        पूरा समाचार पढ्नुहोस् <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <!-- SUB FEATURED SIDE LIST -->
            <div class="sub-featured-list">
                <h3 style="font-size: 1.15rem; font-weight:800; color:var(--navy-blue); margin-bottom: 4px; border-bottom: 2px solid var(--primary-red); padding-bottom: 6px;">
                    <i class="fas fa-fire" style="color:var(--primary-red);"></i> मुख्य हाइलाइटहरू
                </h3>
                <?php foreach ($subFeatured as $sub): ?>
                <div class="sub-featured-card">
                    <div class="sub-thumb">
                        <img src="<?= htmlspecialchars($sub['image_url']) ?>" alt="thumb">
                    </div>
                    <div class="sub-info">
                        <h4 class="sub-title">
                            <a href="javascript:void(0)" onclick="openNewsModal(<?= $sub['id'] ?>)">
                                <?= htmlspecialchars($sub['title_np']) ?>
                            </a>
                        </h4>
                        <div class="sub-meta">
                            <i class="far fa-clock"></i> <?= date('m-d H:i', strtotime($sub['created_at'])) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CATEGORY FILTER BAR -->
    <section class="container filter-section" id="news-section">
        <div class="category-tabs">
            <button class="tab-btn active" data-category="all">
                <i class="fas fa-th-large"></i> सबै समाचार
            </button>
            <?php foreach ($categories as $cat): ?>
            <button class="tab-btn" data-category="<?= $cat['id'] ?>">
                <i class="fas <?= htmlspecialchars($cat['icon']) ?>"></i> <?= htmlspecialchars($cat['name_np']) ?>
            </button>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- MAIN NEWS GRID & SIDEBAR SECTION -->
    <main class="container main-content-layout">
        <div>
            <div class="section-header">
                <h2>नगर समाचार तथा अद्यावधिक</h2>
            </div>
            <!-- DYNAMIC AJAX NEWS GRID -->
            <div class="news-grid" id="news-grid">
                <!-- Dynamically populated by main.js via AJAX -->
            </div>
        </div>

        <!-- SIDEBAR WIDGETS -->
        <aside class="sidebar">
            <!-- MAYOR DESK WIDGET -->
            <div class="widget mayor-profile">
                <div class="widget-title"><i class="fas fa-user-tie"></i> नगरप्रमुखको भनाइ</div>
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300" alt="Mayor" class="mayor-avatar">
                <div class="mayor-name">माननीय नगरप्रमुख</div>
                <div class="mayor-role">नगर कार्यपालिका अध्यक्ष</div>
                <div class="mayor-msg">
                    "पारदर्शी, प्रविधिमैत्री र समृद्ध नगर निर्माणमा हामी निरन्तर समर्पित छौँ। नागरिकका सल्लाह र सुझाव नै हाम्रो प्रेरणा हुन्।"
                </div>
            </div>

            <!-- EMERGENCY HELPLINES WIDGET -->
            <div class="widget">
                <div class="widget-title"><i class="fas fa-phone-volume"></i> आपत्कालीन सम्पर्क नम्बरहरू</div>
                <div class="helpline-list">
                    <div class="helpline-item">
                        <div class="helpline-info"><i class="fas fa-shield-alt" style="color:var(--navy-blue);"></i> नेपाल प्रहरी</div>
                        <span class="helpline-number">१००</span>
                    </div>
                    <div class="helpline-item">
                        <div class="helpline-info"><i class="fas fa-fire-extinguisher" style="color:var(--primary-red);"></i> नगर दमकल</div>
                        <span class="helpline-number">१०१</span>
                    </div>
                    <div class="helpline-item">
                        <div class="helpline-info"><i class="fas fa-ambulance" style="color:green;"></i> एम्बुलेन्स सेवा</div>
                        <span class="helpline-number">१०२</span>
                    </div>
                </div>
            </div>
        </aside>
    </main>

    <!-- CITIZEN TIP SUBMISSION FORM SECTION -->
    <section class="container citizen-tip-section" id="citizen-tip-section">
        <div class="tip-flex">
            <div class="tip-content">
                <h2><i class="fas fa-bullhorn"></i> नागरिक समाचार तथा सुझाव पठाउनुहोस्</h2>
                <p>तपाईंको वडा वा टोलमा घटेका घटना, विकास निर्माणसम्बन्धी समाचार वा नगरपालिकालाई दिनुपर्ने सुझाव सिधै पठाउनुहोस्।</p>
                <div style="background: rgba(255,255,255,0.1); padding:16px; border-radius:8px;">
                    <i class="fas fa-shield-alt" style="color:var(--accent-gold);"></i> तपाईंको व्यक्तिगत विवरण गोप्य राखिनेछ।
                </div>
            </div>
            <div>
                <form id="citizen-tip-form" class="tip-form">
                    <div class="form-group">
                        <label for="sender_name">तपाईंको नाम *</label>
                        <input type="text" id="sender_name" name="sender_name" class="form-control" required placeholder="उदाहरण: राम शर्मा">
                    </div>
                    <div class="form-group">
                        <label for="sender_phone">मोबाइल नम्बर *</label>
                        <input type="tel" id="sender_phone" name="sender_phone" class="form-control" required placeholder="९८XXXXXXXX">
                    </div>
                    <div class="form-group">
                        <label for="tip_title">समाचार / विषयको शीर्षक *</label>
                        <input type="text" id="tip_title" name="tip_title" class="form-control" required placeholder="विषय लेख्नुहोस्">
                    </div>
                    <div class="form-group">
                        <label for="tip_details">विस्तृत विवरण *</label>
                        <textarea id="tip_details" name="tip_details" rows="3" class="form-control" required placeholder="घटना वा सुझावको विस्तृत विवरण..."></textarea>
                    </div>
                    <div id="tip-form-response" style="margin-bottom: 12px;"></div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> सबमिट गर्नुहोस्
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- SEARCH MODAL POPUP -->
    <div class="modal-overlay" id="search-modal">
        <div class="modal-content" style="max-width: 600px;">
            <button class="modal-close" id="close-search-modal">&times;</button>
            <h3 style="margin-bottom: 16px; color:var(--navy-blue);"><i class="fas fa-search"></i> समाचार खोजी गर्नुहोस्</h3>
            <input type="text" id="search-input" class="form-control" placeholder="समाचारको शीर्षक वा शब्द टाइप गर्नुहोस्..." style="margin-bottom: 20px; font-size:1.1rem; padding:12px;">
            <div id="search-results"></div>
        </div>
    </div>

    <!-- NEWS ARTICLE READ MODAL POPUP -->
    <div class="modal-overlay" id="news-article-modal">
        <div class="modal-content">
            <button class="modal-close" id="close-news-modal">&times;</button>
            <div id="modal-body-content">
                <!-- Dynamically loaded content -->
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="main-footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <h3>नगरिक खबर - नगर समाचार पोर्टल</h3>
                <p style="color:#94A3B8; font-size:0.95rem; margin-bottom:12px;">नगरपालिका सूचना, प्रविधि तथा सञ्चार शाखाद्वारा सञ्चालित आधिकारिक डिजिटल समाचार पत्र।</p>
                <p style="font-size:0.9rem;"><i class="fas fa-map-marker-alt" style="color:var(--accent-gold);"></i> नगर कार्यपालिकाको कार्यालय, नेपाल</p>
            </div>
            <div class="footer-links">
                <h4>महत्त्वपूर्ण लिङ्कहरू</h4>
                <ul>
                    <li><a href="index.php"><i class="fas fa-angle-right"></i> गृहपृष्ठ</a></li>
                    <li><a href="#news-section"><i class="fas fa-angle-right"></i> सार्वजनिक सूचनाहरू</a></li>
                    <li><a href="#citizen-tip-section"><i class="fas fa-angle-right"></i> नागरिक प्रतिक्रिया</a></li>
                    <li><a href="admin/index.php" target="_blank"><i class="fas fa-angle-right"></i> एडमिन प्यानल</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>हामीलाई पछ्याउनुहोस्</h4>
                <div style="display:flex; gap:12px; margin-top:10px;">
                    <a href="#" style="background:#1877F2; color:#FFF; width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="background:#1DA1F2; color:#FFF; width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="background:#FF0000; color:#FFF; width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center;"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                &copy; <?= date('Y') ?> नगरिक खबर - सर्वाधिकार सुरक्षित | Nepal Municipality News Portal
            </div>
        </div>
    </footer>

    <!-- jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Custom Scripts -->
    <script src="assets/js/nepali_date.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
