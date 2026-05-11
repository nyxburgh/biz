<?php
$pageTitle  = "Post Your Ad";
$activePage = "post-ad";
$extraCss = <<<'ENDCSS'
<style>
.steps-bar{background:#fff;border-bottom:1px solid var(--border);padding:0 16px;position:sticky;top:var(--header-h);z-index:800}
.steps-inner{display:flex;align-items:center;max-width:880px;margin:0 auto;padding:10px 0}
.step{display:flex;align-items:center;flex:1}
.s-circle{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:700;flex-shrink:0;border:2px solid var(--border);background:#fff;color:var(--text-muted);transition:all .25s}
.step.active .s-circle{background:var(--primary);border-color:var(--primary);color:#fff}
.step.done .s-circle{background:var(--green);border-color:var(--green);color:#fff}
.s-lbl{font-size:0.72rem;font-weight:600;color:var(--text-muted);margin-left:6px;white-space:nowrap}
.step.active .s-lbl,.step.done .s-lbl{color:var(--text-dark)}
.s-line{flex:1;height:2px;background:var(--border);margin:0 5px}
.step.done .s-line{background:var(--green)}
.post-wrap{max-width:880px;margin:0 auto;padding:20px 16px 48px;display:grid;grid-template-columns:1fr 260px;gap:18px;align-items:start}
.form-step{display:none}.form-step.active{display:block}
.form-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;margin-bottom:14px}
.form-card h3{font-family:"Syne",sans-serif;font-weight:700;font-size:0.92rem;color:var(--text-dark);margin-bottom:14px;display:flex;align-items:center;gap:6px}
.fg{margin-bottom:14px}
.fg label{display:block;font-size:0.8rem;font-weight:600;color:var(--text-mid);margin-bottom:5px}
.fi{width:100%;padding:11px 13px;border:1.5px solid var(--border);border-radius:9px;font-size:0.88rem;font-family:inherit;outline:none;transition:border-color .2s}
.fi:focus{border-color:var(--primary)}
.fr{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.cat-grid-pick{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:4px}
.cat-pick{border:2px solid var(--border);border-radius:10px;padding:12px 8px;text-align:center;cursor:pointer;transition:var(--transition);background:#fff}
.cat-pick:hover{border-color:var(--purple-muted);transform:translateY(-2px)}
.cat-pick.sel{border-color:var(--primary);background:var(--purple-light)}
.cat-pick i{display:block;font-size:1.4rem;margin-bottom:5px;color:var(--primary)}
.cat-pick span{font-size:0.72rem;font-weight:600;color:var(--text-dark)}
.subcat-wrap{display:flex;flex-wrap:wrap;gap:7px;padding:10px;border:1.5px solid var(--border);border-radius:9px;min-height:50px}
.subcat-chip{padding:5px 12px;border-radius:40px;border:1.5px solid var(--border);font-size:0.78rem;font-weight:600;color:var(--text-mid);cursor:pointer;transition:var(--transition)}
.subcat-chip.sel{border-color:var(--primary);background:var(--purple-light);color:var(--primary)}
.kw-wrap{display:flex;flex-wrap:wrap;gap:6px;padding:10px;border:1.5px solid var(--border);border-radius:9px}
.kw-chip{padding:4px 10px;border-radius:40px;border:1.5px solid var(--border);font-size:0.75rem;font-weight:600;color:var(--text-mid);cursor:pointer;transition:var(--transition)}
.kw-chip.sel{border-color:var(--primary);background:var(--purple-light);color:var(--primary)}
.plan-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-bottom:4px}
.plan-pick{border:2px solid var(--border);border-radius:12px;padding:14px 10px;text-align:center;cursor:pointer;transition:var(--transition);background:#fff;position:relative}
.plan-pick:hover{border-color:var(--purple-muted);transform:translateY(-2px)}
.plan-pick.sel{border-color:var(--primary);background:var(--purple-light)}
.plan-pick .pp-name{font-family:"Syne",sans-serif;font-weight:800;font-size:0.9rem;color:var(--text-dark);margin-bottom:4px}
.plan-pick .pp-price{font-size:0.82rem;font-weight:700;color:var(--primary);margin-bottom:6px}
.plan-pick .pp-features{font-size:0.7rem;color:var(--text-muted);line-height:1.5;text-align:left}
.plan-pick .pp-paid-badge{display:none;position:absolute;top:8px;right:8px;background:var(--green);color:#fff;border-radius:20px;font-size:0.65rem;font-weight:700;padding:2px 7px}
.plan-pick.already-paid .pp-paid-badge{display:inline-block}
.btn-next{width:100%;padding:12px;border-radius:10px;background:var(--primary);color:#fff;border:none;font-size:0.92rem;font-weight:700;font-family:inherit;cursor:pointer;min-height:46px;display:flex;align-items:center;justify-content:center;gap:7px;transition:background .2s}
.btn-next:hover{background:#6d28d9}
.btn-back{width:100%;padding:11px;border-radius:10px;border:1.5px solid var(--border);background:#fff;color:var(--text-mid);font-size:0.88rem;font-weight:600;font-family:inherit;cursor:pointer;margin-bottom:8px;min-height:44px}
.sum-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:18px;position:sticky;top:calc(var(--header-h)+60px)}
.upg-banner{background:linear-gradient(135deg,var(--primary),#2d1b69);border-radius:var(--radius);padding:16px;color:#fff;text-align:center;margin-top:12px}
.upg-banner h4{font-family:"Syne",sans-serif;font-weight:800;font-size:0.9rem;margin-bottom:5px}
.upg-banner p{font-size:0.75rem;opacity:0.85;margin-bottom:10px}
.upg-banner a{display:inline-flex;align-items:center;gap:5px;padding:8px 16px;background:#fff;color:var(--primary);border-radius:40px;font-weight:700;font-size:0.8rem;text-decoration:none}
.pay-note{background:var(--amber-light);border-radius:9px;padding:12px;font-size:0.8rem;color:var(--amber);margin-bottom:14px;line-height:1.6}
.upload-area{border:2px dashed var(--border);border-radius:9px;padding:16px;text-align:center;cursor:pointer;transition:border-color .2s}
.upload-area:hover{border-color:var(--primary)}
.image-previews{display:grid;grid-template-columns:repeat(auto-fill,minmax(92px,1fr));gap:8px;margin-top:10px}
.img-preview{aspect-ratio:1;border-radius:10px;overflow:hidden;background:var(--sand-light);border:1px solid var(--border);position:relative}
.img-preview img{width:100%;height:100%;object-fit:cover;display:block}
.img-preview .img-name{position:absolute;left:0;right:0;bottom:0;padding:4px 6px;font-size:0.62rem;line-height:1.2;color:#fff;background:linear-gradient(180deg,transparent,rgba(0,0,0,0.72))}
.plan-fields{display:none}
.free-notice{background:var(--green-light);border-radius:9px;padding:14px;font-size:0.82rem;color:var(--green);line-height:1.6;display:none}
@media(max-width:768px){.post-wrap{grid-template-columns:1fr}.sum-card{position:static}.fr{grid-template-columns:1fr}.cat-grid-pick{grid-template-columns:repeat(3,1fr)}.plan-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:480px){.cat-grid-pick{grid-template-columns:repeat(2,1fr)}.steps-bar .s-lbl{display:none}}
</style>
ENDCSS;
require CITY_DIR . "/views/layout/header.php";

$confirmedPlanIdsJson = json_encode(array_values($confirmedPlanIds ?? []));
$planMapJson = json_encode(array_map(function($p){ return ['name'=>$p['name'],'price'=>$p['price'],'label'=>$p['label']]; }, array_combine(array_column($plans,'id'),$plans)));
?>
<main>
<div class="steps-bar"><div class="steps-inner">
  <div class="step active" id="sb1"><div class="s-circle">1</div><span class="s-lbl">Category</span><div class="s-line"></div></div>
  <div class="step" id="sb2"><div class="s-circle">2</div><span class="s-lbl">Details</span><div class="s-line"></div></div>
  <div class="step" id="sb3"><div class="s-circle">3</div><span class="s-lbl">Payment</span></div>
</div></div>

<form method="POST" action="<?= $cityUrl ?>/post-ad" enctype="multipart/form-data" id="adForm" novalidate>
<input type="hidden" name="csrf_token" value="<?= $csrf ?>">
<input type="hidden" name="plan_level" id="fPlanLevel" value="free">
<input type="hidden" name="category_id" id="fCatId">

<div class="post-wrap">
<div>

  <!-- Step 1: Category & Plan -->
  <div class="form-step active" id="fs1">
    <div class="form-card">
      <h3><i class="bi bi-grid" style="color:var(--primary)"></i>Select Category</h3>
      <div class="cat-grid-pick">
        <?php
        $catIcons = ['Restaurants'=>'bi-shop','Hotels & Stays'=>'bi-building','Shopping'=>'bi-bag-heart',
                     'Health & Clinic'=>'bi-heart-pulse','Services'=>'bi-tools','Education'=>'bi-mortarboard',
                     'Automobile'=>'bi-car-front','Photography'=>'bi-camera'];
        foreach($categories as $cat):
        ?>
        <div class="cat-pick" data-id="<?= $cat['id'] ?>">
          <i class="bi <?= $catIcons[$cat['name']] ?? 'bi-shop' ?>"></i>
          <span><?= htmlspecialchars($cat['name']) ?></span>
        </div>
        <?php endforeach ?>
      </div>
    </div>
    <div class="form-card" id="subcatCard" style="display:none">
      <h3><i class="bi bi-tags" style="color:var(--primary)"></i>Select Subcategory</h3>
      <div class="subcat-wrap" id="subcatWrap"><span style="font-size:0.82rem;color:var(--text-muted)">Loading...</span></div>
    </div>

    <!-- Plan Picker -->
    <div class="form-card">
      <h3><i class="bi bi-tag" style="color:var(--primary)"></i>Choose Your Plan</h3>
      <div class="plan-grid">
        <?php
        $planFeatures = [
          'free'    => ['Name, Profession & Phone only','Shown in sidebar listing','No business page created'],
          'basic'   => ['Business name & address','Phone, WhatsApp, Email','Short description','Business page created'],
          'premium' => ['Everything in Basic','Photos & images','Website & social links','Keywords'],
          'pro'     => ['Everything in Premium','Services list','Top banner image','YouTube embed','Priority placement'],
        ];
        foreach($allPlans as $p):
          $isConfirmed = in_array($p['id'], $confirmedPlanIds ?? []);
        ?>
        <div class="plan-pick <?= $isConfirmed ? 'already-paid' : '' ?>"
             data-plan-name="<?= htmlspecialchars($p['name']) ?>"
             data-plan-id="<?= $p['id'] ?>"
             data-plan-price="<?= $p['price'] ?>">
          <span class="pp-paid-badge"><i class="bi bi-check-lg"></i> Paid</span>
          <div class="pp-name"><?= htmlspecialchars($p['label'] ?? ucfirst($p['name'])) ?></div>
          <div class="pp-price"><?= $p['price'] > 0 ? '&#8377;'.number_format($p['price'],0).'/yr' : 'Free' ?></div>
          <ul class="pp-features list-unstyled mb-0">
            <?php foreach(($planFeatures[$p['name']] ?? []) as $f): ?>
            <li><i class="bi bi-check2 text-success me-1"></i><?= htmlspecialchars($f) ?></li>
            <?php endforeach ?>
          </ul>
        </div>
        <?php endforeach ?>
      </div>
    </div>

    <div class="form-card">
      <div class="fg">
        <label>City</label>
        <?php $city = Database::fetchOne("SELECT id,name FROM cities WHERE id=? AND status='active'", [CITY_ID]); ?>
        <input type="hidden" name="city_id" value="<?= $city['id'] ?>">
        <div class="fi" style="padding:12px 13px;border:1.5px solid var(--border);border-radius:10px;background:#f8f5ff;"><?= htmlspecialchars($city['name']) ?></div>
      </div>
    </div>
    <div class="err" id="e1" style="color:#ef4444;font-size:0.8rem;display:none;margin-bottom:8px"></div>
    <button type="button" class="btn-next" onclick="gotoStep(2)">Continue <i class="bi bi-arrow-right"></i></button>
  </div>

  <!-- Step 2: Business Details -->
  <div class="form-step" id="fs2">

    <!-- Free plan notice (shown instead of business form) -->
    <div class="form-card" id="freePlanNotice" style="display:none">
      <h3><i class="bi bi-person-check" style="color:var(--green)"></i>Free Plan — Sidebar Listing</h3>
      <div style="font-size:0.85rem;color:var(--text-mid);line-height:1.7">
        <p>With the <strong>Free</strong> plan your profile will appear in the <strong>sidebar listing</strong> on the home page showing your <strong>Name</strong>, <strong>Profession</strong>, and <strong>Phone</strong>.</p>
        <p style="margin-top:8px">No dedicated business page will be created. Upgrade to <strong>Basic</strong> or higher to get a full business listing page.</p>
      </div>
    </div>

    <!-- Business Info (basic / premium / pro) -->
    <div class="form-card plan-fields plan-basic plan-premium plan-pro" id="businessInfoCard">
      <h3><i class="bi bi-building" style="color:var(--primary)"></i>Business Info</h3>
      <div class="fg"><label>Business Name *</label><input type="text" name="business_name" class="fi" placeholder="e.g. Sunrise Hotel"></div>
      <div class="fr">
        <div class="fg"><label>Phone</label><input type="tel" name="phone" class="fi" value="<?= htmlspecialchars($user['phone']) ?>"></div>
        <div class="fg"><label>WhatsApp</label><input type="tel" name="whatsapp" class="fi"></div>
      </div>
      <div class="fr">
        <div class="fg"><label>Email</label><input type="email" name="email" class="fi" value="<?= htmlspecialchars($user['email']??'') ?>"></div>
        <div class="fg plan-fields plan-premium plan-pro"><label>Website</label><input type="url" name="website" class="fi" placeholder="https://..."></div>
      </div>
      <div class="fg"><label>Address</label><input type="text" name="address" class="fi"></div>
      <div class="fg"><label>Google Map Embed <span style="font-weight:400;color:var(--text-muted);font-size:0.78rem">(Optional — paste the iframe code or src URL from Google Maps "Share &rarr; Embed a map")</span></label>
        <textarea name="map_embed" class="fi" rows="2" placeholder='&lt;iframe src="https://www.google.com/maps/embed?pb=..." ...&gt;&lt;/iframe&gt; or just the URL'></textarea>
      </div>
      <div class="fg"><label>Short Description</label><textarea name="short_description" class="fi" rows="3" maxlength="300"></textarea></div>
    </div>

    <!-- Images: premium+ -->
    <div class="form-card plan-fields plan-premium plan-pro" id="imagesCard">
      <h3><i class="bi bi-images" style="color:var(--primary)"></i>Business Images</h3>
      <div class="fg">
        <div class="upload-area" onclick="document.getElementById('listingImagesI').click()">
          <i class="bi bi-cloud-upload" style="font-size:1.8rem;color:var(--primary);display:block;margin-bottom:6px"></i>
          <p style="font-size:0.78rem;color:var(--text-muted)">Click to upload images (max 5MB each, up to 5 images)</p>
          <input type="file" name="listing_images[]" id="listingImagesI" accept="image/*" multiple style="display:none" onchange="showImageNames(this)">
          <span id="listingImagesN" style="font-size:0.75rem;color:var(--primary)"></span>
        </div>
        <div class="image-previews" id="imagePreviews"></div>
      </div>
    </div>

    <!-- Social & Keywords: premium+ -->
    <div class="form-card plan-fields plan-premium plan-pro" id="socialCard">
      <h3><i class="bi bi-star" style="color:var(--amber)"></i>Social & Keywords</h3>
      <div class="fr">
        <div class="fg"><label>Facebook</label><input type="url" name="facebook" class="fi"></div>
        <div class="fg"><label>Instagram</label><input type="url" name="instagram" class="fi"></div>
      </div>
      <div class="fg"><label>Keywords</label>
        <div class="kw-wrap" id="kwWrap">
          <span style="font-size:0.82rem;color:var(--text-muted)" id="kwPlaceholder">Select a category first to see keywords.</span>
        </div>
      </div>
    </div>

    <!-- Pro fields -->
    <div class="form-card plan-fields plan-pro" id="proCard">
      <h3><i class="bi bi-trophy" style="color:var(--green)"></i>Pro Fields</h3>
      <div class="fg"><label>YouTube URL</label><input type="url" name="youtube_url" class="fi" placeholder="https://youtube.com/watch?v=..."></div>
      <div class="fg"><label>Services List</label>
        <div id="servicesWrap">
          <div class="service-row fr" style="margin-bottom:8px">
            <input type="text" name="service_titles[]" class="fi" placeholder="Service name">
            <input type="text" name="service_prices[]" class="fi" placeholder="Price (optional)">
          </div>
        </div>
        <button type="button" onclick="addServiceRow()" style="font-size:0.78rem;color:var(--primary);background:none;border:none;cursor:pointer;padding:0"><i class="bi bi-plus-circle me-1"></i>Add another service</button>
      </div>
      <div class="fg"><label>Top Banner Image <span style="font-weight:400;color:var(--text-muted);font-size:0.78rem">— Recommended Size: 382 px × 132 px</span></label>
        <div class="upload-area" onclick="document.getElementById('bannerI').click()">
          <i class="bi bi-cloud-upload" style="font-size:1.8rem;color:var(--primary);display:block;margin-bottom:6px"></i>
          <p style="font-size:0.78rem;color:var(--text-muted)">Click to upload (max 5MB)</p>
          <input type="file" name="top_banner" id="bannerI" accept="image/*" style="display:none" onchange="showSinglePreview(this,'bannerPreview','bannerN')">
        </div>
        <div id="bannerPreview" style="margin-top:8px"></div>
        <span id="bannerN" style="font-size:0.75rem;color:var(--primary)"></span>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
      <button type="button" class="btn-back" onclick="gotoStep(1)"><i class="bi bi-arrow-left me-1"></i>Back</button>
      <button type="button" class="btn-next" onclick="gotoStep(3)">Continue <i class="bi bi-arrow-right"></i></button>
    </div>
  </div>

  <!-- Step 3: Payment -->
  <div class="form-step" id="fs3">
    <div class="form-card">
      <h3><i class="bi bi-credit-card" style="color:var(--primary)"></i>Payment</h3>
      <div id="payAlreadyPaid" style="display:none;background:var(--green-light);border-radius:9px;padding:12px;font-size:0.85rem;color:var(--green);margin-bottom:14px">
        <i class="bi bi-check-circle-fill me-2"></i>Payment already confirmed for this plan — submit your ad directly.
      </div>
      <div id="payFreeNote" style="display:none;background:var(--green-light);border-radius:9px;padding:12px;font-size:0.85rem;color:var(--green);margin-bottom:14px">
        <i class="bi bi-check-circle-fill me-2"></i>Your plan is free — submit to register your profile.
      </div>
      <div id="payForm">
        <div class="pay-note">
          <strong>Amount: <span id="payAmountVal">—</span>/year</strong><br>
          Transfer to our UPI/bank and upload the screenshot below.
        </div>
        <div class="fr">
          <div class="fg"><label>Payment Mode</label><select name="payment_mode" class="fi"><option value="UPI">UPI / GPay</option><option value="NEFT">Bank Transfer</option><option value="Cash">Cash</option></select></div>
          <div class="fg"><label>Transaction Reference</label><input type="text" name="reference" class="fi" placeholder="Txn ID / UPI ref"></div>
        </div>
        <div class="fg"><label>Payment Proof</label>
          <div class="upload-area" onclick="document.getElementById('proofI').click()">
            <i class="bi bi-upload" style="font-size:1.5rem;color:var(--primary);display:block;margin-bottom:6px"></i>
            <p style="font-size:0.78rem;color:var(--text-muted)">Upload payment screenshot</p>
            <input type="file" name="payment_proof" id="proofI" accept="image/*,application/pdf" style="display:none" onchange="showSinglePreview(this,'proofPreview','proofN')">
          </div>
          <div id="proofPreview" style="margin-top:8px"></div>
          <span id="proofN" style="font-size:0.75rem;color:var(--primary)"></span>
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:8px">
        <button type="button" class="btn-back" onclick="gotoStep(selPlanName==='free'?1:2)"><i class="bi bi-arrow-left me-1"></i>Back</button>
        <button type="submit" class="btn-next"><i class="bi bi-send-fill me-1"></i>Submit</button>
      </div>
    </div>
  </div>

</div>
<!-- Sidebar -->
<div>
  <div class="sum-card">
    <h4 style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.88rem;margin-bottom:10px">Selected Plan</h4>
    <div style="background:var(--purple-light);color:var(--primary);border-radius:40px;padding:4px 12px;font-size:0.82rem;font-weight:700;display:inline-flex;align-items:center;gap:5px;margin-bottom:8px">
      <i class="bi bi-tag"></i> <span id="sumPlanName">—</span>
    </div>
    <div id="sumPlanPrice" style="font-size:0.82rem;color:var(--text-muted);margin-bottom:12px"></div>
    <div style="font-size:0.76rem;color:var(--text-muted);line-height:1.6">
      <i class="bi bi-shield-check" style="color:var(--green)"></i> Reviewed before publishing<br>
      <i class="bi bi-clock" style="color:var(--amber)"></i> Approval within 24 hours
    </div>
  </div>
  <div class="upg-banner">
    <h4>Want More Visibility?</h4>
    <p>Upgrade to Premium or Pro for photos, keywords and top placement.</p>
    <a href="<?= $cityUrl ?>/upgrade"><i class="bi bi-arrow-up-circle"></i> Upgrade Plan</a>
  </div>
</div>
</div>
</form>
<script>
var allSubcats      = <?= json_encode(array_values($subcategories)) ?>;
var allKeywords     = <?= json_encode(array_values($keywords)) ?>;
var confirmedPlanIds = <?= json_encode(array_values($confirmedPlanIds ?? [])) ?>;
var selCatId = 0, selPlanName = '', selPlanId = 0, selPlanPrice = 0;

// Wire up category picks
document.querySelectorAll('.cat-pick').forEach(function(el) {
  el.addEventListener('click', function() {
    selectCat(parseInt(this.getAttribute('data-id')), this);
  });
});

// Wire up plan picks
document.querySelectorAll('.plan-pick').forEach(function(el) {
  el.addEventListener('click', function() {
    selectPlan(
      this.getAttribute('data-plan-name'),
      parseInt(this.getAttribute('data-plan-id')),
      parseFloat(this.getAttribute('data-plan-price')),
      this
    );
  });
});

function selectCat(id, el) {
  selCatId = id;
  document.getElementById("fCatId").value = id;
  document.querySelectorAll(".cat-pick").forEach(function(c){ c.classList.remove("sel"); });
  el.classList.add("sel");
  loadSubcats(id);
  loadKeywords(id);
}

function loadKeywords(catId) {
  var kws = allKeywords.filter(function(k){ return k.category_id == catId || !k.category_id; });
  var wrap = document.getElementById("kwWrap");
  if (!kws.length) {
    wrap.innerHTML = '';
    var p = document.createElement('span');
    p.id = 'kwPlaceholder';
    p.style.cssText = 'font-size:0.82rem;color:var(--text-muted)';
    p.textContent = 'No keywords for this category.';
    wrap.appendChild(p);
    return;
  }
  wrap.innerHTML = kws.map(function(k){
    return '<label class="kw-chip" onclick="this.classList.toggle(\'sel\');var i=this.querySelector(\'input\');i.checked=!i.checked;">' +
           '<input type="checkbox" name="keyword_ids[]" value="'+k.id+'" style="display:none">'+esc(k.name)+'</label>';
  }).join('');
}

function loadSubcats(catId) {
  var subs = allSubcats.filter(function(s){ return s.category_id == catId; });
  var card = document.getElementById("subcatCard"), wrap = document.getElementById("subcatWrap");
  if (!subs.length) { card.style.display = "none"; return; }
  card.style.display = "block";
  wrap.innerHTML = subs.map(function(s){
    return '<label class="subcat-chip" onclick="event.preventDefault();this.classList.toggle(\'sel\');var i=this.querySelector(\'input\');i.checked=!i.checked;">' +
           '<input type="checkbox" name="subcategory_ids[]" value="'+s.id+'" style="display:none">'+esc(s.name)+'</label>';
  }).join("");
}

function selectPlan(planName, planId, planPrice, el) {
  selPlanName = planName; selPlanId = planId; selPlanPrice = planPrice;
  document.getElementById("fPlanLevel").value = planName;
  document.querySelectorAll(".plan-pick").forEach(function(c){ c.classList.remove("sel"); });
  el.classList.add("sel");
  document.getElementById("sumPlanName").textContent = planName.charAt(0).toUpperCase() + planName.slice(1);
  document.getElementById("sumPlanPrice").innerHTML = planPrice > 0
    ? '<strong style="color:var(--text-dark);font-size:1.1rem">&#8377;' + planPrice.toLocaleString() + '</strong>/year'
    : '<span style="color:var(--green)">Free</span>';

  // Toggle plan-gated fields
  document.querySelectorAll(".plan-fields").forEach(function(e){ e.style.display = "none"; });
  var freeNotice = document.getElementById("freePlanNotice");

  if (planName === "free") {
    freeNotice.style.display = "block";
  } else {
    freeNotice.style.display = "none";
    // basic and above
    document.querySelectorAll(".plan-basic").forEach(function(e){ e.style.display = "block"; });
    if (planName === "premium" || planName === "pro") {
      document.querySelectorAll(".plan-premium").forEach(function(e){ e.style.display = "block"; });
    }
    if (planName === "pro") {
      document.querySelectorAll(".plan-pro").forEach(function(e){ e.style.display = "block"; });
    }
  }
}

function updatePaymentStep() {
  var alreadyPaid = confirmedPlanIds.indexOf(selPlanId) !== -1;
  var isFree = selPlanPrice <= 0;
  document.getElementById("payAlreadyPaid").style.display = alreadyPaid ? "" : "none";
  document.getElementById("payFreeNote").style.display   = (!alreadyPaid && isFree) ? "" : "none";
  document.getElementById("payForm").style.display       = (!alreadyPaid && !isFree) ? "" : "none";
  if (!alreadyPaid && !isFree) {
    document.getElementById("payAmountVal").innerHTML = "&#8377;" + selPlanPrice.toLocaleString();
  }
}

function gotoStep(n) {
  if (n === 2 && !selCatId) {
    var e = document.getElementById("e1"); e.textContent = "Please select a category."; e.style.display = "block";
    setTimeout(function(){ e.style.display = "none"; }, 3000); return;
  }
  if (n === 2 && !selPlanId) {
    var e = document.getElementById("e1"); e.textContent = "Please select a plan."; e.style.display = "block";
    setTimeout(function(){ e.style.display = "none"; }, 3000); return;
  }
  // Free plan: skip Step 2 (no business form needed), go straight to Step 3
  if (n === 2 && selPlanName === "free") { n = 3; }
  if (n === 3) {
    // For non-free plans, business name is required
    if (selPlanName !== "free") {
      var bn = document.querySelector("input[name='business_name']");
      if (!bn || !bn.value.trim()) {
        bn && bn.focus();
        bn && (bn.style.borderColor = "#ef4444");
        setTimeout(function(){ if(bn) bn.style.borderColor = ""; }, 3000);
        return;
      }
    }
    updatePaymentStep();
  }
  document.querySelectorAll(".form-step").forEach(function(s,i){ s.classList.toggle("active", i+1 === n); });
  ["sb1","sb2","sb3"].forEach(function(id,i){
    var el = document.getElementById(id);
    var stepN = i + 1;
    var skipped = (stepN === 2 && selPlanName === "free");
    el.classList.toggle("active", stepN === n);
    el.classList.toggle("done", stepN < n || skipped);
  });
  window.scrollTo({top:0, behavior:"smooth"});
}

function showImageNames(input) {
  var names = [];
  var previews = document.getElementById("imagePreviews");
  previews.innerHTML = "";
  for (var i = 0; i < input.files.length; i++) {
    var file = input.files[i];
    names.push(file.name);
    var box = document.createElement("div");
    box.className = "img-preview";
    box.title = file.name;

    if (file.type && file.type.indexOf("image/") === 0) {
      var img = document.createElement("img");
      img.alt = file.name;
      img.src = URL.createObjectURL(file);
      img.onload = (function(src){ return function(){ URL.revokeObjectURL(src); }; })(img.src);
      box.appendChild(img);
    } else {
      box.innerHTML = '<div style="font-size:1.4rem;color:var(--primary)"><i class="bi bi-image"></i></div>';
    }

    var label = document.createElement("div");
    label.className = "img-name";
    label.textContent = file.name;
    box.appendChild(label);
    previews.appendChild(box);
  }
  document.getElementById("listingImagesN").textContent = names.join(", ");
}

function showSinglePreview(input, previewId, nameId) {
  var preview = document.getElementById(previewId);
  var nameEl  = document.getElementById(nameId);
  preview.innerHTML = "";
  if (!input.files || !input.files[0]) { if (nameEl) nameEl.textContent = ""; return; }
  var file = input.files[0];
  if (nameEl) nameEl.textContent = file.name;
  if (file.type && file.type.indexOf("image/") === 0) {
    var img = document.createElement("img");
    img.src = URL.createObjectURL(file);
    img.onload = function(){ URL.revokeObjectURL(img.src); };
    img.style.cssText = "max-width:100%;max-height:180px;border-radius:8px;border:1.5px solid var(--border);margin-top:4px;display:block";
    preview.appendChild(img);
  } else {
    preview.innerHTML = '<div style="font-size:0.8rem;color:var(--primary);padding:6px 0"><i class="bi bi-file-earmark-check me-1"></i>' + file.name + '</div>';
  }
}

function addServiceRow() {
  var wrap = document.getElementById("servicesWrap");
  var row = document.createElement("div");
  row.className = "service-row fr";
  row.style.marginBottom = "8px";
  row.innerHTML = '<input type="text" name="service_titles[]" class="fi" placeholder="Service name">' +
                  '<input type="text" name="service_prices[]" class="fi" placeholder="Price (optional)">';
  wrap.appendChild(row);
}

function esc(s){ return s?s.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;"):""; }

// Auto-select plan matching user's current DB plan (or first plan)
(function(){
  var userPlan = <?= json_encode($user['plan_name'] ?? 'free') ?>;
  var matched = false;
  document.querySelectorAll('.plan-pick').forEach(function(el) {
    if (el.getAttribute('data-plan-name') === userPlan) {
      el.dispatchEvent(new Event('click', { bubbles: true }));
      matched = true;
    }
  });
  if (!matched) {
    var first = document.querySelector('.plan-pick');
    if (first) first.dispatchEvent(new Event('click', { bubbles: true }));
  }
})();
</script>
<?php require CITY_DIR . "/views/layout/footer.php"; ?>
