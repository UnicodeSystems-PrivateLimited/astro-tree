<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RelationType;
use App\Models\Relation;
use App\Http\Controllers\Controller;
use View,
    URL,
    DB;

class RelationController extends Controller {

    private $user_profile_table_name = "user_profile";
    private $relation_table_name = "relations";
    private $relation_type_table_name = "relation_types";

    public function getList(Request $req) {
        if ($req->has('cid')) {
            $q = DB::table($this->relation_table_name)
                    ->select(DB::raw("`$this->relation_table_name`.`id`, `$this->user_profile_table_name`.`first_name`,`$this->user_profile_table_name`.`last_name`,`$this->relation_type_table_name`.`name`"))
                    ->join($this->user_profile_table_name, $this->relation_table_name . '.rel_user_id', '=', $this->user_profile_table_name . '.user_id')
                    ->join($this->relation_type_table_name, $this->relation_table_name . '.rel_type_id', '=', $this->relation_type_table_name . '.id')
                    ->where("$this->relation_table_name.user_id", $req->get('cid'));
            $rels = $q->get();
            return View::make('admin.relation.treegrid')->with(['rels' => $rels, 'offset' => $req->get('rows')]);
        }
    }

    public function addRelation(Request $req) {
        try {
            if ($req->has('rel_client_id') && $req->has('rel_user_id') && $req->has('rel_type_id')) {
                $relData = [
                    'user_id' => $req->get('rel_client_id'),
                    'rel_user_id' => $req->get('rel_user_id'),
                    'rel_type_id' => $req->get('rel_type_id'),
                ];
                if ($req->has('is_referrer')) {
                    $relData['is_referrer'] = $req->get('is_referrer');
                }
                Relation::create($relData);
                return '{"SUCCESS":"true", "msg":"Relation saved successfully!"}';
            } else {
                return '{"SUCCESS":"false","ERRNO":"4", "msg":"Invalid data"}';
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getTypeList(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $types = RelationType::all();
            $data = ['data' => []];
            foreach ($types as $type) {
                $data['data'][] = [$type->name, $type->description, (($type->status == 0) ? '<span class="label label-danger">Inactive</span>' : '<span class="label label-success">Active</span>'), $type->created_at->format('d M, Y'), '<label class="table-edit-trash"><a href="' . URL::route('relation.type.view', ['id' => $type->id]) . '" class="dev-edit-rec" data-recId="' . $type->id . '"><span class="fa fa-edit"></span></a> <a href="' . URL::route('relation.type.delete', ['id' => $type->id]) . '" class="dev-del-rec" data-recId="' . $type->id . '"><span class="fa fa-trash-o"></span></a></label>'];
            }
            return $data;
        } else {
            return View::make('admin.relation.type.list');
        }
    }

    public function addType(Request $req) {
        try {
            $relationData = [
                'name' => $req->get('name'),
                'description' => $req->get('description'),
                'status' => $req->get('status', 0)
            ];
            if ($req->has('id')) {
                RelationType::where('id', intval($req->get('id')))->update($relationData);
                return '{"success":"true","msg":"Type updated successfully!"}';
            } else {
                $relation = RelationType::create($relationData);
                return '{"success":"true","msg":"Type saved successfully!"}';
            }
        } catch (Exception $e) {
            return $e->getTraceAsString();
        }
        return '{"success":"true","message":"Type saved successfully!"}'; //Redirect::route('gift.list')->withMessage('Gift saved successfully!');
    }

    public function getType(Request $req) {
        if (isset($req->id)) {
            $type = RelationType::find($req->id);
            return json_encode(['success' => TRUE, 'data' => $type]);
        }
    }

    public function deleteType(Request $req) {
        try {
            if (isset($req->id)) {
                RelationType::destroy(intval($req->id));
                return '{"success":"false","msg":"Type deleted successfully!"}';
            } else {
                return '{"success":"false","msg":"Record not found"}';
            }
        } catch (Exception $e) {
            return '{"success":"false","msg":"An error has been occurred!"}';
        }
    }

}
