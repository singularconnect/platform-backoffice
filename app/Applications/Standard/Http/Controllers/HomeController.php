<?php

namespace App\Applications\Standard\Http\Controllers;


use App\Applications\Standard\Exceptions\StandardValidationException;
use App\Domains\Repositories\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Validator;

class HomeController extends BaseController
{
	protected $defData = [
		'appTitle' => "Backoffice SingularBet",
		'language' => 'pt-BR',

		'pageTitle' => '',
		'page' => '',
		'subPage' => '',
	];

	public function __construct()
	{}

	//
	public function home()
	{
		return "mah, mah, maaaaHh oe!";
	}

	//
	public function dashboard()
	{
		$this->defData['pageTitle'] = 'Dashboard';
		$this->defData['page']      = 'dashboard';
		$this->defData['subPage']   = '';

		return $this->view('pages.dashboard', $this->defData);
	}

	//
	public function users()
	{
		$this->defData['pageTitle'] = 'Users';
		$this->defData['page']      = 'users';
		$this->defData['subPage']   = '';

		return $this->view('pages.users.list', $this->defData);
	}

	//
	public function signin()
	{
		return $this->view('pages.signin', $this->defData);
	}

	//
	public function signout()
	{
		Auth::logout();
		return redirect()->route('signin');
	}

	//
	public function postSignin(Request $request, UsersRepository $usersRepository)
	{
		/** @var Validator $validator */
		$validator = $usersRepository->validator($request->input(), 'login');

		if( $validator->fails() )
			return redirect()->route('signin')->withInput()->withErrors($validator);

		$data = $validator->getData();
		$remember = false;

		if( isset($data['remember']) ) {
			$remember = $data['remember'];
			unset($data['remember']);
		}

		if( Auth::attempt($data, $remember) )
			return redirect()->route('dashboard');

		Session::flash('error', 'E-mail or Password incorrect');
		return redirect()->route('signin');
	}
}