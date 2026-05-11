<?php
$pageTitle  = htmlspecialchars($listing["business_name"]);
$activePage = "";
$extraCss = <<<'ENDCSS'
<style>
.listing-wrap{max-width:1050px;margin:0 auto;padding:24px 16px}
.listing-grid{display:grid;grid-template-columns:1fr 280px;gap:22px;align-items:start}
.l-hero{background:linear-gradient(135deg,#2d1b69,var(--primary));border-radius:var(--radius);overflow:hidden;margin-bottom:14px}
.l-banner-wrap{width:100%;height:160px;overflow:hidden}
.l-banner{width:100%;height:160px;object-fit:cover;object-position:center;display:block}
.l-hero-body{padding:18px}
.l-title{font-family:'Syne',sans-serif;font-weight:800;font-size:1.3rem;color:#fff;margin-bottom:4px}
.l-badges{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:10px}
.l-badge{display:inline-flex;align-items:center;gap:3px;background:rgba(255,255,255,0.15);border-radius:40px;padding:3px 9px;font-size:0.7rem;font-weight:600;color:#fff}
.icard{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:18px;margin-bottom:12px}
.icard h3{font-family:'Syne',sans-serif;font-weight:700;font-size:0.9rem;color:var(--text-dark);margin-bottom:12px;display:flex;align-items:center;gap:7px}
.drow{display:flex;gap:10px;padding:7px 0;border-bottom:1px solid var(--sand-dark);font-size:0.85rem}
.drow:last-child{border-bottom:none}
.dlbl{width:80px;flex-shrink:0;color:var(--text-muted);font-size:0.78rem}
.kw-tag{display:inline-flex;padding:3px 9px;border-radius:40px;background:var(--purple-light);color:var(--primary);font-size:0.72rem;font-weight:600;margin:2px}
.c-btn{display:flex;align-items:center;gap:9px;padding:12px 14px;border-radius:10px;border:none;width:100%;font-family:inherit;font-size:0.88rem;font-weight:600;cursor:pointer;transition:var(--transition);text-decoration:none;margin-bottom:7px;min-height:46px}
.c-call{background:var(--green-light);color:var(--green)}.c-call:hover{background:var(--green);color:#fff}
.c-wa{background:#dcfce7;color:#16a34a}.c-wa:hover{background:#16a34a;color:#fff}
.c-web{background:var(--purple-light);color:var(--primary)}.c-web:hover{background:var(--primary);color:#fff}
.rev-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:14px;margin-bottom:9px}
.rev-form{background:var(--sand-light);border:1px solid var(--border);border-radius:var(--radius);padding:16px;margin-bottom:14px}
.star-row{display:flex;gap:5px;margin-bottom:12px}
.star-btn{font-size:1.4rem;cursor:pointer;color:var(--border);background:none;border:none;padding:0;min-width:36px;min-height:36px;transition:color .15s}
.star-btn.on{color:#f59e0b}
.rel-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:12px;text-decoration:none;display:block;color:inherit;transition:var(--transition);margin-bottom:8px}
.rel-card:hover{transform:translateY(-2px)}
.qr-box{text-align:center;padding:14px 0}
.qr-box img{width:120px;height:120px;border-radius:8px}
.share-url{background:var(--sand-light);border-radius:9px;padding:10px 12px;font-size:0.75rem;color:var(--text-muted);word-break:break-all;margin-top:8px;cursor:pointer;border:1px solid var(--border)}
.archived-banner{background:#fee2e2;border-radius:var(--radius);padding:20px;text-align:center;margin-bottom:14px}
.login-prompt{background:var(--purple-light);border-radius:10px;padding:14px;font-size:0.85rem;margin-bottom:14px;text-align:center}
.login-prompt a{color:var(--primary);font-weight:600}
@media(max-width:768px){.listing-grid{grid-template-columns:1fr}}
</style>
ENDCSS;
require CITY_DIR . "/views/layout/header.php";
?>
<main>
<div class="listing-wrap">

<?php if($listing["status"] === "archived"): ?>
<div class="archived-banner">
  <div style="font-size:2rem;margin-bottom:8px">⏸️</div>
  <h3 style="font-family:'Syne',sans-serif;font-weight:800;margin-bottom:6px">Listing Temporarily Unavailable</h3>
  <p style="font-size:0.85rem;color:var(--text-mid)">The owner is renewing their subscription. Check back soon.</p>
</div>
<?php elseif($listing["status"] === "pending"): ?>
<div style="background:#fef3c7;border-radius:var(--radius);padding:14px;text-align:center;margin-bottom:14px;font-size:0.85rem;color:#92400e">
  <i class="bi bi-hourglass-split me-2"></i>This listing is pending admin approval.
</div>
<?php endif ?>

<div class="listing-grid">
<div>
  <div class="l-hero">
    <?php if($listing["top_banner"] && in_array($listing["plan_level"],["premium","pro"])): ?>
    <img src="<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($listing["top_banner"]) ?>" class="l-banner" alt="">
    <?php elseif(!empty($images) && in_array($listing["plan_level"],["premium","pro"])): ?>
    <img src="<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($images[0]["filename"]) ?>" class="l-banner" alt="">
    <?php endif ?>
    <div class="l-hero-body">
      <div class="l-badges">
        <span class="l-badge"><i class="bi bi-tag-fill"></i> <?= ucfirst($listing["plan_level"]) ?></span>
        <?php if($listing["cat_name"]): ?><span class="l-badge"><i class="bi bi-grid"></i> <?= htmlspecialchars($listing["cat_name"]) ?></span><?php endif ?>
        <?php if($listing["city_name"]): ?><span class="l-badge"><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($listing["city_name"]) ?></span><?php endif ?>
      </div>
      <div class="l-title"><?= htmlspecialchars($listing["business_name"]) ?></div>
      <?php if($listing["avg_rating"]): ?>
      <div style="display:flex;align-items:center;gap:7px;margin-top:6px">
        <span style="color:#f59e0b;font-size:1rem"><?= str_repeat("★",(int)$listing["avg_rating"]) ?><?= str_repeat("☆",5-(int)$listing["avg_rating"]) ?></span>
        <span style="color:#fff;font-weight:700"><?= $listing["avg_rating"] ?></span>
        <span style="color:rgba(255,255,255,0.7);font-size:0.78rem">(<?= $listing["review_count"] ?> reviews)</span>
      </div>
      <?php endif ?>
    </div>
  </div>

  <?php if($listing["short_description"]): ?>
  <div class="icard"><h3><i class="bi bi-info-circle" style="color:var(--primary)"></i>About</h3>
    <p style="font-size:0.88rem;color:var(--text-mid);line-height:1.7"><?= nl2br(htmlspecialchars($listing["short_description"])) ?></p>
  </div>
  <?php endif ?>

  <div class="icard"><h3><i class="bi bi-card-text" style="color:var(--primary)"></i>Details</h3>
    <?php if($listing["address"]): ?><div class="drow"><span class="dlbl">Address</span><span><?= htmlspecialchars($listing["address"]) ?></span></div><?php endif ?>
    <?php if($listing["phone"]): ?><div class="drow"><span class="dlbl">Phone</span><span><?= htmlspecialchars($listing["phone"]) ?></span></div><?php endif ?>
    <?php if($listing["whatsapp"] && in_array($listing["plan_level"],["basic","premium","pro"])): ?><div class="drow"><span class="dlbl">WhatsApp</span><span><?= htmlspecialchars($listing["whatsapp"]) ?></span></div><?php endif ?>
    <?php if($listing["email"]): ?><div class="drow"><span class="dlbl">Email</span><span><?= htmlspecialchars($listing["email"]) ?></span></div><?php endif ?>
    <?php if($listing["website"] && in_array($listing["plan_level"],["premium","pro"])): ?><div class="drow"><span class="dlbl">Website</span><span><a href="<?= htmlspecialchars($listing["website"]) ?>" target="_blank" style="color:var(--primary)"><?= htmlspecialchars($listing["website"]) ?></a></span></div><?php endif ?>
  </div>

  <?php if(!empty($listing["map_embed"])): ?>
  <?php
    $mapSrc = $listing["map_embed"];
    if (preg_match('/<iframe[^>]*src=["\'"]([^"\'"]+)["\'"][^>]*>/i', $mapSrc, $mapM)) {
        $mapSrc = $mapM[1];
    }
  ?>
  <div class="icard"><h3><i class="bi bi-geo-alt-fill" style="color:var(--maroon)"></i>Location</h3>
    <div style="position:relative;padding-bottom:56.25%;border-radius:9px;overflow:hidden">
      <iframe style="position:absolute;inset:0;width:100%;height:100%;border:0" src="<?= htmlspecialchars($mapSrc) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
    </div>
  </div>
  <?php endif ?>

  <?php if(!empty($images) && in_array($listing["plan_level"],["premium","pro"])): ?>
  <div class="icard"><h3><i class="bi bi-images" style="color:var(--primary)"></i>Photos</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:8px">
      <?php foreach($images as $img): ?>
      <img src="<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($img["filename"]) ?>"
           alt="<?= htmlspecialchars($img["alt_text"]??"") ?>"
           style="width:100%;height:110px;object-fit:cover;border-radius:8px;cursor:pointer"
           onclick="this.requestFullscreen&&this.requestFullscreen()">
      <?php endforeach ?>
    </div>
  </div>
  <?php endif ?>

  <?php if(!empty($keywords) && in_array($listing["plan_level"],["premium","pro"])): ?>
  <div class="icard"><h3><i class="bi bi-tags" style="color:var(--primary)"></i>Keywords</h3>
    <?php foreach($keywords as $kw): ?><span class="kw-tag"><?= htmlspecialchars($kw["name"]) ?></span><?php endforeach ?>
  </div>
  <?php endif ?>

  <?php if(!empty($services) && $listing["plan_level"] === "pro"): ?>
  <div class="icard"><h3><i class="bi bi-list-check" style="color:var(--green)"></i>Services</h3>
    <?php foreach($services as $sv): ?>
    <div style="padding:7px 0;border-bottom:1px solid var(--sand-dark);font-size:0.85rem">
      <div style="font-weight:600"><?= htmlspecialchars($sv["title"] ?? "") ?></div>
      <?php if(!empty($sv["price"])): ?><div style="color:var(--text-muted);font-size:0.78rem"><?= htmlspecialchars($sv["price"]) ?></div><?php endif ?>
    </div>
    <?php endforeach ?>
  </div>
  <?php endif ?>

  <?php if($listing["youtube_url"] && $listing["status"]==="approved" && $listing["plan_level"]==="pro"): ?>
  <div class="icard"><h3><i class="bi bi-youtube" style="color:#ef4444"></i>Video</h3>
    <?php preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/',$listing["youtube_url"],$m); if(!empty($m[1])): ?>
    <div style="position:relative;padding-bottom:56.25%;border-radius:9px;overflow:hidden">
      <iframe style="position:absolute;inset:0;width:100%;height:100%" src="https://www.youtube.com/embed/<?= htmlspecialchars($m[1]) ?>" frameborder="0" allowfullscreen></iframe>
    </div>
    <?php endif ?>
  </div>
  <?php endif ?>

  <!-- Reviews -->
  <div class="icard" id="reviews">
    <h3><i class="bi bi-star-fill" style="color:var(--amber)"></i>Reviews (<?= count($reviews) ?>)</h3>

    <?php if(!$isLoggedIn): ?>
    <div class="login-prompt">
      <i class="bi bi-person-circle me-2" style="color:var(--primary)"></i>
      <a href="<?= $cityUrl ?>/login?return=<?= urlencode($listingUrl) ?>">Login or sign up</a> to leave a review.
    </div>
    <?php elseif(!$hasReviewed && ($listing["user_id"]??0) != ($_SESSION["user_id"]??-1)): ?>
    <div class="rev-form">
      <h4 style="font-size:0.88rem;font-weight:700;margin-bottom:10px">Write a Review</h4>
      <div class="star-row">
        <?php for($i=1;$i<=5;$i++): ?>
        <button class="star-btn" onclick="setRating(<?= $i ?>)">★</button>
        <?php endfor ?>
      </div>
      <textarea id="revTxt" style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:9px;font-family:inherit;font-size:0.85rem;resize:vertical;outline:none;margin-bottom:10px" rows="3" placeholder="Share your experience..."></textarea>
      <div id="revErr" style="color:#ef4444;font-size:0.78rem;display:none;margin-bottom:7px"></div>
      <button onclick="submitReview(<?= $listing["id"] ?>)" style="padding:11px 22px;border-radius:9px;background:var(--primary);color:#fff;border:none;font-weight:700;font-family:inherit;cursor:pointer;font-size:0.85rem;min-height:44px">Submit Review</button>
    </div>
    <?php endif ?>

    <?php if(empty($reviews)): ?>
    <p style="color:var(--text-muted);font-size:0.85rem;text-align:center;padding:18px 0">No reviews yet. Be the first!</p>
    <?php else: foreach($reviews as $rev): ?>
    <div class="rev-card">
      <div style="display:flex;justify-content:space-between;margin-bottom:5px">
        <strong style="font-size:0.85rem"><?= htmlspecialchars($rev["reviewer_name"]) ?></strong>
        <span style="color:#f59e0b"><?= str_repeat("★",(int)$rev["rating"]) ?><?= str_repeat("☆",5-(int)$rev["rating"]) ?></span>
      </div>
      <p style="font-size:0.82rem;color:var(--text-mid)"><?= htmlspecialchars($rev["comment"]??"") ?></p>
      <div style="font-size:0.72rem;color:var(--text-muted);margin-top:4px"><?= Helper::timeAgo($rev["created_at"]) ?></div>
    </div>
    <?php endforeach; endif ?>
  </div>
</div>

<!-- Right sidebar -->
<div>
  <?php if($listing["status"]==="approved"): ?>
  <div class="icard">
    <h3><i class="bi bi-telephone" style="color:var(--primary)"></i>Contact</h3>
    <?php if($listing["phone"]): ?>
    <a href="tel:<?= htmlspecialchars($listing["phone"]) ?>" class="c-btn c-call">
      <i class="bi bi-telephone-fill" style="font-size:1.1rem"></i>
      <div><div style="font-size:0.7rem;opacity:.75">Call Now</div><div><?= htmlspecialchars($listing["phone"]) ?></div></div>
    </a>
    <?php endif ?>
    <?php if($listing["whatsapp"] && in_array($listing["plan_level"],["basic","premium","pro"])): ?>
    <a href="https://wa.me/91<?= preg_replace('/\D/','',$listing["whatsapp"]) ?>" target="_blank" class="c-btn c-wa">
      <i class="bi bi-whatsapp" style="font-size:1.1rem"></i>
      <div><div style="font-size:0.7rem;opacity:.75">WhatsApp</div><div><?= htmlspecialchars($listing["whatsapp"]) ?></div></div>
    </a>
    <?php endif ?>
    <?php if($listing["website"] && in_array($listing["plan_level"],["premium","pro"])): ?>
    <a href="<?= htmlspecialchars($listing["website"]) ?>" target="_blank" class="c-btn c-web">
      <i class="bi bi-globe" style="font-size:1.1rem"></i>
      <div><div style="font-size:0.7rem;opacity:.75">Website</div><div>Visit Website</div></div>
    </a>
    <?php endif ?>
  </div>

  <!-- QR + Share (basic/premium/pro only) -->
  <?php if(in_array($listing["plan_level"],["basic","premium","pro"])): ?>
  <div class="icard">
    <h3><i class="bi bi-share" style="color:var(--primary)"></i>Share</h3>
    <div class="qr-box">
      <img src="<?= htmlspecialchars($qrUrl) ?>" alt="QR Code" loading="lazy">
      <p style="font-size:0.72rem;color:var(--text-muted);margin-top:6px">Scan to open this page</p>
    </div>
    <div class="share-url" onclick="copyUrl()" title="Click to copy">
      <i class="bi bi-link-45deg me-1"></i><?= htmlspecialchars($listingUrl) ?>
    </div>
    <?php if(in_array($listing["plan_level"],["premium","pro"])): ?>
    <div style="display:flex;gap:8px;margin-top:10px">
      <?php if($listing["facebook"]): ?><a href="<?= htmlspecialchars($listing["facebook"]) ?>" target="_blank" style="width:36px;height:36px;border-radius:9px;background:#1877f2;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1rem"><i class="bi bi-facebook"></i></a><?php endif ?>
      <?php if($listing["instagram"]): ?><a href="<?= htmlspecialchars($listing["instagram"]) ?>" target="_blank" style="width:36px;height:36px;border-radius:9px;background:linear-gradient(45deg,#f09433,#dc2743);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1rem"><i class="bi bi-instagram"></i></a><?php endif ?>
      <a href="https://api.whatsapp.com/send?text=<?= urlencode($listing["business_name"].' - '.$listingUrl) ?>" target="_blank" style="width:36px;height:36px;border-radius:9px;background:#16a34a;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1rem"><i class="bi bi-whatsapp"></i></a>
    </div>
    <?php endif ?>
  </div>
  <?php endif ?>
  <?php endif ?>

  <!-- Related -->
  <?php if(!empty($related)): ?>
  <div class="icard"><h3><i class="bi bi-grid" style="color:var(--primary)"></i>Related</h3>
    <?php foreach($related as $r): ?>
    <a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($r["slug"]) ?>" class="rel-card">
      <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.85rem;color:var(--text-dark)"><?= htmlspecialchars($r["business_name"]) ?></div>
      <?php if($r["avg_rating"]): ?><div style="color:#f59e0b;font-size:0.75rem;margin-top:2px">⭐ <?= $r["avg_rating"] ?></div><?php endif ?>
    </a>
    <?php endforeach ?>
  </div>
  <?php endif ?>
</div>
</div>
</div>
</main>
<script>
var selRating = 0;
function setRating(n) {
  selRating = n;
  document.querySelectorAll(".star-btn").forEach(function(b,i){ b.classList.toggle("on",i<n); });
}
async function submitReview(lid) {
  var comment = document.getElementById("revTxt").value.trim();
  if (!selRating) { showRE("Please select a star rating."); return; }
  if (!comment)   { showRE("Please write a comment."); return; }
  try {
    var r = await fetch("<?= $cityUrl ?>/review", {
      method:"POST", headers:{"Content-Type":"application/x-www-form-urlencoded"},
      body: new URLSearchParams({listing_id:lid, rating:selRating, comment:comment, csrf_token:"<?= htmlspecialchars($csrf, ENT_QUOTES) ?>"})
    });
    var j = await r.json();
    if (j.success) {
      document.querySelector(".rev-form").innerHTML = '<div style="background:var(--green-light);border-radius:9px;padding:14px;text-align:center;color:var(--green);font-weight:600"><i class="bi bi-check-circle-fill me-2"></i>Review submitted! Appears after approval.</div>';
    } else { showRE(j.error||"Error."); }
  } catch(e) { showRE("Network error."); }
}
function showRE(msg){var e=document.getElementById("revErr");e.textContent=msg;e.style.display="block";setTimeout(function(){e.style.display="none";},4000);}
function copyUrl() {
  navigator.clipboard.writeText("<?= htmlspecialchars($listingUrl, ENT_QUOTES) ?>").then(function(){
    alert("URL copied!");
  }).catch(function(){ alert("<?= htmlspecialchars($listingUrl, ENT_QUOTES) ?>"); });
}
</script>
<?php require CITY_DIR . "/views/layout/footer.php"; ?>
