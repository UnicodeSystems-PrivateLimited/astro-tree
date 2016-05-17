<?php

//namespace LaravelAcl\Authentication\Controllers;

namespace App\Http\Controllers;

/**
 * Class UserController
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
use App\Models\Client;
use App\Models\Relation;
use App\Models\RelationType;
use App\Models\AstroProfile;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;
use LaravelAcl\Authentication\Exceptions\PermissionException;
use LaravelAcl\Authentication\Exceptions\ProfileNotFoundException;
use LaravelAcl\Authentication\Helpers\DbHelper;
use LaravelAcl\Authentication\Models\UserProfile;
use LaravelAcl\Authentication\Presenters\UserPresenter;
use LaravelAcl\Authentication\Services\UserProfileService;
use LaravelAcl\Authentication\Validators\UserProfileAvatarValidator;
use LaravelAcl\Library\Exceptions\NotFoundException;
use LaravelAcl\Authentication\Models\User;
use LaravelAcl\Authentication\Helpers\FormHelper;
use LaravelAcl\Authentication\Exceptions\UserNotFoundException;
use LaravelAcl\Authentication\Validators\UserValidator;
use LaravelAcl\Library\Exceptions\JacopoExceptionsInterface;
use LaravelAcl\Authentication\Validators\UserProfileValidator;
use View,
    URL,
    Redirect,
    App,
    DB,
    Config;
use LaravelAcl\Authentication\Interfaces\AuthenticateInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientController extends Controller {

    const CLIENT_GROUP_ID = 4;

    /**
     * @var \LaravelAcl\Authentication\Repository\SentryUserRepository
     */
    protected $user_repository;
    protected $user_validator;

    /**
     * @var \LaravelAcl\Authentication\Helpers\FormHelper
     */
    protected $form_helper;
    protected $profile_repository;
    protected $profile_validator;

    /**
     * @var use LaravelAcl\Authentication\Interfaces\AuthenticateInterface;
     */
    protected $auth;
    protected $register_service;
    protected $custom_profile_repository;
    private $user_table_name = "users";
    private $user_groups_table_name = "users_groups";
    private $groups_table_name = "groups";
    private $profile_table_name = "user_profile";
    private $astro_profile_table_name = "astro_profile";

    public function __construct(UserValidator $v, FormHelper $fh, UserProfileValidator $vp, AuthenticateInterface $auth) {
        $this->user_repository = App::make('user_repository');
        $this->user_validator = $v;
        $this->f = App::make('form_model', [$this->user_validator, $this->user_repository]);
        $this->form_helper = $fh;
        $this->profile_validator = $vp;
        $this->profile_repository = App::make('profile_repository');
        $this->auth = $auth;
        $this->register_service = App::make('register_service');
        $this->custom_profile_repository = App::make('custom_profile_repository');
    }

    public function showDetails(Request $request) {
        try {
//            print_r($request->cid);exit;
            $user = $this->user_repository->find($request->cid);
            $user_id = $user->id;
            try {
                $user_profile = $this->profile_repository->getFromUserId($user_id);
                $astroProfile = AstroProfile::where('user_id', $user_id)->firstOrFail();
                $clients = $this->user_repository->all(['group_id' => self::CLIENT_GROUP_ID]);
                $relTypes = RelationType::where('status', 1)->get();
//                print_r($astroProfile);
            } catch (UserNotFoundException $e) {
                return Redirect::route('client.list')
                                ->withErrors(new MessageBag(['model' => Config::get('acl_messages.flash.error.user_user_not_found')]));
            } catch (ProfileNotFoundException $e) {
                $user_profile = new UserProfile(["user_id" => $user_id]);
            }
        } catch (JacopoExceptionsInterface $e) {
            //$user = new User;
            echo 'User not found!';
        }
        return View::make('admin.client.details')->with(["user" => $user, "profile" => $user_profile, 'astroProfile' => $astroProfile, 'clients' => $clients, 'relTypes' => $relTypes]);
    }

    public function getList(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $query = $this->createClientQuery(DB::raw("`$this->user_table_name`.`id`, `$this->user_table_name`.`email` , `$this->user_table_name`.`activated`, `$this->astro_profile_table_name`.`dob`, `$this->profile_table_name`.`phone`, `$this->profile_table_name`.`first_name`, `$this->profile_table_name`.`last_name`,`$this->profile_table_name`.`avatar`"));
            $clients = $query->where($this->groups_table_name . '.id', self::CLIENT_GROUP_ID)->get();
            $data = ['data' => []];
            foreach ($clients as $client) {
                $name = $client->first_name . (isset($client->last_name) ? " $client->last_name" : "");
                $nameLink = '<a href="' . URL::to('admin/client/' . $client->id . '/details') . '">' . $name . '</a>';
                if (!isset($client->avatar)) {
                    $imgSrc = URL::to('public/packages/jacopo/laravel-authentication-acl/images/avatar.png');
                } else {
                    $imgBase64String = base64_encode($client->avatar);
                    $imgSrc = 'data: image/png;base64,' . $imgBase64String;
                }
                $image = '<img class="img-responsive img-circle" src="' . $imgSrc . '" alt="' . $name . '" />';
                $activated = ($client->activated == 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>');
                $data['data'][] = [$image, $nameLink, $client->email, $client->phone, date('d M, Y', strtotime($client->dob)), $activated, '<label class="table-edit-trash"><a href="#" data-toggle="modal" data-target="#add-cat"><span class="fa fa-edit"></span></a> <a href="#"><span class="fa fa-trash-o"></span></a></label>'];
            }
            return $data;
        } else {
            return View::make('admin.client.list')->with(["request" => $request]);
        }
    }

    public function editUser(Request $request) {
        try {
            $user = $this->user_repository->find($request->get('id'));
        } catch (JacopoExceptionsInterface $e) {
            $user = new User;
        }
        $presenter = new UserPresenter($user);
        $clients = $this->user_repository->all(['group_id' => self::CLIENT_GROUP_ID]);
        $relTypes = RelationType::where('status', 1)->get();
        return View::make('admin.client.edit')->with(["user" => $user, "presenter" => $presenter, 'clients' => $clients, 'relTypes' => $relTypes]);
    }

    public function postEditUser(Request $request) {
        $id = $request->get('id');
        $clientGroupId = 4;
        DbHelper::startTransaction();
        try {
            $exParam = [
                'activated' => 1,
                'banned' => 0
            ];
            $nameParts = explode(' ', $request->get('name'), 2);
            $first_name = $nameParts[0];
            if (count($nameParts) > 1) {
                $last_name = $nameParts[1];
            }
//            echo "Date:$bDate";exit;
            $request->request->add($exParam);
            $user = $this->f->process($request->all());
            $this->user_repository->addGroup($user->id, $clientGroupId);
            $profile_repo = $this->profile_repository->attachEmptyProfile($user);
            $profile_repo->first_name = $first_name;
            if (isset($last_name)) {
                $profile_repo->last_name = $last_name;
            }
            if ($request->has('phone')) {
                $profile_repo->phone = $request->get('phone');
            }
            if ($request->has('addr_street')) {
                $profile_repo->address = $request->get('addr_street');
            }
            if ($request->has('addr_state')) {
                $profile_repo->state = $request->get('addr_state');
            }
            if ($request->has('addr_city')) {
                $profile_repo->city = $request->get('addr_city');
            }
            if ($request->has('addr_country')) {
                $profile_repo->country = $request->get('addr_country');
            }
            if ($request->has('pincode')) {
                $profile_repo->zip = $request->get('pincode');
            }
            $profile_repo->code = $user->id;
            $profile_repo->save();
            //creating astro profile
            $astroParams = [
                'user_id' => $user->id
            ];
            if (isset($bDate)) {
                $astroParams['dob'] = $bDate;
            }
            if ($request->has('b_place')) {
                $astroParams['b_place'] = $request->get('b_place');
            }
            if ($request->has('sex')) {
                $astroParams['sex'] = $request->get('sex');
            }
            if ($request->has('b_date') && $request->has('b_time')) {
                $bDate = Carbon::createFromFormat('d/m/Y G:i A', $request->get('b_date') . ' ' . $request->get('b_time'));
            }
            $astro_profile = AstroProfile::create($astroParams);
            if ($request->has('rel_person') && $request->has('rel_type')) {
                $relParams = [
                    'user_id' => $user->id,
                    'rel_user_id' => $request->get('rel_person'),
                    'rel_type_id' => $request->get('rel_type'),
                    'is_referrer'=> TRUE
                ];
                Relation::create($relParams);
            }
        } catch (JacopoExceptionsInterface $e) {
            DbHelper::rollback();
            $errors = $this->f->getErrors();
            // passing the id incase fails editing an already existing item
            return Redirect::route("client.add", $id ? ["id" => $id] : [])->withInput()->withErrors($errors);
        }

        DbHelper::commit();

        return Redirect::route('client.list')
                        ->withMessage('Client added successfully!');
    }

    public function deleteUser(Request $request) {
        try {
            $this->f->delete($request->all());
        } catch (JacopoExceptionsInterface $e) {
            $errors = $this->f->getErrors();
            return Redirect::route('users.list')->withErrors($errors);
        }
        return Redirect::route('users.list')
                        ->withMessage(Config::get('acl_messages.flash.success.user_delete_success'));
    }

    public function addGroup(Request $request) {
        $user_id = $request->get('id');
        $group_id = $request->get('group_id');

        try {
            $this->user_repository->addGroup($user_id, $group_id);
        } catch (JacopoExceptionsInterface $e) {
            return Redirect::route('users.edit', ["id" => $user_id])
                            ->withErrors(new MessageBag(["name" => Config::get('acl_messages.flash.error.user_group_not_found')]));
        }
        return Redirect::route('users.edit', ["id" => $user_id])
                        ->withMessage(Config::get('acl_messages.flash.success.user_group_add_success'));
    }

    public function deleteGroup(Request $request) {
        $user_id = $request->get('id');
        $group_id = $request->get('group_id');

        try {
            $this->user_repository->removeGroup($user_id, $group_id);
        } catch (JacopoExceptionsInterface $e) {
            return Redirect::route('users.edit', ["id" => $user_id])
                            ->withErrors(new MessageBag(["name" => Config::get('acl_messages.flash.error.user_group_not_found')]));
        }
        return Redirect::route('users.edit', ["id" => $user_id])
                        ->withMessage(Config::get('acl_messages.flash.success.user_group_delete_success'));
    }

    public function editPermission(Request $request) {
        // prepare input
        $input = $request->all();
        $operation = $request->get('operation');
        $this->form_helper->prepareSentryPermissionInput($input, $operation);
        $id = $request->get('id');

        try {
            $obj = $this->user_repository->update($id, $input);
        } catch (JacopoExceptionsInterface $e) {
            return Redirect::route("users.edit")->withInput()
                            ->withErrors(new MessageBag(["permissions" => Config::get('acl_messages.flash.error.user_permission_not_found')]));
        }
        return Redirect::route('users.edit', ["id" => $obj->id])
                        ->withMessage(Config::get('acl_messages.flash.success.user_permission_add_success'));
    }

    public function editProfile(Request $request) {
        $user_id = $request->get('user_id');

        try {
            $user_profile = $this->profile_repository->getFromUserId($user_id);
        } catch (UserNotFoundException $e) {
            return Redirect::route('users.list')
                            ->withErrors(new MessageBag(['model' => Config::get('acl_messages.flash.error.user_user_not_found')]));
        } catch (ProfileNotFoundException $e) {
            $user_profile = new UserProfile(["user_id" => $user_id]);
        }
        $custom_profile_repo = App::make('custom_profile_repository', [$user_profile->id]);

        return View::make('laravel-authentication-acl::admin.user.profile')->with([
                    'user_profile' => $user_profile,
                    "custom_profile" => $custom_profile_repo
        ]);
    }

    public function postEditProfile(Request $request) {
        $input = $request->all();
        $service = new UserProfileService($this->profile_validator);

        try {
            $service->processForm($input);
        } catch (JacopoExceptionsInterface $e) {
            $errors = $service->getErrors();
            return Redirect::back()
                            ->withInput()
                            ->withErrors($errors);
        }
        return Redirect::back()
                        ->withInput()
                        ->withMessage(Config::get('acl_messages.flash.success.user_profile_edit_success'));
    }

    public function editOwnProfile(Request $request) {
        $logged_user = $this->auth->getLoggedUser();

        $custom_profile_repo = App::make('custom_profile_repository', [$logged_user->user_profile()->first()->id]);

        return View::make('laravel-authentication-acl::admin.user.self-profile')
                        ->with([
                            "user_profile" => $logged_user->user_profile()
                            ->first(),
                            "custom_profile" => $custom_profile_repo
        ]);
    }

    public function signup(Request $request) {
        $enable_captcha = Config::get('acl_base.captcha_signup');

        if ($enable_captcha) {
            $captcha = App::make('captcha_validator');
            return View::make('laravel-authentication-acl::client.auth.signup')->with('captcha', $captcha);
        }

        return View::make('laravel-authentication-acl::client.auth.signup');
    }

    public function postSignup(Request $request) {
        $service = App::make('register_service');

        try {
            $service->register($request->all());
        } catch (JacopoExceptionsInterface $e) {
            return Redirect::route('user.signup')->withErrors($service->getErrors())->withInput();
        }

        return Redirect::route("user.signup-success");
    }

    public function signupSuccess(Request $request) {
        $email_confirmation_enabled = Config::get('acl_base.email_confirmation');
        return $email_confirmation_enabled ? View::make('laravel-authentication-acl::client.auth.signup-email-confirmation') : View::make('laravel-authentication-acl::client.auth.signup-success');
    }

    public function emailConfirmation(Request $request) {
        $email = $request->get('email');
        $token = $request->get('token');

        try {
            $this->register_service->checkUserActivationCode($email, $token);
        } catch (JacopoExceptionsInterface $e) {
            return View::make('laravel-authentication-acl::client.auth.email-confirmation')->withErrors($this->register_service->getErrors());
        }
        return View::make('laravel-authentication-acl::client.auth.email-confirmation');
    }

    public function addCustomFieldType(Request $request) {
        $description = $request->get('description');
        $user_id = $request->get('user_id');

        try {
            $this->custom_profile_repository->addNewType($description);
        } catch (PermissionException $e) {
            return Redirect::route('users.profile.edit', ["user_id" => $user_id])
                            ->withErrors(new MessageBag(["model" => $e->getMessage()]));
        }

        return Redirect::route('users.profile.edit', ["user_id" => $user_id])
                        ->with('message', Config::get('acl_messages.flash.success.custom_field_added'));
    }

    public function deleteCustomFieldType(Request $request) {
        $id = $request->get('id');
        $user_id = $request->get('user_id');

        try {
            $this->custom_profile_repository->deleteType($id);
        } catch (ModelNotFoundException $e) {
            return Redirect::route('users.profile.edit', ["user_id" => $user_id])
                            ->withErrors(new MessageBag(["model" => Config::get('acl_messages.flash.error.custom_field_not_found')]));
        } catch (PermissionException $e) {
            return Redirect::route('users.profile.edit', ["user_id" => $user_id])
                            ->withErrors(new MessageBag(["model" => $e->getMessage()]));
        }

        return Redirect::route('users.profile.edit', ["user_id" => $user_id])
                        ->with('message', Config::get('acl_messages.flash.success.custom_field_removed'));
    }

    public function changeAvatar(Request $request) {
        $user_id = $request->get('user_id');
        $profile_id = $request->get('user_profile_id');

        // validate input
        $validator = new UserProfileAvatarValidator();
        if (!$validator->validate($request->all())) {
            return Redirect::route('users.profile.edit', ['user_id' => $user_id])
                            ->withInput()->withErrors($validator->getErrors());
        }

        // change picture
        try {
            $this->profile_repository->updateAvatar($profile_id);
        } catch (NotFoundException $e) {
            return Redirect::route('users.profile.edit', ['user_id' => $user_id])->withInput()
                            ->withErrors(new MessageBag(['avatar' => Config::get('acl_messages.flash.error.')]));
        }

        return Redirect::route('users.profile.edit', ['user_id' => $user_id])
                        ->withMessage(Config::get('acl_messages.flash.success.avatar_edit_success'));
    }

    public function refreshCaptcha() {
        return View::make('laravel-authentication-acl::client.auth.captcha-image')
                        ->with(['captcha' => App::make('captcha_validator')]);
    }

    public function addAstroProfile(Request $req) {
        try {
            $allPlaces = $req->all();
            $planets = [];
            foreach ($allPlaces as $place => $planet) {
                if (strpos($place, 'place_') !== FALSE) {
                    $ix = substr($place, strpos($place, '_') + 1);
                    $planets[intval($ix)] = $planet;
                }
            }
            $planetJson = json_encode($planets);
            $astroProfile = AstroProfile::where('user_id', $req->cid)->firstOrFail();
            $astroProfile->planets = $planetJson;
            $astroProfile->manglik = $req->get('manglik');
            $astroProfile->save();
        } catch (Exception $e) {
            return '{"success":"false","msg":"An error occurred while saving astro data!"}';
        }
        return '{"success":"true","msg":"Astro details saved successfully!"}';
    }

    private function createClientQuery($select = null) {
        $q = DB::table($this->user_table_name);
        if (NULL != $select) {
            $q->select($select);
        }
        $q->leftJoin($this->profile_table_name, $this->user_table_name . '.id', '=', $this->profile_table_name . '.user_id')
                ->leftJoin($this->astro_profile_table_name, $this->user_table_name . '.id', '=', $this->astro_profile_table_name . '.user_id')
                ->leftJoin($this->user_groups_table_name, $this->user_table_name . '.id', '=', $this->user_groups_table_name . '.user_id')
                ->leftJoin($this->groups_table_name, $this->user_groups_table_name . '.group_id', '=', $this->groups_table_name . '.id');
        return $q;
    }

}
