<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/shared/models/CategoryModel.php';

class CategoryController extends Controller
{
    private CategoryModel $model;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    public function index(): void
    {
        $this->requireAuth();
        $filters = [
            'search' => $this->sanitize($this->input('search', '')),
            'status' => $this->input('status', ''),
        ];
        $page  = max(1, (int) $this->input('page', 1));
        $pager = $this->model->getAllWithCount($filters, $page);
        $csrf  = $this->csrfToken();
        $this->view('categories.index', compact('pager', 'filters', 'csrf'));
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $name = $this->sanitize($this->input('name', ''));
        if ($name) {
            $this->model->create([
                'name'        => $name,
                'slug'        => Helper::slug($name),
                'description' => $this->sanitize($this->input('description', '')),
                'sort_order'  => (int) $this->input('sort_order', 0),
                'status'      => $this->input('status', 'active'),
            ]);
            Helper::flash('success', 'Category created.');
        }
        $this->redirect(BASE_URL . '/admin/categories');
    }

    public function update(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id   = (int) $this->input('id');
        $name = $this->sanitize($this->input('name', ''));
        $this->model->update($id, [
            'name'        => $name,
            'slug'        => Helper::slug($name),
            'description' => $this->sanitize($this->input('description', '')),
            'sort_order'  => (int) $this->input('sort_order', 0),
            'status'      => $this->input('status', 'active'),
        ]);
        Helper::flash('success', 'Category updated.');
        $this->redirect(BASE_URL . '/admin/categories');
    }

    public function delete(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->model->delete((int) $this->input('id'));
        Helper::flash('success', 'Category deleted.');
        $this->redirect(BASE_URL . '/admin/categories');
    }

    public function subcategories(string $catId): void
    {
        $this->requireAuth();
        $cat = $this->model->find((int) $catId);
        if (!$cat) {
            $this->redirect(BASE_URL . '/admin/categories');
        }
        $filters = [
            'search' => $this->sanitize($this->input('search', '')),
            'status' => $this->input('status', ''),
        ];
        $page  = max(1, (int) $this->input('page', 1));
        $pager = $this->model->getSubcategories((int) $catId, $filters, $page);
        $csrf  = $this->csrfToken();
        $this->view('categories.subcategories', compact('cat', 'pager', 'filters', 'csrf'));
    }

    public function storeSubcategory(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $catId = (int) $this->input('category_id');
        $name  = $this->sanitize($this->input('name', ''));
        if ($name) {
            Database::execute(
                "INSERT INTO subcategories (category_id, name, slug, sort_order, status) VALUES (?,?,?,?,?)",
                [$catId, $name, Helper::slug($name), (int)$this->input('sort_order', 0), $this->input('status', 'active')]
            );
            Helper::flash('success', 'Subcategory created.');
        }
        $this->redirect(BASE_URL . '/admin/categories/' . $catId . '/subcategories');
    }

    public function deleteSubcategory(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $catId = (int) $this->input('category_id');
        Database::execute("DELETE FROM subcategories WHERE id = ?", [(int) $this->input('id')]);
        Helper::flash('success', 'Subcategory deleted.');
        $this->redirect(BASE_URL . '/admin/categories/' . $catId . '/subcategories');
    }
}
