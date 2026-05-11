<?php
// ADD this method to CategoryController — updateSubcategory
// Route: POST /admin/categories/subcategories/update

    public function updateSubcategory(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id    = (int) $this->input('id');
        $catId = (int) $this->input('category_id');
        $name  = $this->sanitize($this->input('name', ''));
        Database::execute(
            "UPDATE subcategories SET name=?, slug=?, sort_order=?, status=? WHERE id=?",
            [$name, Helper::slug($name), (int)$this->input('sort_order', 0), $this->input('status', 'active'), $id]
        );
        Helper::flash('success', 'Subcategory updated.');
        $this->redirect(BASE_URL . '/admin/categories/' . $catId . '/subcategories');
    }
