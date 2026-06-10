<?php

namespace App\Http\Controllers\Dashboard;

use App\Services\Dashboard\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index()
    {
        return $this->dashboardService->indexData();
    }

    public function kpis()
    {
        return $this->dashboardService->kpisData();
    }
}


