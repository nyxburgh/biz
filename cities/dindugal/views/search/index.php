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
.results-wrap{max-width:900px;margin:0 auto;padding:20px 16px}
.result-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:16px;margin-bottom:10px;display:flex;gap:14px;transition:var(--transition);text-decoration:none;color:inherit}
.result-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-hover)}
.result-icon{width:50px;height:50px;border-radius:12px;background:var(--purple-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0}
.result-name{font-family:"Syne",sans-serif;font-weight:700;font-size:0.95rem;color:var(--text-dark);margin-bottom:3px}
.result-desc{font-size:0.8rem;color:var(--text-muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:6px}
.result-meta{display:flex;gap:12px;font-size:0.72rem;color:var(--text-muted);flex-wrap:wrap}
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
  <?php else: foreach($pager["data"] as $r): ?>
  <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($r["slug"]) ?>" class="result-card">
    <div class="result-icon"><i class="bi bi-building"></i></div>
    <div style="flex:1;min-width:0">
      <div class="result-name"><?= htmlspecialchars($r["business_name"]) ?></div>
      <div class="result-desc"><?= htmlspecialchars($r["short_description"]??"") ?></div>
      <div class="result-meta">
        <?php if($r["cat_name"]): ?><span><i class="bi bi-grid"></i> <?= htmlspecialchars($r["cat_name"]) ?></span><?php endif ?>
        <?php if($r["address"]): ?><span><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($r["address"]) ?></span><?php endif ?>
        <?php if($r["avg_rating"]): ?><span style="color:#f59e0b">⭐ <?= $r["avg_rating"] ?></span><?php endif ?>
      </div>
    </div>
  </a>
  <?php endforeach; endif ?>
  <?php if($pager["last_page"]>1): ?>
  <div style="text-align:center;margin-top:20px">
    <?= Helper::paginationLinks($pager, $cityUrl."/search?".http_build_query(array_filter(["q"=>$q,"cat"=>$catId]))) ?>
  </div>
  <?php endif ?>
</div>
</main>
<script>
function doSearch(){var q=document.getElementById("sQ").value.trim(),cat=document.getElementById("sCat").value,p=new URLSearchParams();if(q)p.set("q",q);if(cat)p.set("cat",cat);window.location.href="<?= $cityUrl ?>/search"+(p.toString()?"?"+p.toString():"");}
document.getElementById("sQ").addEventListener("keydown",function(e){if(e.key==="Enter")doSearch();});
</script>
<?php require CITY_DIR . "/views/layout/footer.php"; ?>
