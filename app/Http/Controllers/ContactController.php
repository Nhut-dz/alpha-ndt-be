<?php

namespace App\Http\Controllers;

use App\Models\ContactModel;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return response()->json(
            ContactModel::orderBy('created_at', 'desc')->get()
        );
    }

    public function show($id)
    {
        return response()->json(ContactModel::findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $contact = ContactModel::create($request->only([
            'name', 'email', 'phone', 'service', 'message',
        ]));

        return response()->json($contact, 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $contact = ContactModel::findOrFail($id);
        $contact->update(['status' => $request->status]);
        return response()->json($contact);
    }

    public function destroy($id)
    {
        ContactModel::findOrFail($id)->delete();
        return response()->json(['message' => 'Đã xóa liên hệ.']);
    }
}
