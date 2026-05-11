<?php
$pageTitle  = "Edit Listing";
$activePage = "dashboard";
require CITY_DIR . "/views/layout/header.php";
?>
<main>
<style>
.edit-wrap{max-width:720px;margin:0 auto;padding:24px 16px}
.form-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;margin-bottom:14px}
.form-card h3{font-family:"Syne",sans-serif;font-weight:700;font-size:0.92rem;color:var(--text-dark);margin-bottom:14px;display:flex;align-items:center;gap:6px}
.fg{margin-bottom:14px}
.fg label{display:block;font-size:0.8rem;font-weight:600;color:var(--text-mid);margin-bottom:5px}
.fi{width:100%;padding:11px 13px;border:1.5px solid var(--border);border-radius:9px;font-size:0.88rem;font-family:inherit;outline:none;transition:border-color 0.2s}
.fi:focus{border-color:var(--primary)}
.fr{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.kw-wrap{display:flex;flex-wrap:wrap;gap:6px;padding:10px;border:1.5px solid var(--border);border-radius:9px}
.kw-chip{padding:4px 10px;border-radius:40px;border:1.5px solid var(--border);font-size:0.75rem;font-weight:600;color:var(--text-mid);cursor:pointer;transition:var(--transition)}
.kw-chip.selected{border-color:var(--primary);background:var(--purple-light);color:var(--primary)}
.btn-save{padding:12px 28px;border-radius:10px;background:var(--primary);color:#fff;border:none;font-size:0.92rem;font-weight:700;font-family:inherit;cursor:pointer;min-height:46px}
.btn-cancel{padding:12px 20px;border-radius:10px;border:1.5px solid var(--border);background:#fff;color:var(--text-mid);font-size:0.88rem;font-weight:600;font-family:inherit;cursor:pointer;min-height:46px;text-decoration:none;display:inline-flex;align-items:center}
@media(max-width:480px){.fr{grid-template-columns:1fr}}
</style>
<div class="edit-wrap">
  <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
    <a href="<?= $cityUrl ?>/dashboard" style="color:var(--text-muted);font-size:0.85rem;display:flex;align-items:center;gap:4px"><i class="bi bi-arrow-left"></i>Dashboard</a>
    <span style="color:var(--border)">•</span>
    <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.88rem">Edit Listing</span>
  </div>
  <div style="background:var(--amber-light);border-radius:9px;padding:10px 14px;font-size:0.82rem;color:var(--amber);margin-bottom:14px">
    <i class="bi bi-exclamation-triangle me-1"></i>Editing will re-submit your listing for admin approval.
  </div>
  <form method="POST" action="<?= $cityUrl ?>/edit-ad" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <div class="form-card">
      <h3><i class="bi bi-building" style="color:var(--primary)"></i>Business Info</h3>
      <div class="fg"><label>Business Name *</label><input type="text" name="business_name" class="fi" value="<?= htmlspecialchars($listing["business_name"]??"") ?>" required></div>
      <div class="fr">
        <div class="fg"><label>Phone</label><input type="tel" name="phone" class="fi" value="<?= htmlspecialchars($listing["phone"]??"") ?>"></div>
        <div class="fg"><label>WhatsApp</label><input type="tel" name="whatsapp" class="fi" value="<?= htmlspecialchars($listing["whatsapp"]??"") ?>"></div>
      </div>
      <div class="fr">
        <div class="fg"><label>Email</label><input type="email" name="email" class="fi" value="<?= htmlspecialchars($listing["email"]??"") ?>"></div>
        <div class="fg"><label>Category</label><select name="category_id" class="fi"><?php foreach($categories as $c):?><option value="<?= $c["id"] ?>" <?= $listing["category_id"]==$c["id"]?"selected":"" ?>><?= htmlspecialchars($c["name"]) ?></option><?php endforeach ?></select></div>
      </div>
      <div class="fg"><label>Address</label><input type="text" name="address" class="fi" value="<?= htmlspecialchars($listing["address"]??"") ?>"></div>
      <div class="fg"><label>Google Map Embed <span style="font-weight:400;color:var(--text-muted);font-size:0.78rem">(Optional — paste the iframe code or src URL from Google Maps "Share &rarr; Embed a map")</span></label>
        <textarea name="map_embed" class="fi" rows="2" placeholder='&lt;iframe src="https://www.google.com/maps/embed?pb=..." ...&gt;&lt;/iframe&gt; or just the URL'><?= htmlspecialchars($listing["map_embed"]??"") ?></textarea>
      </div>
      <div class="fg"><label>Short Description</label><textarea name="short_description" class="fi" rows="3"><?= htmlspecialchars($listing["short_description"]??"") ?></textarea></div>
    </div>
    <?php if(in_array(strtolower($listing["plan_level"]??""),['premium','pro'])): ?>
    <div class="form-card">
      <h3><i class="bi bi-star" style="color:var(--amber)"></i>Premium Fields</h3>
      <div class="fr">
        <div class="fg"><label>Website</label><input type="url" name="website" class="fi" value="<?= htmlspecialchars($listing["website"]??"") ?>"></div>
        <div class="fg"><label>Facebook</label><input type="url" name="facebook" class="fi" value="<?= htmlspecialchars($listing["facebook"]??"") ?>"></div>
      </div>
      <div class="fg"><label>Instagram</label><input type="url" name="instagram" class="fi" value="<?= htmlspecialchars($listing["instagram"]??"") ?>"></div>
      <div class="fg"><label>Keywords</label>
        <div class="kw-wrap"><?php foreach($keywords as $kw):?><label class="kw-chip <?= in_array($kw["id"],$selKwIds)?"selected":"" ?>" onclick="this.classList.toggle('selected');this.querySelector('input').checked=!this.querySelector('input').checked"><input type="checkbox" name="keyword_ids[]" value="<?= $kw["id"] ?>" style="display:none" <?= in_array($kw["id"],$selKwIds)?"checked":"" ?>><?= htmlspecialchars($kw["name"]) ?></label><?php endforeach ?></div>
      </div>
    </div>
    <div class="form-card">
      <h3><i class="bi bi-images" style="color:var(--primary)"></i>Business Images</h3>
      <div class="fg">
        <?php if(!empty($currentImages)): ?>
          <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px">
            <?php foreach($currentImages as $cimg): ?>
              <img src="<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($cimg["filename"]) ?>" style="width:70px;height:70px;object-fit:cover;border-radius:8px;border:1.5px solid var(--border)">
            <?php endforeach ?>
          </div>
        <?php endif ?>
        <label>Upload New Images (Max 5 images total, max 5MB each)</label>
        <div class="upload-area" onclick="document.getElementById('listingImagesI').click()" style="border:2px dashed var(--border);border-radius:9px;padding:16px;text-align:center;cursor:pointer;margin-top:5px;transition:border-color .2s">
          <i class="bi bi-cloud-upload" style="font-size:1.8rem;color:var(--primary);display:block;margin-bottom:6px"></i>
          <p style="font-size:0.78rem;color:var(--text-muted)">Click to upload new images</p>
          <input type="file" name="listing_images[]" id="listingImagesI" accept="image/*" multiple style="display:none" onchange="document.getElementById('listingImagesN').textContent = Array.from(this.files).map(f => f.name).join(', ')">
          <span id="listingImagesN" style="font-size:0.75rem;color:var(--primary)"></span>
        </div>
      </div>
    </div>
    <?php endif ?>
    <?php if(strtolower($listing["plan_level"]??"")==="pro"): ?>
    <div class="form-card">
      <h3><i class="bi bi-trophy" style="color:var(--green)"></i>Pro Fields</h3>
      <div class="fg"><label>YouTube URL</label><input type="url" name="youtube_url" class="fi" value="<?= htmlspecialchars($listing["youtube_url"]??"") ?>"></div>
      <div class="fg"><label>Top Banner Image</label>
        <?php if($listing["top_banner"]): ?>
          <div style="margin-bottom:8px">
            <img src="<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($listing["top_banner"]) ?>" style="height:60px;border-radius:6px;border:1.5px solid var(--border)">
          </div>
        <?php endif ?>
        <div class="upload-area" onclick="document.getElementById('bannerI').click()" style="border:2px dashed var(--border);border-radius:9px;padding:16px;text-align:center;cursor:pointer;transition:border-color .2s">
          <i class="bi bi-cloud-upload" style="font-size:1.5rem;color:var(--primary);display:block;margin-bottom:6px"></i>
          <p style="font-size:0.75rem;color:var(--text-muted)">Change top banner image (Max 5MB)</p>
          <input type="file" name="top_banner" id="bannerI" accept="image/*" style="display:none" onchange="document.getElementById('bannerN').textContent = this.files[0].name">
          <span id="bannerN" style="font-size:0.75rem;color:var(--primary)"></span>
        </div>
      </div>
    </div>
    <?php endif ?>
    <div style="display:flex;gap:10px">
      <button type="submit" class="btn-save"><i class="bi bi-save me-2"></i>Save & Resubmit</button>
      <a href="<?= $cityUrl ?>/dashboard" class="btn-cancel">Cancel</a>
    </div>
  </form>
</div>
</main>
<?php require CITY_DIR . "/views/layout/footer.php"; ?>
