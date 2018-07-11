<?php

namespace App;

use Bnb\Laravel\Attachments\HasAttachment;
use Cmgmyr\Messenger\Models\Thread as MessengerThread;
use Illuminate\Database\Eloquent\Model;

class MyThread extends MessengerThread
{
    
    use HasAttachment;
    
}
