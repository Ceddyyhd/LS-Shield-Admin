<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document; // Import the Document model
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'document_name' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,png'
        ]);

        $originalName = $request->file('document')->getClientOriginalName();
        $extension = $request->file('document')->getClientOriginalExtension();
        $fileName = $request->document_name . '_' . Str::random(10) . '.' . $extension;
        $path = $request->file('document')->storeAs('public/documents', $fileName);

        Document::create([
            'user_id' => $request->user_id,
            'file_name' => $fileName,
            'file_path' => $path,
            'uploaded_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'document_id' => 'required|integer|exists:documents,id'
        ]);

        $document = Document::findOrFail($request->document_id);
        Storage::delete($document->file_path);
        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}