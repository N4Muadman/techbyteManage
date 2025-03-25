<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\FeedBackCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;

class Customer_manageController extends Controller
{
    public function newCustomer(Request $request){
        $user = Auth::user();
        $customerQuery = Customer::with('employee')->where('type', 0);

        if($user->role_id == 5){
            $customerQuery->where('employee_id', $user->employee->id);
        }

        if($request->name){
            $customerQuery->where('full_name', 'like' , '%' .$request->name .'%');
        }
        if($request->phone_number){
            $customerQuery->where('phone_number', 'like' , '%' .$request->phone_number .'%');
        }
        if($request->employee){
            $customerQuery->whereHas('employee', function ($query) use ($request){
                $query->where('full_name', 'like' , '%' .$request->employee .'%');
            });
        }

        $customers = $customerQuery->paginate(10);

        return view('customer.new', compact('customers'));
    }

    public function businessCustomer(Request $request){
        $user = Auth::user();
        $customerQuery = Customer::with('employee')->where('type', 1);

        if($user->role_id == 5){
            $customerQuery->where('employee_id', $user->employee->id);
        }

        if($request->name){
            $customerQuery->where('full_name', 'like' , '%' .$request->name .'%');
        }
        if($request->phone_number){
            $customerQuery->where('phone_number', 'like' , '%' .$request->phone_number .'%');
        }
        if($request->employee){
            $customerQuery->whereHas('employee', function ($query) use ($request){
                $query->where('full_name', 'like' , '%' .$request->name .'%');
            });
        }

        $customers = $customerQuery->paginate(10);

        return view('customer.business', compact('customers'));
    }

    public function show($id){
        $customer = Customer::with('employee', 'contract')->find($id);

        if (!$customer){
            return response()->json([
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'customer' => $customer
        ], 200);
    }

    public function create(Request $request){
        $request->validate([
            'full_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'social_network' => 'required',
            'date_find_to_me' => 'required',
            'object' => 'required',
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
    public function update(Request $request, $id){
        $request->validate([
            'full_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'social_network' => 'required',
            'date_find_to_me' => 'required',
            'object' => 'required',
        ]);

        $customer = Customer::find($id);

        if (!$customer){
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

    public function delete($id){
        $customer = Customer::find($id);

        if (!$customer){
            return redirect()->back()->with('error', 'Khách hàng không tồn tại!');
        }

        $customer->delete();

        return redirect()->back()->with('success', 'Xóa khách hàng thành công');
    }

    public function deal(Request $request, $id){
        $request->validate([
            'date' => 'required',
            'contract_code' => 'required',
            'contract_value' => 'required',
            'advance_money' => 'required',
        ]);
        $customer = Customer::find($id);

        if (!$customer){
            return redirect()->back()->with('error', 'Khách hàng không tồn tại!');
        }
        try {
            DB::beginTransaction();
            $contract_value = preg_replace('/\D/', '', $request->contract_value);
            $advance_money = preg_replace('/\D/', '', $request->advance_money);

            Log::info($advance_money . $contract_value);

            Contract::create([
                'customer_id' => $customer->id,
                'contract_code' => $request->contract_code,
                'contract_value' => $contract_value,
                'advance_money' => $advance_money,
                'date' => $request->date,
            ]);

            $customer->update([
                'type' => 1,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Chốt deal hợp đồng thành công');

        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra!');
        }
    }


    public function getFeedback($id){
        $feedbacks = FeedBackCustomer::with('customer')->where('customer_id', $id)->get();

        return response()->json([
            'feedbacks' => $feedbacks,
        ], 200);
    }

    public function statistics(Request $request){
        $user = Auth::user();
        $customerQuery = Customer::with('contract');

        if($user->role_id == 5){
            $customerQuery->where('employee_id', $user->employee->id);
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date ?? now()->startOfMonth()->format('Y-m-d'));
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date ?? now()->endOfMonth()->format('Y-m-d'));

        $totalCustomers = $customerQuery->count();

        $closedDeals = (clone $customerQuery)->where('type', 1)->whereHas('contract', function ($query) use ($startDate, $endDate){
            $query->whereBetween('date', [$startDate, $endDate]);
        })->count();
        $notCloseds = (clone $customerQuery)->where('type', 0)->whereHas('contract', function ($query) use ($startDate, $endDate){
            $query->whereBetween('date', [$startDate, $endDate]);
        })->count();

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
        ->map(function($data){
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

    public function uploadFeedback(Request $request){
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

    public function debtContract(Request $request){
        $contractQuery = Contract::with('customer.employee')->whereColumn('contract_value', '>', 'advance_money');

        if(Auth::user()->role_id == 5){
            $contractQuery->where('customer_id', Auth::user()->employee_id);
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date ?? now()->startOfMonth()->format('Y-m-d'));
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date ?? now()->endOfMonth()->format('Y-m-d'));

        $contractQuery->whereBetween('date', [$startDate, $endDate]);

        $debts = $contractQuery->paginate(10);

        return view('customer.debt', compact('debts'));
    }

    public function getContract($id){
        $contract = Contract::find($id);

        return response()->json([
            'contract' => $contract,
        ], 200);
    }

    public function updateContract(Request $request, $id){
        $request->validate([
            'date' => 'required',
            'contract_code' => 'required',
        ]);
        $advance_money_request = $request->advance_money ? preg_replace('/\D/', '', $request->advance_money): 0;
        $contract = Contract::find($id);

        $advance_money = $advance_money_request + $contract->advance_money;

        if ($advance_money > $contract->contract_value){
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
    public function businessStatistics(Request $request){
        $contractQuery = Contract::query();

        if(Auth::user()->role_id == 5){
            $contractQuery->where('customer_id', Auth::user()->employee_id);
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date ?? now()->startOfMonth()->format('Y-m-d'));
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date ?? now()->endOfMonth()->format('Y-m-d'));

        $previousStartDate = (clone $startDate)->subMonth();

        $previousStatistics =(clone $contractQuery)->select(
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
}
