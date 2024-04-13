<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Article;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ArticleResource::collection(Article::with('authors')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        try{
            //dd($request->validated());
            if($request->hasFile('image')){
                $image_path = $request->file('image')->store('images','public');
            }
            $validated = $request->validated();
            $validated['published_at'] = now();
            $validated['image'] = $image_path ;
            $article = Article::create($validated);
            $article->authors()->attach($request->input('author_id'));
            return new ArticleResource($article->load('authors'));
        }
        catch (\Exception $e) {
            // Log the error
            Log::error('Error storing article: ' . $e->getMessage());

            // Return an error response
            return response()->json(['message' => 'An error occurred while processing your request.'], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return new ArticleResource($article->load('authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $article->update($request->only(['title', 'body','published_at']));
        $article->authors()->sync($request->only('author_id'));
        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->authors()->detach();
        $article->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
