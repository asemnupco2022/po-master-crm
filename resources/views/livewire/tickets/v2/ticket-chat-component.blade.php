@push('styles')
    <style>
        .direct-chat-messages {
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
            height: 351px !important;
            overflow: auto;
            padding: 10px;
        }

        .chat_template .direct-chat-text {
            width: 45%;
            border-radius: 20px;
        }

        .direct-chat-primary .right > .direct-chat-text {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
            float: right;
        }

        .chat_template .direct-chat-messages {
            padding: 24px;
        }

        .right .direct-chat-text {
            margin-left: 0;
            margin-right: 10px !important;
        }

        .predefine_msg li {
            display: inline-block;
            font-size: 16px;
            line-height: 1.25;
            border-radius: 13px;
            border: 1px solid rgba(20, 118, 242, .2);
            padding: 5px 20px;
        }

        .predefine_msg ul {
            list-style: none;
            margin-left: 0px !important;
            padding-left: 20px;
        }

        .predefine_msg li:hover {
            background: #f3f3f3;
        }

        .chat_send_btn input.form-control {
            border-radius: 20px;
            padding: 20px;
        }

        .chat_send_btn button.btn.btn-primary {
            padding: 2px 18px;
            border-radius: 0px 20px 20px 0px;
            font-size: 20px;
        }

        .upload_file_chat {
            overflow: hidden;
        }

        .upload_file_chat .btn.btn-file {
            overflow: hidden;
            position: relative;
            padding: 8px;
            border: none;
        }

        .upload_file_chat .btn.btn-default.btn-file {
            background: #fff !important;
        }

        .chat_send_btn input.form-control {
            border-right: 0px !important;
        }

        .upload_file_chat .btn.btn-default.btn-file {
            background: #fff !important;
            border: 1px solid #ced4da;
            border-left: 0px;
            border-radius: 0px !important;
        }

        .file_name {
            padding: 0px 30px;
        }

        .badge {
            display: inline-block;
            padding: .25em .4em;
            font-size: 75%;
            font-weight: 200 !important;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
        .chat_batch span.right.badge.badge-danger {
            position: absolute;
            top: 10px;
            right: 0;
        }
        .chat_batch li.nav-item {
            position: relative;
        }
        .chat_batch span.right.badge.badge-success {
            position: absolute;
            top: 10px;
            right: 80px !important;
        }

        .active {
    background-color: #e375261c;

}

    </style>
@endpush
<div>
<div class="row chat_row">
    <div class="col-md-12">
        <div class="card  card-outline">
            <div class="card-header">
                <h3 class="card-title">Notification Type :
                    @if($notificationHistory)
                        @if($notificationHistory->message_type =='enquiry-email')
                            <span class="right badge" style="background: #17a2b7; color: #fff; margin-left: 10px;">Enquiry Email</span>
                        @elseif($notificationHistory->message_type =='expedite-email')
                            <span class="right badge" style="background: #27a844; color: #fff; margin-left: 10px;">Expedite Email</span>
                        @elseif($notificationHistory->message_type =='warning-email')
                            <span class="right badge" style="background: #e37526; color: #fff; margin-left: 10px;">Warning Email</span>
                        @elseif($notificationHistory->message_type =='reminder-email')
                            <span class="right badge" style="background: #17a2b8; color: #fff; margin-left: 10px;">Warning Email</span>
                        @elseif($notificationHistory->message_type =='penalty-email')
                            <span class="right badge" style="background: #fec107; color: #fff; margin-left: 10px;">Penalty Email</span>
                        @endif
                    @endif
                </h3>

                @if($notificationHistory)
                <h3 class="card-title pl-2">vendor #:
                 {{$notificationHistory->has_vendor->vendor_code}} | {{$notificationHistory->has_vendor->display_name}}
                </h3>
                @endif
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<div class="row">

        <div class="col-md-4">
        <div class="card card-primary chat_sidebar">
            <div class="card-header">
            </div>
            <div class="">
                <div class="card-body p-2 ">

                    <table class="table">
                        <tr>
                            <td>Email Type: </td>
                            <td>{{\App\Helpers\PoHelper::NormalizeColString($notificationHistory->message_type)}}</td>
                        </tr>
                        <tr>
                            <td>PO Number: </td>
                            <td>{{$notificationHistory->po_num}}</td>
                        </tr>
                            <td>PO Item: </td>
                            <td>{{$notificationHistory->po_item_num}}</td>
                        </tr>
                            <td>Matrial Description: </td>
                            <td>{{$notificationHistory->item_desc}}</td>
                        </tr>
                            <td>Matrial Code:  </td>
                            <td>{{$notificationHistory->mat_num}}</td>
                        </tr>
                        </tr>
                            <td>Delivery Date:  </td>
                            <td>{{$notificationHistory->delivery_date}}</td>
                        </tr>
                            <td>Customer:  </td>
                            <td>{{$notificationHistory->customer_name}}</td>
                        </tr>

                    </table>

                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.card -->
    </div>

    <!-- /.col -->
    <div class="col-md-8">
        <div class="card direct-chat direct-chat-primary">

            <!-- /.card-header -->
            <div class="card-body">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages">
                    <!-- Message. Default to the left -->

                @if($collections)
                    @foreach($collections as $collection)

                        <!-- Message. Default to the left -->
                            <div class="direct-chat-msg {{$collection->msg_sender_id == 'staff' ? 'right':''}}">
                                <div class="direct-chat-infos clearfix">
                                    <span
                                        class="direct-chat-name {{$collection->msg_sender_id == 'staff' ? 'float-right':'float-left'}} ">{{$collection->name}}</span>
                                    <span
                                        class="direct-chat-timestamp  {{$collection->msg_sender_id == 'staff' ? 'float-left':'float-right'}} ">{{\Carbon\Carbon::parse($collection->created_at)->format('Y-M-d H:i:s')}}</span>
                                </div>
                                <!-- /.direct-chat-infos -->
                                <img class="direct-chat-img"
                                     src="{{URL('')}}/{{$collection->msg_sender_id == 'staff' ?$collection->userdata->avatar : $collection->VendorData->avatar}}"
                                     alt="{{$collection->msg_sender_id == 'staff' ? $collection->staff_name : $collection->vendor_name }}">
                                <!-- /.direct-chat-img -->
                                <div class="direct-chat-text">

                                    @php
                                        if($collection->msg_sender_id == 'staff'){
                                             echo($collection->msg_body) ;
                                              echo '<br><br>';
                                             if($collection->attachment){
                                                echo '<a class="text-white" href="'.URL($collection->attachment).'" download ><i
                                            class="fas fa-file-alt fa-2x  "></i>  '.$collection->attachment_name.'</a>';
                                           }

                                        }else{
                                                echo '<ul>';
                                            $jsonCheck = json_decode( $collection->msg_body, true);
                                            if ($jsonCheck and is_array($jsonCheck)) {
                                                foreach (json_decode( $collection->msg_body, true) as $comment){
                                                    echo '<li>'.$comment.'</li>';
                                                }
                                        }else{
                                            echo '<li>'.$collection->msg_body.'</li>';
                                        }

                                           if($collection->json_data){
                                                echo '<li>'.$collection->json_data.'</li>';
                                           }

                                           echo '</ul>';
                                           if($collection->attachment){
                                                echo '<a href="'.$collection->attachment.'" download  target="_blank" ><i
                                            class="fas fa-file-alt fa-2x"></i>  '.$collection->attachment_name.'</a>';
                                           }

                                        }
                                    @endphp
{{--                                    {!! \App\Helpers\PoHelper::NormalizeColString(json_decode( $collection->msg_body, true) ) !!}--}}
                                </div>
                            <!-- /.direct-chat-text -->
                            </div>
                            <!-- /.direct-chat-msg -->
                        @endforeach
                    @else
                        <p class="text-center">no comment found</p>
                    @endif

                </div>
                <!--/.direct-chat-messages-->
            </div>
            <div class="file_name">
                <div class="row">
                    @if($attachmentName)
                        <br>
                        <div class="col">
                            <i class="fas fa-file-alt fa-2x"></i>
                            <span class="error">{{$attachmentName}}</span>
                        </div>
                    @endif
                </div>
            </div>

        @if($collections)
            <!-- /.card-body -->
            @if(auth()->user()->hasAnyPermission(['who_can-reply_notification']))
            <div class="card-footer">
                <div class="input-group chat_send_btn">

                    <input type="text" name="message" placeholder="Type Message ..." class="form-control"
                           wire:model.defer="msg_body">
                    <div class="upload_file_chat">
                        <div class="btn btn-default btn-file">
                            <i class="fas fa-paperclip"></i>
                            <input type="file" name="attachment" wire:model="attachment">
                        </div>
                    </div>
                    <span class="input-group-append ">
                      <button type="button" class="btn btn-primary" wire:click="saveComment"><i
                              class="fas fa-paper-plane"></i></button>
                    </span>

                </div>
                <br>
                @error('msg_body') <span class="error">{{ $message }}</span> @enderror
                @error('attachment') <span class="error">{{ $message }}</span> @enderror

            </div>
            @endif
            <!-- /.card-footer-->
        @endif
        </div>
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

        <!-- loader -->
        <div class="loading" wire:loading>
            <div class='uil-ring-css' style='transform:scale(0.79);'>
                <div></div>
            </div>
        </div>
        <!-- loader -->

    <script>
        window.addEventListener('load', event => {
            $('.direct-chat-messages').animate({scrollTop: document.body.scrollHeight},"fast");
        })

        window.addEventListener('scroll-down-chat', event => {

            $('.direct-chat-messages').animate({scrollTop: document.body.scrollHeight},"fast");
        })
    </script>
</div>
