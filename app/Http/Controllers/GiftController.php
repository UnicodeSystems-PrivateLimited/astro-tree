<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Gift;
use App\Models\GiftCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag;
use LaravelAcl\Authentication\Exceptions\PermissionException;
use View,
    Redirect,
    App,
    URL,
    Config;
use LaravelAcl\Authentication\Interfaces\AuthenticateInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GiftController extends Controller {

    protected $user_repository;

    public function __construct() {
        $this->user_repository = App::make('user_repository');
    }

    public function getList(Request $request) {
        if ($request->isXmlHttpRequest()) {
            if ($request->has('cid')) {
                $gifts = Gift::where('user_id', $request->get('cid'))->get();
            } else {
                $gifts = Gift::all();
            }
            $data = ['data' => []];
            foreach ($gifts as $gift) {
                $cat = $gift->category;
                $user = $gift->client->user_profile->first();
                $data['data'][] = [$gift->name, $user->first_name, $gift->created_at->format('d M, Y'), $cat->name, '<label class="table-edit-trash"><a href="#" data-toggle="modal" data-target="#add-cat"><span class="fa fa-edit"></span></a> <a href="#"><span class="fa fa-trash-o"></span></a></label>'];
            }
            return $data;
        } else {
            $giftCats = GiftCategory::where('status', 1)->get();
            $clients = $this->user_repository->all(['group_id' => 4]);
            return View::make('admin.gift.list')->with(['giftCats' => $giftCats, 'clients' => $clients]);
        }
    }

    public function addGift(Request $req) {
        try {
            $giftData = [
                'name' => $req->get('name'),
                'description' => $req->get('description'),
                'user_id' => $req->get('user_id'),
                'cat_id' => $req->get('cat_id'),
            ];
            $gift = Gift::create($giftData);
        } catch (Exception $e) {
            return $e->getTraceAsString();
        }
        return '{"success":"true","message":"Gift saved successfully!"}'; //Redirect::route('gift.list')->withMessage('Gift saved successfully!');
    }

    public function deleteGift(Request $req) {
        
    }

    public function getDetail(Request $req) {
        
    }

    /*     * *******Gift Category Master************************ */

    public function getCategoryList(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $cats = GiftCategory::all();
            $data = ['data' => []];
            foreach ($cats as $cat) {
                $data['data'][] = [$cat->name, $cat->description, (($cat->status == 0) ? '<span class="label label-danger">Inactive</span>' : '<span class="label label-success">Active</span>'), $cat->created_at->format('d M, Y'), '<label class="table-edit-trash"><a href="' . URL::route('gift.category.view', ['id' => $cat->id]) . '" class="dev-edit-rec" data-recId="' . $cat->id . '"><span class="fa fa-edit"></span></a> <a href="' . URL::route('gift.category.delete', ['id' => $cat->id]) . '" class="dev-del-rec" data-recId="' . $cat->id . '"><span class="fa fa-trash-o"></span></a></label>'];
//                $data['data'][] = [$cat->name, $cat->description, (($cat->status == 0) ? '<span class="label label-danger">Inactive</span>' : '<span class="label label-success">Active</span>'), $cat->created_at->format('d M, Y'), '<label class="table-edit-trash"><a href="#" onclick="editCat('.$cat->id.')><span class="fa fa-edit"></span></a> <a href="#" onclick="deleteCat('.$cat->id.')"><span class="fa fa-trash-o"></span></a></label>'];
            }
            return $data;
        } else {
            return View::make('admin.gift.category.list');
        }
    }

    public function addCategory(Request $req) {
        try {
            $catData = [
                'name' => $req->get('name'),
                'description' => $req->get('description'),
                'status' => $req->get('status', 0)
            ];
            if ($req->has('id')) {
                GiftCategory::where('id', intval($req->get('id')))->update($catData);
                return '{"success":"true","msg":"Category updated successfully!"}';
            } else {
                $gift = GiftCategory::create($catData);
                return '{"success":"true","msg":"Category saved successfully!"}';
            }
        } catch (Exception $e) {
            return $e->getTraceAsString();
        }
    }

    public function getCategory(Request $req) {
        if (isset($req->id)) {
            $cat = GiftCategory::find($req->id);
            return json_encode(['success' => TRUE, 'data' => $cat]);
        }
    }

    public function deleteCategory(Request $req) {
        try {
            if (isset($req->id)) {
                GiftCategory::destroy(intval($req->id));
                return '{"success":"false","msg":"Category deleted successfully!"}';
            } else {
                return '{"success":"false","msg":"Record not found"}';
            }
        } catch (Exception $e) {
            return '{"success":"false","msg":"An error has been occurred!"}';
        }
    }

}
