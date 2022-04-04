<?php

namespace App\Http\Livewire\Mail;


use App\Helpers\PoHelper;
use App\Models\LbsUserSearchSet;
use App\Models\NupAutoSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use rifrocket\LaravelCms\Facades\LaravelCmsFacade;
use rifrocket\LaravelCms\Models\LbsMember;


class ComposeMailComponent extends Component
{

    public function emitNotifications($message, $msgType)
    {
        $this->emit('toast-notification-component', $message, $msgType);
    }

    public $mail_to, $mail_subject, $mail_content, $mailType_pro, $tableType, $mailableData, $importance;



    protected $listeners = ['event-show-compose-email' => 'prepareComposerModal'];

    protected $rules = [
        'mail_to' => 'required',
        'mail_subject' => 'required',
        'mail_content' => 'required',
    ];


    public function prepareComposerModal($mailType, $mail_data, $tableType, $to)
    {

        $this->mail_to = $to;
        $this->mailableData = $mail_data;
        $this->mail_content = null;
        $this->mailType_pro = $mailType;
        $this->tableType = $tableType;
        $this->mail_content = $this->fetchTemplate($mailType, $mail_data);
        $this->emit('set-mail-content', $this->mail_content);
        $this->dispatchBrowserEvent('open-mail-composer');
    }


    public function fetchTemplate($mailType, $mail_data)
    {

        if (empty($mailType)) {
            return "write something...";
        }
        switch ($mailType) {
            case 'enquiry-email':
                $this->mail_subject = PoHelper::NormalizeColString('warning email');
                return $getView = view('mail-templates.enquiry-email', compact('mail_data'))->render();

                break;
            case 'expedite-email':
                $this->mail_subject = PoHelper::NormalizeColString('remider email');
                return $getView = view('mail-templates.expedite-email', compact('mail_data'))->render();
                break;
            case 'warning-email':
                $this->mail_subject = PoHelper::NormalizeColString('warning email');
                return $getView = view('mail-templates.warning-email', compact('mail_data'))->render();
                break;
            case 'penalty-email':
                $this->mail_subject = PoHelper::NormalizeColString('penalty email');
                return $getView = view('mail-templates.penalty-email', compact('mail_data'))->render();
                break;
            default:
                echo "no default template found";
        }
    }



    public function sendEmail()
    {
        switch ($this->mailType_pro) {
            case 'enquiry-email':
               $this->mailType_pro= 'warning-email';
                break;

            case 'expedite-email':
               $this->mailType_pro= 'reminder-email';
                break;

            default:
               $this->mailType_pro= 'reminder-email';
                break;
        }

        $this->validate();

        $mailData = ['msg_content' => $this->mail_content, 'msg_subject' => $this->mail_subject];

        $emails_to = explode(',', $this->mail_to);
        $this->mail_to = $emails_to[0];
        $emails_subject = $this->mail_subject;

        // $autoSetting = NupAutoSetting::where('setting_for_table', LbsUserSearchSet::TEMPLATE_SAP_LINE_ITEM)->first();
        // if ($autoSetting) {
        //     if ($autoSetting->setting_switch == 'quality') {
        //         $emails_to = $autoSetting->test_email;
        //         $this->mail_to = $autoSetting->test_email;
        //     }
        // }

        try {

            Mail::send('livewire.mail.notice-mailable', $mailData, function ($message) use ($emails_to, $emails_subject) {
                $message->to($emails_to)->subject($emails_subject)->bcc('developer.tech.dev@gmail.com');
            });

            if (Mail::failures()) {
                return redirect()->back()->with('error', 'mail sending failed, check log for more details');
            }

            $messageBody = view('livewire.mail.notice-mailable', $mailData)->render();
            $vendorDetails = LbsMember::where('vendor_code', $this->mailableData['vendor_code'])->first();
            $ticket_number = LaravelCmsFacade::lbs_random_generator(16, true, false, true, false);
            $ticket_hash = Hash::make($ticket_number);
            $notifiable =  [
                'broadcast_type' => 'manual',
                'mail_ticket_number' => $ticket_number,
                'mail_ticket_hash' => $ticket_hash,
                'mail_type' => $this->mailType_pro,
                'table_type' => $this->tableType,
                'sender_user_id' => auth()->user()->id,
                'sender_user_model' => "rifrocket\\LaravelCms\\Models\\LbsAdmin",
                'sender_name' => auth()->user()->display_name,
                'sender_email' => auth()->user()->email,
                'recipient_user_id' => $vendorDetails ? $vendorDetails->id : null,
                'recipient_user_model' => "rifrocket\\LaravelCms\\Models\\LbsMember",
                'recipient_name' => $vendorDetails ? $vendorDetails->display_name : null,
                'recipient_email' => $this->mail_to,
                'msg_subject' => $this->mail_subject,
                'msg_body' => $messageBody,
                'execute_at_date' => Carbon::now()->format('Y-m-d'),
                'execute_at_time' => Carbon::now()->format('h:m'),
                'last_executed_at' => Carbon::now(),
                'importance' => $this->importance,
                'meta' => null,
                'json_data' => null,
            ];
            $this->mailableData['mail_objects']['importance'] = $this->importance;

            PoHelper::SaveNotificationHistory($notifiable, $this->mailableData);

            $this->clearData();

            $this->dispatchBrowserEvent('close-mail-composer');
            return $this->emitNotifications('mail send successfully', 'success');
        } catch (\Throwable $exception) {
            return $this->emitNotifications($exception->getMessage(), 'error');
        }
    }

    public function clearData()
    {
        $this->mail_to = null;
        $this->mail_subject = null;
        $this->mail_content = null;
    }


    public function render()
    {
        return view('livewire.mail.compose-mail-component');
    }
}
