<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Session;

class Permissions extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['usersId', 'pageAdminId', 'access', 'add', 'edit', 'delete'];

    protected $primaryKey = 'permissionsId';

    public $timestamps = false;

    public static function getUserPermission()
    {
        return self::permissionByUser(Auth::getUser()->id);
    }

    public static function permissionByUser($userId)
    {
        return self::where('usersId', $userId)
            ->join('pagesAdmin', 'pagesAdminId', '=', 'pageAdminId')
            ->orderBy('sortorder', 'ASC')->get();
    }

    public static function permissionByUserAndPage($userId, $pageAdminId)
    {
        return self::where('usersId', $userId)->where('pageAdminId', $pageAdminId)->first();
    }

    public static function deletePermissionByUser($userId)
    {
        return self::where('usersId', $userId)->delete();
    }

    public function page()
    {
        return $this->belongsTo(PagesAdmin::class, 'pageAdminId', 'pagesAdminId');
    }

}