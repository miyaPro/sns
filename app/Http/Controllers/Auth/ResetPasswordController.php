<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\PasswordResetRepository;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $repUser;
    protected $repPass;
    protected $resetEmail;
    public function __construct(
        UserRepository $user,
        PasswordResetRepository $passReset
    )
    {
        $this->repUser      = $user;
        $this->repPass      = $passReset;
//        $this->middleware('auth', ['except' => ['sendMail', 'show']]);

    }

    public function show()
    {
        return view('auth.passwords.email');
    }

    public function sendMail (Request $request)
    {
//        $this->validate($request, $this->rules());
        $user  = $this->repUser->getOneByField('email', $request->get('email'));
        if($user){
            $this->resetEmail = $request->get('email');
            $token = $request->get('_token');
            $input = [
                'email'     => $this->resetEmail,
                'token'     => $token
            ];
            $this->repPass->store($input);
                                            /*---send mail reset password---*/
            $linkReset = URL::to('/').'/password/reset/'.$token;
            Mail::send('emails.send',
                ['link' => $linkReset],
                function($message) {
                    if($this->resetEmail) {
                        $message->to($this->resetEmail)
                            ->subject('Yêu cầu đặt lại mật khẩu');
                    }
                });

                                                    /*---end---*/
            return redirect()->back()->with('alert-success', trans('message.reset_email_success', ['name' => trans('default.user')]));
        } else {
            return redirect()->back()->with('alert-danger', trans('message.reset_email_error', ['name' => trans('default.user')]));
        }

    }


}
