<?php
namespace App\DataTables\Admin;

use App\Http\Helpers\Common;
use App\Models\TatumCryptoWallet;
use Yajra\DataTables\Services\DataTable;
use Session, Config, Auth;
 
class TatumCryptoWalletDataTable extends DataTable
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
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = TatumCryptoWallet::select()->orderBy('id', 'desc');
        
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
            ->addColumn(['data' => 'id', 'name' => 'tatum_bsc_wallet.id', 'title' => 'ID'])
            ->addColumn(['data' => 'blockchain_type', 'name' => 'tatum_bsc_wallet.blockchain_type', 'title' => 'Blockchain'])
            ->addColumn(['data' => 'public_key', 'name' => 'tatum_bsc_wallet.public_key', 'title' => 'Public Key'])
            ->addColumn(['data' => 'private_key', 'name' => 'tatum_bsc_wallet.private_key', 'title' => 'Private Key'])
            ->addColumn(['data' => 'xpub', 'name' => 'tatum_bsc_wallet.xpub', 'title' => 'Xpub'])
            ->addColumn(['data' => 'mnemonic', 'name' => 'tatum_bsc_wallet.mnemonic', 'title' => 'mnemonic'])
            ->parameters();
    }

}
