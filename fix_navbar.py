import os
import re

files = [
    '/opt/lampp/htdocs/biz/cities/_template/views/layout/header.php',
    '/opt/lampp/htdocs/biz/cities/chennai/views/layout/header.php',
    '/opt/lampp/htdocs/biz/cities/dindugal/views/layout/header.php',
    '/opt/lampp/htdocs/biz/cities/kodaikanal/views/layout/header.php'
]

css_block = """    .mobile-bottom-bar {
      display: none;
    }

    .bottom-sheet-modal .modal-dialog {
      display: flex;
      align-items: flex-end;
      min-height: 100%;
      margin: 0;
      padding: 0;
    }

    .bottom-sheet-modal .modal-content {
      width: 100%;
      border: none;
      border-radius: 24px 24px 0 0;
      background: #fff;
      padding-bottom: env(safe-area-inset-bottom);
      box-shadow: 0 -10px 40px rgba(0,0,0,0.15);
    }

    .bottom-sheet-modal.fade .modal-dialog {
      transform: translateY(100%);
      transition: transform 0.35s cubic-bezier(0.1, 0.9, 0.2, 1);
    }

    .bottom-sheet-modal.show .modal-dialog {
      transform: translateY(0);
    }

    .city-card-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
      padding: 0 20px 20px 20px;
    }

    .city-card {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      background: #f9f9f9;
      border-radius: 16px;
      border: 2px solid transparent;
      text-decoration: none;
      color: var(--text-dark);
      transition: all 0.2s cubic-bezier(0.34, 1.2, 0.64, 1);
      -webkit-tap-highlight-color: transparent;
    }

    .city-card:active {
      transform: scale(0.96);
      background: #f0f0f0;
    }

    .city-card.active {
      border-color: var(--primary);
      background: var(--purple-light);
    }

    .city-card-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      color: var(--primary);
      margin-right: 16px;
    }

    .city-card-info {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .city-card-info .city-name {
      font-weight: 700;
      font-size: 1rem;
    }

    .city-card-info .city-desc {
      font-size: 0.75rem;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .bs-drag-handle {
      width: 40px;
      height: 5px;
      background: #e0e0e0;
      border-radius: 10px;
      margin: 12px auto;
    }
    
    @media(max-width:768px) {
      .mobile-bottom-bar {
        display: flex;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 950;
        height: 72px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border-top: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 24px 24px 0 0;
        box-shadow: 0 -4px 30px rgba(0, 0, 0, 0.05);
        align-items: center;
        justify-content: space-around;
        padding: 0 10px;
        padding-bottom: env(safe-area-inset-bottom);
      }
      
      .mb-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        flex: 1;
        height: 100%;
        color: var(--text-muted);
        text-decoration: none;
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.2s ease;
        -webkit-tap-highlight-color: transparent;
        background: none;
        border: none;
        padding: 0;
      }

      .mb-btn span {
        font-size: 0.65rem;
        font-weight: 500;
        letter-spacing: 0.02em;
        transition: color 0.2s ease;
      }

      .mb-btn i {
        font-size: 1.3rem;
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.3s ease;
      }

      .mb-btn:active {
        transform: scale(0.9);
      }

      .mb-btn.active {
        color: var(--primary);
      }

      .mb-btn.active i {
        transform: translateY(-2px);
        filter: drop-shadow(0 4px 8px rgba(124, 58, 237, 0.4));
      }
      
      .mb-btn.active span {
        font-weight: 700;
      }
    }
"""

html_block = """  <nav class="mobile-bottom-bar">
    <a href="<?= $cityUrl ?>" class="mb-btn <?= ($activePage ?? '') === 'home' ? 'active' : '' ?>">
      <i class="bi bi-house-fill"></i><span>Home</span>
    </a>
    <a href="<?= $cityUrl ?>/search" class="mb-btn <?= ($activePage ?? '') === 'search' ? 'active' : '' ?>">
      <i class="bi bi-search"></i><span>Search</span>
    </a>
    <?php if ($isUser): ?>
      <a href="<?= $cityUrl ?>/dashboard" class="mb-btn <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">
        <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
      </a>
    <?php else: ?>
      <a href="<?= $cityUrl ?>/login" class="mb-btn <?= ($activePage ?? '') === 'post-ad' ? 'active' : '' ?>">
        <i class="bi bi-plus-lg"></i><span>Post Ad</span>
      </a>
    <?php endif ?>
    <a href="#" class="mb-btn" data-bs-toggle="modal" data-bs-target="#citySelectModal">
      <i class="bi bi-map-fill"></i><span>Map</span>
    </a>
  </nav>

  <!-- City Selection Bottom Sheet -->
  <div class="modal fade bottom-sheet-modal" id="citySelectModal" tabindex="-1" aria-labelledby="citySelectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="bs-drag-handle"></div>
        <div class="modal-header" style="border:none; padding:10px 20px;">
          <h5 class="modal-title" id="citySelectModalLabel" style="font-family:'Syne',sans-serif;font-weight:700;font-size:1.25rem;">
            Select City
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="padding:0;">
          <div class="city-card-list">
            <a href="/biz/cities/kodaikanal" class="city-card <?= strpos($cityUrl, 'kodaikanal') !== False ? 'active' : '' ?>">
              <div class="city-card-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <div class="city-card-info">
                <span class="city-name">Kodaikanal</span>
                <span class="city-desc"><i class="bi bi-pin-map"></i> Tamil Nadu</span>
              </div>
            </a>
            <a href="/biz/cities/dindugal" class="city-card <?= strpos($cityUrl, 'dindugal') !== False ? 'active' : '' ?>">
              <div class="city-card-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <div class="city-card-info">
                <span class="city-name">Dindigul</span>
                <span class="city-desc"><i class="bi bi-pin-map"></i> Tamil Nadu</span>
              </div>
            </a>
            <a href="/biz/cities/bengaluru" class="city-card <?= strpos($cityUrl, 'bengaluru') !== False ? 'active' : '' ?>">
              <div class="city-card-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <div class="city-card-info">
                <span class="city-name">Bengaluru</span>
                <span class="city-desc"><i class="bi bi-pin-map"></i> Karnataka</span>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>"""

for f in files:
    with open(f, 'r') as file:
        content = file.read()
    
    # Replace CSS: from .mobile-bottom-bar to before .desktop-only
    pattern = r'(\s*\.mobile-bottom-bar\s*\{.*?\}(?:\s*\.mb-btn.*?\{.*?\})*.*?\n)(?=\s*\.desktop-only\s*\{)'
    content = re.sub(pattern, "\n" + css_block + "\n", content, flags=re.DOTALL)
    
    # Just in case, try simpler pattern if first fails
    if ".mobile-bottom-bar" in content and "city-card-list" not in content:
        # User formatted:
        p2 = re.compile(r'\s*\.mobile-bottom-bar\s*\{.*?(?=\s*\.desktop-only\s*\{)', re.DOTALL)
        content = p2.sub("\n" + css_block + "\n", content)
        
    # Replace HTML: form <nav class="mobile-bottom-bar"> to </nav>
    html_pattern = re.compile(r'\s*<nav class="mobile-bottom-bar">.*?</nav>', re.DOTALL)
    content = html_pattern.sub("\n" + html_block, content)
    
    with open(f, 'w') as file:
        file.write(content)
    
    print(f"Updated {f}")
