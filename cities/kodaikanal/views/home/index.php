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
.main-wrap{max-width:1150px;margin:0 auto;padding:24px 16px 0}
.site-footer-main{margin-top:14px!important}
#topPicksSection .ads-grid{margin-bottom:0}
.main-grid{display:grid;grid-template-columns:minmax(0,1fr) 260px;gap:22px}
.main-grid>div:first-child{min-width:0}
.section-title{font-family:"Syne",sans-serif;font-weight:700;font-size:1rem;color:var(--text-dark);margin-bottom:14px;display:flex;align-items:center;gap:8px}
.section-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:18px}
.section-head .section-title{margin-bottom:0}
.see-all{font-size:0.85rem;font-weight:700;color:var(--purple);text-decoration:none;opacity:.92;transition:var(--transition)}
.see-all:hover{color:var(--primary);opacity:1}
.category-panel{background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.18);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border-radius:28px;box-shadow:0 24px 60px rgba(45,23,105,0.14);padding:18px;overflow:hidden;margin-bottom:24px}
.category-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:0}
.category-card{display:block;border-radius:24px;overflow:hidden;position:relative;min-height:220px;box-shadow:0 16px 45px rgba(15,23,57,0.12);transition:transform 0.35s ease-in-out,box-shadow 0.35s ease-in-out,border-color 0.35s ease-in-out;z-index:0;border:2px solid transparent;background-color:#111}
.cat-img{position:relative;height:220px;background-size:cover;background-position:center center;display:flex;align-items:flex-end;overflow:hidden;transition:transform 0.45s ease-in-out,filter 0.45s ease-in-out;transform-origin:center center;z-index:1}
.cat-img::before{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(124,58,237,0.24),rgba(236,72,153,0.16));opacity:0;transition:opacity 0.35s ease-in-out;pointer-events:none;z-index:1}
.cat-img::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0.16),rgba(0,0,0,0.46));opacity:1;transition:opacity 0.35s ease-in-out,background 0.35s ease-in-out;z-index:1}
.cat-info{position:absolute;bottom:18px;left:18px;right:18px;z-index:2;color:#fff}
.cat-title{font-family:"Syne",sans-serif;font-weight:800;font-size:1.05rem;margin:0 0 4px;line-height:1.1;text-shadow:0 10px 30px rgba(0,0,0,0.35);transition:color 0.35s ease-in-out,text-shadow 0.35s ease-in-out}
.cat-count{font-size:0.8rem;color:rgba(255,255,255,0.82);letter-spacing:0.02em;transition:color 0.35s ease-in-out}
@media (hover: hover) and (pointer: fine) {
  .category-card:hover{transform:translateY(-5px) scale(1.02);box-shadow:0 24px 70px rgba(124,58,237,0.18),0 10px 30px rgba(124,58,237,0.12);border-color:#7C3AED}
  .category-card:hover .cat-img{transform:scale(1.08);filter:brightness(0.92)}
  .category-card:hover .cat-img::before{opacity:1}
  .category-card:hover .cat-img::after{background:linear-gradient(180deg,rgba(0,0,0,0.24),rgba(0,0,0,0.6));opacity:0.92}
  .category-card:hover .cat-title{color:#ffffff;text-shadow:0 18px 48px rgba(0,0,0,0.5)}
  .category-card:hover .cat-count{color:rgba(255,255,255,0.95)}
}
@media(max-width:1024px){.category-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:760px){.category-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;padding-bottom:6px;margin-bottom:0}
.category-card{min-width:0;max-width:none;flex:none;scroll-snap-align:none}}
@media(max-width:540px){.category-grid{grid-template-columns:1fr;gap:12px}.cat-img{height:170px}}
.ads-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px}
.ad-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;transition:var(--transition);text-decoration:none;display:block;color:inherit}
.ad-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-hover)}
.ad-card-body{padding:14px}
.ad-card-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px}
.ad-title{font-family:"Syne",sans-serif;font-weight:700;font-size:0.88rem;color:var(--text-dark);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:140px}
.ad-card h3{font-family:"Syne",sans-serif;font-weight:700;font-size:0.95rem;color:var(--text-dark);margin:5px 0 4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ad-card p{font-size:0.8rem;color:var(--text-muted);margin-bottom:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
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
.banner-slider-wrap{position:relative;margin-bottom:18px;overflow:hidden;border-radius:var(--radius);box-shadow:var(--shadow)}
.banner-slider-track{display:flex;transition:transform 0.55s cubic-bezier(0.77,0,0.18,1);will-change:transform}
.promo-banner{min-width:100%;width:100%;border-radius:0;display:flex;align-items:stretch;height:190px;text-decoration:none;color:inherit;position:relative;flex-shrink:0;background:#fff}
.promo-banner-details{flex:0 0 24%;max-width:24%;min-width:180px;padding:12px 14px 12px 18px;display:flex;flex-direction:column;justify-content:space-between;background:#fff;gap:0;position:relative;box-sizing:border-box}
.promo-banner-details::before{content:"";position:absolute;left:0;top:0;bottom:0;width:5px}
.promo-banner.banner-maroon .promo-banner-details::before{background:linear-gradient(180deg,var(--maroon),#c0392b)}
.promo-banner.banner-teal .promo-banner-details::before{background:linear-gradient(180deg,var(--teal),#1a6e7a)}
.promo-banner.banner-amber .promo-banner-details::before{background:linear-gradient(180deg,var(--amber),#92400e)}
.promo-banner-img{flex:1 1 0;min-width:0;height:190px;overflow:hidden;background:linear-gradient(135deg,var(--sand-dark),var(--sand));display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:2rem}
.promo-banner-img img{width:100%;height:190px;object-fit:cover;object-position:center;display:block}
.promo-banner-top{display:flex;flex-wrap:wrap;align-items:center;gap:6px;margin-bottom:2px}
.promo-banner-top h3{font-family:"Syne",sans-serif;font-weight:800;font-size:0.92rem;color:var(--text-dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;width:100%}
.promo-banner p{font-size:0.74rem;color:var(--text-muted);line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.promo-banner-meta{display:flex;flex-direction:column;align-items:flex-start;gap:2px;flex-shrink:0}
.promo-banner-meta span{font-size:0.7rem;font-weight:500;color:var(--text-mid);display:flex;align-items:center;gap:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%}
.promo-cta-rating{display:flex;align-items:center;gap:3px;font-size:0.72rem;color:var(--amber);font-weight:600;margin-top:4px}
.promo-cta-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:40px;font-size:0.72rem;font-weight:700;white-space:nowrap;transition:var(--transition);align-self:flex-start;flex-shrink:0}
.banner-maroon .promo-cta-btn{background:var(--maroon);color:#fff}.banner-teal .promo-cta-btn{background:var(--teal);color:#fff}.banner-amber .promo-cta-btn{background:var(--amber);color:#fff}
.banner-controls{position:absolute;bottom:10px;left:50%;transform:translateX(-50%);display:flex;align-items:center;gap:6px;z-index:2}
.banner-dot{width:7px;height:7px;border-radius:50%;background:rgba(124,58,237,0.25);cursor:pointer;transition:all .25s;border:none;padding:0}
.banner-dot.active{background:var(--purple);width:20px;border-radius:4px}
.slider-wrap{margin-bottom:0;overflow:hidden;width:100%;min-width:0}
.slider-track{display:flex;gap:14px;overflow-x:auto;scroll-behavior:smooth;scrollbar-width:none;padding-bottom:4px;width:100%}
.slider-track::-webkit-scrollbar{display:none}
.slider-card{flex:0 0 220px;background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;cursor:pointer;transition:var(--transition);border:1.5px solid transparent;text-decoration:none;color:inherit}
.slider-card:hover{transform:translateY(-4px) scale(1.02);box-shadow:var(--shadow-hover);border-color:var(--purple-muted)}
.slider-img{height:110px;background:linear-gradient(135deg,var(--sand-dark),var(--sand));display:flex;align-items:center;justify-content:center;font-size:2rem;color:var(--text-muted);overflow:hidden}
.slider-img img{width:100%;height:100%;object-fit:cover;display:block}
.slider-body{padding:12px}
.slider-body h4{font-size:0.85rem;font-weight:700;color:var(--text-dark);margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.slider-body p{font-size:0.75rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ad-card-img{height:130px;background:linear-gradient(135deg,var(--sand-dark),var(--sand));display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:2rem;overflow:hidden}
.ad-card-img img{width:100%;height:100%;object-fit:cover;display:block}
.pro-ad-card{background:#fff;border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow);transition:var(--transition);cursor:pointer;border:1.5px solid transparent;text-decoration:none;color:inherit;display:block}
.pro-ad-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-hover);border-color:var(--amber)}
.pro-thumb{height:118px;background:linear-gradient(135deg,#1a1018 0%,var(--maroon) 100%);display:flex;align-items:center;justify-content:center;font-size:2.2rem;color:rgba(255,255,255,0.5);position:relative;overflow:hidden}
.pro-thumb img{width:100%;height:100%;object-fit:cover;display:block}
.pro-yt-badge{position:absolute;bottom:8px;right:8px;background:rgba(0,0,0,0.6);color:#fff;border-radius:4px;padding:2px 6px;font-size:0.65rem;font-weight:700;display:flex;align-items:center;gap:4px}
.pro-ad-body{padding:12px 14px}
.pro-ad-body h4{font-weight:700;font-size:0.88rem;color:var(--text-dark);margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pro-ad-body p{font-size:0.75rem;color:var(--text-muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.pro-services{display:flex;flex-wrap:wrap;gap:5px;margin-top:8px}
.pro-services span{background:var(--purple-light);color:var(--purple);font-size:0.68rem;font-weight:600;padding:2px 8px;border-radius:40px}
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
.premium-banner{border-radius:var(--radius);overflow:hidden;cursor:pointer;background:linear-gradient(135deg,var(--primary),#9333ea);padding:20px 16px;text-align:center;transition:var(--transition);box-shadow:var(--shadow)}
.premium-banner:hover{transform:translateY(-3px);box-shadow:0 12px 40px rgba(124,58,237,0.3)}
.premium-banner h4{font-family:"Syne",sans-serif;font-weight:800;font-size:1rem;color:#fff;margin-bottom:6px}
.premium-banner p{font-size:0.78rem;color:rgba(255,255,255,0.75);margin-bottom:14px}
.btn-upgrade{display:inline-block;padding:8px 20px;background:#fff;color:var(--primary);border-radius:40px;font-size:0.82rem;font-weight:700;transition:var(--transition)}
.btn-upgrade:hover{background:var(--sand);transform:scale(1.04)}
.sidebar{display:flex;flex-direction:column;gap:14px}
@media(max-width:768px){body{padding-bottom:calc(var(--footer-h) + 14px + env(safe-area-inset-bottom))}.main-grid{grid-template-columns:1fr}.sidebar{display:none}.cat-grid{grid-template-columns:repeat(3,1fr)}.hero h1{font-size:1.5rem}.promo-banner{flex-direction:column;height:auto}.promo-banner-details{flex:0 0 auto;max-width:100%;width:100%;min-width:0;min-height:190px}.promo-banner-img{flex:0 0 auto;max-width:100%;width:100%;height:160px}.promo-banner-details::before{width:100%;height:4px;bottom:auto}.promo-banner-meta{flex-direction:row;flex-wrap:wrap;gap:8px}}
@media(max-width:480px){.ads-grid{grid-template-columns:1fr}.cat-grid{grid-template-columns:repeat(3,1fr);gap:8px}.slider-card{flex:0 0 170px}.promo-banner-img{height:140px}.promo-banner-details{min-height:200px}}
</style>';
require CITY_DIR . '/views/layout/header.php';
$catIcons = ['Restaurants'=>'bi-shop','Hotels & Stays'=>'bi-building','Shopping'=>'bi-bag-heart','Health & Clinic'=>'bi-heart-pulse','Services'=>'bi-tools','Education'=>'bi-mortarboard','Automobile'=>'bi-car-front','Photography'=>'bi-camera'];
$catColors = ['ic-purple','ic-green','ic-maroon','ic-teal','ic-amber','ic-purple','ic-green','ic-maroon'];
$catImages = [
    'Travels' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80',
    'Travel' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80',
    'Hotels' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1200&q=80',
    'Hotel' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1200&q=80',
    'Hotels & Stays' => 'https://images.unsplash.com/photo-1551882547-ff46d4462ae1?auto=format&fit=crop&w=1200&q=80',
    'Cafe' => 'https://images.unsplash.com/photo-1470337458703-46ad1756a187?auto=format&fit=crop&w=1200&q=80',
    'Cafes' => 'https://images.unsplash.com/photo-1470337458703-46ad1756a187?auto=format&fit=crop&w=1200&q=80',
    'Restaurant' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
    'Restaurants' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
    'Bakery' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=1200&q=80',
    'Photography' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=1200&q=80',
    'Shopping' => 'https://images.unsplash.com/photo-1472851294608-062f824d29cc?auto=format&fit=crop&w=1200&q=80',
    'Health & Clinic' => 'https://images.unsplash.com/photo-1498804103079-a6351b050096?auto=format&fit=crop&w=1200&q=80',
    'Services' => 'https://images.unsplash.com/photo-1559126763-55ef1198cc18?auto=format&fit=crop&w=1200&q=80',
    'Education' => 'https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1200&q=80',
    'Automobile' => 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?auto=format&fit=crop&w=1200&q=80',
    'Default' => 'https://images.unsplash.com/photo-1519821172141-bd4c0d3360c2?auto=format&fit=crop&w=1200&q=80'
];
$listingImage = function(array $ad): string {
    $file = $ad['top_banner'] ?? ($ad['first_image'] ?? '');
    return BASE_URL . '/assets/uploads/listings/' . htmlspecialchars($file ?: 'demo.jpg');
};
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
  <div class="category-panel">
    <div class="section-head">
      <h2 class="section-title"><i class="bi bi-grid-fill" style="color:var(--primary)"></i> Categories</h2>
      <a href="<?= $cityUrl ?>/search" class="see-all">See All</a>
    </div>
    <div class="category-grid">
      <?php foreach($categories as $i=>$cat): ?>
      <?php
        if (!empty($cat['image'])) {
            $cimg = BASE_URL . '/assets/uploads/categories/' . $cat['image'];
        } else {
            $cimg = $catImages[$cat['name']] ?? $catImages[strtolower($cat['name'])] ?? $catImages['Default'];
        }
      ?>
      <a href="<?= $cityUrl ?>/search?cat=<?= $cat['id'] ?>" class="category-card">
        <div class="cat-img" style="background-image:url('<?= htmlspecialchars($cimg) ?>')">
          <div class="cat-info">
            <h3 class="cat-title"><?= htmlspecialchars($cat['name']) ?></h3>
            <p class="cat-count"><?= $cat['listing_count'] ?> listings</p>
          </div>
        </div>
      </a>
      <?php endforeach ?>
    </div>
  </div>
  <?php if(!empty($banners)): ?>
  <h2 class="section-title"><i class="bi bi-star-fill" style="color:var(--amber)"></i> Featured Businesses</h2>
  <div class="banner-slider-wrap" id="bannerSlider">
    <button class="banner-arrow prev" id="bannerPrev" type="button"><i class="bi bi-chevron-left"></i></button>
    <button class="banner-arrow next" id="bannerNext" type="button"><i class="bi bi-chevron-right"></i></button>
    <div class="banner-slider-track" id="bannerTrack">
      <?php $bcols=['banner-maroon','banner-teal','banner-amber']; foreach($banners as $bi=>$b): ?>
      <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($b['slug']) ?>" class="promo-banner <?= $bcols[$bi%count($bcols)] ?>">
        <div class="promo-banner-details">
          <div class="promo-banner-top">
            <span class="plan-tag plan-pro">PRO</span>
            <h3><?= htmlspecialchars($b['business_name']) ?></h3>
          </div>
          <p><?= htmlspecialchars(Helper::truncate($b['short_description'] ?? '', 80)) ?></p>
          <div class="promo-banner-meta">
            <?php if(!empty($b['address'])): ?><span><i class="bi bi-geo-alt-fill" style="color:var(--maroon)"></i> <?= htmlspecialchars($b['address']) ?></span><?php endif ?>
            <?php if(!empty($b['phone'])): ?><span><i class="bi bi-telephone-fill" style="color:var(--maroon)"></i> <?= htmlspecialchars($b['phone']) ?></span><?php endif ?>
          </div>
          <?php if($b['avg_rating']): ?>
          <div class="promo-cta-rating"><i class="bi bi-star-fill"></i> <?= $b['avg_rating'] ?> <span style="color:var(--text-muted);font-weight:400">(<?= $b['review_count'] ?>)</span></div>
          <?php endif ?>
          <div class="promo-cta-btn"><i class="bi bi-arrow-right"></i> View Ad</div>
        </div>
        <div class="promo-banner-img"><img src="<?= $listingImage($b) ?>" alt="<?= htmlspecialchars($b['business_name']) ?>"></div>
      </a>
      <?php endforeach ?>
    </div>
    <?php if(count($banners)>1): ?>
    <div class="banner-controls">
      <?php foreach($banners as $i=>$b): ?><button class="banner-dot <?= $i===0?'active':'' ?>" type="button" data-i="<?= $i ?>"></button><?php endforeach ?>
    </div>
    <?php endif ?>
  </div>
  <?php endif ?>

  <?php
    $recentAll = array_merge($banners ?? [], $featured ?? [], $basics ?? []);
    usort($recentAll, fn($a,$b) => strtotime($b['published_at']) - strtotime($a['published_at']));
  ?>
  <?php if(!empty($recentAll)): ?>
  <div id="recentSliderSection">
    <h2 class="section-title"><i class="bi bi-clock-history" style="color:var(--teal)"></i> Recently Updated</h2>
    <div class="slider-wrap">
      <div class="slider-track" id="sliderTrack">
        <?php foreach($recentAll as $ad): ?>
        <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($ad['slug']) ?>" class="slider-card">
          <div class="slider-img"><img src="<?= $listingImage($ad) ?>" alt="<?= htmlspecialchars($ad['business_name']) ?>"></div>
          <div class="slider-body">
            <h4><?= htmlspecialchars($ad['business_name']) ?></h4>
            <p><?= htmlspecialchars($ad['cat_name'] ?? ucfirst($ad['plan_level'])) ?></p>
          </div>
        </a>
        <?php endforeach ?>
      </div>
    </div>
  </div>
  <?php endif ?>

  <?php $topPro = $banners[0] ?? null; $topPremium = $featured[0] ?? null; ?>
  <?php if($topPro || $topPremium): ?>
  <div id="topPicksSection">
    <h2 class="section-title"><i class="bi bi-star-fill" style="color:var(--amber)"></i> Top Picks</h2>
    <div class="ads-grid">
      <?php foreach(array_filter([$topPro, $topPremium]) as $ad): ?>
      <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($ad['slug']) ?>" class="ad-card">
        <div class="ad-card-img"><img src="<?= $listingImage($ad) ?>" alt="<?= htmlspecialchars($ad['business_name']) ?>"></div>
        <div class="ad-card-body">
          <span class="plan-tag plan-<?= htmlspecialchars(strtolower($ad['plan_level'])) ?>"><?= strtoupper(htmlspecialchars($ad['plan_level'])) ?></span>
          <h3><?= htmlspecialchars($ad['business_name']) ?></h3>
          <p><i class="bi bi-geo-alt" style="font-size:0.72rem"></i> <?= htmlspecialchars($ad['address'] ?? '') ?></p>
          <div class="ad-card-meta">
            <?php if(!empty($ad['avg_rating'])): ?><div class="ad-card-rating"><i class="bi bi-star-fill"></i> <?= $ad['avg_rating'] ?></div><?php endif ?>
            <?php if(!empty($ad['phone'])): ?><div class="ad-card-phone"><i class="bi bi-telephone-fill"></i> Call Now</div><?php endif ?>
          </div>
        </div>
      </a>
      <?php endforeach ?>
    </div>
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
  <?php if(!empty($banners)): $sidePro = $banners[0]; ?>
  <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($sidePro['slug']) ?>" class="pro-ad-card">
    <div class="pro-thumb">
      <img src="<?= $listingImage($sidePro) ?>" alt="<?= htmlspecialchars($sidePro['business_name']) ?>">
      <div class="pro-yt-badge"><i class="bi bi-star-fill"></i> PRO</div>
    </div>
    <div class="pro-ad-body">
      <span class="plan-tag plan-pro">PRO AD</span>
      <h4><?= htmlspecialchars($sidePro['business_name']) ?></h4>
      <p><?= htmlspecialchars(Helper::truncate($sidePro['short_description'] ?? '', 80)) ?></p>
      <div class="pro-services"><span>Featured</span><span><?= htmlspecialchars($sidePro['profession'] ?? 'Business') ?></span></div>
    </div>
  </a>
  <?php endif ?>

  <!-- 4. Basic Ad -->
  <?php if(!empty($basics)): $sideBasic = $basics[0]; ?>
  <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($sideBasic['slug']) ?>" class="ad-card">
    <div class="ad-card-img" style="height:100px"><img src="<?= $listingImage($sideBasic) ?>" alt="<?= htmlspecialchars($sideBasic['business_name']) ?>"></div>
    <div class="ad-card-body">
      <span class="plan-tag plan-basic">Basic</span>
      <h3><?= htmlspecialchars($sideBasic['business_name']) ?></h3>
      <p><?= htmlspecialchars(Helper::truncate($sideBasic['short_description'] ?? '', 70)) ?></p>
      <div class="ad-card-meta">
        <?php if(!empty($sideBasic['avg_rating'])): ?><div class="ad-card-rating"><i class="bi bi-star-fill"></i> <?= $sideBasic['avg_rating'] ?></div><?php endif ?>
        <?php if(!empty($sideBasic['phone'])): ?><div class="ad-card-phone"><i class="bi bi-telephone-fill"></i> Call</div><?php endif ?>
      </div>
    </div>
  </a>
  <?php endif ?>
</div>
</div></div>
</main>
<script>
function goSearch(){var q=document.getElementById("hsearch").value.trim();if(q)window.location.href="<?= $cityUrl ?>/search?q="+encodeURIComponent(q);}
var bIdx=0,bTrack=document.getElementById("bannerTrack"),bSlides=document.querySelectorAll(".promo-banner"),bDots=document.querySelectorAll(".banner-dot");
function slideBanner(d){if(!bSlides.length||!bTrack)return;bIdx=(bIdx+d+bSlides.length)%bSlides.length;bTrack.style.transform="translateX(-"+bIdx*100+"%)";bDots.forEach(function(dot,i){dot.classList.toggle("active",i===bIdx);});}
bDots.forEach(function(dot){dot.addEventListener("click",function(){bIdx=parseInt(dot.dataset.i,10)||0;slideBanner(0);});});
if(bSlides.length>1)setInterval(function(){slideBanner(1);},4000);
var sTrack=document.getElementById("sliderTrack"),sDir=1,sTimer;
function startRecentSlider(){if(!sTrack)return;sTimer=setInterval(function(){sTrack.scrollLeft+=sDir*1.5;if(sTrack.scrollLeft+sTrack.clientWidth>=sTrack.scrollWidth-10)sDir=-1;if(sTrack.scrollLeft<=0)sDir=1;},16);}
startRecentSlider();
if(sTrack){sTrack.addEventListener("mouseenter",function(){clearInterval(sTimer);});sTrack.addEventListener("mouseleave",startRecentSlider);sTrack.addEventListener("touchstart",function(){clearInterval(sTimer);});}
</script>
<?php require CITY_DIR . '/views/layout/footer.php'; ?>
