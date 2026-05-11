<?php $pageTitle = 'Post Ad'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<div class="d-flex align-items-center gap-2 mb-3">
  <a href="<?= BASE_URL ?>/admin/users/<?= $user['id'] ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back to <?= htmlspecialchars($user['name']) ?>
  </a>
</div>

<div class="alert alert-info py-2 small mb-3">
  <i class="bi bi-person me-1"></i>
  Posting ad for <strong><?= htmlspecialchars($user['name']) ?></strong> —
  Current plan: <?= Helper::planBadge($user['plan_name'] ?? 'free') ?>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/listings/store" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?= $csrf ?>">
<input type="hidden" name="user_id" value="<?= $user['id'] ?>">

<div class="row g-3">
<div class="col-lg-8">

  <!-- Plan + basic config -->
  <div class="card mb-3">
    <div class="ch"><i class="bi bi-sliders me-2"></i>Ad Settings</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-sm-4">
          <label class="form-label fw-600">Ad Plan <span class="text-danger">*</span></label>
          <select name="plan_level" id="planSelect" class="form-select" onchange="showPlanFields(this.value)">
            <option value="basic"   <?= $user['plan_name']==='basic'   ?'selected':'' ?>>Basic</option>
            <option value="premium" <?= $user['plan_name']==='premium' ?'selected':'' ?>>Premium</option>
            <option value="pro"     <?= $user['plan_name']==='pro'     ?'selected':'' ?>>Pro</option>
          </select>
        </div>
        <div class="col-sm-4">
          <label class="form-label fw-600">Publish Status</label>
          <select name="status" class="form-select">
            <option value="approved">Approved (Live)</option>
            <option value="pending">Pending Review</option>
          </select>
        </div>
        <div class="col-sm-4">
          <label class="form-label fw-600">City</label>
          <select name="city_id" class="form-select">
            <option value="">— Select City —</option>
            <?php foreach($cities as $c): ?>
              <option value="<?= $c['id'] ?>" <?= $user['city_id']==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Category</label>
          <select name="category_id" id="categorySelect" class="form-select">
            <option value="">— Select Category —</option>
            <?php foreach($cats as $c): ?>
              <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-12" id="subcategory_section">
          <label class="form-label fw-600">Sub Categories</label>
          <div class="d-flex flex-wrap gap-2 p-2 border rounded" style="max-height:150px;overflow-y:auto">
            <?php foreach($subcats as $s): ?>
            <label class="d-flex align-items-center gap-1 small sc-item" data-cat="<?= $s['category_id'] ?>">
              <input type="checkbox" name="subcategory_ids[]" value="<?= $s['id'] ?>">
              <?= htmlspecialchars($s['name']) ?>
            </label>
            <?php endforeach ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Basic fields (all plans) -->
  <div class="card mb-3">
    <div class="ch"><i class="bi bi-building me-2"></i>Business Info</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-sm-6">
          <label class="form-label fw-600">Business Name <span class="text-danger">*</span></label>
          <input type="text" name="business_name" class="form-control" required>
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Phone</label>
          <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">WhatsApp</label>
          <input type="text" name="whatsapp" class="form-control">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
        </div>
        <div class="col-12">
          <label class="form-label fw-600">Address</label>
          <input type="text" name="address" class="form-control">
        </div>
        <div class="col-12">
          <label class="form-label fw-600">Google Map Embed <small class="text-muted fw-normal">(Optional — paste the iframe code or src URL from Google Maps "Share &rarr; Embed a map")</small></label>
          <textarea name="map_embed" class="form-control" rows="2" placeholder='&lt;iframe src="https://www.google.com/maps/embed?pb=..." ...&gt;&lt;/iframe&gt; or just the URL'></textarea>
        </div>
        <div class="col-12">
          <label class="form-label fw-600">Short Description</label>
          <textarea name="short_description" class="form-control" rows="3" maxlength="300"></textarea>
        </div>
      </div>
    </div>
  </div>

  <!-- Premium+ fields -->
  <div id="f_premium" class="card mb-3" style="display:none">
    <div class="ch"><i class="bi bi-star me-2"></i>Premium Fields</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-sm-6">
          <label class="form-label fw-600">Website</label>
          <input type="url" name="website" class="form-control" placeholder="https://...">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Facebook</label>
          <input type="url" name="facebook" class="form-control">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Instagram</label>
          <input type="url" name="instagram" class="form-control">
        </div>
        <div class="col-12">
          <label class="form-label fw-600">Business Images (Gallery)</label>
          <input type="file" name="business_images[]" class="form-control" accept="image/*" multiple>
        </div>
        <div class="col-12">
          <label class="form-label fw-600">Keywords</label>
          <div class="d-flex flex-wrap gap-2 p-2 border rounded" style="max-height:150px;overflow-y:auto">
            <?php foreach($keywords as $kw): ?>
            <label class="d-flex align-items-center gap-1 small kw-item" data-cat="<?= $kw['category_id'] ?>">
              <input type="checkbox" name="keyword_ids[]" value="<?= $kw['id'] ?>">
              <?= htmlspecialchars($kw['name']) ?>
            </label>
            <?php endforeach ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Pro fields -->
  <div id="f_pro" class="card mb-3" style="display:none">
    <div class="ch"><i class="bi bi-trophy me-2"></i>Pro Fields</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label fw-600">YouTube URL</label>
          <input type="url" name="youtube_url" class="form-control" placeholder="https://youtube.com/watch?v=...">
        </div>
        <div class="col-12">
          <label class="form-label fw-600">Top Banner Image <small class="text-muted" style="font-weight:400">— Recommended Size: 382 px × 132 px</small></label>
          <input type="file" name="top_banner" class="form-control" accept="image/*">
        </div>
      </div>
    </div>
  </div>

</div>
<div class="col-lg-4">
  <div class="card sticky-top" style="top:80px">
    <div class="ch"><i class="bi bi-check2-circle me-2"></i>Submit</div>
    <div class="card-body">
      <button type="submit" class="btn btn-p w-100 btn-lg">
        <i class="bi bi-send me-1"></i>Post Ad
      </button>
      <p class="small text-muted mt-3 mb-0">
        User plan will be updated to match the selected ad plan on submit.
      </p>
    </div>
  </div>
</div>
</div>
</form>

<?php $extraJs = '<script>
function showPlanFields(plan) {
  const pref = document.getElementById("f_premium");
  const pro  = document.getElementById("f_pro");
  if(pref) pref.style.display = (plan==="premium"||plan==="pro") ? "" : "none";
  if(pro)  pro.style.display  = (plan==="pro") ? "" : "none";
}

function filterKeywords() {
  const catId = document.getElementById("categorySelect").value;
  const items = document.querySelectorAll(".kw-item");
  const scItems = document.querySelectorAll(".sc-item");
  const scSect  = document.getElementById("subcategory_section");

  let hasSc = false;
  scItems.forEach(el => {
    if (catId && el.dataset.cat == catId) {
      el.classList.remove("d-none");
      el.classList.add("d-flex");
      hasSc = true;
    } else {
      el.classList.remove("d-flex");
      el.classList.add("d-none");
    }
  });
  if(scSect) scSect.style.display = hasSc ? "" : "none";

  items.forEach(el => {
    if (catId && el.dataset.cat == catId) {
      el.classList.remove("d-none");
      el.classList.add("d-flex");
    } else {
      el.classList.remove("d-flex");
      el.classList.add("d-none");
    }
  });
}

document.addEventListener("DOMContentLoaded", function() {
  const planSel = document.getElementById("planSelect");
  const catSel  = document.getElementById("categorySelect");

  if(planSel) {
    planSel.addEventListener("change", (e) => showPlanFields(e.target.value));
    showPlanFields(planSel.value);
  }

  if(catSel) {
    catSel.addEventListener("change", filterKeywords);
    filterKeywords();
  }
});
</script>' ?>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
