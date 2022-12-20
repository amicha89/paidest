<?php
namespace App\DataTables\Admin;

use App\Http\Helpers\Common;
use App\Models\AppReg;
use Yajra\DataTables\Services\DataTable;
use Session, Config, Auth;
 
class AppRegistrationDataTable extends DataTable
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
            ->addColumn('status', function ($AppReg) {
                $status = '';
                if($AppReg->status == 0 ){
                    $status = '<span class="label label-info">Pending</span>';
                }
                elseif($AppReg->status == 1 ){
                    $status = '<span class="label label-success">Processing</span>';
                }
                else{
                    $status = '<span class="label label-danger">Rejected</span>';
                }
                return $status;
            })
            ->addColumn('action', function ($appRgs) {
                $edit = (Common::has_permission(Auth::guard('admin')->user()->id, 'edit_user')) ? '<a href="' . url(Config::get('adminPrefix') . '/app-registrations/edit/' . $appRgs->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;' : '';
                $delete = (Common::has_permission(Auth::guard('admin')->user()->id, 'delete_user')) ? '<a href="' . url(Config::get('adminPrefix') . '/app-registrations/delete/' . $appRgs->id) . '" class="btn btn-xs btn-danger delete-warning"><i class="glyphicon glyphicon-trash"></i></a>' : '';
                return $edit . $delete;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = AppReg::select();
        
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
            ->addColumn(['data' => 'first_name', 'name' => 'applications.first_name', 'title' => 'First Name'])
            ->addColumn(['data' => 'last_name', 'name' => 'applications.last_name', 'title' => 'Last Name'])
            ->addColumn(['data' => 'email', 'name' => 'applications.email', 'title' => 'Email'])
            ->addColumn(['data' => 'phone', 'name' => 'applications.phone', 'title' => 'Phone'])
            ->addColumn(['data' => 'company_name', 'name' => 'applications.company_name', 'title' => 'Company Name'])
            ->addColumn(['data' => 'rule', 'name' => 'applications.rule', 'title' => 'Role'])
            ->addColumn(['data' => 'company_type', 'name' => 'applications.company_type', 'title' => 'Company Type'])
            ->addColumn(['data' => 'company_number', 'name' => 'applications.company_number', 'title' => 'Company Number'])
            ->addColumn(['data' => 'status', 'name' => 'applications.status', 'title' => 'Status'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
            ->parameters();
    }

}
