<?php
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/shared/models/ReportModel.php';

class ReportController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $model     = new ReportModel();
        $chart     = $model->getRegistrationChart(30);
        $cities    = $model->getCityReport();
        $planStats = $model->getPlanReport();
        $this->view('reports.index', compact('chart', 'cities', 'planStats'));
    }
}
