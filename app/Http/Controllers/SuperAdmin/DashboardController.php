<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $companiesCount = Company::count();
        $activeCompaniesCount = Company::where('status', 'active')->count();
        $totalUsers = User::where('is_super_admin', false)->count();

        // Calculate growth percentage based on last month
        $lastMonthCompaniesCount = Company::where('created_at', '<', Carbon::now()->startOfMonth())->count();
        $companiesGrowth = 0;
        if ($lastMonthCompaniesCount > 0) {
            $companiesGrowth = (($companiesCount - $lastMonthCompaniesCount) / $lastMonthCompaniesCount) * 100;
        } elseif ($companiesCount > 0) {
            $companiesGrowth = 100;
        }

        // Get recent users for the avatars widget
        $recentUsers = User::where('is_super_admin', false)
            ->latest()
            ->take(3)
            ->get();

        // Get companies expiring soon
        $expiringCompanies = Company::where('status', 'active')
            ->whereNotNull('expiry_date')
            ->orderBy('expiry_date', 'asc')
            ->take(4)
            ->get();

        return view('superadmin.dashboard.index', compact('companiesCount', 'activeCompaniesCount', 'totalUsers', 'companiesGrowth', 'recentUsers', 'expiringCompanies'));
    }
}
