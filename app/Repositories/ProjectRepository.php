<?php

namespace App\Repositories;

use Exception;
use App\Models\Project;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ProjectRepository
{
    public function getAll(object $request): array
    {
        if(!Gate::allows('view-all-project')){
            throw new Exception("Forbidden. You don't have permission to access this resource", Response::HTTP_FORBIDDEN);
        }

        $search = $request->search;

        //filters
        $offset = $request->has('offset') ? (int)$request->offset : 0;
        $limit = $request->has('limit') ? (int)$request->limit : 10;
        $orderBy =  $request->has('orderBy') ? $request->orderBy : 'id';
        $orderDesc =  $request->get('orderDesc') === 'true' ? 'desc' : 'asc';


        $projects = Project::where(function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', $this->searchString($search));
            }
        })
            ->limit($limit)
            ->offset($offset)
            ->orderBy($orderBy, $orderDesc)
            ->get();

        $data = [
            'total' => count($projects),
            'records' => $projects,
            'offset' => $offset,
            'limit' => $limit
        ];

        return $data;
    }

    public function getByID(int $id): ?Project
    {

        if(!Gate::allows('view-project')){
            throw new Exception("Forbidden. You don't have permission to access this resource", Response::HTTP_FORBIDDEN);
        }

        $project = Project::find($id);

        if (empty($project)) {
            throw new Exception("Project does not exist.", Response::HTTP_NOT_FOUND);
        }

        return $project;
    }

    public function create(array $params): Project
    {

        if(!Gate::allows('create-project')){
            throw new Exception("Forbidden. You don't have permission to access this resource", Response::HTTP_FORBIDDEN);
        }

        $data = [
            'title' => $params['title'],
            'description' => $params['description'],
            'is_active' => $params['is_active'] ?? 1,
        ];

        $create = Project::create($data);

        if (!$create) {
            throw new Exception("Could not create project, Please try again.", 500);
        }

        return $create->fresh();
    }

    public function update(int $id, array $params): ?Project
    {

        if(!Gate::allows('update-project')){
            throw new Exception("Forbidden. You don't have permission to access this resource", Response::HTTP_FORBIDDEN);
        }


        $project = $this->getById($id);

        $data = [
            'title' => $params['title'] ?? $project['title'],
            'description' => $params['description'] ?? $project['description'],
            'is_active' => $params['is_active'] ?? $project['is_active']
        ];

        $updated = $project->update($data);
        
        if ($updated) {
            $project = $this->getById($id);
        }

        return $project;
    }

    public function delete(int $id): ?Project
    {

        if(!Gate::allows('delete-project')){
            throw new Exception("Forbidden. You don't have permission to access this resource", Response::HTTP_FORBIDDEN);
        }

        $project = $this->getById($id);

        $deleted = $project->delete();

        if (!$deleted) {
            throw new Exception("Project could not be deleted.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $project;
    }

    private function searchString(string $search): string
    {
        $urlDecode = urldecode($search);
        return "$urlDecode%";
    }
}
