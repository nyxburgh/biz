<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/shared/models/KeywordModel.php';

class KeywordController extends Controller
{
    private KeywordModel $model;

    public function __construct()
    {
        $this->model = new KeywordModel();
    }

    public function index(): void
    {
        $this->requireAuth();
        $filters = [
            'search'   => $this->sanitize($this->input('search', '')),
            'category' => $this->input('category', ''),
            'status'   => $this->input('status', ''),
        ];
        $page  = max(1, (int) $this->input('page', 1));
        $pager = $this->model->getAllWithRelations($filters, $page);
        $cats  = Database::fetchAll("SELECT id, name FROM categories WHERE status='active' ORDER BY name");
        $csrf  = $this->csrfToken();
        $this->view('keywords.index', compact('pager', 'filters', 'cats', 'csrf'));
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $name = $this->sanitize($this->input('name', ''));
        if ($name) {
            $this->model->create([
                'name'           => $name,
                'slug'           => Helper::slug($name),
                'category_id'    => (int) $this->input('category_id') ?: null,
                'subcategory_id' => (int) $this->input('subcategory_id') ?: null,
                'status'         => $this->input('status', 'active'),
            ]);
            Helper::flash('success', 'Keyword added.');
        }
        $this->redirect(BASE_URL . '/admin/keywords');
    }

    public function delete(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->model->delete((int) $this->input('id'));
        Helper::flash('success', 'Keyword deleted.');
        $this->redirect(BASE_URL . '/admin/keywords');
    }

    public function suggestions(): void
    {
        $this->requireAuth();
        $filters = [
            'search' => $this->sanitize($this->input('search', '')),
            'status' => $this->input('status', ''),
        ];
        $page  = max(1, (int) $this->input('page', 1));
        $pager = $this->model->getSuggestions($filters, $page);
        $csrf  = $this->csrfToken();
        $this->view('keywords.suggestions', compact('pager', 'filters', 'csrf'));
    }

    public function approveSuggestion(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id  = (int) $this->input('id');
        $sug = Database::fetchOne("SELECT * FROM keyword_suggestions WHERE id = ?", [$id]);
        if (!$sug) {
            $this->redirect(BASE_URL . '/admin/keywords/suggestions');
        }
        $name = $this->sanitize($this->input('keyword', $sug['keyword']));
        $this->model->create([
            'name'           => $name,
            'slug'           => Helper::slug($name),
            'category_id'    => $sug['category_id'],
            'subcategory_id' => $sug['subcategory_id'],
            'status'         => 'active',
        ]);
        Database::execute(
            "UPDATE keyword_suggestions SET status='converted', reviewed_by=?, reviewed_at=NOW() WHERE id=?",
            [Auth::id(), $id]
        );
        Helper::flash('success', 'Keyword suggestion approved and added.');
        $this->redirect(BASE_URL . '/admin/keywords/suggestions');
    }

    public function rejectSuggestion(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id = (int) $this->input('id');
        Database::execute(
            "UPDATE keyword_suggestions SET status='rejected', admin_note=?, reviewed_by=?, reviewed_at=NOW() WHERE id=?",
            [$this->sanitize($this->input('admin_note', '')), Auth::id(), $id]
        );
        Helper::flash('info', 'Suggestion rejected.');
        $this->redirect(BASE_URL . '/admin/keywords/suggestions');
    }
}
