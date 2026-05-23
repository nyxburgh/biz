import os

files = [
    '/opt/lampp/htdocs/biz/cities/_template/views/layout/header.php',
    '/opt/lampp/htdocs/biz/cities/chennai/views/layout/header.php',
    '/opt/lampp/htdocs/biz/cities/dindugal/views/layout/header.php',
    '/opt/lampp/htdocs/biz/cities/kodaikanal/views/layout/header.php'
]

js_block = """
<script>
  function openCitySelectModal(e) {
    if(e) e.preventDefault();
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
    
    if "function openCitySelectModal(e)" not in content:
        content = content + js_block
        with open(f, 'w') as file:
            file.write(content)
            print(f"Added script to {f}")
    else:
        print(f"Script already in {f}")

