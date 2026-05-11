<?php $pageTitle = 'Listing Detail'; require BASE_PATH . '/admin/views/layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <a href="<?= BASE_URL ?>/admin/listings" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back
  </a>
  <div class="d-flex gap-2 flex-wrap">
    <?php if ($listing['status'] === 'pending'): ?>
      <form method="POST" action="<?= BASE_URL ?>/admin/listings/approve">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <input type="hidden" name="id" value="<?= $listing['id'] ?>">
        <button class="btn btn-success"><i class="bi bi-check2-circle me-1"></i>Approve & Publish</button>
      </form>
      <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
        <i class="bi bi-x-circle me-1"></i>Reject
      </button>
    <?php elseif ($listing['status'] === 'approved'): ?>
      <span class="badge bg-success fs-6 py-2 px-3"><i class="bi bi-check2-circle me-1"></i>Published</span>
    <?php elseif ($listing['status'] === 'rejected'): ?>
      <span class="badge bg-danger fs-6 py-2 px-3"><i class="bi bi-x-circle me-1"></i>Rejected</span>
    <?php endif ?>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="card mb-3">
      <div class="ch"><i class="bi bi-building me-2"></i>Business Information</div>
      <div class="card-body">
        <div class="row g-3 small">
          <div class="col-sm-6"><label class="text-muted d-block">Business Name</label><div class="fw-600"><?= htmlspecialchars($listing['business_name'] ?? '—') ?></div></div>
          <div class="col-sm-6"><label class="text-muted d-block">Category</label><div><?= htmlspecialchars($listing['cat_name'] ?? '—') ?></div></div>
          <div class="col-sm-6"><label class="text-muted d-block">Phone</label><div><?= htmlspecialchars($listing['phone'] ?? '—') ?></div></div>
          <div class="col-sm-6"><label class="text-muted d-block">WhatsApp</label><div><?= htmlspecialchars($listing['whatsapp'] ?? '—') ?></div></div>
          <div class="col-sm-6"><label class="text-muted d-block">Email</label><div><?= htmlspecialchars($listing['email'] ?? '—') ?></div></div>
          <div class="col-sm-6"><label class="text-muted d-block">Website</label>
            <div><?= $listing['website'] ? '<a href="'.htmlspecialchars($listing['website']).'" target="_blank">'.htmlspecialchars($listing['website']).'</a>' : '—' ?></div>
          </div>
          <div class="col-12"><label class="text-muted d-block">Address</label><div><?= htmlspecialchars($listing['address'] ?? '—') ?></div></div>
          <div class="col-12"><label class="text-muted d-block">Description</label><div><?= htmlspecialchars($listing['short_description'] ?? '—') ?></div></div>
          <?php if (!empty($listing['map_embed'])): ?>
            <div class="col-12"><label class="text-muted d-block">Google Map</label>
              <div style="position:relative;padding-bottom:40%;border-radius:6px;overflow:hidden;border:1px solid #e5e7eb;margin-top:6px">
                <iframe style="position:absolute;inset:0;width:100%;height:100%;border:0" src="<?= htmlspecialchars($listing['map_embed']) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
              </div>
            </div>
          <?php endif ?>
          <?php if ($listing['youtube_url']): ?>
            <div class="col-12"><label class="text-muted d-block">YouTube</label><div><?= htmlspecialchars($listing['youtube_url']) ?></div></div>
          <?php endif ?>
          <?php if ($listing['top_banner']): ?>
            <div class="col-12"><label class="text-muted d-block">Top Banner (Pro)</label>
              <img src="<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($listing['top_banner']) ?>" class="mt-1 rounded border" style="max-height:120px">
            </div>
          <?php endif ?>
        </div>
      </div>
    </div>

    <?php if (!empty($subcats)): ?>
    <div class="card mb-3">
      <div class="ch"><i class="bi bi-grid-3x3-gap me-2"></i>Subcategories</div>
      <div class="card-body">
        <?php foreach ($subcats as $s): ?>
          <span class="badge bg-secondary me-1 mb-1"><?= htmlspecialchars($s['name']) ?></span>
        <?php endforeach ?>
      </div>
    </div>
    <?php endif ?>

    <?php if (!empty($keywords)): ?>
    <div class="card mb-3">
      <div class="ch"><i class="bi bi-tags me-2"></i>Keywords</div>
      <div class="card-body">
        <?php foreach ($keywords as $k): ?>
          <span class="badge bg-info me-1 mb-1"><?= htmlspecialchars($k['name']) ?></span>
        <?php endforeach ?>
      </div>
    </div>
    <?php endif ?>

    <?php if (!empty($images)): ?>
    <div class="card mb-3">
      <div class="ch"><i class="bi bi-images me-2"></i>Gallery</div>
      <div class="card-body">
        <div class="row g-2">
          <?php foreach ($images as $img): ?>
          <div class="col-4 col-md-3">
            <img src="<?= BASE_URL ?>/assets/uploads/listings/<?= htmlspecialchars($img['filename']) ?>"
                 class="img-fluid rounded" style="height:85px;object-fit:cover;width:100%">
          </div>
          <?php endforeach ?>
        </div>
      </div>
    </div>
    <?php endif ?>

    <?php if (!empty($services)): ?>
    <div class="card">
      <div class="ch"><i class="bi bi-list-check me-2"></i>Services</div>
      <div class="card-body p-0">
        <table class="table mb-0 small">
          <thead><tr><th>Title</th><th>Description</th><th>Price</th></tr></thead>
          <tbody>
          <?php foreach ($services as $svc): ?>
          <tr>
            <td class="fw-600"><?= htmlspecialchars($svc['title']) ?></td>
            <td><?= htmlspecialchars($svc['description'] ?? '') ?></td>
            <td><?= htmlspecialchars($svc['price'] ?? '—') ?></td>
          </tr>
          <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif ?>
  </div>

  <div class="col-lg-4">
    <div class="card mb-3">
      <div class="ch"><i class="bi bi-person me-2"></i>Owner</div>
      <div class="card-body small">
        <dl class="row mb-0">
          <dt class="col-5 text-muted">Name</dt><dd class="col-7 fw-600"><?= htmlspecialchars($listing['uname']) ?></dd>
          <dt class="col-5 text-muted">Phone</dt><dd class="col-7"><?= htmlspecialchars($listing['uphone'] ?? '—') ?></dd>
          <dt class="col-5 text-muted">Email</dt><dd class="col-7"><?= htmlspecialchars($listing['uemail'] ?? '—') ?></dd>
          <dt class="col-5 text-muted">City</dt><dd class="col-7"><?= htmlspecialchars($listing['city_name'] ?? '—') ?></dd>
        </dl>
        <a href="<?= BASE_URL ?>/admin/users/<?= $listing['user_id'] ?>" class="btn btn-sm btn-op mt-2 w-100">
          <i class="bi bi-person-badge me-1"></i>View User
        </a>
      </div>
    </div>
    <div class="card">
      <div class="ch"><i class="bi bi-info-circle me-2"></i>Meta</div>
      <div class="card-body small">
        <dl class="row mb-0">
          <dt class="col-5 text-muted">Plan</dt><dd class="col-7"><?= Helper::planBadge($listing['plan_level']) ?></dd>
          <dt class="col-5 text-muted">Status</dt><dd class="col-7"><?= Helper::statusBadge($listing['status']) ?></dd>
          <dt class="col-5 text-muted">Views</dt><dd class="col-7"><?= number_format($listing['views']) ?></dd>
          <dt class="col-5 text-muted">Featured</dt><dd class="col-7"><?= $listing['is_featured'] ? '<span class="badge bg-warning text-dark">Yes</span>' : 'No' ?></dd>
          <dt class="col-5 text-muted">Submitted</dt><dd class="col-7"><?= Helper::formatDate($listing['created_at']) ?></dd>
          <?php if ($listing['approved_at']): ?>
            <dt class="col-5 text-muted">Approved</dt><dd class="col-7"><?= Helper::formatDate($listing['approved_at']) ?></dd>
          <?php endif ?>
        </dl>
        <?php if ($listing['rejection_note']): ?>
          <div class="alert alert-danger py-2 mt-2 mb-0 small">
            <strong>Rejection note:</strong> <?= htmlspecialchars($listing['rejection_note']) ?>
          </div>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Reject Listing</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="<?= BASE_URL ?>/admin/listings/reject">
        <div class="modal-body">
          <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
          <input type="hidden" name="id" value="<?= $listing['id'] ?>">
          <label class="form-label fw-600">Reason for rejection</label>
          <textarea name="rejection_note" class="form-control" rows="4"
                    placeholder="Tell the user why this listing is being rejected…"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle me-1"></i>Confirm Reject</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require BASE_PATH . '/admin/views/layout/footer.php'; ?>
