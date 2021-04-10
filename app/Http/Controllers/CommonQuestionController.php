<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommonQuestionResource;
use App\Models\CommonQuestion;
use Illuminate\Http\Request;
use App\Models\Article;
use mysql_xdevapi\Exception;
use Validator;
use Auth;
use Response;
class CommonQuestionController extends Controller
{
    // show CommonQuestions
    public function index(){
        return CommonQuestionResource::collection(CommonQuestion::orderBy('id','DESC')->paginate(10));
    }

    // store new CommonQuestion into the database
    public function store(Request $request){
        $validators=Validator::make($request->all(),[
            'question'=>'required',
            'answer'=>'required'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $q=new CommonQuestion();
            $q->question=$request->question;
            $q->user_id=Auth::user()->id;
            $q->answer=$request->answer;
            $q->save();
            return Response::json(['success'=>'CommonQuestion  added successfully !']);
        }
    }

    // show a specific CommonQuestion
    public function show($id){
        if(CommonQuestion::where('id',$id)->first()){
            return new CommonQuestionResource(CommonQuestion::findOrFail($id));
        }else{
            return Response::json(['error'=>'CommonQuestion not found!']);
        }
    }

    // update CommonQuestion into the database
    public function update(Request $request){
        $validators=Validator::make($request->all(),[
            'question'=>'required',
            'answer'=>'required'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $q=CommonQuestion::where('id',$request->id)->where('user_id',Auth::user()->id)->first();
            if($q){
                $q->question=$request->question;
                $q->user_id=Auth::user()->id;
                $q->answer=$request->answer;
                $q->save();
                return Response::json(['success'=>'CommonQuestion updated successfully !']);
            }else{
                return Response::json(['error'=>'CommonQuestion not found !']);
            }
        }
    }

    // remove article
    public function remove(Request $request){
        try{
            $q=CommonQuestion::where('id',$request->id)->where('user_id',Auth::user()->id)->first();
            if($q){
                $q->delete();
                return Response::json(['success'=>'CommonQuestion removed successfully !']);
            }else{
                return Response::json(['error'=>'CommonQuestion not found!']);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'CommonQuestion belongs to author/article.So you cann\'t delete this CommonQuestion!']);
        }
    }
}
