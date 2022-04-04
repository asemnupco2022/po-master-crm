<?php

namespace App\Console\Commands\Import;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class SapFilterSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lbs:sync-sap-filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stringiFy = $this->getViewBladeData();
        $stringiFyFilter = $this->getViewBladeDataFilter();
        $stringiFySCFilter = $this->getSuplierRequestView();

        Storage::disk('localViewPath')->put('sap-filter-tmp-new.blade.php', $stringiFy);
        Storage::disk('localViewPath')->put('livewire/filters/v2/advance-filter-component.blade.php', $stringiFyFilter);
        Storage::disk('localViewPath')->put('livewire/tickets/v2/filter/notification-history-filter-template.blade.php', $stringiFySCFilter);

        return Command::SUCCESS;
    }

    public function getSuplierRequestView()
    {
        $collection_tender_num = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('tender_num', 'tender_num');
        $collection_vendor_num = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('vendor_num', 'vendor_num');
        $collection_vendor_name_en = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('vendor_name_en', 'vendor_name_en');
        $collection_cust_code = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('cust_code', 'cust_code');
        $collection_customer_name = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('customer_name', 'customer_name');
        $collection_po_num = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('po_num', 'po_num');
        $collection_po_item_num = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('po_item_num', 'po_item_num');
        $collection_mat_num = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('mat_num', 'mat_num');
        $collection_tender_desc = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('tender_desc', 'tender_desc');
        $collection_customer_po_no = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('customer_po_no', 'customer_po_no');
        $collection_customer_po_item = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('customer_po_item', 'customer_po_item');
        $collection_uom = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('uom', 'uom');
        $collection_plant = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('plant', 'plant');
        $collection_item_desc = DB::table('ticket_master_headrs')->where('meta','!=','init')->pluck('item_desc', 'item_desc');
        $collection_employee_names = DB::table('ticket_managers')->pluck('staff_name', 'staff_name');


        return   View::make('livewire.tickets.v2.filter.notification-history-filter-tmp', compact(
            'collection_tender_num',
            'collection_vendor_num',
            'collection_vendor_name_en',
            'collection_cust_code',
            'collection_customer_name',
            'collection_po_num',
            'collection_po_item_num',
            'collection_mat_num',
            'collection_tender_desc',
            'collection_customer_po_no',
            'collection_customer_po_item',
            'collection_uom',
            'collection_plant',
            'collection_item_desc',
            'collection_employee_names'
        ))->render();
    }


    public function getViewBladeDataFilter()
    {
        $collection_sap_po_types = DB::table('collection_sap_po_types')->pluck('document_type', 'document_type');
        $collection_sap_pur_groups =  DB::table('collection_sap_pur_groups')->pluck('purchasing_group', 'purchasing_group');
        $collection_sap_customer_names = DB::table('collection_sap_customer_names')->pluck('customer_name', 'customer_name');
        $collection_sap_tender_nos =  DB::table('collection_sap_tender_nos')->pluck('tender_no', 'tender_no');
        $collection_sap_tender_descs = DB::table('collection_sap_tender_descs')->pluck('tender_desc', 'tender_desc');
        $collection_sap_vendor_name_ens = DB::table('collection_sap_vendor_name_ens')->pluck('vendor_name_en', 'vendor_name_en');
        $collection_sap_po_numbers = DB::table('collection_sap_po_numbers')->pluck('po_number', 'po_number');
        $collection_sap_generic_mat_codes = DB::table('collection_sap_generic_mat_codes')->pluck('generic_mat_code', 'generic_mat_code');
        $collection_sap_cust_gen_codes = DB::table('collection_sap_cust_gen_codes')->pluck('cust_gen_code', 'cust_gen_code');
        $collection_sap_mat_descriptions = DB::table('collection_sap_mat_descriptions')->pluck('mat_description', 'mat_description');
        $collection_sap_delivery_address =  DB::table('collection_sap_delivery_address')->pluck('delivery_address', 'delivery_address');
        $collection_sap_storage_locations = DB::table('collection_sap_storage_locations')->pluck('storage_location', 'storage_location');
        $collection_sap_customer_nos =  DB::table('collection_sap_customer_nos')->pluck('customer_no', 'customer_no');
        $collection_vendor_codes = DB::table('collection_vendor_codes')->pluck('vendor_code', 'vendor_code');
        $collection_sap_plnts = DB::table('collection_sap_plnts')->pluck('plant', 'plant');

        $collection_sap_customer_po_nos = DB::table('collection_sap_customer_po_nos')->pluck('customer_po_no', 'customer_po_no');
        $collection_sap_customer_po_items = DB::table('collection_sap_customer_po_items')->pluck('customer_po_item', 'customer_po_item');
        $collection_sap_pur_grp_names = DB::table('collection_sap_pur_grp_names')->pluck('pur_grp_name', 'pur_grp_name');
        $collection_sap_nupco_trade_codes = DB::table('po_sap_masters')->pluck('nupco_trade_code', 'nupco_trade_code');
        $collection_sap_notifieds = [
            'yes' => 'yes',
            'no' => 'no',
        ];


        return   View::make('livewire.filters.v2.adavance-filter-tem', compact(
            'collection_sap_po_types',
            'collection_sap_pur_groups',
            'collection_sap_customer_names',
            'collection_sap_tender_nos',
            'collection_sap_tender_descs',
            'collection_sap_vendor_name_ens',
            'collection_sap_po_numbers',
            'collection_sap_generic_mat_codes',
            'collection_sap_cust_gen_codes',
            'collection_sap_mat_descriptions',
            'collection_sap_delivery_address',
            'collection_sap_storage_locations',
            'collection_sap_customer_nos',
            'collection_vendor_codes',
            'collection_sap_customer_po_nos',
            'collection_sap_customer_po_items',
            'collection_sap_pur_grp_names',
            'collection_sap_notifieds',
            'collection_sap_plnts',
            'collection_sap_nupco_trade_codes'
        ))->render();
    }

    public function getViewBladeData()
    {
        $collection_sap_po_types = DB::table('collection_sap_po_types')->pluck('document_type', 'document_type');
        $collection_sap_pur_groups =  DB::table('collection_sap_pur_groups')->pluck('purchasing_group', 'purchasing_group');
        $collection_sap_customer_names = DB::table('collection_sap_customer_names')->pluck('customer_name', 'customer_name');
        $collection_sap_tender_nos =  DB::table('collection_sap_tender_nos')->pluck('tender_no', 'tender_no');
        $collection_sap_tender_descs = DB::table('collection_sap_tender_descs')->pluck('tender_desc', 'tender_desc');
        $collection_sap_vendor_name_ens = DB::table('collection_sap_vendor_name_ens')->pluck('vendor_name_en', 'vendor_name_en');
        $collection_sap_po_numbers = DB::table('collection_sap_po_numbers')->pluck('po_number', 'po_number');
        $collection_sap_generic_mat_codes = DB::table('collection_sap_generic_mat_codes')->pluck('generic_mat_code', 'generic_mat_code');
        $collection_sap_cust_gen_codes = DB::table('collection_sap_cust_gen_codes')->pluck('cust_gen_code', 'cust_gen_code');
        $collection_sap_mat_descriptions = DB::table('collection_sap_mat_descriptions')->pluck('mat_description', 'mat_description');
        $collection_sap_delivery_address =  DB::table('collection_sap_delivery_address')->pluck('delivery_address', 'delivery_address');
        $collection_sap_storage_locations = DB::table('collection_sap_storage_locations')->pluck('storage_location', 'storage_location');
        $collection_sap_customer_nos =  DB::table('collection_sap_customer_nos')->pluck('customer_no', 'customer_no');
        $collection_vendor_codes = DB::table('collection_vendor_codes')->pluck('vendor_code', 'vendor_code');
        $collection_sap_plnts = DB::table('collection_sap_plnts')->pluck('plant', 'plant');

        $collection_sap_customer_po_nos = DB::table('collection_sap_customer_po_nos')->pluck('customer_po_no', 'customer_po_no');
        $collection_sap_customer_po_items = DB::table('collection_sap_customer_po_items')->pluck('customer_po_item', 'customer_po_item');
        $collection_sap_pur_grp_names = DB::table('collection_sap_pur_grp_names')->pluck('pur_grp_name', 'pur_grp_name');
        $collection_sap_nupco_trade_codes = DB::table('po_sap_masters')->pluck('nupco_trade_code', 'nupco_trade_code');
        $collection_sap_notifieds = [
            'yes' => 'yes',
            'no' => 'no',
        ];


        return View::make('sap-filter-tmp', compact(
            'collection_sap_po_types',
            'collection_sap_pur_groups',
            'collection_sap_customer_names',
            'collection_sap_tender_nos',
            'collection_sap_tender_descs',
            'collection_sap_vendor_name_ens',
            'collection_sap_po_numbers',
            'collection_sap_generic_mat_codes',
            'collection_sap_cust_gen_codes',
            'collection_sap_mat_descriptions',
            'collection_sap_delivery_address',
            'collection_sap_storage_locations',
            'collection_sap_customer_nos',
            'collection_vendor_codes',
            'collection_sap_customer_po_nos',
            'collection_sap_customer_po_items',
            'collection_sap_pur_grp_names',
            'collection_sap_notifieds',
            'collection_sap_plnts',
            'collection_sap_nupco_trade_codes'
        ))->render();
    }
}
