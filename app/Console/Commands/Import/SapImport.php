<?php

namespace App\Console\Commands\Import;

use App\Jobs\Import\SapImportJob;
use App\Jobs\Import\SapImportJobNew;
use App\Jobs\Vendor\FetchVendorJob;
use App\Models\LbsUserSearchSet;
use App\Models\PoImportScheduler;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SapImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lbs:import-sap-po';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import data from SAP server to local storage and chunk them into 5000 records/file';

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
    public function importSapCsv()
    {
        $baseFilePath=public_path('uploads/sap_nupco_backup.csv');



        if (env('APP_DEBUG') == false) {

            if (File::exists($baseFilePath)) {
                File::delete($baseFilePath);
            }
            Storage::disk('public_uploads')->append('uploads/sap_nupco_backup.csv', '');
            if (App::environment('local')) {
                $filesNames =   Storage::disk('nupco_remote_dev')->allFiles();
                foreach ($filesNames as $fileKey => $fileName) {
                    if ($fileKey == 0) {
                        Storage::disk('public_uploads')->put('uploads/sap_nupco_backup.csv', Storage::disk('nupco_remote_dev')->get($fileName));
                    } else {
                        Storage::disk('public_uploads')->append('uploads/sap_nupco_backup.csv', Storage::disk('nupco_remote_dev')->get($fileName));
                    }
                }
            } else {
                $filesNames =   Storage::disk('nupco_remote')->allFiles();
                foreach ($filesNames as $fileKey => $fileName) {
                    if ($fileKey == 0) {
                        Storage::disk('public_uploads')->put('uploads/sap_nupco_backup.csv', Storage::disk('nupco_remote')->get($fileName));
                    } else {
                        Storage::disk('public_uploads')->append('uploads/sap_nupco_backup.csv', Storage::disk('nupco_remote')->get($fileName));
                    }
                }
            }
        }

        $baseFile = file(public_path('uploads/sap_nupco_backup.csv'));
        if (!File::exists($baseFilePath)) {
            return 0;
        }

        $parts = (array_chunk($baseFile, 50000));
        File::deleteDirectory(public_path('uploads/sap_parts/'.Carbon::now()->format('Y_m_d')));
        $partPath='uploads/sap_parts/'.Carbon::now()->format('Y_m_d').'/';

        $total_files=0;
        $total_records=0;

        foreach ($parts as $key => $part) {
            $total_files++;
            $total_records=$total_records+count($part);


            $fileName = 'sap_part_' . $key . '.csv';
            Storage::disk('public_uploads')->put($partPath . $fileName, $part);
        }

        if (PoImportScheduler::where('table_type',LbsUserSearchSet::TEMPLATE_SAP_LINE_ITEM)->whereDate('created_at', Carbon::now()->format('Y_m_d'))->first()){

            $insert=PoImportScheduler::where('table_type',LbsUserSearchSet::TEMPLATE_SAP_LINE_ITEM)->whereDate('created_at', Carbon::now()->format('Y_m_d'))->first();
        }else{
            $insert=new PoImportScheduler();
        }

        $insert->table_type=LbsUserSearchSet::TEMPLATE_SAP_LINE_ITEM;
        $insert->path=$partPath;
        $insert->total_files=$total_files;
        $insert->total_records=$total_records;
        $insert->total_ex_files=0;
        $insert->total_ex_records=0;
        $insert->start_time=null;
        $insert->end_time=null;
        $insert->save();

        // Artisan::call('backup:run --only-db');
        DB::table('po_sap_masters')->truncate();


        $paths = public_path($partPath);
        $path = ($paths.'*.csv');
        $global = glob($path);
        natsort($global);

        foreach ($global as $globalKey => $file) {

            $fileOrigin=1;
            if ( $globalKey == 0 ){
                $fileOrigin= explode('_',basename($file, '.csv'));
                $fileOrigin=((int)end($fileOrigin));
            }

            dispatch(new SapImportJob($file,$insert->id,$fileOrigin,$globalKey));
        }
        // Artisan::call('lbs:fetch-vendors');
        dispatch(new FetchVendorJob());

        return 1;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->importSapCsv();
        return 0;
    }
}
