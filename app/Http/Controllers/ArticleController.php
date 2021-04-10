<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Response;
use Validator;
use Auth;
class ArticleController extends Controller
{
    public function get_all()
    {
        /* $articles = DB::table('articles')->get();
         //$articles=Article::all();
         //return json_encode($articles);
         $res=[];
         foreach ($articles as $article) {
             $res[]=[
                 "id"=>$article->id,
                 "title"=>$article->title,
                 "body"=>$article->body,
                 "image"=>$article->image,
                 'links'=>$article->links,
                 "doctor_id"=>$article->doctor_id,
             ];
         }
         return response()->json($res);*/
        return ArticleResource::collection(Article::orderBy('id', 'DESC')->paginate(15));
    }

    public function write_article(Request $request)
    {
        $validators = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required'
        ]);
        if ($validators->fails()) {
            return Response::json(['errors' => $validators->getMessageBag()->toArray()]);
        } else {
            $article = new Article();
            $article->title = $request->title;
            $article->body = $request->body;
            $article->author_id = Auth::user()->id;
            if ($request->file('image') == NULL) {
                $article->image = 'placeholder.png';
            } else {
                $filename = Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
                $article->image = $filename;
                $request->image->move(public_path('ImagesForArticle'), $filename);
            }
            $article->save();
            return Response::json(['success' => 'Article created successfully !']);
        }
    }

    //get article by id
    public function get_article_by_id($id)
    {
        if (Article::where('id', $id)->first()) {
            return new ArticleResource(Article::findOrFail($id));
        } else {
            return Response::json(['error' => 'Article not found!']);
        }
    }

    //search by title
    public function get_article_by_word(Request $request)
    {
        $articles = Article::where('title', 'LIKE', '%' . $request->keyword . '%')->get();
        if (count($articles) == 0) {
            return Response::json(['message' => 'No article match found !']);
        } else {
            return Response::json($articles);
        }
    }

    public function update_article(Request $request, $id)
    {
        $validators = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required'
        ]);
        if ($validators->fails()) {
            return Response::json(['errors' => $validators->getMessageBag()->toArray()]);
        } else {
            $article = Article::where('id', $request->id)->where('author_id', Auth::user()->id)->first();
            if ($article) {
                $article->title = $request->title;
                $article->author_id = Auth::user()->id;
                $article->body = $request->body;
                if ($request->file('image') == NULL) {
                    $article->image = 'placeholder.png';
                } else {
                    $filename = Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
                    $article->image = $filename;
                    $request->image->move(public_path('images'), $filename);
                }
                $article->save();
                return Response::json(['success' => 'Article updated successfully !']);
            } else {
                return Response::json(['error' => 'Article not found  or you not article writer']);
            }
        }
    }

    //destroy Article
    public function destroy_article(Request $request)
    {
        try {
            $article = Article::where('id', $request->id)->where('author_id', Auth::user()->id)->first();
            if ($article) {
                $article->delete();
                return Response::json(['success' => 'Article removed successfully !']);
            } else {
                return Response::json(['error' => 'Article not found or you not article writer']);
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            return Response::json(['error' => 'Article belongs to comment.So you cann\'t delete this article!']);
        }
    }

    // fetch comments for a specific article
    public function comments($id){
        if(Article::where('id',$id)->first()){
            return CommentResource::collection(Comment::where('article_id',$id)->get());
        }else{
            return Response::json(['error'=>'Article not found!']);
        }
    }
    /*public function is_user_article($article)
    {
        if ( Auth::id() !== $article->user_id ) {
            throw new NotUserPost;
        }
        return true;
    }*/
}
