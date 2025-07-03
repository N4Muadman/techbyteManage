<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\FeedBackCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Customer_manageController extends Controller
{
    public function newCustomer(Request $request)
    {
        $user = Auth::user();
        $customerQuery = Customer::with('employee')->where('type', 0);

        if ($user->role_id == 5) {
            $customerQuery->where('employee_id', $user->employee_id);
        }

        if ($request->name) {
            $customerQuery->where('full_name', 'like', '%' . $request->name . '%');
        }
        if ($request->phone_number) {
            $customerQuery->where('phone_number', 'like', '%' . $request->phone_number . '%');
        }
        if ($request->employee) {
            $customerQuery->whereHas('employee', function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->employee . '%');
            });
        }

        $customers = $customerQuery->OrderByDESC('created_at')->paginate(10);

        return view('customer.new', compact('customers'));
    }

    public function businessCustomer(Request $request)
    {
        $user = Auth::user();
        $customerQuery = Customer::with('employee')->where('type', 1);

        if ($user->role_id == 5) {
            $customerQuery->where('employee_id', $user->employee->id);
        }

        if ($request->name) {
            $customerQuery->where('full_name', 'like', '%' . $request->name . '%');
        }
        if ($request->phone_number) {
            $customerQuery->where('phone_number', 'like', '%' . $request->phone_number . '%');
        }
        if ($request->employee) {
            $customerQuery->whereHas('employee', function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->name . '%');
            });
        }

        $customers = $customerQuery->OrderByDESC('created_at')->paginate(10);

        return view('customer.business', compact('customers'));
    }

    public function show($id)
    {
        $customer = Customer::with('employee', 'contract')->find($id);

        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'customer' => $customer
        ], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'full_name' => 'required|unique:customers,full_name',
            'phone_number' => 'required|unique:customers,phone_number',
            'email' => 'required|unique:customers,email',
            'gender' => 'required',
            'social_network' => 'required',
            'date_find_to_me' => 'required',
            'object' => 'required',
        ], [
            'full_name.unique' => 'Tên khách hàng đã có trong hệ thống',
            'phone_number.unique' => 'Số điện thoại khách hàng đã có trong hệ thống',
            'email.unique' => 'Email khách hàng đã có trong hệ thống',
        ]);

        Customer::create([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'gender' => $request->gender,
            'social_network' => $request->social_network,
            'date_find_to_me' => $request->date_find_to_me,
            'object' => $request->object,
            'employee_id' => Auth::user()->employee->id,
            'type' => 0
        ]);

        return redirect()->back()->with('success', 'Thêm mới khách hàng thành công');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|unique:customers,full_name,'.$id,
            'phone_number' => 'required|unique:customers,phone_number,'.$id,
            'email' => 'required|unique:customers,email,'.$id,
            'gender' => 'required',
            'social_network' => 'required',
            'date_find_to_me' => 'required',
            'object' => 'required',
        ]);

        $customer = Customer::find($id);

        if (!$customer) {
            return redirect()->back()->with('error', 'Khách hàng không tồn tại!');
        }

        $customer->update([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'gender' => $request->gender,
            'social_network' => $request->social_network,
            'date_find_to_me' => $request->date_find_to_me,
            'object' => $request->object,
        ]);

        return redirect()->back()->with('success', 'Sửa khách hàng thành công');
    }

    public function delete($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return redirect()->back()->with('error', 'Khách hàng không tồn tại!');
        }

        $customer->delete();

        return redirect()->back()->with('success', 'Xóa khách hàng thành công');
    }

    public function deal(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'contract_code' => 'required',
            'contract_value' => 'required',
            'advance_money' => 'required',
        ]);
        $customer = Customer::find($id);

        if (!$customer) {
            return redirect()->back()->with('error', 'Khách hàng không tồn tại!');
        }
        try {
            DB::beginTransaction();
            $contract_value = preg_replace('/\D/', '', $request->contract_value);
            $advance_money = preg_replace('/\D/', '', $request->advance_money);

            Contract::create([
                'customer_id' => $customer->id,
                'contract_code' => $request->contract_code,
                'contract_value' => $contract_value,
                'advance_money' => $advance_money,
                'date' => $request->date,
            ]);

            $customer->update([
                'type' => 1
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Chốt deal hợp đồng thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra!');
        }
    }


    public function getFeedback($id)
    {
        $feedbacks = FeedBackCustomer::with('customer')->where('customer_id', $id)->get();

        return response()->json([
            'feedbacks' => $feedbacks,
        ], 200);
    }

    public function statistics(Request $request)
    {
        $user = Auth::user();

        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date ?? now()->startOfMonth()->format('Y-m-d'));
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date ?? now()->endOfMonth()->format('Y-m-d'));

        $customerQuery = Customer::with('contract');

        if ($user->role_id == 5) {
            $customerQuery->where('employee_id', $user->employee->id);
        }

        $totalCustomers = (clone $customerQuery)->whereBetween('date_find_to_me', [$startDate, $endDate])->count();

        $closedDeals = (clone $customerQuery)->whereBetween('date_find_to_me', [$startDate, $endDate])->where('type', 1)->count();
        $notCloseds = $totalCustomers - $closedDeals;

        $conversionRate = $totalCustomers > 0 ? ($closedDeals / $totalCustomers) * 100 : 0;
        $notClosedRate = $totalCustomers > 0 ? ($notCloseds / $totalCustomers) * 100 : 0;

        $dealRate = [$totalCustomers, $closedDeals, $notCloseds, $conversionRate, $notClosedRate];

        $socialNetworkRate = $customerQuery->select(
            DB::raw('count(*) as total_customers'),
            DB::raw("SUM(CASE WHEN social_network = 'Tiktok' THEN 1 ELSE 0 END) as total_tiktok"),
            DB::raw("SUM(CASE WHEN social_network = 'Facebook' THEN 1 ELSE 0 END) as total_facebook"),
            DB::raw("SUM(CASE WHEN social_network = 'Youtube' THEN 1 ELSE 0 END) as total_youtube"),
            DB::raw("SUM(CASE WHEN social_network = 'Web' THEN 1 ELSE 0 END) as total_web"),
            DB::raw("SUM(CASE WHEN social_network = 'Được giới thiệu' THEN 1 ELSE 0 END) as total_referral")
        )->whereBetween('date_find_to_me', [$startDate, $endDate])
            ->get()
            ->map(function ($data) {
                $tiktokRate = $data->total_customers > 0 ? ($data->total_tiktok / $data->total_customers) * 100 : 0;
                $facebookRate = $data->total_customers > 0 ? ($data->total_facebook / $data->total_customers) * 100 : 0;
                $youtubeRate = $data->total_customers > 0 ? ($data->total_youtube / $data->total_customers) * 100 : 0;
                $webRate = $data->total_customers > 0 ? ($data->total_web / $data->total_customers) * 100 : 0;
                $referralRate = $data->total_customers > 0 ? ($data->total_referral / $data->total_customers) * 100 : 0;
                return [
                    'total_tiktok' => $data->total_tiktok,
                    'total_facebook' => $data->total_facebook,
                    'total_youtube' => $data->total_youtube,
                    'total_web' => $data->total_web,
                    'total_referral' => $data->total_referral,
                    'tiktok_rate' => $tiktokRate,
                    'facebook_rate' => $facebookRate,
                    'youtube_rate' => $youtubeRate,
                    'web_rate' => $webRate,
                    'referral_rate' => $referralRate,
                ];
            });

        return view('customer.statistics', compact('dealRate', 'socialNetworkRate'));
    }

    public function uploadFeedback(Request $request)
    {
        $request->validate([
            'feedbacks.*' => 'required|file|mimes:jpg,png,gif,jpeg,webp|max:2120',
            'customer_id' => 'required|integer'
        ]);

        if ($request->hasFile('feedbacks')) {
            foreach ($request->file('feedbacks') as $feedback) {
                $fileName = time() . '_' . $feedback->getClientOriginalName();
                $filePath = '/feedbacks/' . $fileName;
                $feedback->move(public_path('feedbacks'), $fileName);

                FeedBackCustomer::create([
                    'customer_id' => $request->customer_id,
                    'img' => $filePath,
                ]);
            }

            return redirect()->back()->with('success', 'Gửi phản hồi thành công');
        }

        return redirect()->back()->with('error', 'Gửi phản hồi không thành công');
    }

    public function debtContract(Request $request)
    {
        $contractQuery = Contract::with('customer.employee')->whereColumn('contract_value', '>', 'advance_money');

        if (Auth::user()->role_id == 5) {
            $contractQuery->where('customer_id', Auth::user()->employee_id);
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date ?? now()->startOfMonth()->format('Y-m-d'));
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date ?? now()->endOfMonth()->format('Y-m-d'));

        $contractQuery->whereBetween('date', [$startDate, $endDate]);

        $debts = $contractQuery->paginate(10);

        return view('customer.debt', compact('debts'));
    }

    public function getContract($id)
    {
        $contract = Contract::find($id);

        return response()->json([
            'contract' => $contract,
        ], 200);
    }

    public function updateContract(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'contract_code' => 'required',
        ]);
        $advance_money_request = $request->advance_money ? preg_replace('/\D/', '', $request->advance_money) : 0;
        $contract = Contract::find($id);

        $advance_money = $advance_money_request + $contract->advance_money;

        if ($advance_money > $contract->contract_value) {
            return redirect()->back()->with('error', 'Tổng số tiền tạm ứng đã lớn hơn giá trị hợp đồng');
        }

        $contract->update([
            'date' => $request->date,
            'contract_code' => $request->contract_code,
            'advance_money' => $advance_money,
            'note' => $request->note,
        ]);

        return redirect()->back()->with('success', 'Chỉnh sửa hợp đồng thành công');
    }
    public function businessStatistics(Request $request)
    {
        $contractQuery = Contract::query();

        if (Auth::user()->role_id == 5) {
            $contractQuery->where('customer_id', Auth::user()->employee_id);
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date ?? now()->startOfMonth()->format('Y-m-d'));
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date ?? now()->endOfMonth()->format('Y-m-d'));

        $previousStartDate = (clone $startDate)->subMonth();

        $previousStatistics = (clone $contractQuery)->select(
            DB::raw('SUM(contract_value) as total_contract_value'),
            DB::raw('SUM(advance_money) as advance_money'),
            DB::raw('SUM(contract_value - advance_money) as debt'),
        )->whereBetween('date', [$previousStartDate, $startDate])
            ->get();

        $contractQuery->whereBetween('date', [$startDate, $endDate]);

        $totalStatistics = $contractQuery->select(
            DB::raw('SUM(contract_value) as total_contract_value'),
            DB::raw('SUM(advance_money) as advance_money'),
            DB::raw('SUM(contract_value - advance_money) as debt'),
        )->get();

        $contractStatistics = $contractQuery->select(
            DB::raw('SUM(contract_value) as total_contract_value'),
            DB::raw('SUM(contract_value - advance_money) as debt'),
            DB::raw('date')
        )
            ->groupby(DB::raw('date'))
            ->get();

        return view('customer.businessStatistics', compact('contractStatistics', 'totalStatistics', 'previousStatistics'));
    }

    public function BusinessCustomerStatistics(Request $request)
    {
        $now = now();
        $type = $request->type ?? 'month';

        $response = [
            'statistics' => collect(),
            'current' => [
                'new_customers' => 0,
                'returning_customers' => 0,
                'total' => 0
            ],
            'previous' => [
                'new_customers' => 0,
                'returning_customers' => 0,
                'total' => 0
            ],
            'comparison' => [
                'new_customers' => [
                    'difference' => 0,
                    'percentage' => 0,
                    'status' => 'equal'
                ],
                'returning_customers' => [
                    'difference' => 0,
                    'percentage' => 0,
                    'status' => 'equal'
                ],
                'total' => [
                    'difference' => 0,
                    'percentage' => 0,
                    'status' => 'equal'
                ]
            ]
        ];

        switch ($type) {
            case 'day':
                $response = $this->getDayStatistics($now, $request);
                break;

            case 'month':
                $response = $this->getMonthStatistics($now, $request);
                break;

            case 'year':
                $response = $this->getYearStatistics($now);
                break;

            default:
                $response = $this->getMonthStatistics($now, $request);
                break;
        }

        return response()->json($response);
    }

    private function getDayStatistics($now, $request)
    {
        // Thống kê cho ngày hiện tại
        $currentStats = $this->getContractStatsByDate($now->format('Y-m-d'));

        // Thống kê cho ngày trước đó
        $previousStats = $this->getContractStatsByDate($now->copy()->subDay()->format('Y-m-d'));

        // Lấy dữ liệu cho cả tháng (nếu có tham số month)
        $month = $request->month
            ? Carbon::createFromFormat('Y-m', $request->month)->format('m')
            : $now->format('m');
        $year = $request->month
            ? Carbon::createFromFormat('Y-m', $request->month)->format('Y')
            : $now->format('Y');

        $statistics = DB::table('contracts')
            ->join('customers', 'contracts.customer_id', '=', 'customers.id')
            ->selectRaw('
            DATE(contracts.date) as date,
            COUNT(DISTINCT CASE WHEN customer_contract_counts.contract_count = 1 THEN contracts.customer_id END) as new_customers,
            COUNT(DISTINCT CASE WHEN customer_contract_counts.contract_count > 1 THEN contracts.customer_id END) as returning_customers,
            COUNT(DISTINCT contracts.customer_id) as total_customers
        ')
            ->joinSub(
                DB::table('contracts')
                    ->selectRaw('customer_id, COUNT(*) as contract_count')
                    ->groupBy('customer_id'),
                'customer_contract_counts',
                'contracts.customer_id',
                '=',
                'customer_contract_counts.customer_id'
            )
            ->where('customers.type', 1)
            ->whereMonth('contracts.date', $month)
            ->whereYear('contracts.date', $year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'statistics' => $statistics,
            'current' => $currentStats,
            'previous' => $previousStats,
            'comparison' => $this->calculateDetailedComparison($currentStats, $previousStats)
        ];
    }

    private function getMonthStatistics($now, $request)
    {
        // Thống kê cho tháng hiện tại
        $currentStats = $this->getContractStatsByMonth($now->format('Y'), $now->format('m'));

        // Thống kê cho tháng trước
        $lastMonth = $now->copy()->subMonth();
        $previousStats = $this->getContractStatsByMonth($lastMonth->format('Y'), $lastMonth->format('m'));

        // Lấy dữ liệu cho cả năm
        $year = $request->year
            ? Carbon::createFromFormat('Y', $request->year)->format('Y')
            : $now->format('Y');

        $statistics = DB::table('contracts')
            ->join('customers', 'contracts.customer_id', '=', 'customers.id')
            ->selectRaw('
            YEAR(contracts.date) as year,
            MONTH(contracts.date) as month,
            COUNT(DISTINCT CASE WHEN customer_contract_counts.contract_count = 1 THEN contracts.customer_id END) as new_customers,
            COUNT(DISTINCT CASE WHEN customer_contract_counts.contract_count > 1 THEN contracts.customer_id END) as returning_customers,
            COUNT(DISTINCT contracts.customer_id) as total_customers
        ')
            ->joinSub(
                DB::table('contracts')
                    ->selectRaw('customer_id, COUNT(*) as contract_count')
                    ->groupBy('customer_id'),
                'customer_contract_counts',
                'contracts.customer_id',
                '=',
                'customer_contract_counts.customer_id'
            )
            ->where('customers.type', 1)
            ->whereYear('contracts.date', $year)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->limit(12)
            ->get()
            ->map(function ($item) {
                $item->label = sprintf('%02d/%d', $item->month, $item->year);
                $item->date = Carbon::createFromDate($item->year, $item->month, 1)->format('Y-m');
                return $item;
            });

        return [
            'statistics' => $statistics,
            'current' => $currentStats,
            'previous' => $previousStats,
            'comparison' => $this->calculateDetailedComparison($currentStats, $previousStats),
        ];
    }

    private function getYearStatistics($now)
    {
        $currentStats = $this->getContractStatsByYear($now->format('Y'));

        $previousStats = $this->getContractStatsByYear($now->copy()->subYear()->format('Y'));

        $statistics = DB::table('contracts')
            ->join('customers', 'contracts.customer_id', '=', 'customers.id')
            ->selectRaw('
            YEAR(contracts.date) as year,
            COUNT(DISTINCT CASE WHEN customer_contract_counts.contract_count = 1 THEN contracts.customer_id END) as new_customers,
            COUNT(DISTINCT CASE WHEN customer_contract_counts.contract_count > 1 THEN contracts.customer_id END) as returning_customers,
            COUNT(DISTINCT contracts.customer_id) as total_customers
        ')
            ->joinSub(
                DB::table('contracts')
                    ->selectRaw('customer_id, COUNT(*) as contract_count')
                    ->groupBy('customer_id'),
                'customer_contract_counts',
                'contracts.customer_id',
                '=',
                'customer_contract_counts.customer_id'
            )
            ->where('customers.type', 1)
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'statistics' => $statistics,
            'current' => $currentStats,
            'previous' => $previousStats,
            'comparison' => $this->calculateDetailedComparison($currentStats, $previousStats),
        ];
    }

    private function getContractStatsByDate($date)
    {
        $stats = DB::table('contracts')
            ->join('customers', 'contracts.customer_id', '=', 'customers.id')
            ->selectRaw('
            COUNT(DISTINCT CASE WHEN customer_contract_counts.contract_count = 1 THEN contracts.customer_id END) as new_customers,
            COUNT(DISTINCT CASE WHEN customer_contract_counts.contract_count > 1 THEN contracts.customer_id END) as returning_customers,
            COUNT(DISTINCT contracts.customer_id) as total_customers
        ')
            ->joinSub(
                DB::table('contracts')
                    ->selectRaw('customer_id, COUNT(*) as contract_count')
                    ->groupBy('customer_id'),
                'customer_contract_counts',
                'contracts.customer_id',
                '=',
                'customer_contract_counts.customer_id'
            )
            ->where('customers.type', 1)
            ->whereDate('contracts.date', $date)
            ->first();

        return [
            'new_customers' => $stats->new_customers ?? 0,
            'returning_customers' => $stats->returning_customers ?? 0,
            'total' => $stats->total_customers ?? 0,
        ];
    }

    private function getContractStatsByMonth($year, $month)
    {
        $stats = DB::table('contracts')
            ->join('customers', 'contracts.customer_id', '=', 'customers.id')
            ->selectRaw('
            COUNT(DISTINCT CASE WHEN customer_contract_counts.contract_count = 1 THEN contracts.customer_id END) as new_customers,
            COUNT(DISTINCT CASE WHEN customer_contract_counts.contract_count > 1 THEN contracts.customer_id END) as returning_customers,
            COUNT(DISTINCT contracts.customer_id) as total_customers
        ')
            ->joinSub(
                DB::table('contracts')
                    ->selectRaw('customer_id, COUNT(*) as contract_count')
                    ->groupBy('customer_id'),
                'customer_contract_counts',
                'contracts.customer_id',
                '=',
                'customer_contract_counts.customer_id'
            )
            ->where('customers.type', 1)
            ->whereYear('contracts.date', $year)
            ->whereMonth('contracts.date', $month)
            ->first();

        return [
            'new_customers' => $stats->new_customers ?? 0,
            'returning_customers' => $stats->returning_customers ?? 0,
            'total' => $stats->total_customers ?? 0,
        ];
    }

    private function getContractStatsByYear($year)
    {
        $stats = DB::table('contracts')
            ->join('customers', 'contracts.customer_id', '=', 'customers.id')
            ->selectRaw('
            COUNT(DISTINCT CASE WHEN customer_contract_counts.contract_count = 1 THEN contracts.customer_id END) as new_customers,
            COUNT(DISTINCT CASE WHEN customer_contract_counts.contract_count > 1 THEN contracts.customer_id END) as returning_customers,
            COUNT(DISTINCT contracts.customer_id) as total_customers
        ')
            ->joinSub(
                DB::table('contracts')
                    ->selectRaw('customer_id, COUNT(*) as contract_count')
                    ->groupBy('customer_id'),
                'customer_contract_counts',
                'contracts.customer_id',
                '=',
                'customer_contract_counts.customer_id'
            )
            ->where('customers.type', 1)
            ->whereYear('contracts.date', $year)
            ->first();

        return [
            'new_customers' => $stats->new_customers ?? 0,
            'returning_customers' => $stats->returning_customers ?? 0,
            'total' => $stats->total_customers ?? 0,
        ];
    }

    private function calculateDetailedComparison($current, $previous)
    {
        return [
            'new_customers' => $this->calculateComparison($current['new_customers'], $previous['new_customers']),
            'returning_customers' => $this->calculateComparison($current['returning_customers'], $previous['returning_customers']),
        ];
    }

    private function calculateComparison($current, $previous)
    {
        $difference = $current - $previous;
        $percentage = 0;
        $status = 'equal';

        if ($previous > 0) {
            $percentage = round(($difference / $previous) * 100, 2);
        } elseif ($current > 0) {
            $percentage = 100; // Tăng 100% nếu từ 0 lên số dương
        }

        if ($difference > 0) {
            $status = 'increase';
        } elseif ($difference < 0) {
            $status = 'decrease';
        }

        return [
            'difference' => $difference,
            'percentage' => $percentage,
            'status' => $status,
        ];
    }
}
