<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Http\Requests\FileRequest;
use App\Traits\GenTraits;
class FileController extends Controller
{
    use GenTraits;

    public function index()
    {
        return File::all();
    }
    //..........................................................................................
    public function store(FileRequest $request)
    {
        $file = new File();
        $file->Name_File = $request->Name_File;
        $file->Active = $request->Active;
        $file->description = $request->description;
        $file->user_id = $request->user_id;
        $file->group_id = $request->group_id;
        $file->path = $request->path;
        $file->name = $request->name;
        $file->save();
        return $this->success($file, 200, '');
      
    }
    //............................................................................................
    public function delete($id){
        $file=File::find($id);
    
            if (!$file) {
                return $this->error('','cannot find file',500);
            }
    
            $file->delete();
    
            return $this ->success($file,200,'file delete successfully');
        }
   //............................................................................................
   public function  update_file(Request $request, $id){
    $file = File::find($id);
    if (!$file) {
        return $this->error('','cannot show anysthing',500);
    }
   
    if($request->name)
    {
        $group->name = $request->name;
    }

    if($request->description)
    {
        $group->description = $request->description;
    }
  
    if($request->path)
    {
        $group->path = $request->path;
    }
    if($request->user_id)
    {
        $group->user_id = $request->user_id;
    }
    if($request->group_id)
    {
        $group->group_id = $request->group_id;
    }
    if($request->Active)
    {
        $group->Active = $request->Active;
    }
    if($request->Name_File)
    {
        $group->Name_File = $request->Name_File;
    }
    

    $file->save();
    return $this ->success($file,200,'file updated successfully');

}    
//.....................................................................................
public function search_file(Request $request)
{
    $file = $request->input('Name_File');
   
    $file = File::where('Name_File', 'like', "%$file%")->first();

    if (!$file) {
        return $this->error('', 'Cannot find file', 500);
    }

    return $this->success($file, 200, '');
}
//............................................................................................
public function search_file_in_group(Request $request)
{
    $group = $request->input('group_id');
    $group = Group::find($group);
    if (!$group) {
        return $this->error('', 'Cannot find group', 500);
    }

    $file = $request->input('Name_File');
  
    $file = File::where('Name_File', 'like', "%$file%")
        ->first();

    if (!$file) {
        return $this->error('', 'Cannot find file', 500);
    }

    return $this->success($file, 200, '');
}
//..................................................................................................

}
