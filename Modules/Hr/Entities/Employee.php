<?php

namespace Modules\Hr\Entities;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Entities\CustomerType;
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\StorePos;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Employee extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $appends = ['store_pos'];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $hidden = ['pass_string'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'working_day_per_week' => 'array',
        'store_id' => 'array',
        'check_in' => 'array',
        'check_out' => 'array',
        'upload_files' => 'array',
        'commissioned_products' => 'array',
        'commission_customer_types' => 'array',
        'commission_stores' => 'array',
        'commission_cashiers' => 'array'

    ];

    public function job_type()
    {
        return $this->belongsTo(JobType::class);
    }

    public function user()
    {
        return $this->belongsTo(Admin::class,'admin_id','id');
    }
    public static function getWeekDays()
    {
        return [
            'sunday' => 'Sunday',
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
        ];
    }
    public static function paymentCycle()
    {
        return [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'bi-weekly' => 'Bi-Weekly',
            'monthly' => 'Monthly'
        ];
    }
    public static function commissionType()
    {
        return [
            'sales' => 'Sales',
            'profit' => 'Profit'
        ];
    }
    public static function commissionCalculationPeriod()
    {
        return [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'one_month' => 'One Month',
            'three_month' => 'Three Month',
            'six_month' => 'Six Month',
            'one_year' => 'One Year',
        ];
    }

    public static function getEmployeeLeave($employee_id, $type = 'Annual')
    {
        $number_of_leaves = LeaveType::leftjoin('number_of_leaves', 'leave_types.id', 'number_of_leaves.leave_type_id')
            ->where('number_of_leaves.employee_id', $employee_id)
            ->where('leave_types.name', 'like', '%' . $type . '%')
            ->select('leave_types.id', 'leave_types.name', 'leave_types.number_of_days_per_year as number_of_days', 'number_of_leaves.enabled')
            ->first();

        if (!empty($number_of_leaves->enabled) && $number_of_leaves->enabled == 1) {
            return $number_of_leaves->number_of_days;
        } else {
            return 0;
        }
    }

    public static function getEmployeeLeaveTotal($employee_id)
    {
        $number_of_leaves = LeaveType::leftjoin('number_of_leaves', 'leave_types.id', 'number_of_leaves.leave_type_id')
            ->where('number_of_leaves.employee_id', $employee_id)
            ->where('number_of_leaves.enabled', 1)
            ->select('leave_types.id', 'leave_types.name', 'leave_types.number_of_days_per_year as number_of_days', 'number_of_leaves.enabled')
            ->get();


        return $number_of_leaves->sum('number_of_days');
    }
    public static function getEmployeeLeaveTotalByLeaveType($employee_id, $id)
    {
        $number_of_leaves = LeaveType::leftjoin('number_of_leaves', 'leave_types.id', 'number_of_leaves.leave_type_id')
            ->where('number_of_leaves.employee_id', $employee_id)
            ->where('number_of_leaves.leave_type_id', $id)
            ->where('number_of_leaves.enabled', 1)
            ->select('leave_types.id', 'leave_types.name', 'leave_types.number_of_days_per_year as number_of_days', 'number_of_leaves.enabled')
            ->get();


        return $number_of_leaves->sum('number_of_days');
    }

    public static function getBalanceLeave($id)
    {
        $employee = Employee::find($id);

        $leave_balance = 0;
        $worked_months = Employee::getWorkedMonth($employee);

        $per_month_leaves = Employee::getEmployeeLeaveTotal($id) / 12;
        $deserving_leaves_till_date = $per_month_leaves * $worked_months;

        $leave_taken = Leave::whereDate('start_date', '<=', \Carbon\Carbon::now())->where('employee_id', $id)->where('status', 'approved')->sum('number_of_days');
        $leave_balance = $deserving_leaves_till_date - $leave_taken;
        //leave taken from attendance
        $leave_taken_from_attendance = 0;
        if (!empty($employee->date_of_start_working)) {
            $leave_taken_from_attendance = Attendance::where('employee_id', $employee->id)->where('status', 'on_leave')->whereDate('date', '>=', $employee->date_of_start_working)->whereDate('date', '<=', date('Y-m-d'))->count();
        } else {
            $leave_taken_from_attendance = Attendance::where('employee_id', $employee->id)->where('status', 'on_leave')->whereDate('date', '<=', date('Y-m-d'))->count();
        }

        $leave_balance = $leave_balance - $leave_taken_from_attendance;

        $forfeit_leaves = ForfeitLeave::where('employee_id', $id)->where('start_date', Carbon::now()->format('Y'))->sum('number_of_days');
        $leave_balance = $leave_balance - $forfeit_leaves;

        return number_format($leave_balance, 2,',','.');
    }

    public static function getBalanceLeaveByLeaveType($employee_id, $id)
    {
        $employee = Employee::find($employee_id);

        $leave_balance = 0;
        $worked_months = Employee::getWorkedMonth($employee);

        $per_month_leaves = Employee::getEmployeeLeaveTotalByLeaveType($employee_id, $id) / 12;
        $deserving_leaves_till_date = $per_month_leaves * $worked_months;

        $leave_taken = Leave::whereDate('start_date', '<=', \Carbon\Carbon::now())->where('employee_id', $employee_id)->where('leave_type_id', $id)->where('status', 'approved')->sum('number_of_days');

        $leave_balance = $deserving_leaves_till_date - $leave_taken;
        //leave taken from attendance
        $leave_taken_from_attendance = 0;
        if (!empty($employee->date_of_start_working)) {
            $leave_taken_from_attendance = Attendance::where('employee_id', $employee->id)->where('status', 'on_leave')->whereDate('date', '>=', $employee->date_of_start_working)->whereDate('date', '<=', date('Y-m-d'))->count();
        } else {
            $leave_taken_from_attendance = Attendance::where('employee_id', $employee->id)->where('status', 'on_leave')->whereDate('date', '<=', date('Y-m-d'))->count();
        }

        $leave_balance = $leave_balance - $leave_taken_from_attendance;

        $forfeit_leaves = ForfeitLeave::where('employee_id', $employee_id)->where('leave_type_id', $id)->where('start_date', Carbon::now()->format('Y'))->sum('number_of_days');
        $leave_balance = $leave_balance - $forfeit_leaves;

        return number_format($leave_balance, 2,',','.');
    }

    public static function getWorkedMonth($employee)
    {
        $worked_months = 0;
        $this_year = Carbon::now()->format('Y');

        if (!empty($employee->date_of_start_working) && Carbon::parse($this_year . '-01-01')->lt(Carbon::parse($employee->date_of_start_working))) {
            $worked_months = Carbon::parse($employee->date_of_start_working)->diffInMonths(\Carbon\Carbon::now());
        } else {
            $worked_months = Carbon::parse($this_year . '-01-01')->diffInMonths(\Carbon\Carbon::now());
        }

        return $worked_months;
    }

    public static function getDropdown()
    {
        $employees = Employee::leftjoin('admins', 'employees.admin_id', 'admins.id')->pluck('admins.name', 'employees.id');

        return $employees;
    }

    public static function getDropdownByJobType($job_type, $include_superadmin = false, $return_admin_id = false)
    {
        if($job_type == 'Deliveryman'){
            $query = Employee::leftjoin('job_types', 'employees.job_type_id', 'job_types.id')
                ->leftjoin('admins', 'employees.admin_id', 'admins.id');


            if ($return_admin_id) {
                $employees = $query->pluck('admins.name', 'admins.id');
            } else {
                $employees = $query->pluck('admins.name', 'employees.id');
            }
            return $employees->toArray();
        }
        else{
            $query = Employee::leftjoin('job_types', 'employees.job_type_id', 'job_types.id')
                ->leftjoin('admins', 'employees.admin_id', 'admins.id')
                ->leftjoin('store_pos', 'admins.id', 'store_pos.admin_id')
        //            ->where('job_types.job_title', $job_type)
                ->whereNotNull('store_pos.admin_id');

            if ($return_admin_id) {
                $employees = $query->pluck('admins.name', 'admins.id');
            } else {
                $employees = $query->pluck('admins.name', 'employees.id');
            }
            return $employees->toArray();
        }
    }
    public static function getCommissionEmployeeDropdown($include_superadmin = false, $return_admin_id = false)
    {
        $query = Employee::leftjoin('admins', 'employees.admin_id', 'admins.id')
            ->where('employees.commission_value', '>', 0);

        if ($return_admin_id) {
            $employees = $query->pluck('admins.name', 'admins.id');
        } else {
            $employees = $query->pluck('admins.name', 'employees.id');
        }
        return $employees->toArray();
    }

    public static function getDropdownChefs()
    {
        $query = Employee::leftjoin('job_types', 'employees.job_type_id', 'job_types.id')
            ->leftjoin('admins', 'employees.admin_id', 'admins.id')
            ->where('job_types.job_title', 'chef');

        if (!auth()->user()->can('raw_material_module.add_consumption_for_others.create_and_edit') && !auth()->user()->can('superadmin')  && auth()->user()->is_admin != 1) {
            $employees = $query->where('admins.id', Auth::user()->id)->pluck('admins.name', 'admins.id');
        } else {
            $employees = $query->pluck('admins.name', 'admins.id');
        }
        return $employees->toArray();
    }

    public function getStorePosAttribute()
    {
        $admin_id = $this->admin_id;

        $store_pos = StorePos::where('admin_id', $admin_id)->first();

        return $store_pos->name ?? null;
    }

    public function store()
    {
        return $this->belongsToJson(Store::class, 'store_id');
    }

    public function commission_customer_type()
    {
        return $this->belongsToJson(CustomerType::class, 'commission_customer_types');
    }

    public function commission_store()
    {
        return $this->belongsToJson(Store::class, 'commission_stores');
    }

    public function commission_cashier()
    {
        return $this->belongsToJson(Admin::class, 'commission_cashiers');
    }
}
