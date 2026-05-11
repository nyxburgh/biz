<?php
$pageTitle  = "Upgrade Plan";
$activePage = "dashboard";
require CITY_DIR . "/views/layout/header.php";
?>
<main>
<style>
.upg-wrap{max-width:840px;margin:0 auto;padding:24px 16px}
.plan-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:22px}
.pc{border:2px solid var(--border);border-radius:var(--radius);padding:18px;text-align:center;cursor:pointer;transition:var(--transition);background:#fff;position:relative;overflow:hidden}
.pc:hover:not(.pc-disabled){border-color:var(--purple-muted);transform:translateY(-2px)}
.pc.sel{border-color:var(--primary);background:var(--purple-light)}
.pc.pc-disabled{opacity:0.55;cursor:not-allowed;background:#f9f9f9}
.pc-check{display:none;position:absolute;top:8px;right:8px;width:20px;height:20px;border-radius:50%;background:var(--primary);color:#fff;align-items:center;justify-content:center;font-size:0.68rem}
.pc.sel .pc-check{display:flex}
.pc-active-badge{position:absolute;top:8px;left:8px;background:#10b981;color:#fff;border-radius:20px;font-size:0.65rem;font-weight:700;padding:2px 7px}
.pc-em{font-size:2rem;margin-bottom:8px;display:block}
.pc-name{font-family:"Syne",sans-serif;font-weight:700;font-size:0.95rem;margin-bottom:3px}
.pc-price{font-size:1.2rem;font-weight:800;color:var(--primary);font-family:"Syne",sans-serif;margin-bottom:8px}
.pc-feats{list-style:none;text-align:left;padding:0}
.pc-feats li{font-size:0.72rem;color:var(--text-muted);padding:2px 0;display:flex;align-items:center;gap:4px}
.pc-feats li i{color:var(--green);font-size:0.68rem}
.pay-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:20px}
.pay-note{background:var(--amber-light);border-radius:9px;padding:12px;font-size:0.8rem;color:var(--amber);margin-bottom:16px;line-height:1.6}
.fg{margin-bottom:14px}
.fg label{display:block;font-size:0.8rem;font-weight:600;color:var(--text-mid);margin-bottom:5px}
.fi{width:100%;padding:11px 13px;border:1.5px solid var(--border);border-radius:9px;font-size:0.88rem;font-family:inherit;outline:none}
.fi:focus{border-color:var(--primary)}
.fi.is-invalid{border-color:#ef4444}
.invalid-feedback{color:#ef4444;font-size:0.76rem;margin-top:3px}
.fr{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.upload-area{border:2px dashed var(--border);border-radius:9px;padding:16px;text-align:center;cursor:pointer;transition:border-color 0.2s}
.upload-area:hover{border-color:var(--primary)}
.upload-area.is-invalid{border-color:#ef4444}
.btn-sub{padding:13px 28px;border-radius:10px;background:var(--primary);color:#fff;border:none;font-size:0.92rem;font-weight:700;font-family:inherit;cursor:pointer;min-height:46px}
@media(max-width:600px){.plan-grid{grid-template-columns:1fr}.fr{grid-template-columns:1fr}}
</style>
<div class="upg-wrap">
  <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px">
    <a href="<?= $cityUrl ?>/dashboard" style="color:var(--text-muted);font-size:0.85rem;display:flex;align-items:center;gap:4px"><i class="bi bi-arrow-left"></i>Dashboard</a>
  </div>
  <h2 style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.3rem;margin-bottom:4px">Upgrade Your Plan</h2>
  <p style="color:var(--text-muted);font-size:0.88rem;margin-bottom:18px">Current plan: <strong><?= htmlspecialchars($user["plan_label"]??"Free") ?></strong></p>

  <form method="POST" action="<?= $cityUrl ?>/upgrade" enctype="multipart/form-data" id="upgradeForm" novalidate>
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <input type="hidden" name="plan_id" id="planId" value="">
    <div class="plan-grid">
      <?php
      $pdefs=["basic"=>["⭐","Basic",["Contact & address","Description","Public listing"]],"premium"=>["💎","Premium",["All Basic","Photos & social","Website","Keywords"]],"pro"=>["🚀","Pro",["All Premium","Services","Top banner","YouTube"]]];
      // Determine if current plan is still active
      $currentPlanName = $user["plan_name"] ?? "free";
      $planExpires = $user["plan_expires_at"] ?? null;
      $isCurrentPlanActive = $planExpires && strtotime($planExpires) > time();

      foreach($plans as $pl): $pn=$pl["name"]; if(!isset($pdefs[$pn]))continue; [$em,$lb,$fts]=$pdefs[$pn];
      $isActive = ($currentPlanName === $pn && $isCurrentPlanActive);
      ?>
      <div class="pc <?= $isActive ? 'pc-disabled' : '' ?>"
           data-plan-id="<?= $pl["id"] ?>"
           data-disabled="<?= $isActive ? '1' : '0' ?>"
           onclick="selPlan(<?= $pl["id"] ?>,this)">
        <?php if($isActive): ?>
        <div class="pc-active-badge"><i class="bi bi-check-lg"></i> Active</div>
        <?php endif ?>
        <div class="pc-check"><i class="bi bi-check-lg"></i></div>
        <span class="pc-em"><?= $em ?></span>
        <div class="pc-name"><?= htmlspecialchars($pl["label"]) ?></div>
        <div class="pc-price">₹<?= number_format($pl["price"],0) ?><span style="font-size:0.7rem;font-weight:400;color:var(--text-muted)">/yr</span></div>
        <ul class="pc-feats"><?php foreach($fts as $ft):?><li><i class="bi bi-check2"></i><?= htmlspecialchars($ft) ?></li><?php endforeach ?></ul>
        <?php if($isActive): ?><div style="font-size:0.7rem;color:#10b981;margin-top:6px;font-weight:600">Expires: <?= date('d M Y', strtotime($planExpires)) ?></div><?php endif ?>
      </div>
      <?php endforeach ?>
    </div>

    <div class="pay-card">
      <h3 style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.92rem;margin-bottom:14px"><i class="bi bi-credit-card me-2" style="color:var(--primary)"></i>Payment Details</h3>
      <div class="pay-note"><strong>Instructions:</strong> Transfer the plan amount to our UPI/bank account and upload the screenshot below. Your plan will be upgraded after admin confirmation.</div>
      <div class="fr">
        <div class="fg"><label>Payment Mode</label><select name="payment_mode" class="fi"><option value="UPI">UPI / GPay / PhonePe</option><option value="NEFT">Bank Transfer</option><option value="Cash">Cash</option></select></div>
        <div class="fg">
          <label>Transaction Reference <span style="color:#ef4444">*</span></label>
          <input type="text" name="reference" id="referenceInput" class="fi" placeholder="UPI ID / Txn ID">
          <div class="invalid-feedback" id="refError">Transaction reference is required.</div>
        </div>
      </div>
      <div class="fg"><label>Payment Proof (screenshot) <span style="color:#ef4444">*</span></label>
        <div class="upload-area" id="proofArea" onclick="document.getElementById('proofI').click()">
          <i class="bi bi-upload" style="font-size:1.5rem;color:var(--primary);display:block;margin-bottom:6px"></i>
          <p style="font-size:0.78rem;color:var(--text-muted)">Click to upload screenshot</p>
          <input type="file" name="payment_proof" id="proofI" accept="image/*,application/pdf" style="display:none" onchange="onProofChange(this)">
          <span id="proofN" style="font-size:0.75rem;color:var(--primary)"></span>
        </div>
        <div class="invalid-feedback" id="proofError">Payment proof screenshot is required.</div>
      </div>
      <button type="button" class="btn-sub" onclick="validateAndSubmit()"><i class="bi bi-arrow-up-circle me-2"></i>Submit Upgrade Request</button>
    </div>
  </form>
</div>
</main>
<script>
function selPlan(id, card) {
  if (card.getAttribute("data-disabled") === "1") return;
  document.querySelectorAll(".pc").forEach(function(c){ c.classList.remove("sel"); });
  card.classList.add("sel");
  document.getElementById("planId").value = id;
}

function onProofChange(input) {
  document.getElementById("proofN").textContent = input.files[0] ? input.files[0].name : "";
  if (input.files[0]) {
    document.getElementById("proofArea").classList.remove("is-invalid");
    document.getElementById("proofError").style.display = "none";
  }
}

function validateAndSubmit() {
  var valid = true;
  if (!document.getElementById("planId").value) {
    alert("Please select a plan.");
    return false;
  }
  var ref = document.getElementById("referenceInput");
  if (!ref.value.trim()) {
    ref.classList.add("is-invalid");
    document.getElementById("refError").style.display = "block";
    valid = false;
  } else {
    ref.classList.remove("is-invalid");
    document.getElementById("refError").style.display = "none";
  }
  var proofFile = document.getElementById("proofI");
  if (!proofFile.files.length) {
    document.getElementById("proofArea").classList.add("is-invalid");
    document.getElementById("proofError").style.display = "block";
    valid = false;
  } else {
    document.getElementById("proofArea").classList.remove("is-invalid");
    document.getElementById("proofError").style.display = "none";
  }
  if (valid) document.getElementById("upgradeForm").submit();
}

// Init — auto-select first non-disabled plan
(function(){
  var cards = document.querySelectorAll(".pc:not(.pc-disabled)");
  if (cards.length) cards[0].click();
})();
</script>
<?php require CITY_DIR . "/views/layout/footer.php"; ?>
