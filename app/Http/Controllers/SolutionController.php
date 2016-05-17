<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Solution;
use App\Models\SolutionCategory;
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

class SolutionController extends Controller {

    protected $user_repository;

    public function __construct() {
        $this->user_repository = App::make('user_repository');
    }

    public function getList(Request $request) {
        if ($request->isXmlHttpRequest()) {
            if ($request->has('cid')) {
                $solutions = Solution::where('user_id', $request->get('cid'))->orderBy('created_at', 'DESC')->get();
            } else {
                $solutions = Solution::orderBy('created_at', 'DESC')->get();
            }
            return View::make('admin.solution.items')->with(['solutions' => $solutions]);
        } else {
            $solnCats = SolutionCategory::where('status', 1)->get();
            $clients = $this->user_repository->all(['group_id' => 4]);
            return View::make('admin.solution.list')->with(['solnCats' => $solnCats, 'clients' => $clients]);
        }
    }

    public function addSolution(Request $req) {
        try {
            $solutionData = [
                'name' => $req->get('name'),
                'description' => $req->get('description'),
                'user_id' => $req->get('user_id'),
                'cat_id' => $req->get('cat_id'),
            ];
            $solution = Solution::create($solutionData);
        } catch (Exception $e) {
            return $e->getTraceAsString();
        }
        return '{"success":"true","message":"Gift saved successfully!"}'; //Redirect::route('gift.list')->withMessage('Gift saved successfully!');
    }

    public function deleteSolution(Request $req) {
        
    }

    public function getDetail(Request $req) {
        
    }

    /*     * *******Gift Category Master************************ */

    public function getCategoryList(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $cats = SolutionCategory::all();
            $data = ['data' => []];
            foreach ($cats as $cat) {
                $data['data'][] = [$cat->name, $cat->description, (($cat->status == 0) ? '<span class="label label-danger">Inactive</span>' : '<span class="label label-success">Active</span>'), $cat->created_at->format('d M, Y'), '<label class="table-edit-trash"><a href="' . URL::route('solution.category.view', ['id' => $cat->id]) . '" class="dev-edit-rec" data-recId="' . $cat->id . '"><span class="fa fa-edit"></span></a> <a href="' . URL::route('solution.category.delete', ['id' => $cat->id]) . '" class="dev-del-rec" data-recId="' . $cat->id . '"><span class="fa fa-trash-o"></span></a></label>'];
//                $data['data'][] = [$cat->name, $cat->description, (($cat->status == 0) ? '<span class="label label-danger">Inactive</span>' : '<span class="label label-success">Active</span>'), $cat->created_at->format('d M, Y'), '<label class="table-edit-trash"><a href="#" onclick="editCat('.$cat->id.')><span class="fa fa-edit"></span></a> <a href="#" onclick="deleteCat('.$cat->id.')"><span class="fa fa-trash-o"></span></a></label>'];
            }
            return $data;
        } else {
            return View::make('admin.solution.category.list');
        }
    }

    public function addCategory(Request $req) {
        try {
            $catData = [
                'name' => $req->get('name'),
                'description' => $req->get('description'),
                'status'=>$req->get('status',0)
            ];
            if ($req->has('id')) {
                SolutionCategory::where('id', intval($req->get('id')))->update($catData);
                return '{"success":"true","msg":"Category updated successfully!"}';
            } else {
                $gift = SolutionCategory::create($catData);
                return '{"success":"true","msg":"Category saved successfully!"}';
            }
        } catch (Exception $e) {
            return $e->getTraceAsString();
        }
    }

    public function getCategory(Request $req) {
        if (isset($req->id)) {
            $cat = SolutionCategory::find($req->id);
            return json_encode(['success' => TRUE, 'data' => $cat]);
        }
    }

    public function deleteCategory(Request $req) {
        try {
            if (isset($req->id)) {
                SolutionCategory::destroy(intval($req->id));
                return '{"success":"true","msg":"Category deleted successfully!"}';
            }else{
                return '{"success":"false","msg":"Record not found!"}';
            }
        } catch (Exception $e) {
            return '{"success":"false","msg":"An error has been occurred!"}';
        }
    }

}
