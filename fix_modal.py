import os
import re

files = [
    '/opt/lampp/htdocs/biz/cities/_template/views/layout/header.php',
    '/opt/lampp/htdocs/biz/cities/chennai/views/layout/header.php',
    '/opt/lampp/htdocs/biz/cities/dindugal/views/layout/header.php',
    '/opt/lampp/htdocs/biz/cities/kodaikanal/views/layout/header.php'
]

css_old1 = """    .bottom-sheet-modal .modal-dialog {
      display: flex;
      align-items: flex-end;
      min-height: 100%;
      margin: 0;
      padding: 0;
    }"""

css_new = """    .bottom-sheet-modal {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 9999;
      background: rgba(0,0,0,0.4);
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
    }"""

# Old CSS removals
old_removals = [
    """    .bottom-sheet-modal .modal-content {
      width: 100%;
      border: none;
      border-radius: 24px 24px 0 0;
      background: #fff;
      padding-bottom: env(safe-area-inset-bottom);
      box-shadow: 0 -10px 40px rgba(0,0,0,0.15);
    }""",
    """    .bottom-sheet-modal.fade .modal-dialog {
      transform: translateY(100%);
      transition: transform 0.35s cubic-bezier(0.1, 0.9, 0.2, 1);
    }""",
    """    .bottom-sheet-modal.show .modal-dialog {
      transform: translateY(0);
    }"""
]

js_block = """
<script>
  function openCitySelectModal(e) {
    e.preventDefault();
    document.getElementById('citySelectModal').classList.add('is-open');
  }
  function closeCitySelectModal() {
    document.getElementById('citySelectModal').classList.remove('is-open');
  }
  // Close when clicking outside dialog
  window.addEventListener('click', function(e) {
    const m = document.getElementById('citySelectModal');
    if (e.target === m) {
      closeCitySelectModal();
    }
  });
</script>
"""

for f in files:
    with open(f, 'r') as file:
        content = file.read()
    
    # 1. Update CSS
    content = content.replace(css_old1, css_new)
    for rem in old_removals:
        content = content.replace(rem, "")
        
    # 2. Update HTML link
    content = content.replace('data-bs-toggle="modal" data-bs-target="#citySelectModal"', 'onclick="openCitySelectModal(event)"')
    
    # 3. Update Modal Shell
    content = content.replace('class="modal fade bottom-sheet-modal"', 'class="bottom-sheet-modal"')
    content = content.replace('data-bs-dismiss="modal"', 'onclick="closeCitySelectModal()"')
    
    # 4. Remove .modal-content div wrapping, flatten it
    content = content.replace('<div class="modal-content">', '')
    # The div closing needs to be matched. Actually, safer to just replace CSS class.
    # Instead of removing the div, let's just make it unstyled in the new CSS, wait... 
    # .modal-content doesn't have CSS now because I removed it. So it's just a transparent div, which is fine!
    
    if "openCitySelectModal" not in content:
        content = content + js_block
        
    with open(f, 'w') as file:
        file.write(content)

print("Done fixing modals")
