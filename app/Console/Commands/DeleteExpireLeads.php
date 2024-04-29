<?php

namespace App\Console\Commands;

use App\Http\Models\Leads;
use App\Http\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteExpireLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will delete the leads after one day whoes status is urgent';

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
        $lead_types = Leads::where('is_urgent','Y')->where('is_deleted','N')->get();
       $khan =[];
         foreach ($lead_types as $lead)
         {
             $current_date= Carbon::now();
             $lead_expire_date = Carbon::create( $lead->created_at)->addDays(1);
             if($lead_expire_date < $current_date)
             {
                 $this->info("Leads expired");
                  $lead->is_deleted ="Y";
                     $notification = new Notification();
                     $notification->user_id = $lead->user_id;
                     $notification->type_id = $lead->id;
                     $notification->schedule_date = \Carbon\Carbon::now();
                     $notification->is_msg_app = 'Y';
                     $notification->notification_type = 'Lead';
                     $notification->title = 'Lead Expired';
                     $notification->description = 'Dear! Your lead'.$lead->product_name. 'has been Expired';
                     $notification->save();
                     //    $this->send_comm_app_notification();
             }
         }
    }
}
