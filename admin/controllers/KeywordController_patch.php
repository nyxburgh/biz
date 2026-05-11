<?php
// ADD this method to KeywordController — update
// Route: POST /admin/keywords/update

    public function update(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id   = (int) $this->input('id');
        $name = $this->sanitize($this->input('name', ''));
        Database::execute(
            "UPDATE keywords SET name=?, slug=?, category_id=?, status=? WHERE id=?",
            [$name, Helper::slug($name), (int)$this->input('category_id') ?: null, $this->input('status','active'), $id]
        );
        Helper::flash('success', 'Keyword updated.');
        $this->redirect(BASE_URL . '/admin/keywords');
    }
