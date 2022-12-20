<?php
namespace App\DataTables\Admin;

use App\Http\Helpers\Common;
use App\Models\ApiCredential;
use Yajra\DataTables\Services\DataTable;
use Session, Config, Auth;
 
class ApiCredentialDataTable extends DataTable
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
            ->addColumn('action', function ($apiCred) {
                $edit = (Common::has_permission(Auth::guard('admin')->user()->id, 'edit_user')) ? '<a href="' . url(Config::get('adminPrefix') . '/app-registrations/edit/' . $apiCred->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;' : '';
                $delete = (Common::has_permission(Auth::guard('admin')->user()->id, 'delete_user')) ? '<a href="' . route('apiCred.delete', $apiCred->id) . '" class="btn btn-xs btn-danger delete-warning"><i class="glyphicon glyphicon-trash"></i></a>' : '';
                return $edit . $delete;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = ApiCredential::select();
        
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
            ->addColumn(['data' => 'application_id', 'name' => 'api_credential.application_id', 'title' => 'Application ID'])
            ->addColumn(['data' => 'api_key', 'name' => 'api_credential.api_key', 'title' => 'API Key'])
            ->addColumn(['data' => 'ui_key', 'name' => 'api_credential.ui_key', 'title' => 'UI key'])
            ->addColumn(['data' => 'corporates_id', 'name' => 'api_credential.corporates_id', 'title' => 'Corporates ID'])
            ->addColumn(['data' => 'managed_accounts', 'name' => 'api_credential.managed_accounts', 'title' => 'Managed Accounts'])
            ->addColumn(['data' => 'managed_cards_debit', 'name' => 'api_credential.managed_cards_debit', 'title' => 'Managed Cards Debit'])
            ->addColumn(['data' => 'managed_cards_prepaid', 'name' => 'api_credential.managed_cards_prepaid', 'title' => 'Managed Cards Prepaid'])
            ->addColumn(['data' => 'transfer_id', 'name' => 'api_credential.transfer_id', 'title' => 'Transfers'])
            ->addColumn(['data' => 'send_id', 'name' => 'api_credential.send_id', 'title' => 'Send'])
            ->addColumn(['data' => 'outgoing_wire_transfer', 'name' => 'api_credential.outgoing_wire_transfer', 'title' => 'Outgoing Wire Transfers'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
            ->parameters();
    }

}
