<?php

namespace App\Service;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProjectService
{

    public function getAll($request): Builder
    {
        $projectQuery = Project::with([
            'tasks',
            'leader',
            'members.user'
        ]);

        $user = Auth::user();

        if ($user->role_id != 1) {
            $projectQuery->where(function ($query) use ($user) {
                $query->where('leader_id', $user->id)
                    ->orWhereHas('members.user', fn($q) => $q->where('id', $user->id));
            });
        }

        if ($request->leader) {
            $projectQuery->whereHas('leader.employee', function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->leader . '%');
            });
        }

        if ($request->member) {
            $projectQuery->whereHas('members.user.employee', function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->member . '%');
            });
        }

        if ($request->name) {
            $projectQuery->where('name', 'like', '%' . $request->name . '%');
        }

        return $projectQuery;
    }

    public function insert($data)
    {
        DB::transaction(function () use ($data) {
            $membersData = $this->handleMemberData($data['project_members']);

            unset($data['project_members']);

            $data['total_cost'] = preg_replace('/\D/', '', $data['total_cost']);

            $project = Project::create($data);

            $user = User::findOrFail($project->leader_id);

            if ($user) {
                $user->update(['role_id', 6]);
                Cache::forget('user_permissions_' . $user->id);
            }

            $project->members()->createMany($membersData);
        });
    }

    public function insertMember($data, $id)
    {
        DB::transaction(function () use ($data, $id) {
            $project = Project::findOrFail($id);

            $project->members()->createMany($this->handleMemberData($data['project_members']));
        });
    }

    public function update($data, $id)
    {
        DB::transaction(function () use ($data, $id) {
            $project = Project::findOrFail($id);

            $newMembersData = $this->handleMemberData($data['project_members']);

            unset($data['project_members']);

            $data['total_cost'] = preg_replace('/\D/', '', $data['total_cost']);

            $project->update($data);

            $user = User::findOrFail($project->leader_id);

            if ($user) {
                $user->update(['role_id' => 6]);
                Cache::forget('user_permissions_' . $user->id);
            }

            $this->syncProjectMembers($project, $newMembersData);
        });
    }

    private function syncProjectMembers($project, $newMembersData)
    {
        $newUserIds = array_column($newMembersData, 'user_id');

        $project->members()->whereNotIn('user_id', $newUserIds)->delete();

        $existingUserIds = $project->members()->pluck('user_id')->toArray();

        $membersToCreate = array_filter($newMembersData, function ($member) use ($existingUserIds) {
            return !in_array($member['user_id'], $existingUserIds);
        });

        if (!empty($membersToCreate)) {
            $project->members()->createMany($membersToCreate);
        }
    }

    public function handleMemberData($data): array
    {
        $projectMembers = json_decode($data, true);

        if (!is_array($projectMembers)) {
            return [];
        }

        return array_map(
            fn($member) => ['user_id' => (int)($member['value'] ?? 0)],
            $projectMembers
        );
    }
}
