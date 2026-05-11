<?php
$pageTitle  = 'Login / Register';
$activePage = 'login';
require CITY_DIR . '/views/layout/header.php';
?>
<main>
<style>
.auth-wrap{min-height:calc(100dvh - var(--header-h));display:flex;align-items:center;justify-content:center;padding:24px 16px;background:var(--sand-light)}
.auth-card{background:#fff;border-radius:20px;box-shadow:var(--shadow-hover);width:100%;max-width:440px;overflow:hidden}
.auth-head{background:linear-gradient(135deg,var(--primary),#2d1b69);padding:28px 24px;text-align:center;color:#fff}
.auth-head h2{font-family:'Syne',sans-serif;font-weight:800;font-size:1.4rem;margin-bottom:4px}
.auth-head p{font-size:0.83rem;opacity:0.82}
.city-tag{display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,0.15);border-radius:40px;padding:4px 12px;font-size:0.7rem;font-weight:600;margin-bottom:14px}
.auth-body{padding:24px}
.type-cards{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:18px}
.type-card{border:2px solid var(--border);border-radius:12px;padding:16px 12px;text-align:center;cursor:pointer;transition:var(--transition);background:#fff}
.type-card:hover{border-color:var(--purple-muted);transform:translateY(-2px);box-shadow:var(--shadow)}
.type-card.sel{border-color:var(--primary);background:var(--purple-light)}
.tc-em{font-size:1.8rem;display:block;margin-bottom:7px}
.type-card h4{font-family:'Syne',sans-serif;font-weight:700;font-size:0.88rem;color:var(--text-dark);margin-bottom:3px}
.type-card p{font-size:0.71rem;color:var(--text-muted);line-height:1.4}
.btn-google{width:100%;padding:13px;border-radius:11px;border:1.5px solid var(--border);background:#fff;font-size:0.92rem;font-weight:600;font-family:inherit;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;transition:var(--transition);min-height:48px;color:var(--text-dark)}
.btn-google:hover{border-color:var(--primary);box-shadow:0 2px 12px rgba(124,58,237,0.12)}
.btn-google img{width:20px;height:20px}
.divider{text-align:center;color:var(--text-muted);font-size:0.78rem;margin:14px 0;position:relative}
.divider::before,.divider::after{content:'';position:absolute;top:50%;width:40%;height:1px;background:var(--border)}
.divider::before{left:0}.divider::after{right:0}
.fg{margin-bottom:14px}
.fg label{display:block;font-size:0.82rem;font-weight:600;color:var(--text-mid);margin-bottom:5px}
.fi{width:100%;padding:12px 13px;border:1.5px solid var(--border);border-radius:10px;font-size:0.9rem;font-family:inherit;outline:none;transition:border-color .2s}
.fi:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(124,58,237,.08)}
.btn-main{width:100%;padding:13px;border-radius:11px;background:var(--primary);color:#fff;border:none;font-size:0.92rem;font-weight:700;font-family:inherit;cursor:pointer;min-height:48px;transition:background .2s;display:flex;align-items:center;justify-content:center;gap:7px}
.btn-main:hover{background:#6d28d9}
.btn-main:disabled{opacity:.6;cursor:not-allowed}
.link-toggle{text-align:center;font-size:0.82rem;color:var(--text-muted);margin-top:14px}
.link-toggle a{color:var(--primary);font-weight:600;cursor:pointer;text-decoration:none}
.err{color:#ef4444;font-size:0.79rem;margin-top:6px;display:none}
.back-link{background:none;border:none;color:var(--text-muted);font-size:0.82rem;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:4px;padding:0;margin-bottom:16px}
.back-link:hover{color:var(--primary)}
.spin{width:18px;height:18px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:sp .7s linear infinite;display:inline-block}
@keyframes sp{to{transform:rotate(360deg)}}
.fr{display:grid;grid-template-columns:1fr 1fr;gap:12px}
@media(max-width:400px){.fr{grid-template-columns:1fr}}
</style>

<!-- Google Sign-In SDK -->
<script src="https://accounts.google.com/gsi/client" async defer></script>

<div class="auth-wrap">
<div class="auth-card">
  <div class="auth-head">
    <div class="city-tag"><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars(CITY_NAME) ?></div>
    <h2 id="authTitle">Welcome to BizGuide</h2>
    <p id="authSub">Login or create your account</p>
  </div>
  <div class="auth-body">

    <!-- Step 1: Visitor or Owner -->
    <div id="sType">
      <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:14px;text-align:center">I am a...</p>
      <div class="type-cards">
        <div class="type-card" id="tcVisitor" onclick="chooseType('visitor')">
          <span class="tc-em">🔍</span>
          <h4>Visitor</h4>
          <p>Looking for services, want to rate & review</p>
        </div>
        <div class="type-card" id="tcOwner" onclick="chooseType('owner')">
          <span class="tc-em">🏢</span>
          <h4>Business Owner</h4>
          <p>Want to post my business listing</p>
        </div>
      </div>
    </div>

    <!-- Step 2a: Visitor — Google only -->
    <div id="sVisitor" style="display:none">
      <button class="back-link" onclick="showStep('sType')"><i class="bi bi-arrow-left"></i> Back</button>
      <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:16px;text-align:center">
        Sign in quickly with Google to rate &amp; review businesses.
      </p>
      <div id="googleBtnVisitor"></div>
      <div class="err" id="eVisitor"></div>
    </div>

    <!-- Step 2b: Owner — Google or Email -->
    <div id="sOwner" style="display:none">
      <button class="back-link" onclick="showStep('sType')"><i class="bi bi-arrow-left"></i> Back</button>
      <div id="googleBtnOwner"></div>
      <div class="divider">or register with email</div>
      <!-- Owner signup form -->
      <div id="ownerForm">
        <div class="fg"><label>Full Name *</label><input type="text" class="fi" id="oName" placeholder="Your name" autocomplete="name"></div>
        <div class="fr">
          <div class="fg"><label>Email *</label><input type="email" class="fi" id="oEmail" placeholder="you@email.com" autocomplete="email"></div>
          <div class="fg"><label>Phone *</label><input type="tel" class="fi" id="oPhone" placeholder="98765 43210" maxlength="10" inputmode="numeric"></div>
        </div>
        <div class="fr">
          <div class="fg"><label>Profession</label><input type="text" class="fi" id="oProf" placeholder="e.g. Hotel Owner"></div>
          <div class="fg">
            <label>City *</label>
            <?php $city = Database::fetchOne("SELECT id,name FROM cities WHERE id=? AND status='active'", [CITY_ID]); ?>
            <input type="hidden" id="oCity" value="<?= $city['id'] ?>">
            <div class="fi" style="padding:12px 13px; border:1.5px solid var(--border); border-radius:10px; background:#f8f5ff;">
              <?= htmlspecialchars($city['name']) ?>
            </div>
          </div>
        </div>
        <div class="fg"><label>Password *</label><input type="password" class="fi" id="oPass" placeholder="Min 6 characters" autocomplete="new-password"></div>
        <div class="err" id="eOwner"></div>
        <button class="btn-main" id="btnOwner" onclick="registerOwner()"><span>Create Account</span></button>
      </div>
      <div class="link-toggle">Already have an account? <a onclick="showStep('sLogin')">Login</a></div>
    </div>

    <!-- Step 3: Login (existing users) -->
    <div id="sLogin" style="display:none">
      <button class="back-link" onclick="showStep('sType')"><i class="bi bi-arrow-left"></i> Back</button>
      <div id="googleBtnLogin"></div>
      <div class="divider">or login with email</div>
      <div class="fg"><label>Email or Phone</label><input type="text" class="fi" id="lEmail" placeholder="Email or phone number" autocomplete="username"></div>
      <div class="fg"><label>Password</label><input type="password" class="fi" id="lPass" placeholder="Your password" autocomplete="current-password"></div>
      <div class="err" id="eLogin"></div>
      <button class="btn-main" id="btnLogin" onclick="doLogin()"><span>Login</span></button>
      <div class="link-toggle">New here? <a onclick="showStep('sOwner')">Create account</a></div>
    </div>

    <!-- Step 4: Complete profile after Google (owner) -->
    <div id="sComplete" style="display:none">
      <h3 style="font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;margin-bottom:4px">Almost done!</h3>
      <p style="font-size:0.82rem;color:var(--text-muted);margin-bottom:16px">Just a few more details to set up your business account.</p>
      <div class="fg"><label>Phone *</label><input type="tel" class="fi" id="cpPhone" placeholder="98765 43210" maxlength="10" inputmode="numeric"></div>
      <div class="fr">
        <div class="fg"><label>Profession</label><input type="text" class="fi" id="cpProf" placeholder="e.g. Hotel Owner"></div>
        <div class="fg">
          <label>City</label>
          <?php $city = Database::fetchOne("SELECT id,name FROM cities WHERE id=? AND status='active'", [CITY_ID]); ?>
          <input type="hidden" id="cpCity" value="<?= $city['id'] ?>">
          <div class="fi" style="padding:12px 13px; border:1.5px solid var(--border); border-radius:10px; background:#f8f5ff;">
            <?= htmlspecialchars($city['name']) ?>
          </div>
        </div>
      </div>
      <div class="err" id="eComplete"></div>
      <button class="btn-main" id="btnComplete" onclick="completeProfile()"><span>Complete Registration</span></button>
    </div>

  </div>
</div>
</div>
</main>

<script>
var CITY    = "<?= htmlspecialchars(CITY_URL, ENT_QUOTES) ?>";
var CSRF    = "<?= htmlspecialchars($csrf, ENT_QUOTES) ?>";
var G_ID    = "<?= htmlspecialchars($googleClientId, ENT_QUOTES) ?>";
var RETURN  = "<?= htmlspecialchars($returnTo, ENT_QUOTES) ?>";
var userType = "";

function chooseType(type) {
  userType = type;
  document.querySelectorAll(".type-card").forEach(function(c){c.classList.remove("sel");});
  document.getElementById(type==="visitor"?"tcVisitor":"tcOwner").classList.add("sel");
  if (type==="visitor") {
    document.getElementById("authTitle").textContent = "Welcome, Visitor";
    document.getElementById("authSub").textContent = "Sign in to rate & review businesses";
    showStep("sVisitor");
  } else {
    document.getElementById("authTitle").textContent = "Create Business Account";
    document.getElementById("authSub").textContent = "List your business on BizGuide";
    showStep("sOwner");
  }
}

// Init Google Sign-In buttons
function initGoogle() {
  if (!G_ID || !window.google) return;
  var cfg = {client_id: G_ID, callback: handleGoogle, auto_select: false};

  ["googleBtnVisitor","googleBtnOwner","googleBtnLogin"].forEach(function(id) {
    var el = document.getElementById(id);
    if (el) {
      google.accounts.id.initialize(Object.assign({}, cfg));
      google.accounts.id.renderButton(el, {
        theme:"outline", size:"large", width: el.offsetWidth || 380,
        text: id==="googleBtnLogin" ? "signin_with" : "signup_with"
      });
    }
  });
}

function handleGoogle(response) {
  setBtnLoad("btnOwner", true);
  api("auth/google", {
    credential: response.credential,
    user_type: userType || "visitor",
    return_to: RETURN
  }).then(function(r) {
    if (r.action === "complete_profile") {
      document.getElementById("authTitle").textContent = "Complete Your Profile";
      showStep("sComplete");
    } else {
      window.location.href = r.redirect;
    }
  }).catch(function(e) {
    showErr(userType==="visitor"?"eVisitor":"eOwner", e.message);
    setBtnLoad("btnOwner", false);
  });
}

function registerOwner() {
  var n=v("oName"), e=v("oEmail"), p=v("oPhone"), pw=v("oPass"), pr=v("oProf"), c=v("oCity");
  if(!n||!e||!p||!pw){showErr("eOwner","All required fields must be filled.");return;}
  if(pw.length<6){showErr("eOwner","Password must be at least 6 characters.");return;}
  setBtnLoad("btnOwner",true);
  api("auth/register",{name:n,email:e,phone:p,password:pw,profession:pr,city_id:c}).then(function(r){
    window.location.href=r.redirect;
  }).catch(function(e){showErr("eOwner",e.message);setBtnLoad("btnOwner",false);});
}

function doLogin() {
  var e=v("lEmail"), p=v("lPass");
  if(!e||!p){showErr("eLogin","Enter email/phone and password.");return;}
  setBtnLoad("btnLogin",true);
  api("auth/login",{email:e,password:p}).then(function(r){
    window.location.href=r.redirect;
  }).catch(function(e){showErr("eLogin",e.message);setBtnLoad("btnLogin",false);});
}

function completeProfile() {
  var ph=v("cpPhone"), pr=v("cpProf"), c=v("cpCity");
  if(!ph){showErr("eComplete","Phone number required.");return;}
  setBtnLoad("btnComplete",true);
  api("auth/complete-profile",{phone:ph,profession:pr,city_id:c}).then(function(r){
    window.location.href=r.redirect;
  }).catch(function(e){showErr("eComplete",e.message);setBtnLoad("btnComplete",false);});
}

function showStep(id) {
  ["sType","sVisitor","sOwner","sLogin","sComplete"].forEach(function(s){
    var el=document.getElementById(s); if(el) el.style.display=s===id?"":"none";
  });
}
function v(id){var el=document.getElementById(id);return el?el.value.trim():"";}
function showErr(id,msg){var e=document.getElementById(id);if(!e)return;e.textContent=msg;e.style.display="block";setTimeout(function(){e.style.display="none";},4500);}
function setBtnLoad(id,on){var b=document.getElementById(id);if(!b)return;b.disabled=on;b.innerHTML=on?'<span class="spin"></span>':'<span>'+b.textContent+'</span>';}
function api(path,data){
  data.csrf_token=CSRF;
  return fetch(CITY+"/"+path,{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded"},body:new URLSearchParams(data)})
    .then(function(r){return r.text().then(function(txt){
      try{var j=JSON.parse(txt);if(!r.ok)throw new Error(j.error||"Error");return j;}
      catch(e){if(e.message==="Error")throw e;console.error("Non-JSON:",txt);throw new Error("Server error. Check console.");}
    });});
}

document.getElementById("lPass") && document.getElementById("lPass").addEventListener("keydown",function(e){if(e.key==="Enter")doLogin();});
window.addEventListener("load", function(){ if(window.google) initGoogle(); });
document.querySelector("script[src*='gsi']") && document.querySelector("script[src*='gsi']").addEventListener("load", initGoogle);
</script>

<?php require CITY_DIR . '/views/layout/footer.php'; ?>
