<?php

namespace App\Http\Controllers;

use App\Background;
use Illuminate\Http\Request;
use App\User ;
use Illuminate\Support\Facades\Storage;

class BackgroundsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $backgrounds = auth()->user()->backgrounds()->paginate(5) ;
        return view('backgrounds' ,compact('backgrounds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('add_background');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \request()->validate([
            'file_upload'=>'required' ,
            'caption'=>'max:255' ,
            ]
        );

        if($request->hasFile('file_upload')){

            $backgrounde = new Background ;

            $file_name_with_extention = $request->file('file_upload')->getClientOriginalName() ;
            $file_name = pathinfo($file_name_with_extention,PATHINFO_FILENAME);
            $extention = $request->file('file_upload')->getClientOriginalExtension();
            $temp_name = $file_name.'_'.time().".".$extention ;
            $path = $request->file('file_upload')->storeAs('public/uploads',$temp_name);
            $size = $request->file('file_upload')->getSize();

            $backgrounde->create([
                'user_id'=> auth()->id(),
                'file_name'=> $file_name ,
                'type'=> $extention,
                'size'=> $size ,
                'caption'=> sql_sanitize($request->caption),
                'temp_name'=> $temp_name,
                'created_at'=> date("Y-m-d H:m:s") ,

            ]);
            session()->flash('message','Background added successfully');
            return redirect('/backgrounds');
        }else{
            session()->flash('message','Background didn\'t added successfully');
            return redirect('/backgrounds/create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd("destroy");
    }

    public function delete($id)
    {

        $background = Background::findOrfail($id) ;
        // First remove the database entry
        if($background->delete()) {
            // then remove the file
            // Note that even though the database entry is gone, this object
            // is still around (which lets us use $this->image_path()).

            if(unlink(public_path('storage/uploads/'.$background->temp_name))){
                session()->flash('message','Background deleted successfully');
            }else{
                session()->flash('message',"Background didn't delete successfully");
            }
        } else {
            // database delete failed
            session()->flash('message',"Background didn't delete successfully");

        }

        return redirect('/backgrounds');
    }

    public function set($id)
    {

        $temp_name = Background::findorfail($id)->temp_name;
        $user = auth()->user() ;
        $user->background_image = $temp_name ;
        if($user->update()){
            session()->flash('message',"Background updated successfully");
        }else{
            session()->flash('message',"Background didn't update successfully");
        }

        return redirect('/backgrounds');
    }

}
