<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\UpdateFolderRequest;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Support\Facades\Request;

class FolderController extends Controller
{
    public function index()
    {
        $folders = Folder::with('children')->whereNull('parent_id')->paginate(10);
        return $this->responsePagination($folders, FolderResource::collection($folders->load('icon')));
    }

    public function store(StoreFolderRequest $request)
    {

        $uploadedIcon = $this->uploadPhoto($request->file('icon'));

        $folder = new Folder();
        $folder->parent_id = $request->parent_id;
        $folder->name = $request->name;
        $folder->save();
        $folder->icon()->create([
            'path' => $uploadedIcon,
        ]);
        return $this->success(new FolderResource($folder->load('icon')), 201);
    }

    public function show(string $id)
    {
        $folder = Folder::find($id);
        if (!$folder) {
            return $this->error('Folder not found', 404);
        }

        return $this->success(new FolderResource($folder->load('icon')));
    }

    public function update(UpdateFolderRequest $request, string $id)
    {
        $folder = Folder::find($id);
        if (!$folder) {
            return $this->error('Folder not found', 404);
        }
        $folder->name = $request->name;
        $folder->parent_id = $request->parent_id;
        $folder->save();
        if ($request->hasFile('icon')) {
            if ($folder->icon->path) {
                $this->deletePhoto($folder->icon->path);
            }
            $updatedIcon = $this->uploadPhoto($request->file('icon'));
            $folder->icon()->create([
                'path' => $updatedIcon,
            ]);
        }
        return $this->success(new FolderResource($folder->load('icon')), 'Folder updated successfully');
    }

    public function destroy(string $id)
    {
        $folder = Folder::find($id);
        if (!$folder) {
            return $this->error('Folder not found', 404);
        }
        $this->deletePhoto($folder->icon->path);
        $folder->delete();
        return $this->success([], 'Folder deleted successfully', 204);
    }
    public function search(Request $request)
    {
        $folders = Folder::with('children')->where('name', 'like', "%$request->q%")->paginate(8);
        return $this->responsePagination($folders, FolderResource::collection($folders->load('icon')));
    }
}
