<?php
$pageTitle  = "My Dashboard";
$activePage = "dashboard";
require CITY_DIR . "/views/layout/header.php";
?>
<main>
<style>
.dash-hero{background:linear-gradient(135deg,#2d1b69 0%,var(--primary) 60%,#3a7c5a 100%);padding:24px 20px 0}
.dash-inner{max-width:1050px;margin:0 auto;display:flex;align-items:flex-end;gap:16px;flex-wrap:wrap}
.dash-av{width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,0.2);border:3px solid rgba(255,255,255,0.35);display:flex;align-items:center;justify-content:center;font-family:"Syne",sans-serif;font-weight:800;font-size:1.6rem;color:#fff;flex-shrink:0;margin-bottom:20px}
.dash-info{flex:1;min-width:180px;padding-bottom:20px}
.dash-info h1{font-family:"Syne",sans-serif;font-weight:800;font-size:1.3rem;color:#fff;margin-bottom:4px}
.dash-info p{font-size:0.8rem;color:rgba(255,255,255,0.7);margin-bottom:8px}
.dash-badges{display:flex;flex-wrap:wrap;gap:5px}
.dbadge{display:inline-flex;align-items:center;gap:3px;background:rgba(255,255,255,0.15);border-radius:40px;padding:3px 9px;font-size:0.7rem;font-weight:600;color:#fff}
.dash-stats{display:flex;gap:16px;padding-bottom:20px;margin-left:auto}
.dstat{text-align:center}
.dstat strong{display:block;font-family:"Syne",sans-serif;font-weight:800;font-size:1.3rem;color:#fff}
.dstat span{font-size:0.68rem;color:rgba(255,255,255,0.65)}
.tabs-bar{background:#fff;border-bottom:2px solid var(--border);position:sticky;top:var(--header-h);z-index:800}
.tabs-inner{max-width:1050px;margin:0 auto;display:flex;flex-wrap:wrap;overflow-x:auto;scrollbar-width:none;padding:0 8px}
.tabs-inner::-webkit-scrollbar{display:none}
.tab-btn{padding:13px 16px;border:none;background:none;font-family:"DM Sans",sans-serif;font-size:0.85rem;font-weight:600;color:var(--text-muted);cursor:pointer;white-space:nowrap;position:relative;display:flex;align-items:center;gap:5px;flex-shrink:0;min-height:48px}
.tab-btn::after{content:"";position:absolute;bottom:-2px;left:0;right:0;height:2px;background:var(--primary);border-radius:2px;opacity:0}
.tab-btn.active{color:var(--primary)}.tab-btn.active::after{opacity:1}
.tab-badge{background:var(--primary);color:#fff;border-radius:40px;padding:1px 6px;font-size:0.65rem}
.dash-wrap{max-width:1050px;margin:0 auto;padding:20px 16px}
.tab-panel{display:none}.tab-panel.active{display:block}
.stat-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px}
.scard{background:#fff;border-radius:var(--radius);padding:16px;box-shadow:var(--shadow)}
.scard-ic{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:10px}
.scard-ic.p{background:var(--purple-light);color:var(--purple)}.scard-ic.g{background:var(--green-light);color:var(--green)}
.scard-ic.a{background:var(--amber-light);color:var(--amber)}.scard-ic.t{background:var(--teal-light);color:var(--teal)}
.scard strong{display:block;font-family:"Syne",sans-serif;font-weight:800;font-size:1.6rem;color:var(--text-dark);line-height:1}
.scard span{font-size:0.75rem;color:var(--text-muted);margin-top:3px;display:block}
.lcard{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:16px;margin-bottom:12px}
.lcard-head{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;flex-wrap:wrap;gap:8px}
.lcard-name{font-family:"Syne",sans-serif;font-weight:700;font-size:1rem;color:var(--text-dark)}
.lcard-meta{font-size:0.78rem;color:var(--text-muted)}
.lcard-actions{display:flex;gap:7px;flex-wrap:wrap;margin-top:12px}
.btn-a{padding:8px 14px;border-radius:9px;font-size:0.8rem;font-weight:600;font-family:inherit;cursor:pointer;min-height:40px;display:inline-flex;align-items:center;gap:5px;text-decoration:none;border:none}
.btn-a.p{background:var(--primary);color:#fff}.btn-a.o{background:transparent;border:1.5px solid var(--border);color:var(--text-mid)}
.plan-t{padding:2px 8px;border-radius:40px;font-size:0.68rem;font-weight:700}
.plan-t.free{background:#f3f4f6;color:#4b5563}.plan-t.basic{background:var(--teal-light);color:var(--teal)}
.plan-t.premium{background:var(--amber-light);color:var(--amber)}.plan-t.pro{background:var(--green-light);color:var(--green)}
.stat-t{padding:2px 8px;border-radius:40px;font-size:0.68rem;font-weight:600}
.stat-t.approved{background:#d1fae5;color:#065f46}.stat-t.pending{background:#fef3c7;color:#92400e}.stat-t.rejected{background:#fee2e2;color:#991b1b}
.rev-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:14px;margin-bottom:10px}
.pay-tbl{width:100%;border-collapse:collapse;background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden}
.pay-tbl th{background:#f8f7ff;padding:10px 14px;font-size:0.72rem;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);font-weight:600;text-align:left;border-bottom:1px solid var(--border)}
.pay-tbl td{padding:12px 14px;font-size:0.85rem;border-bottom:1px solid var(--sand-dark)}
.pay-s{padding:2px 8px;border-radius:40px;font-size:0.7rem;font-weight:600}
.pay-s.confirmed{background:#d1fae5;color:#065f46}.pay-s.pending{background:#fef3c7;color:#92400e}.pay-s.rejected{background:#fee2e2;color:#991b1b}
.upg-card{background:linear-gradient(135deg,var(--primary),#2d1b69);border-radius:var(--radius);padding:18px;color:#fff;text-align:center;margin-bottom:14px}
.upg-card h4{font-family:"Syne",sans-serif;font-weight:800;font-size:0.95rem;margin-bottom:5px}
.upg-card p{font-size:0.78rem;opacity:0.85;margin-bottom:12px}
.btn-upg{padding:9px 20px;border-radius:40px;background:#fff;color:var(--primary);border:none;font-size:0.82rem;font-weight:700;font-family:inherit;cursor:pointer}
.pform{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:20px}
.pform label{display:block;font-size:0.82rem;font-weight:600;color:var(--text-mid);margin-bottom:5px}
.pform input{width:100%;padding:11px 13px;border:1.5px solid var(--border);border-radius:9px;font-family:inherit;font-size:0.88rem;outline:none;margin-bottom:14px}
.pform input:focus{border-color:var(--primary)}
.btn-save{padding:12px 24px;border-radius:10px;background:var(--primary);color:#fff;border:none;font-size:0.9rem;font-weight:700;font-family:inherit;cursor:pointer;min-height:46px}
@media(max-width:768px){.tabs-inner{flex-wrap:nowrap}.stat-cards{grid-template-columns:repeat(2,1fr)}.dash-stats{gap:12px}.tab-logout-desktop{display:none!important}.mobile-logout-btn{display:flex!important}}
</style>

<section class="dash-hero">
  <div class="dash-inner">
    <div class="dash-av"><?= strtoupper(substr($user["name"],0,1)) ?></div>
    <div class="dash-info">
      <h1><?= htmlspecialchars($user["name"]) ?></h1>
      <p><?= htmlspecialchars($user["phone"]) ?><?= $user["email"] ? " · ".htmlspecialchars($user["email"]) : "" ?></p>
      <div class="dash-badges">
        <?php if($user["city_name"]): ?><span class="dbadge"><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($user["city_name"]) ?></span><?php endif ?>
        <span class="dbadge"><i class="bi bi-star-fill"></i> <?= htmlspecialchars($user["plan_label"]??"Free") ?></span>
        <?php if($user["phone_verified"]??false): ?><span class="dbadge"><i class="bi bi-patch-check-fill"></i> Verified</span><?php endif ?>
      </div>
    </div>
    <div class="dash-stats">
      <div class="dstat"><strong><?= number_format($listing["views"]??0) ?></strong><span>Views</span></div>
      <div class="dstat"><strong><?= $listing["avg_rating"]??"—" ?></strong><span>Rating</span></div>
      <div class="dstat"><strong><?= $listing["review_count"]??0 ?></strong><span>Reviews</span></div>
    </div>
  </div>
</section>

<div class="tabs-bar"><div class="tabs-inner">
  <button class="tab-btn active" data-tab="overview"><i class="bi bi-grid-1x2"></i> Overview</button>
  <button class="tab-btn" data-tab="listing"><i class="bi bi-building"></i> My Ads</button>
  <button class="tab-btn" data-tab="reviews"><i class="bi bi-star"></i> Reviews <span class="tab-badge"><?= $listing["review_count"]??0 ?></span></button>
  <button class="tab-btn" data-tab="payments"><i class="bi bi-credit-card"></i> Payments</button>
  <button class="tab-btn" data-tab="profile"><i class="bi bi-person-gear"></i> Profile</button>
  <a class="tab-btn tab-logout-desktop" href="<?= $cityUrl ?>/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div></div>

<!-- Mobile-only logout button -->
<div class="mobile-logout-btn" style="display:none;padding:10px 16px 0;max-width:1050px;margin:0 auto;justify-content:flex-end;">
  <a href="<?= $cityUrl ?>/logout" style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:40px;border:1.5px solid rgba(124,58,237,0.25);color:#7c3aed;font-size:0.82rem;font-weight:600;font-family:'DM Sans',sans-serif;text-decoration:none;background:rgba(124,58,237,0.06);transition:all 0.2s ease;-webkit-tap-highlight-color:transparent;" onmouseenter="this.style.background='rgba(124,58,237,0.12)'" onmouseleave="this.style.background='rgba(124,58,237,0.06)'"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="dash-wrap">

  <div class="tab-panel active" id="tab-overview">
    <div class="stat-cards">
      <div class="scard"><div class="scard-ic p"><i class="bi bi-eye-fill"></i></div><strong><?= number_format($listing["views"]??0) ?></strong><span>Views</span></div>
      <div class="scard"><div class="scard-ic g"><i class="bi bi-star-fill"></i></div><strong><?= $listing["avg_rating"]??"0" ?></strong><span>Avg Rating</span></div>
      <div class="scard"><div class="scard-ic a"><i class="bi bi-chat-dots-fill"></i></div><strong><?= $listing["review_count"]??0 ?></strong><span>Reviews</span></div>
      <div class="scard"><div class="scard-ic t"><i class="bi bi-tag-fill"></i></div><strong><?= ucfirst($listing["plan_level"] ?? ($user["plan_name"] ?? "free")) ?></strong><span>Plan</span></div>
    </div>
    <?php if(!$listing): ?>
    <div style="background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:28px;text-align:center">
      <div style="font-size:2.5rem;margin-bottom:10px">📋</div>
      <h3 style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:6px"><?= ($user["plan_name"] ?? "free") === "free" ? "Free Plan Active" : "No Listing Yet" ?></h3>
      <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:16px"><?= ($user["plan_name"] ?? "free") === "free" ? "Your profile is shown in the home page sidebar with your name, profession, and phone. Upgrade to Basic or higher to create a business page." : "Post your business listing to appear in search results." ?></p>
      <a href="<?= $cityUrl ?>/post-ad" class="btn-a p"><i class="bi bi-plus-circle"></i> Post Your Ad</a>
    </div>
    <?php else: ?>
    <div class="lcard">
      <div class="lcard-head">
        <div><div class="lcard-name"><?= htmlspecialchars($listing["business_name"]??"My Business") ?></div><div class="lcard-meta"><?= htmlspecialchars($listing["cat_name"]??"") ?></div></div>
        <div style="display:flex;gap:6px"><span class="plan-t <?= $listing["plan_level"] ?>"><?= ucfirst($listing["plan_level"]) ?></span><span class="stat-t <?= $listing["status"] ?>"><?= ucfirst($listing["status"]) ?></span></div>
      </div>
      <?php if($listing["status"]==="pending"): ?><div style="background:#fef3c7;border-radius:8px;padding:9px 12px;font-size:0.8rem;color:#92400e;margin-bottom:10px"><i class="bi bi-hourglass-split me-1"></i>Listing under review. Approval within 24 hours.</div><?php endif ?>
      <?php if($listing["status"]==="rejected"&&$listing["rejection_note"]): ?><div style="background:#fee2e2;border-radius:8px;padding:9px 12px;font-size:0.8rem;color:#991b1b;margin-bottom:10px"><i class="bi bi-x-circle me-1"></i><?= htmlspecialchars($listing["rejection_note"]) ?></div><?php endif ?>
      <div class="lcard-actions">
        <a href="<?= $cityUrl ?>/edit-ad" class="btn-a p"><i class="bi bi-pencil"></i> Edit</a>
        <?php if($listing["status"]==="approved"): ?><a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($listing["slug"]) ?>" class="btn-a o" target="_blank"><i class="bi bi-eye"></i> View Live</a><?php endif ?>
        <?php
        $hasPendingPayment = !empty(array_filter($payments, fn($p) => $p['status'] === 'pending'));
        if (!$hasPendingPayment): ?>
        <a href="<?= $cityUrl ?>/upgrade" class="btn-a o"><i class="bi bi-arrow-up-circle"></i> Upgrade</a>
        <?php else: ?>
        <span class="btn-a o" style="opacity:0.6;cursor:default;pointer-events:none"><i class="bi bi-hourglass-split"></i> Payment Pending</span>
        <?php endif ?>
      </div>
    </div>
    <?php endif ?>
    <?php if(($user["plan_name"]??"free")==="free"): ?>
    <div class="upg-card"><h4>Upgrade for More Visibility</h4><p>Get photos, social links, keywords and appear higher in search.</p><a href="<?= $cityUrl ?>/upgrade" class="btn-upg">View Plans</a></div>
    <?php endif ?>
  </div>

  <div class="tab-panel" id="tab-listing">
    <?php if(!$listing): ?><div style="text-align:center;padding:40px 16px"><a href="<?= $cityUrl ?>/post-ad" class="btn-a p"><i class="bi bi-plus-circle"></i> Post Ad Now</a></div>
    <?php else: ?>
    <div class="lcard">
      <div class="lcard-head">
        <div><div class="lcard-name"><?= htmlspecialchars($listing["business_name"]) ?></div><div class="lcard-meta"><?= htmlspecialchars($listing["address"]??"") ?></div></div>
        <span class="plan-t <?= $listing["plan_level"] ?>"><?= ucfirst($listing["plan_level"]) ?></span>
      </div>
      <dl style="display:grid;grid-template-columns:1fr 1fr;gap:6px 14px;font-size:0.83rem">
        <div><dt style="color:var(--text-muted);font-size:0.72rem">Phone</dt><dd><?= htmlspecialchars($listing["phone"]??"—") ?></dd></div>
        <div><dt style="color:var(--text-muted);font-size:0.72rem">WhatsApp</dt><dd><?= htmlspecialchars($listing["whatsapp"]??"—") ?></dd></div>
        <div><dt style="color:var(--text-muted);font-size:0.72rem">Email</dt><dd><?= htmlspecialchars($listing["email"]??"—") ?></dd></div>
        <div><dt style="color:var(--text-muted);font-size:0.72rem">Status</dt><dd><span class="stat-t <?= $listing["status"] ?>"><?= ucfirst($listing["status"]) ?></span></dd></div>
        <div style="grid-column:1/-1"><dt style="color:var(--text-muted);font-size:0.72rem">Description</dt><dd style="color:var(--text-mid)"><?= htmlspecialchars($listing["short_description"]??"—") ?></dd></div>
      </dl>
      <div class="lcard-actions"><a href="<?= $cityUrl ?>/edit-ad" class="btn-a p"><i class="bi bi-pencil"></i> Edit</a><?php if($listing["status"]==="approved"): ?><a href="<?= $cityUrl ?>/listing/<?= htmlspecialchars($listing["slug"]) ?>" class="btn-a o" target="_blank"><i class="bi bi-box-arrow-up-right"></i> View Live</a><?php endif ?></div>
    </div>
    <?php endif ?>
  </div>

  <div class="tab-panel" id="tab-reviews">
    <?php if(empty($reviews)): ?><div style="text-align:center;padding:40px;color:var(--text-muted)"><i class="bi bi-star" style="font-size:2.5rem;display:block;margin-bottom:10px"></i><p>No reviews yet.</p></div>
    <?php else: foreach($reviews as $rev): ?>
    <div class="rev-card">
      <div style="display:flex;justify-content:space-between;margin-bottom:5px">
        <strong style="font-size:0.85rem"><?= htmlspecialchars($rev["reviewer_name"]) ?></strong>
        <span style="color:#f59e0b"><?= str_repeat("★",(int)$rev["rating"]) ?><?= str_repeat("☆",5-(int)$rev["rating"]) ?></span>
      </div>
      <p style="font-size:0.83rem;color:var(--text-mid)"><?= htmlspecialchars($rev["comment"]??"") ?></p>
      <div style="font-size:0.72rem;color:var(--text-muted);margin-top:4px"><?= Helper::timeAgo($rev["created_at"]) ?></div>
    </div>
    <?php endforeach; endif ?>
  </div>

  <div class="tab-panel" id="tab-payments">
    <?php if(empty($payments)): ?><div style="text-align:center;padding:40px;color:var(--text-muted)"><i class="bi bi-credit-card" style="font-size:2.5rem;display:block;margin-bottom:10px"></i><p>No payment records.</p></div>
    <?php else: ?>
    <div style="overflow-x:auto">
      <table class="pay-tbl">
        <thead><tr><th>Date</th><th>Plan</th><th>Amount</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach($payments as $p): ?>
        <tr>
          <td><?= Helper::formatDate($p["created_at"]) ?></td>
          <td><?= htmlspecialchars($p["label"]??"—") ?></td>
          <td style="font-weight:700">₹<?= number_format($p["amount"],0) ?></td>
          <td><span class="pay-s <?= $p["status"] ?>"><?= ucfirst($p["status"]) ?></span></td>
        </tr>
        <?php endforeach ?>
        </tbody>
      </table>
    </div>
    <?php endif ?>
    <div style="margin-top:14px;text-align:center">
      <?php if(empty(array_filter($payments, fn($p) => $p['status'] === 'pending'))): ?>
      <a href="<?= $cityUrl ?>/upgrade" class="btn-a p"><i class="bi bi-arrow-up-circle"></i> Upgrade / Renew</a>
      <?php else: ?>
      <span class="btn-a o" style="opacity:0.6;cursor:default;pointer-events:none"><i class="bi bi-hourglass-split"></i> Payment Pending — Awaiting Confirmation</span>
      <?php endif ?>
    </div>
  </div>

  <div class="tab-panel" id="tab-profile">
    <div class="pform">
      <h3 style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.95rem;margin-bottom:16px">Edit Profile</h3>
      <form method="POST" action="<?= $cityUrl ?>/profile/update">
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
          <div><label>Name</label><input type="text" name="name" value="<?= htmlspecialchars($user["name"]) ?>"></div>
          <div><label>Phone (cannot change)</label><input type="text" value="<?= htmlspecialchars($user["phone"]) ?>" disabled style="opacity:0.6"></div>
          <div><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars($user["email"]??"") ?>"></div>
          <div><label>Profession</label><input type="text" name="profession" value="<?= htmlspecialchars($user["profession"]??"") ?>"></div>
        </div>
        <button type="submit" class="btn-save"><i class="bi bi-save me-2"></i>Save</button>
      </form>
      <?php if(empty($user["password"])): ?>
      <hr style="margin:20px 0;border-color:var(--border)">
      <a href="<?= $cityUrl ?>/set-password" class="btn-a o" style="text-decoration:none"><i class="bi bi-key"></i> Set Login Password</a>
      <?php endif ?>
    </div>
  </div>

</div>
</main>
<script>
document.querySelectorAll(".tab-btn[data-tab]").forEach(function(btn){
  btn.addEventListener("click",function(){
    var panel = document.getElementById("tab-"+this.dataset.tab);
    if(!panel){
      return;
    }
    document.querySelectorAll(".tab-btn[data-tab]").forEach(function(b){b.classList.remove("active");});
    document.querySelectorAll(".tab-panel").forEach(function(p){p.classList.remove("active");});
    this.classList.add("active");
    panel.classList.add("active");
  });
});
</script>
<?php require CITY_DIR . "/views/layout/footer.php"; ?>
