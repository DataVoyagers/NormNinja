<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LearningMaterialController extends Controller
{
    public function index()
    {
        if (auth()->user()->isTeacher()) {
            $materials = auth()->user()->learningMaterials()->latest()->paginate(15);
        } else {
            $materials = LearningMaterial::where('is_published', true)->latest()->paginate(15);
        }
        
        return view('learning-materials.index', compact('materials'));
    }

    public function create()
    {
        $this->authorize('create', LearningMaterial::class);
        return view('learning-materials.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', LearningMaterial::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,mp4,avi,mov|max:51200', // 50MB
            'subject' => 'nullable|string',
            'grade_level' => 'nullable|integer',
            'is_published' => 'boolean',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('learning-materials', 'public');
        }

        LearningMaterial::create([
            'teacher_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
            'subject' => $request->subject,
            'grade_level' => $request->grade_level,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('learning-materials.index')->with('success', 'Learning material uploaded successfully.');
    }

    public function show(LearningMaterial $learningMaterial)
    {
        if (!$learningMaterial->is_published && !auth()->user()->isTeacher()) {
            abort(403);
        }

        return view('learning-materials.show', compact('learningMaterial'));
    }

    public function edit(LearningMaterial $learningMaterial)
    {
        $this->authorize('update', $learningMaterial);
        return view('learning-materials.edit', compact('learningMaterial'));
    }

    public function update(Request $request, LearningMaterial $learningMaterial)
    {
        $this->authorize('update', $learningMaterial);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,mp4,avi,mov|max:51200',
            'subject' => 'nullable|string',
            'grade_level' => 'nullable|integer',
            'is_published' => 'boolean',
        ]);

        $data = $request->only(['title', 'description', 'subject', 'grade_level']);
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('file')) {
            // Delete old file
            if ($learningMaterial->file_path) {
                Storage::disk('public')->delete($learningMaterial->file_path);
            }
            
            $data['file_path'] = $request->file('file')->store('learning-materials', 'public');
            $data['file_type'] = $request->file('file')->getClientOriginalExtension();
        }

        $learningMaterial->update($data);

        return redirect()->route('learning-materials.index')->with('success', 'Learning material updated successfully.');
    }

    public function destroy(LearningMaterial $learningMaterial)
    {
        $this->authorize('delete', $learningMaterial);

        if ($learningMaterial->file_path) {
            Storage::disk('public')->delete($learningMaterial->file_path);
        }

        $learningMaterial->delete();

        return redirect()->route('learning-materials.index')->with('success', 'Learning material deleted successfully.');
    }
}
