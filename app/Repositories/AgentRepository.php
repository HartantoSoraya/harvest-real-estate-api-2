<?php

namespace App\Repositories;

use App\Interfaces\AgentRepositoryInterface;
use App\Models\Agent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgentRepository implements AgentRepositoryInterface
{
    public function getAllAgents($search = null)
    {
        if ($search) {
            return Agent::where('name', 'like', '%'.$search.'%')->get();
        }

        return Agent::all();
    }

    public function getAgentBySlug($slug)
    {
        return Agent::where('slug', $slug)->first();
    }

    public function getAgentById(string $id)
    {
        return Agent::findOrFail($id);
    }

    public function createAgent(array $data)
    {
        DB::beginTransaction();

        try {
            $agent = new Agent();
            $agent->slug = $data['slug'];
            $agent->code = $data['code'];
            $agent->name = $data['name'];
            $agent->description = $data['description'];
            $agent->specialization = $data['specialization'];
            $agent->email = $data['email'];
            $agent->phone_number = $data['phone_number'];
            $agent->facebook = $data['facebook'];
            $agent->twitter = $data['twitter'];
            $agent->instagram = $data['instagram'];
            $agent->linkedin = $data['linkedin'];
            $agent->avatar = $data['avatar']->store('assets/agents', 'public');
            $agent->save();

            DB::commit();

            return $agent;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function updateAgent(array $data, string $id)
    {
        DB::beginTransaction();

        try {
            $agent = Agent::find($id);
            $agent->slug = $data['slug'];
            $agent->code = $data['code'];
            $agent->name = $data['name'];
            $agent->description = $data['description'];
            $agent->specialization = $data['specialization'];
            $agent->email = $data['email'];
            $agent->phone_number = $data['phone_number'];
            $agent->facebook = $data['facebook'];
            $agent->twitter = $data['twitter'];
            $agent->instagram = $data['instagram'];
            $agent->linkedin = $data['linkedin'];
            if (isset($data['avatar'])) {
                $agent->avatar = $this->updateAvatar($agent->avatar, $data['avatar']);
            }
            $agent->save();

            DB::commit();

            return $agent;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function deleteAgent(string $id)
    {
        DB::beginTransaction();

        try {
            Agent::findOrFail($id)->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function generateCode(int $tryCount): string
    {
        $count = Agent::count() + $tryCount;
        $code = str_pad($count, 2, '0', STR_PAD_LEFT);

        return $code;
    }

    public function isUniqueCode(string $code, ?string $exceptId = null): bool
    {
        if (Agent::count() == 0) {
            return true;
        }

        $result = Agent::where('code', $code);

        if ($exceptId) {
            $result = $result->where('id', '!=', $exceptId);
        }

        return $result->count() == 0 ? true : false;
    }

    public function generateSlug(string $code, string $name, int $tryCount): string
    {
        $slug = Str::slug($name.'_'.$code);

        if ($tryCount > 0) {
            $slug = $slug.'_'.$tryCount;
        }

        return $slug;
    }

    public function isUniqueSlug(string $slug, ?string $exceptId = null): bool
    {
        if (Agent::count() === 0) {
            return true;
        }

        $query = Agent::where('slug', $slug);

        if ($exceptId) {
            $query = $query->where('id', '!=', $exceptId);
        }

        return $query->count() == 0 ? true : false;
    }

    public function updateAvatar($oldAvatar, $newAvatar)
    {
        Storage::disk('public')->delete($oldAvatar);

        return $newAvatar->store('assets/agents', 'public');
    }
}
