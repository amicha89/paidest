<?php
namespace App\DataTables\Admin;

use App\Http\Helpers\Common;
use Yajra\DataTables\Services\DataTable;
use App\Models\VirtualAccounts;
use Session, Config, Auth, DB;
 
class VirtualAccountsDataTable extends DataTable
{
       /**
     * Build DataTable class.
     *
     * @return \Yajra\Datatables\Engines\BaseEngine
     */
    public function ajax() //don't use default dataTable() method
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('first_name', function ($user) {
                $userDetails = DB::table('users')->select('first_name','last_name')->where('id',$user->user_id)->first();
                $userFullName = $userDetails->first_name ."&nbsp". $userDetails->last_name;
                //$userFullName = userName($user->user_id);
                return ($userFullName) ?
                    '<a href="' . url(Config::get('adminPrefix') . '/users/edit/' . $user->user_id) . '">' . $userFullName . '</a>' : $user->user_id;
            })
            ->addColumn('status', function ($account) {
                $status = '';
                if($account->active == 1 ){
                    $status = '<span class="label label-success">Active</span>';
                }
                else{
                    $status = '<span class="label label-danger">Inactive</span>';
                }
                return $status;
            })
            ->rawColumns(['first_name','status'])
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = VirtualAccounts::select()->orderBy('id', 'desc');;
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
       return $this->builder()
            ->addColumn(['data' => 'id', 'id' => 'virtual_account.id', 'title' => 'ID'])
            ->addColumn(['data' => 'first_name', 'name' => 'virtual_account.user_id', 'title' => 'User'])
            ->addColumn(['data' => 'currency', 'name' => 'virtual_account.currency', 'title' => 'Currency'])
            ->addColumn(['data' => 'available_balance', 'name' => 'virtual_account.available_balance', 'title' => 'Available Balance'])
            ->addColumn(['data' => 'virtualacc_id', 'name' => 'virtual_account.virtualacc_id', 'title' => 'Account ID'])
            ->addColumn(['data' => 'deposit_address', 'name' => 'virtual_account.deposit_address', 'title' => 'Deposit Address'])
            ->addColumn(['data' => 'status', 'name' => 'virtual_account.active', 'title' => 'Status'])
            ->parameters();
    }

    //get user name function
    public function userName($user_id){
        $userDetails = DB::table('users')->select('first_name','last_name')->first();
        $userName = $userDetails->first_name ."&nbsp". $userDetails->last_name;
        return $userName;
    }

}
