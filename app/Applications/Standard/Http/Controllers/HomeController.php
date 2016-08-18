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

	public function __construct()
	{}

	//
	public function home()
	{
		return $this->dashboard();
	}

	//
	public function dashboard()
	{
		$this->defData['pageTitle'] = ucfirst(trans('dashboard'));
		$this->defData['page']      = trans('dashboard');
		$this->defData['subPage']   = '';

		return $this->view('pages.dashboard', $this->defData);
	}

	//
	public function users()
	{
		$this->defData['pageTitle'] = ucfirst(trans('users'));
		$this->defData['page']      = trans('users');
		$this->defData['subPage']   = trans('list');

		return $this->view('pages.users.list', $this->defData);
	}

	//
	public function translations()
	{
		$this->defData['pageTitle'] = ucfirst(trans('translations'));
		$this->defData['page']      = trans('translations');
		$this->defData['subPage']   = trans('list');

		return $this->view('pages.translations.list', $this->defData);
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