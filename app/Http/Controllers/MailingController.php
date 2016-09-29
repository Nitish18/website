<?php

namespace App\Http\Controllers;

use App\Mailing; 
use App\NewsLetter;
use Illuminate\Http\Request;
use App\Http\Requests;

/**
 * Class RentalController
 *
 * @package   App\Http\Controllers
 * @author    Tim Joosten <Topairy@gmail.com>
 * @copyright Tim Joosten 2015 - 2016
 * @version   2.0.0
 */
class MailingController extends Controller
{
    /**
     * Auth middleware routes.
     *
     * @var array
     */
    protected $authMiddleware;

    /**
     * MailingController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authMiddleware = [];

        // Middleware
        // $this->middleware('auth');
        $this->middleware('auth')->only($this->authMiddleware);
        $this->middleware('lang');
    }

    /**
     * [BACKEND]: Index overview for the mailing module. 
     * 
     * @url:platform 
     * @see:phpunit
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() 
    {
        $data['newsletter'] = NewsLetter::paginate(25); 
        $data['mailing']    = Mailing::paginate(25);  

        return view('', $data);        
    }

    /**
     * [METHOD]: Delete a mailing record out of the database. 
     * 
     * @param  int $id the id for the email record in the database.  
     * @return \Illuminate\Http\RedirectResponse
     */
    public function MailingDestroy($id) 
    {
        if (Mailing::destroy($id)) {
            session()->flash('class', 'alert alert-success'); 
            session()->flash('message', '');
        }

        return redirect()->back(); 
    }

    /**
     * [METHOD]: Register the email to the newletter module.
     *
     * @url:platform  POST
     * @see:phpunit   TODO: create test when validation fails.
     * @see:phpunit   TODO: create test when validation passes.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerNewsLetter(Request $input)
    {
        // TODO: Create notification to the inserted email address.
        //       In the notification the user must un subscribe his email.

        if (NewsLetter::create($input->except('_token'))) {
            session()->flash('class', 'alert alert-success');
            session()->flash('message', '');
        }

        return redirect()->back(302);
    }


    /**
     * [METHOD]: Delete a email address out of the system.
     *
     * @url:platform GET|HEAD:
     * @see:phpunit
     * @see:phpunit
     *
     * @param  string $string the checksum for the email address.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function DestroyNewsletter($string)
    {
        $data = NewsLetter::where('string', $string);

        if ($data->count() === 1) {
            $data->destroy();

            session()->flash('class', '');
            session()->flash('message', '');
        }

        return redirect()->back();
    }
}
