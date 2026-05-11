<?php
$pageTitle  = $q ? "Search: ".htmlspecialchars($q) : "Search Businesses";
$activePage = "search";
$extraCss = <<<'ENDCSS'
<style>
.search-bar{background:#fff;border-bottom:1px solid var(--border);padding:12px 16px;position:sticky;top:var(--header-h);z-index:800}
.search-inner{max-width:900px;margin:0 auto;display:flex;gap:10px;flex-wrap:wrap}
.s-inp-wrap{flex:1;min-width:180px;display:flex;background:var(--sand-light);border-radius:10px;overflow:hidden;border:1.5px solid var(--border)}
.s-inp-wrap input{flex:1;padding:10px 12px;border:none;background:transparent;font-family:inherit;font-size:0.88rem;outline:none}
.s-inp-wrap button{padding:10px 14px;background:var(--primary);color:#fff;border:none;cursor:pointer}
.s-select{padding:10px 12px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:0.83rem;background:#fff;outline:none;cursor:pointer;min-height:44px}
.results-wrap{max-width:900px;margin:0 auto;padding:36px 16px 20px}
.results-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
.result-card{background:#fff;border:1px solid rgba(226,213,240,.9);border-radius:8px;box-shadow:var(--shadow);overflow:hidden;display:block;transition:var(--transition);text-decoration:none;color:inherit;min-width:0}
.result-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-hover)}
.result-thumb{width:100%;height:118px;background:var(--purple-light);overflow:hidden}
.result-thumb img{width:100%;height:100%;object-fit:cover;display:block}
.result-body{padding:14px 16px}
.result-plan{display:inline-flex;margin-bottom:7px;padding:2px 7px;border-radius:40px;background:var(--green-light);color:var(--green);font-size:0.65rem;font-weight:800;text-transform:uppercase}
.result-plan.plan-premium{background:var(--amber-light);color:var(--amber)}
.result-plan.plan-basic{background:var(--teal-light);color:var(--teal)}
.result-name{font-family:"Syne",sans-serif;font-weight:700;font-size:0.95rem;color:var(--text-dark);margin-bottom:5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.result-meta{font-size:0.78rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:8px}
.result-call{font-size:0.88rem;font-weight:700;color:var(--text-dark);display:flex;align-items:center;gap:5px}
.result-actions{display:flex;align-items:center;gap:7px;flex-wrap:wrap}
.result-action{width:30px;height:30px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:0.9rem;transition:var(--transition)}
.result-action:hover{transform:translateY(-1px);filter:brightness(.96)}
.action-call{background:#dcfce7;color:#15803d}
.action-whatsapp{background:#d1fae5;color:#059669}
.action-facebook{background:#dbeafe;color:#2563eb}
.action-instagram{background:#fce7f3;color:#db2777}
.action-map{background:#fee2e2;color:#dc2626}
.result-action.disabled{opacity:.36;pointer-events:none;filter:grayscale(1)}
.pagination-wrap{display:flex;justify-content:center;margin-top:22px}
.pagination-wrap nav{display:inline-flex}
.pagination{display:flex;align-items:center;justify-content:center;gap:7px;list-style:none;margin:0;padding:0;flex-wrap:wrap}
.page-link{display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;padding:0 13px;border:1.5px solid var(--border);border-radius:10px;background:#fff;color:var(--text-mid);font-size:0.82rem;font-weight:700;line-height:1;text-decoration:none;box-shadow:var(--shadow);transition:var(--transition)}
.page-link:hover{border-color:var(--primary);color:var(--primary);transform:translateY(-1px);box-shadow:var(--shadow-hover)}
.page-item.active .page-link{background:var(--primary);border-color:var(--primary);color:#fff;box-shadow:0 8px 24px rgba(124,58,237,0.25)}
.page-item.disabled .page-link{opacity:.45;pointer-events:none;box-shadow:none}
@media(min-width:769px){
  .site-header{padding:0 24px 0 16px;justify-content:flex-start;gap:14px}
  .site-header .header-logo{flex:0 0 246px}
  .site-header .header-nav{display:none}
  .site-header .header-actions{margin-left:auto}
  .search-bar{position:fixed;top:0;left:276px;right:210px;height:var(--header-h);z-index:910;padding:12px 20px;background:rgba(250,246,240,.97);border-left:none;border-right:none}
  .search-inner{max-width:none;height:100%;margin:0;flex-wrap:nowrap;gap:10px}
  .s-inp-wrap{height:44px;min-width:0}
  .s-select{width:150px;flex:0 0 150px;font-size:0.76rem}
  .results-wrap{padding-top:28px}
}
@media(max-width:768px){.results-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.result-thumb{height:104px}.result-body{padding:12px}.result-name{font-size:0.86rem}.result-meta{font-size:0.72rem}.result-call{font-size:0.8rem}.result-action{width:28px;height:28px;font-size:0.82rem}}
@media(max-width:768px){.search-bar{position:relative;top:auto;padding:8px 10px}.search-inner{max-width:none;flex-wrap:nowrap;gap:10px}.s-inp-wrap{min-width:0}.s-select{width:150px;flex:0 0 150px}.results-wrap{padding-top:8px}}
@media(max-width:480px){.pagination-wrap{margin-top:18px}.pagination{gap:5px}.page-link{min-width:34px;height:34px;padding:0 10px;font-size:0.76rem;border-radius:9px}.results-grid{gap:9px}.result-thumb{height:86px}.result-body{padding:10px}.result-plan{font-size:0.58rem;margin-bottom:5px}.result-name{font-size:0.78rem;margin-bottom:4px}.result-meta{font-size:0.66rem;margin-bottom:6px}.result-call{font-size:0.72rem}.result-actions{gap:5px}.result-action{width:25px;height:25px;font-size:0.74rem}.search-inner{gap:8px}.s-select{width:145px;flex-basis:145px}}
</style>
ENDCSS;
require CITY_DIR . "/views/layout/header.php";
?>
<main>
<div class="search-bar">
  <div class="search-inner">
    <div class="s-inp-wrap">
      <input type="text" id="sQ" value="<?= htmlspecialchars($q) ?>" placeholder="Search businesses...">
      <button onclick="doSearch()"><i class="bi bi-search"></i></button>
    </div>
    <select class="s-select" id="sCat" onchange="doSearch()">
      <option value="">All Categories</option>
      <?php foreach($categories as $c): ?>
        <option value="<?= $c["id"] ?>" <?= $catId==$c["id"]?"selected":"" ?>><?= htmlspecialchars($c["name"]) ?></option>
      <?php endforeach ?>
    </select>
  </div>
</div>
<div class="results-wrap">
  <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:14px">
    <strong><?= $pager["total"] ?></strong> <?= $q ? "results for \"".htmlspecialchars($q)."\"" : "businesses found" ?>
  </div>
  <?php if(empty($pager["data"])): ?>
  <div style="text-align:center;padding:60px 16px;color:var(--text-muted)">
    <i class="bi bi-search" style="font-size:3rem;display:block;margin-bottom:12px"></i>
    <p>No results found. Try different keywords.</p>
  </div>
  <?php else: ?>
  <div class="results-grid">
    <?php foreach($pager["data"] as $r): ?>
    <?php
      $thumb = $r["top_banner"] ?: ($r["first_image"] ?? "");
      $phone = preg_replace('/\D+/', '', $r["phone"] ?? "");
      $whatsapp = preg_replace('/\D+/', '', $r["whatsapp"] ?: ($r["phone"] ?? ""));
      $mapUrl = !empty($r["address"]) ? "https://www.google.com/maps/search/?api=1&query=" . rawurlencode($r["address"]) : "";
    ?>
    <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($r["slug"]) ?>" class="result-card">
      <div class="result-thumb">
        <img src="<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($thumb ?: "demo.jpg") ?>" alt="<?= htmlspecialchars($r["business_name"]) ?>">
      </div>
      <div class="result-body">
        <span class="result-plan plan-<?= htmlspecialchars(strtolower($r["plan_level"] ?? "")) ?>"><?= strtoupper(htmlspecialchars($r["plan_level"] ?? "Ad")) ?></span>
        <div class="result-name"><?= htmlspecialchars($r["business_name"]) ?></div>
        <?php if($r["address"]): ?><div class="result-meta"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($r["address"]) ?></div><?php endif ?>
        <div class="result-actions">
          <span class="result-action action-call <?= $phone ? '' : 'disabled' ?>" title="Call" onclick="<?= $phone ? "event.preventDefault();event.stopPropagation();window.location.href='tel:" . htmlspecialchars($phone, ENT_QUOTES) . "'" : "" ?>"><i class="bi bi-telephone-fill"></i></span>
          <span class="result-action action-whatsapp <?= $whatsapp ? '' : 'disabled' ?>" title="WhatsApp" onclick="<?= $whatsapp ? "event.preventDefault();event.stopPropagation();window.open('https://wa.me/" . htmlspecialchars($whatsapp, ENT_QUOTES) . "','_blank')" : "" ?>"><i class="bi bi-whatsapp"></i></span>
          <span class="result-action action-facebook <?= !empty($r["facebook"]) ? '' : 'disabled' ?>" title="Facebook" onclick="<?= !empty($r["facebook"]) ? "event.preventDefault();event.stopPropagation();window.open('" . htmlspecialchars($r["facebook"], ENT_QUOTES) . "','_blank')" : "" ?>"><i class="bi bi-facebook"></i></span>
          <span class="result-action action-instagram <?= !empty($r["instagram"]) ? '' : 'disabled' ?>" title="Instagram" onclick="<?= !empty($r["instagram"]) ? "event.preventDefault();event.stopPropagation();window.open('" . htmlspecialchars($r["instagram"], ENT_QUOTES) . "','_blank')" : "" ?>"><i class="bi bi-instagram"></i></span>
          <span class="result-action action-map <?= $mapUrl ? '' : 'disabled' ?>" title="Map" onclick="<?= $mapUrl ? "event.preventDefault();event.stopPropagation();window.open('" . htmlspecialchars($mapUrl, ENT_QUOTES) . "','_blank')" : "" ?>"><i class="bi bi-geo-alt-fill"></i></span>
        </div>
      </div>
    </a>
    <?php endforeach ?>
  </div>
  <?php endif ?>
  <?php if($pager["last_page"]>1): ?>
  <div class="pagination-wrap">
    <?php
      $paginationQuery = http_build_query(array_filter(["q"=>$q,"cat"=>$catId]));
      $paginationBase = $cityUrl . "/search" . ($paginationQuery ? "?" . $paginationQuery : "");
    ?>
    <?= Helper::paginationLinks($pager, $paginationBase) ?>
  </div>
  <?php endif ?>
</div>
</main>
<script>
function doSearch(){var q=document.getElementById("sQ").value.trim(),cat=document.getElementById("sCat").value,p=new URLSearchParams();if(q)p.set("q",q);if(cat)p.set("cat",cat);window.location.href="<?= $cityUrl ?>/search"+(p.toString()?"?"+p.toString():"");}
document.getElementById("sQ").addEventListener("keydown",function(e){if(e.key==="Enter")doSearch();});
</script>
<?php require CITY_DIR . "/views/layout/footer.php"; ?>
