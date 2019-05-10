<?php

namespace App\Http\Controllers\Admin;

use App\Transformers\TagTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Http\Requests\TagCreateRequest;
use think\response\Redirect;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    protected $fields=[
        'tag'=>'',
        'title'=>'',
        'subtitle'=>'',
        'meta_description'=>'',
        'page_image'=>'',
        'layout'=>'blog.layout.index',
        'reverse_direction'=>0
    ];
    public function index()
    {
        $tags= Tag::all();
        return view('admin.tag.index')->withTags($tags);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=[];
        foreach ($this->fields as $field=>$default){
            $data[$field]=old($field,$default);
        }
        return view('admin.tag.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = new Tag();
        foreach (array_keys($this->fields) as $field){
            $tag->$field = $request->get($field);
        }
        $tag->save();

        return \redirect('/admin/tag')->with('success','标签【'.$tag->tag.'】创建爱你成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        return $this->response->item($tag,new TagTransformer());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag=Tag::findOrFail($id);
        $data=['id'=>$id];
        foreach (array_keys($this->fields) as $field){
            $data[$field]=old($field,$tag->$field);
        }
        return view('admin.tag.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        foreach (array_keys(array_except($this->fields,['tag']))as $field){
            $tag->$field = $request->get($field);
        }
        $tag->save();
        return \redirect("/admin/tag/$id/edit")->with('success','修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return \redirect('/admin/tag')->with('success','标签【'.$tag->tag.'】删除成功');
    }
}
