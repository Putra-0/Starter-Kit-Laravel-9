<?php

namespace App\Repositories\Auth\Admin;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Auth\Admin\AdminDesign;
use LaravelEasyRepository\Implementations\Eloquent;

class AdminResponse extends Eloquent implements AdminDesign {

/*
|--------------------------------------------------------------------------
| Rumah Dev
| Backend Developer : ibudirsan
| Email             : ibnudirsan@gmail.com
| Copyright © RumahDev 2022
|--------------------------------------------------------------------------
*/
    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;
    public function __construct(User $model, Role $role)
    {
        $this->model = $model;
        $this->role = $role;
    }

    public function datatable()
    {
        return $this->model->select('id','uuid','name','deleted_at','email','created_at')
                            ->with('profile');
    }

    public function role()
    {
        return $this->role->select('id','name','guard_name')
                            ->whereNotIn('name',['SuperAdmin'])
                            ->orderby('id','desc')
                            ->get();
    }

    public function create($param)
    {
        $user = $this->model->create([
            'name'     => $param->name,
            'email'    => $param->email,
            'level'    => 2,
            'password' => bcrypt($param->password)
        ]);
            $user->profile()->create([
                'user_id'       => $user->id,
                'fullName'      => $param->fullName,
                'imageName'     => $param->name,
                'pathImage'     => empty($param->pathImage)   ? 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y' : $param->pathImage,
                'numberPhone'   => empty($param->Numberphone) ? '0' : $param->Numberphone,
                'TeleID'        => empty($param->telegramid)  ? '0' : $param->telegramid,
            ]);
                $user->assignRole($param->roles);
    }

    public function edit($id)
    {
        return $this->model->select('id','uuid','name','email')
                            ->with('profile')
                            ->whereUuid($id)
                            ->firstOrFail();
    }
}
