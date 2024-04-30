<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use Exception;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Repositories\ProjectRepository;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{

    use ResponseTrait;

    public function __construct(private ProjectRepository $projectRepository,)
    {
        $this->projectRepository = $projectRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $projects = $this->projectRepository->getAll(request());

            return $this->responseSuccess($projects, "Projects fetched successfully");
        } catch (Exception $e) {

            return $this->responseError([], $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        try {

            $createProject = $this->projectRepository->create($request->all());

            return $this->responseSuccess($createProject, "Project created successfully");
        } catch (Exception $e) {

            return $this->responseError([], $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectRequest $request): JsonResponse
    {
        try {

            $findAccount = $this->projectRepository->getByID($request->query('id'));

            return $this->responseSuccess($findAccount, "Project find successfully");

        } catch (Exception $e) {

            return $this->responseError([], $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectRequest $projectRequest)
    {
        try {

            $updateProject = $this->projectRepository->update($request->query('id'), $projectRequest->all());

            return $this->responseSuccess($updateProject, 'Project updated successfully.');

        } catch (Exception $exception) {

            return $this->responseError([], $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectRequest $request) : JsonResponse
    {
          try {

            $deleteProject = $this->projectRepository->delete($request->query('id'));

            return $this->responseSuccess($deleteProject, 'Project deleted successfully.');

        } catch (Exception $exception) {
            
            return $this->responseError([], $exception->getMessage(), $exception->getCode());
        }
    }
}
