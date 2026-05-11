<?php $pageTitle = 'Edit Listing'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<div class="d-flex align-items-center gap-2 mb-3">
  <a href="<?= BASE_URL ?>/admin/listings/<?= $listing['id'] ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back
  </a>
</div>
<form method="POST" action="<?= BASE_URL ?>/admin/listings/update" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?= $csrf ?>">
<input type="hidden" name="id" value="<?= $listing['id'] ?>">
<div class="row g-3">
<div class="col-lg-8">

  <div class="card mb-3">
    <div class="ch">
      <i class="bi bi-building me-2"></i><?= htmlspecialchars($listing['business_name']) ?>
      — <?= Helper::planBadge($listing['plan_level']) ?>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-sm-6">
          <label class="form-label fw-600">Business Name *</label>
          <input type="text" name="business_name" class="form-control" value="<?= htmlspecialchars($listing['business_name'] ?? '') ?>" required>
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Phone</label>
          <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($listing['phone'] ?? '') ?>">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">WhatsApp</label>
          <input type="text" name="whatsapp" class="form-control" value="<?= htmlspecialchars($listing['whatsapp'] ?? '') ?>">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($listing['email'] ?? '') ?>">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">City</label>
          <select name="city_id" class="form-select">
            <option value="">—</option>
            <?php foreach($cities as $c): ?>
              <option value="<?= $c['id'] ?>" <?= $listing['city_id']==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Category</label>
          <select name="category_id" id="categorySelect" class="form-select">
            <option value="">—</option>
            <?php foreach($cats as $c): ?>
              <option value="<?= $c['id'] ?>" <?= $listing['category_id']==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-12" id="subcategory_section">
          <label class="form-label fw-600">Sub Categories</label>
          <div class="d-flex flex-wrap gap-2 p-2 border rounded" style="max-height:150px;overflow-y:auto">
            <?php foreach($subcats as $s): ?>
            <label class="d-flex align-items-center gap-1 small sc-item" data-cat="<?= $s['category_id'] ?>">
              <input type="checkbox" name="subcategory_ids[]" value="<?= $s['id'] ?>" <?= in_array($s['id'],$selScIds)?'checked':'' ?>>
              <?= htmlspecialchars($s['name']) ?>
            </label>
            <?php endforeach ?>
          </div>
        </div>
        <div class="col-12">
          <label class="form-label fw-600">Address</label>
          <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($listing['address'] ?? '') ?>">
        </div>
        <div class="col-12">
          <label class="form-label fw-600">Google Map Embed <small class="text-muted fw-normal">(Optional — paste the iframe code or src URL from Google Maps "Share &rarr; Embed a map")</small></label>
          <textarea name="map_embed" class="form-control" rows="2" placeholder='&lt;iframe src="https://www.google.com/maps/embed?pb=..." ...&gt;&lt;/iframe&gt; or just the URL'><?= htmlspecialchars($listing['map_embed'] ?? '') ?></textarea>
        </div>
        <div class="col-12">
          <label class="form-label fw-600">Short Description</label>
          <textarea name="short_description" class="form-control" rows="3"><?= htmlspecialchars($listing['short_description'] ?? '') ?></textarea>
        </div>
      </div>
    </div>
  </div>

  <?php if(in_array($listing['plan_level'],['premium','pro'])): ?>
  <div class="card mb-3">
    <div class="ch"><i class="bi bi-star me-2"></i>Premium Fields</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-sm-6">
          <label class="form-label fw-600">Website</label>
          <input type="url" name="website" class="form-control" value="<?= htmlspecialchars($listing['website'] ?? '') ?>">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Facebook</label>
          <input type="url" name="facebook" class="form-control" value="<?= htmlspecialchars($listing['facebook'] ?? '') ?>">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-600">Instagram</label>
          <input type="url" name="instagram" class="form-control" value="<?= htmlspecialchars($listing['instagram'] ?? '') ?>">
        </div>
        <div class="col-12">
          <label class="form-label fw-600">Business Images (Add to Gallery)</label>
          <input type="file" name="business_images[]" class="form-control" accept="image/*" multiple>
        </div>
        <div class="col-12">
          <label class="form-label fw-600">Keywords</label>
          <div class="d-flex flex-wrap gap-2 p-2 border rounded" style="max-height:150px;overflow-y:auto">
            <?php foreach($keywords as $kw): ?>
            <label class="d-flex align-items-center gap-1 small kw-item" data-cat="<?= $kw['category_id'] ?>">
              <input type="checkbox" name="keyword_ids[]" value="<?= $kw['id'] ?>" <?= in_array($kw['id'],$selKwIds)?'checked':'' ?>>
              <?= htmlspecialchars($kw['name']) ?>
            </label>
            <?php endforeach ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif ?>

  <?php if($listing['plan_level']==='pro'): ?>
  <div class="card mb-3">
    <div class="ch"><i class="bi bi-trophy me-2"></i>Pro Fields</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label fw-600">YouTube URL</label>
          <input type="url" name="youtube_url" class="form-control" value="<?= htmlspecialchars($listing['youtube_url'] ?? '') ?>">
        </div>
        <div class="col-12">
          <label class="form-label fw-600">Top Banner <small class="text-muted" style="font-weight:400">— Recommended Size: 382 px × 132 px (leave blank to keep current)</small></label>
          <input type="file" name="top_banner" class="form-control" accept="image/*">
          <?php if($listing['top_banner']): ?>
            <img src="<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($listing['top_banner']) ?>" class="mt-2 rounded" style="width:382px;max-width:100%;height:132px;object-fit:cover;border:1px solid #dee2e6">
          <?php endif ?>
        </div>
      </div>
    </div>
  </div>
  <?php endif ?>

</div>
<div class="col-lg-4">
  <div class="card sticky-top" style="top:80px">
    <div class="ch"><i class="bi bi-save me-2"></i>Save</div>
    <div class="card-body">
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-p"><i class="bi bi-save me-1"></i>Save Changes</button>
        <a href="<?= BASE_URL ?>/admin/listings/<?= $listing['id'] ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</div>
</div>
</form>
<?php $extraJs = '<script>
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
  const catSel = document.getElementById("categorySelect");
  if(catSel) {
    catSel.addEventListener("change", filterKeywords);
    filterKeywords();
  }
});
</script>' ?>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
