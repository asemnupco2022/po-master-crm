<?php

namespace App\Http\Livewire\Staffs;

use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use rifrocket\LaravelCms\Helpers\Classes\LbsConstants;
use rifrocket\LaravelCms\Models\LbsAdmin;
use Spatie\Permission\Models\Permission;

class EditStaffComponent extends Component
{

    public function emitNotifications($message, $msgType)
    {
        $this->emit('toast-notification-component',$message,$msgType);
    }

    protected $listeners=['update-staff-data'=>'fetchBseInfo'];

    public $staff_id=null;

    public $permissionArray=[];
    public $employee_num=null;
    public $first_name=null;
    public $last_name=null;
    public $username=null;
    public $email=null;
    public $phone=null;
    public $permissions=[];


    public $password=null;
    public $password_confirmation =null;

    protected $rules = [
        'employee_num'=>'required',
        'first_name'=>'required',
        'last_name'=>'required',
        'email'=>'required',
        'phone'=>'required',
    ];

    public function updatedFirstName($value)
    {
        if ($this->first_name and $this->last_name){
            $this->username=$this->first_name.$this->last_name;
        }
    }

    public function updatedLastName($value)
    {
        if ($this->first_name and $this->last_name){
            $this->username=$this->first_name.$this->last_name;
        }
    }

    public function mount()
    {
        $this->permissionArray= Permission::where('deleted_at', null)->where('status','active')->pluck('display_name','name');
    }


    public function fetchBseInfo($value)
    {
        $this->staff_id=$value;
        $updateInfo=LbsAdmin::find($this->staff_id);

        $this->employee_num=$updateInfo->employee_num ;
        $this->first_name=$updateInfo->first_name ;
        $this->last_name=$updateInfo->last_name ;
        $this->username=$updateInfo->username ;

        $this->display_name=$updateInfo->display_name ;
        $this->email=$updateInfo->email ;
        $this->role=$updateInfo->role ;
        $this->phone=$updateInfo->phone ;

        $this->permissions=$updateInfo->getPermissionNames();

    }


    public function updateStaff()
    {

        $this->validate();

        if ($this->password){

            $this->validate([
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                        ->mixedCase()
                        ->letters()
                        ->numbers()
                        ->symbols()
                        ->uncompromised(),
                ],
            ]);
        }


        $saveStaff=LbsAdmin::find($this->staff_id);

        if (LbsAdmin::where('employee_num',$this->employee_num)->first()->id != $saveStaff->id){
            return $this->emitNotifications('Employee Number Already Taken','error');
        }

        if (LbsAdmin::where('email',$this->email)->first()->id != $saveStaff->id){
            return $this->emitNotifications('Email Already Taken','error');
        }

        $saveStaff->employee_num = $this->employee_num;
        $saveStaff->first_name = $this->first_name;
        $saveStaff->last_name = $this->last_name;
        $saveStaff->username = $this->username;
        $saveStaff->display_name = $this->first_name.' '.$this->last_name;
        $saveStaff->email = $this->email;
        $saveStaff->role = LbsConstants::STAFF_ROLE;
        $saveStaff->phone = $this->phone;

        if ($this->password){
            $saveStaff->password = Hash::make($this->password);
        }
        if ($saveStaff->save()){
            $saveStaff->syncPermissions($this->permissions);
            $this->search_reset();
            return $this->emitNotifications('data updated successfully','success');
        }else{
            return $this->emitNotifications('There is something wrong','error');
        }
    }

    public function search_reset()
    {
        $this->employee_num=null;
        $this->first_name=null;
        $this->last_name=null;
        $this->username=[];
        $this->email=null;
        $this->phone=null;
        $this->password_confirmation=null;
        $this->password=null;
        $this->dispatchBrowserEvent('reset-permission-select2');
    }

    public function render()
    {
        return view('livewire.staffs.edit-staff-component');
    }
}
