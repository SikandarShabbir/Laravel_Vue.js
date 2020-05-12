<?php 

namespace App\Helpers;
use App\Models\Admin\Site;
use App\User;
use Config;
use Illuminate\Support\Facades\DB;

class MailConfigurations
{
    
    public static function SetMailConfigurations($site_id){
        
        if (\Schema::hasTable('general_settings')) {
            $mail = DB::table('general_settings')->where('setting_fk_site_id','=',$site_id)->get();
            $mail = $mail[0];
            if ($mail) //checking if table is not empty
            {
                $config = array(
                    'driver'     => 'smtp',
                    'port'       => '587',
                    'host'       => $mail->setting_mail_host,
                    'username'   => $mail->setting_mail_username,
                    'password'   => $mail->setting_mail_password,
                    'encryption' => $mail->setting_mail_encryption,
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                    'pretend'    => false,
                );
                Config::set('mail', $config);
                return $site_id;
            }
        }
        
    }
}