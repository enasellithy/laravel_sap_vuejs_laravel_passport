<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notes;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddNoteRequest;
use App\Http\Requests\EditNotesRequest;

class NotesController extends Controller
{
    public function index()
    {
        $notes = Notes::latest()->with('user')->get();
        foreach ($notes as $note){
            $note->setAttribute('user', $note->user);
            $note->setAttribute('added', \Carbon\Carbon::parse($note->created_at)->diffForHumans());
        }
        return response()->json($notes);
    }

    public function store(AddNoteRequest $request)
    {
        $notes = Notes::create([
            'title'         => $request->title,
            'description'   => $request->description,
            'user_id'       => Auth::id()
        ]);
        return response()->json('The Notes successfully added');
    }

    public function edit($id)
    {
        $notes = Notes::find($id);
        return response()->json($notes);
    }

    public function update($id, EditNotesRequest $request)
    {
        $notes = Notes::find($id)->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'user_id'       => Auth::id()
        ]);
        return response()->json('The Notes successfully updated');
    }

    public function delete($id)
    {
        $notes = Notes::find($id);
        $notes->delete();
        return response()->json('The shoe successfully deleted');
    }
}
