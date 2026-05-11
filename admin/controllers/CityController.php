<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/shared/models/CityModel.php';

class CityController extends Controller
{
    private CityModel $model;

    public function __construct()
    {
        $this->model = new CityModel();
    }

    public function index(): void
    {
        $this->requireAuth();
        $filters = [
            'search' => $this->sanitize($this->input('search', '')),
            'status' => $this->input('status', ''),
        ];
        $page  = max(1, (int) $this->input('page', 1));
        $pager = $this->model->getAllWithStats($filters, $page);
        $csrf  = $this->csrfToken();
        $this->view('cities.index', compact('pager', 'filters', 'csrf'));
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $name = $this->sanitize($this->input('name', ''));
        if (!$name) {
            Helper::flash('error', 'City name is required.');
            $this->redirect(BASE_URL . '/admin/cities');
        }
        $slug = Helper::slug($name);
        $this->model->create([
            'name'        => $name,
            'slug'        => $slug,
            'domain'      => $this->sanitize($this->input('domain', '')),
            'folder_path' => '/cities/' . $slug,
            'description' => $this->sanitize($this->input('description', '')),
            'sort_order'  => (int) $this->input('sort_order', 0),
            'status'      => $this->input('status', 'active'),
        ]);
        $cloned = $this->model->cloneTemplate($slug);
        Helper::flash('success', $cloned
            ? "City '$name' created. Folder /cities/$slug cloned from template."
            : "City '$name' created. (Template clone failed — check /cities/_template exists.)"
        );
        $this->redirect(BASE_URL . '/admin/cities');
    }

    public function update(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id = (int) $this->input('id');
        $this->model->update($id, [
            'name'        => $this->sanitize($this->input('name', '')),
            'domain'      => $this->sanitize($this->input('domain', '')),
            'description' => $this->sanitize($this->input('description', '')),
            'sort_order'  => (int) $this->input('sort_order', 0),
            'status'      => $this->input('status', 'active'),
        ]);
        Helper::flash('success', 'City updated.');
        $this->redirect(BASE_URL . '/admin/cities');
    }

    public function delete(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->model->delete((int) $this->input('id'));
        Helper::flash('success', 'City deleted.');
        $this->redirect(BASE_URL . '/admin/cities');
    }
}
