<?php
$pageTitle  = 'Local Businesses';
$activePage = 'home';
$extraCss = '<style>
.hero{background:linear-gradient(135deg,#2d1b69 0%,var(--primary) 55%,#3a7c5a 100%);padding:40px 20px 36px}
.hero-inner{max-width:680px;margin:0 auto;text-align:center}
.hero-badge{display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,0.15);border-radius:40px;padding:4px 12px;font-size:0.72rem;font-weight:600;color:#fff;margin-bottom:14px}
.hero h1{font-family:"Syne",sans-serif;font-weight:800;font-size:2rem;color:#fff;margin-bottom:10px;line-height:1.2}
.hero h1 em{color:#a78bfa;font-style:normal}
.hero p{color:rgba(255,255,255,0.8);font-size:0.9rem;margin-bottom:22px}
.hero-search{display:flex;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.15);max-width:480px;margin:0 auto}
.hero-search input{flex:1;padding:14px 16px;border:none;font-size:0.9rem;font-family:inherit;outline:none}
.hero-search button{padding:14px 20px;background:var(--primary);color:#fff;border:none;font-size:1rem;cursor:pointer}
.main-wrap{max-width:1150px;margin:0 auto;padding:24px 16px}
.main-grid{display:grid;grid-template-columns:1fr 260px;gap:22px}
.section-title{font-family:"Syne",sans-serif;font-weight:700;font-size:1rem;color:var(--text-dark);margin-bottom:14px;display:flex;align-items:center;gap:8px}
.cat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:24px}
.cat-card{background:#fff;border-radius:var(--radius);padding:14px 10px;text-align:center;cursor:pointer;transition:var(--transition);box-shadow:var(--shadow);text-decoration:none;display:block;color:inherit}
.cat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-hover)}
.cat-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin:0 auto 8px}
.ic-purple{background:var(--purple-light);color:var(--purple)}.ic-green{background:var(--green-light);color:var(--green)}
.ic-maroon{background:var(--maroon-light);color:var(--maroon)}.ic-teal{background:var(--teal-light);color:var(--teal)}.ic-amber{background:var(--amber-light);color:var(--amber)}
.cat-card h4{font-family:"Syne",sans-serif;font-weight:700;font-size:0.75rem;color:var(--text-dark);margin-bottom:2px}
.cat-card span{font-size:0.68rem;color:var(--text-muted)}
.ads-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px}
.ad-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;transition:var(--transition);text-decoration:none;display:block;color:inherit}
.ad-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-hover)}
.ad-card-body{padding:14px}
.ad-card-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px}
.ad-title{font-family:"Syne",sans-serif;font-weight:700;font-size:0.88rem;color:var(--text-dark);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:140px}
.plan-tag{padding:2px 7px;border-radius:40px;font-size:0.65rem;font-weight:700}
.plan-pro{background:var(--green-light);color:var(--green)}.plan-premium{background:var(--amber-light);color:var(--amber)}.plan-basic{background:var(--teal-light);color:var(--teal)}
.ad-desc{font-size:0.78rem;color:var(--text-muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:7px}
.ad-meta{font-size:0.72rem;color:var(--text-muted);display:flex;justify-content:space-between;align-items:center}
.banner-wrap{border-radius:var(--radius);overflow:hidden;margin-bottom:22px;position:relative}
.banner-track{display:flex;transition:transform 0.4s ease}
.banner-slide{flex:0 0 100%;padding:24px;display:flex;gap:20px;align-items:center;min-height:160px;text-decoration:none;color:#fff;position:relative;z-index:1;overflow:hidden}
.banner-overlay{position:absolute;inset:0;background:rgba(0,0,0,0.3);z-index:-1}
.banner-img-bg{position:absolute;inset:0;background-size:cover;background-position:center;z-index:-2;transition:transform 0.5s ease}
.banner-slide:hover .banner-img-bg{transform:scale(1.05)}
.banner-content-wrap{display:flex;gap:18px;align-items:center;width:100%}
.banner-badge{display:inline-block;padding:2px 8px;background:rgba(255,255,255,0.25);border-radius:4px;font-size:0.6rem;font-weight:800;letter-spacing:1px;margin-bottom:6px;text-transform:uppercase}
.banner-btn{margin-top:12px;display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#fff;color:var(--text-dark);border-radius:40px;font-size:0.75rem;font-weight:700;transition:var(--transition)}
.banner-slide:hover .banner-btn{background:var(--primary);color:#fff}
.banner-arrow{position:absolute;top:50%;transform:translateY(-50%);width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.25);color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1rem;z-index:10;transition:var(--transition)}
.banner-arrow:hover{background:rgba(255,255,255,0.4)}
.b-maroon{background:linear-gradient(135deg,var(--maroon),#c0392b)}
.b-teal{background:linear-gradient(135deg,var(--teal),#1a6070)}
.b-green{background:linear-gradient(135deg,var(--green),#2d6a4f)}
.banner-arrow.prev{left:8px}.banner-arrow.next{right:8px}
.free-scroll-box{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden}
.free-scroll-head{padding:12px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.free-scroll-head h4{font-family:"Syne",sans-serif;font-weight:700;font-size:0.9rem;color:var(--text-dark)}
.live-dot{width:7px;height:7px;border-radius:50%;background:var(--green);animation:pulse 1.4s infinite;display:inline-block}
@keyframes pulse{0%,100%{box-shadow:0 0 0 0 rgba(58,124,90,0.5)}50%{box-shadow:0 0 0 5px rgba(58,124,90,0)}}
.free-scroll-list{height:320px;overflow-y:auto;scrollbar-width:thin;scrollbar-color:var(--border) transparent}
.free-item{display:flex;align-items:center;gap:10px;padding:10px 14px;border-bottom:1px solid var(--sand-dark);transition:var(--transition);cursor:pointer}
.free-item:hover{background:var(--purple-light)}
.free-avatar{width:36px;height:36px;border-radius:50%;background:var(--purple-light);display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;color:var(--purple);flex-shrink:0}
.free-info h5{font-size:0.82rem;font-weight:600;color:var(--text-dark);margin-bottom:1px}
.free-info p{font-size:0.72rem;color:var(--text-muted)}
.sidebar-cta{background:linear-gradient(135deg,var(--primary),#2d1b69);border-radius:var(--radius);padding:18px;text-align:center;color:#fff;margin-top:12px}
.sidebar-cta h4{font-family:"Syne",sans-serif;font-weight:800;font-size:0.95rem;margin-bottom:6px}
.sidebar-cta p{font-size:0.76rem;opacity:0.85;margin-bottom:12px;line-height:1.5}
.sidebar-cta a{display:inline-flex;align-items:center;gap:5px;padding:8px 18px;background:#fff;color:var(--primary);border-radius:40px;font-weight:700;font-size:0.8rem}
.premium-banner{border-radius:var(--radius);overflow:hidden;cursor:pointer;background:linear-gradient(135deg,var(--primary),#9333ea);padding:20px 16px;text-align:center;transition:var(--transition);box-shadow:var(--shadow)}
.premium-banner:hover{transform:translateY(-3px);box-shadow:0 12px 40px rgba(124,58,237,0.3)}
.premium-banner h4{font-family:"Syne",sans-serif;font-weight:800;font-size:1rem;color:#fff;margin-bottom:6px}
.premium-banner p{font-size:0.78rem;color:rgba(255,255,255,0.75);margin-bottom:14px}
.btn-upgrade{display:inline-block;padding:8px 20px;background:#fff;color:var(--primary);border-radius:40px;font-size:0.82rem;font-weight:700;transition:var(--transition);cursor:pointer;text-decoration:none}
.btn-upgrade:hover{background:var(--sand);transform:scale(1.04)}
.pro-ad-card{background:#fff;border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow);transition:var(--transition);border:1.5px solid transparent;text-decoration:none;display:block;color:inherit}
.pro-ad-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-hover);border-color:var(--amber)}
.pro-thumb{height:100px;background:linear-gradient(135deg,#1a1018 0%,var(--maroon) 100%);display:flex;align-items:center;justify-content:center;font-size:2.2rem;color:rgba(255,255,255,0.5);position:relative}
.pro-yt-badge{position:absolute;bottom:8px;right:8px;background:rgba(0,0,0,0.6);color:#fff;border-radius:4px;padding:2px 6px;font-size:0.65rem;font-weight:700;display:flex;align-items:center;gap:4px}
.pro-ad-body{padding:12px 14px}
.pro-ad-body h4{font-weight:700;font-size:0.88rem;color:var(--text-dark);margin-bottom:3px}
.pro-ad-body p{font-size:0.75rem;color:var(--text-muted)}
.pro-services{display:flex;flex-wrap:wrap;gap:5px;margin-top:8px}
.pro-services span{background:var(--sand-dark);color:var(--text-mid);border-radius:4px;padding:2px 7px;font-size:0.68rem;font-weight:600}
@media(max-width:768px){.main-grid{grid-template-columns:1fr}.sidebar{display:none}.cat-grid{grid-template-columns:repeat(3,1fr)}.hero h1{font-size:1.5rem}}
@media(max-width:480px){.ads-grid{grid-template-columns:1fr}.cat-grid{grid-template-columns:repeat(3,1fr);gap:8px}}
</style>';
require CITY_DIR . '/views/layout/header.php';
$catIcons = ['Restaurants'=>'bi-shop','Hotels & Stays'=>'bi-building','Shopping'=>'bi-bag-heart','Health & Clinic'=>'bi-heart-pulse','Services'=>'bi-tools','Education'=>'bi-mortarboard','Automobile'=>'bi-car-front','Photography'=>'bi-camera'];
$catColors = ['ic-purple','ic-green','ic-maroon','ic-teal','ic-amber','ic-purple','ic-green','ic-maroon'];
?>
<main>
<section class="hero">
  <div class="hero-inner">
    <div class="hero-badge"><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars(CITY_NAME) ?></div>
    <h1>Find the Best <em>Local Businesses</em> Near You</h1>
    <p>Discover trusted shops, services & professionals in <?= htmlspecialchars(CITY_NAME) ?></p>
    <div class="hero-search">
      <input type="text" id="hsearch" placeholder="Search businesses..." onkeydown="if(event.key==='Enter')goSearch()">
      <button onclick="goSearch()"><i class="bi bi-search"></i></button>
    </div>
  </div>
</section>
<div class="main-wrap"><div class="main-grid">
<div>
  <h2 class="section-title"><i class="bi bi-grid-fill" style="color:var(--primary)"></i> Categories</h2>
  <div class="cat-grid">
    <?php foreach($categories as $i=>$cat): ?>
    <a href="<?= $cityUrl ?>/search?cat=<?= $cat['id'] ?>" class="cat-card">
      <div class="cat-icon <?= $catColors[$i%count($catColors)] ?>"><i class="bi <?= $catIcons[$cat['name']] ?? 'bi-shop' ?>"></i></div>
      <h4><?= htmlspecialchars($cat['name']) ?></h4>
      <span><?= $cat['listing_count'] ?> listings</span>
    </a>
    <?php endforeach ?>
  </div>
  <?php if(!empty($banners)): ?>
  <h2 class="section-title"><i class="bi bi-star-fill" style="color:var(--amber)"></i> Featured</h2>
  <div class="banner-wrap"><div class="banner-track" id="btrack">
    <?php $bcols=['b-maroon','b-teal','b-green']; foreach($banners as $bi=>$b): 
      $isPro = strtolower($b['plan_level']) === 'pro';
    ?>
    <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($b['slug']) ?>" class="banner-slide <?= $bcols[$bi%3] ?>">
      <?php if($isPro && !empty($b['top_banner'])): ?>
        <div class="banner-img-bg" style="background-image:url('<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($b['top_banner']) ?>')"></div>
        <div class="banner-overlay"></div>
      <?php endif ?>
      <div class="banner-content-wrap">
        <?php if(!$isPro): ?>
          <div style="width:90px;height:90px;border-radius:12px;overflow:hidden;border:2px solid rgba(255,255,255,0.3);flex-shrink:0;background:rgba(0,0,0,0.1);display:flex;align-items:center;justify-content:center">
            <?php 
              $thumb = !empty($b['top_banner']) ? $b['top_banner'] : (!empty($b['first_image']) ? $b['first_image'] : '');
              if($thumb): ?>
              <img src="<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($b['business_name']) ?>" style="width:100%;height:100%;object-fit:cover">
            <?php else: ?>
              <div style="font-size:2.5rem">🏢</div>
            <?php endif ?>
          </div>
        <?php endif ?>
        <div style="flex:1">
          <div class="banner-badge"><?= $isPro ? 'PRO Recommended' : 'Premium Listing' ?></div>
          <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.2rem;margin-bottom:4px;line-height:1.2"><?= htmlspecialchars($b['business_name']) ?></div>
          <div style="font-size:0.85rem;opacity:0.9;line-height:1.4;max-width:450px"><?= htmlspecialchars(Helper::truncate($b['short_description']??'', 110)) ?></div>
          <div style="display:flex;align-items:center;gap:15px;margin-top:10px">
            <?php if($b['avg_rating']): ?><div style="font-size:0.8rem;font-weight:700;color:#fbbf24"><i class="bi bi-star-fill"></i> <?= $b['avg_rating'] ?> (<?= $b['review_count'] ?>)</div><?php endif ?>
            <div class="banner-btn">View Ads <i class="bi bi-arrow-right"></i></div>
          </div>
        </div>
      </div>
    </a>
    <?php endforeach ?>
  </div>
  <button class="banner-arrow prev" onclick="slideBanner(-1)"><i class="bi bi-chevron-left"></i></button>
  <button class="banner-arrow next" onclick="slideBanner(1)"><i class="bi bi-chevron-right"></i></button>
  </div>
  <?php endif ?>
  <?php if(!empty($featured)): ?>
  <h2 class="section-title" style="margin-top:4px"><i class="bi bi-lightning-charge-fill" style="color:var(--purple)"></i> Premium Listings</h2>
  <div class="ads-grid">
    <?php foreach($featured as $ad): ?>
    <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($ad['slug']) ?>" class="ad-card">
      <div class="ad-card-body">
        <div class="ad-card-top"><div class="ad-title"><?= htmlspecialchars($ad['business_name']) ?></div><span class="plan-tag plan-<?= $ad['plan_level'] ?>"><?= ucfirst($ad['plan_level']) ?></span></div>
        <div class="ad-desc"><?= htmlspecialchars($ad['short_description']??'') ?></div>
        <div class="ad-meta"><span><?= htmlspecialchars($ad['cat_name']??'') ?></span><?php if($ad['avg_rating']): ?><span style="color:#f59e0b">⭐ <?= $ad['avg_rating'] ?></span><?php endif ?></div>
      </div>
    </a>
    <?php endforeach ?>
  </div>
  <?php endif ?>
  <?php if(!empty($basics)): ?>
  <h2 class="section-title"><i class="bi bi-grid" style="color:var(--teal)"></i> More Businesses</h2>
  <div class="ads-grid">
    <?php foreach($basics as $ad): ?>
    <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($ad['slug']) ?>" class="ad-card">
      <div class="ad-card-body">
        <div class="ad-card-top"><div class="ad-title"><?= htmlspecialchars($ad['business_name']) ?></div><span class="plan-tag plan-basic">Basic</span></div>
        <div class="ad-desc"><?= htmlspecialchars($ad['short_description']??'') ?></div>
        <div class="ad-meta"><span><?= htmlspecialchars($ad['cat_name']??'') ?></span></div>
      </div>
    </a>
    <?php endforeach ?>
  </div>
  <?php endif ?>
</div>
<div class="sidebar">
  <!-- 1. Free User List -->
  <?php if(!empty($freeUsers)): ?>
  <div class="free-scroll-box" id="freeScrollBox">
    <div class="free-scroll-head">
      <h4>Local Members</h4>
      <div class="live-dot"></div>
    </div>
    <div class="free-scroll-list">
      <?php foreach($freeUsers as $fu): ?>
      <div class="free-item">
        <div class="free-avatar"><?= strtoupper(substr($fu['name'],0,1)) ?></div>
        <div class="free-info">
          <h5><?= htmlspecialchars($fu['name']) ?></h5>
          <p><?= htmlspecialchars($fu['profession']??'') ?></p>
        </div>
      </div>
      <?php endforeach ?>
    </div>
  </div>
  <?php endif ?>

  <!-- 2. Upgrade Plan Banner -->
  <div class="premium-banner" onclick="window.location='<?= $cityUrl ?>/post-ad'">
    <h4>🚀 Boost Your Business</h4>
    <p>Upgrade to Pro and reach thousands of customers in <?= htmlspecialchars(CITY_NAME) ?> daily.</p>
    <div class="btn-upgrade">Upgrade Now →</div>
  </div>

  <!-- 3. Pro Ad -->
  <?php if(!empty($sidebarProAd)): ?>
  <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($sidebarProAd['slug']) ?>" class="pro-ad-card">
    <div class="pro-thumb">
      🎬
      <div class="pro-yt-badge"><i class="bi bi-youtube"></i> Watch</div>
    </div>
    <div class="pro-ad-body">
      <span class="plan-tag plan-pro">PRO AD</span>
      <h4><?= htmlspecialchars($sidebarProAd['business_name']) ?></h4>
      <p><?= htmlspecialchars($sidebarProAd['short_description']??'') ?></p>
      <?php if(!empty($sidebarProAd['cat_name'])): ?>
      <div class="pro-services"><span><?= htmlspecialchars($sidebarProAd['cat_name']) ?></span></div>
      <?php endif ?>
    </div>
  </a>
  <?php endif ?>

  <!-- 4. Basic Ad -->
  <?php if(!empty($sidebarBasicAd)): ?>
  <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($sidebarBasicAd['slug']) ?>" class="ad-card" style="display:block;text-decoration:none;color:inherit">
    <div class="ad-card-body">
      <div class="ad-card-top">
        <div class="ad-title"><?= htmlspecialchars($sidebarBasicAd['business_name']) ?></div>
        <span class="plan-tag plan-basic">Basic</span>
      </div>
      <div class="ad-desc"><?= htmlspecialchars($sidebarBasicAd['short_description']??'') ?></div>
      <div class="ad-meta"><span><?= htmlspecialchars($sidebarBasicAd['cat_name']??'') ?></span></div>
    </div>
  </a>
  <?php endif ?>
</div>
</div></div>
</main>
<script>
function goSearch(){var q=document.getElementById("hsearch").value.trim();if(q)window.location.href="<?= $cityUrl ?>/search?q="+encodeURIComponent(q);}
var bIdx=0,bSlides=document.querySelectorAll(".banner-slide");
function slideBanner(d){if(!bSlides.length)return;bIdx=(bIdx+d+bSlides.length)%bSlides.length;document.getElementById("btrack").style.transform="translateX(-"+bIdx*100+"%)";}
if(bSlides.length>1)setInterval(function(){slideBanner(1);},4000);
</script>
<?php require CITY_DIR . '/views/layout/footer.php'; ?>
