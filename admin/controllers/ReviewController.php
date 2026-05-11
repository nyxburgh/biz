<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/shared/models/ReviewModel.php';

class ReviewController extends Controller
{
    private ReviewModel $model;

    public function __construct()
    {
        $this->model = new ReviewModel();
    }

    public function index(): void
    {
        $this->requireAuth();
        $filters = [
            'search'  => $this->sanitize($this->input('search', '')),
            'status'  => $this->input('status', ''),
            'rating'  => $this->input('rating', ''),
            'listing' => $this->input('listing', ''),
        ];
        $page  = max(1, (int) $this->input('page', 1));
        if (Auth::isCityAdmin()) $filters['_city_scope'] = Auth::cityId();
        $pager = $this->model->getAllWithRelations($filters, $page);
        $stats = $this->model->getStats();
        $csrf  = $this->csrfToken();
        $this->view('reviews.index', compact('pager', 'filters', 'stats', 'csrf'));
    }

    public function approve(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id = (int) $this->input('id');
        $this->model->update($id, [
            'status'       => 'approved',
            'approved_by'  => Auth::id(),
            'approved_at'  => date('Y-m-d H:i:s'),
        ]);
        Helper::flash('success', 'Review approved and published.');
        $this->redirect(BASE_URL . '/admin/reviews');
    }

    public function reject(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $id   = (int) $this->input('id');
        $note = $this->sanitize($this->input('rejection_note', ''));
        $this->model->update($id, [
            'status'         => 'rejected',
            'rejection_note' => $note,
        ]);
        Helper::flash('info', 'Review rejected.');
        $this->redirect(BASE_URL . '/admin/reviews');
    }

    public function delete(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->model->delete((int) $this->input('id'));
        Helper::flash('success', 'Review deleted.');
        $this->redirect(BASE_URL . '/admin/reviews');
    }
}
