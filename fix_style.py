import re

files = [
    '/opt/lampp/htdocs/biz/cities/_template/views/layout/header.php',
    '/opt/lampp/htdocs/biz/cities/chennai/views/layout/header.php',
    '/opt/lampp/htdocs/biz/cities/dindugal/views/layout/header.php',
    '/opt/lampp/htdocs/biz/cities/kodaikanal/views/layout/header.php'
]

full_style = """  <style>
    :root {
      --primary: <?= htmlspecialchars($cityColor) ?>;
      --sand: #f5ede0;
      --sand-light: #faf6f0;
      --sand-dark: #e8d9c4;
      --purple: #7c3aed;
      --purple-light: #ede9fe;
      --purple-muted: #a78bfa;
      --green: #3a7c5a;
      --green-light: #e6f4ec;
      --teal: #2a7d8c;
      --teal-light: #e0f5f8;
      --amber: #b45309;
      --amber-light: #fef3c7;
      --maroon: #9b2335;
      --maroon-light: #fde8eb;
      --text-dark: #1a1018;
      --text-mid: #4a3f52;
      --text-muted: #8b7d96;
      --border: #e2d5f0;
      --header-h: 64px;
      --footer-h: 64px;
      --radius: 14px;
      --radius-sm: 8px;
      --shadow: 0 4px 20px rgba(124, 58, 237, 0.08);
      --shadow-hover: 0 12px 40px rgba(124, 58, 237, 0.18);
      --transition: all 0.25s cubic-bezier(0.34, 1.2, 0.64, 1);
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    html {
      scroll-behavior: smooth;
      overflow-x: hidden;
      -webkit-text-size-adjust: 100%
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--sand-light);
      color: var(--text-dark);
      font-size: 15px;
      line-height: 1.6;
      padding-top: var(--header-h);
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
      min-height: 100vh
    }

    main {
      flex: 1
    }

    a {
      text-decoration: none;
      color: inherit
    }

    img {
      max-width: 100%;
      height: auto
    }

    .site-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 900;
      height: var(--header-h);
      background: rgba(250, 246, 240, 0.95);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px
    }

    .header-logo {
      display: flex;
      align-items: center;
      gap: 8px;
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 1.2rem;
      color: var(--primary)
    }

    .city-tag {
      font-size: 0.68rem;
      font-weight: 600;
      color: var(--green);
      background: var(--green-light);
      padding: 2px 8px;
      border-radius: 40px
    }

    .header-nav {
      display: flex;
      align-items: center;
      gap: 4px
    }

    .header-nav a {
      padding: 7px 13px;
      border-radius: 40px;
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--text-mid);
      transition: var(--transition)
    }

    .header-nav a:hover {
      background: var(--purple-light);
      color: var(--primary)
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 8px
    }

    .btn-login {
      padding: 7px 16px;
      border-radius: 40px;
      border: 1.5px solid var(--primary);
      color: var(--primary);
      background: transparent;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: 500;
      font-family: inherit;
      transition: var(--transition)
    }

    .btn-login:hover {
      background: var(--primary);
      color: #fff
    }

    .btn-post {
      padding: 8px 16px;
      border-radius: 40px;
      background: var(--primary);
      color: #fff;
      border: none;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: 600;
      font-family: inherit;
      display: flex;
      align-items: center;
      gap: 5px;
      transition: var(--transition)
    }

    .btn-post:hover {
      background: #6d28d9
    }

    .user-av {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--primary);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.88rem;
      text-decoration: none
    }

    .mobile-back-btn {
      display: none;
      position: fixed;
      top: calc(12px + env(safe-area-inset-top));
      left: 12px;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #fff;
      border: 1px solid rgba(124, 58, 237, 0.18);
      box-shadow: 0 12px 26px rgba(124, 58, 237, 0.18);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
      overflow: hidden;
      touch-action: manipulation
    }

    .mobile-back-btn svg {
      width: 18px;
      height: 18px;
      stroke: var(--primary);
      stroke-width: 2;
      fill: none
    }

    .mobile-back-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 16px 32px rgba(124, 58, 237, 0.22);
      background: rgba(124, 58, 237, 0.04)
    }

    .mobile-back-btn:active {
      transform: scale(0.96)
    }

    .mobile-home-btn {
      display: none;
      position: fixed;
      top: calc(12px + env(safe-area-inset-top));
      right: 12px;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #fff;
      border: 1px solid rgba(124, 58, 237, 0.18);
      box-shadow: 0 12px 26px rgba(124, 58, 237, 0.18);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
      touch-action: manipulation;
      text-decoration: none;
      color: var(--primary);
      font-size: 1.1rem
    }

    .mobile-home-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 16px 32px rgba(124, 58, 237, 0.22);
      background: rgba(124, 58, 237, 0.04)
    }

    .mobile-home-btn:active {
      transform: scale(0.96)
    }

    .desktop-only {
      display: flex
    }

    .mobile-center-logo {
      display: none;
      align-items: center;
      justify-content: center;
      width: 100%;
      gap: 8px
    }

    .flash-area {
      position: fixed;
      top: calc(var(--header-h)+8px);
      right: 12px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 8px;
      max-width: 300px
    }

    .flash {
      padding: 11px 15px;
      border-radius: 10px;
      font-size: 0.85rem;
      font-weight: 500;
      box-shadow: 0 4px 20px rgba(0, 0, 0, .12);
      display: flex;
      align-items: center;
      gap: 8px;
      animation: fIn .3s ease
    }

    @keyframes fIn {
      from {
        transform: translateX(100%);
        opacity: 0
      }
      to {
        transform: translateX(0);
        opacity: 1
      }
    }

    .flash-s {
      background: #d1fae5;
      color: #065f46;
      border-left: 4px solid #10b981
    }

    .flash-e {
      background: #fee2e2;
      color: #991b1b;
      border-left: 4px solid #ef4444
    }

    .flash-i {
      background: #e0f2fe;
      color: #0c4a6e;
      border-left: 4px solid #0ea5e9
    }

    .mobile-bottom-bar {
      display: none;
    }

    .bottom-sheet-modal {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 99999;
      background: rgba(0,0,0,0.5);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }
    
    .bottom-sheet-modal.is-open {
      opacity: 1;
      pointer-events: auto;
    }
    
    .bottom-sheet-modal .modal-dialog {
      width: 100%;
      margin: 0;
      background: #fff;
      border-radius: 24px 24px 0 0;
      padding-bottom: env(safe-area-inset-bottom);
      box-shadow: 0 -10px 40px rgba(0,0,0,0.15);
      transform: translateY(100%);
      transition: transform 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    
    .bottom-sheet-modal.is-open .modal-dialog {
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
      body {
        padding-bottom: calc(var(--footer-h) + 24px + env(safe-area-inset-bottom));
      }
      
      .mobile-bottom-bar {
        display: flex;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 950;
        height: 72px; /* Thumb friendly */
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border-top: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 20px 20px 0 0;
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

      .mobile-back-btn {
        display: flex;
      }

      .mobile-home-btn {
        display: flex;
      }

      .header-nav,
      .header-actions,
      .desktop-only {
        display: none !important;
      }

      .mobile-center-logo {
        display: flex;
      }

      .site-header {
        justify-content: center;
      }

      .flash-area {
        right: 8px;
        left: 8px;
        max-width: 100%;
      }
      
      .site-footer-main {
        display: none !important;
      }
    }
  </style>"""

for f in files:
    with open(f, 'r') as file:
        content = file.read()
    
    # Replace the entire style block
    pattern = re.compile(r'\s*<style>.*?</style>', re.DOTALL)
    content = pattern.sub('\n' + full_style, content)
    
    with open(f, 'w') as file:
        file.write(content)

print("Done completely fixing style blocks.")
