<?php

namespace App\Interfaces;

interface agentRepositoryInterface
{
    public function getAllAgents(?string $search = null);

    public function getAgentBySlug(string $slug);

    public function getAgentById(string $id);

    public function createAgent(array $data);

    public function updateAgent(array $data, string $id);

    public function deleteAgent(string $id);

    public function generateCode(int $tryCount): string;

    public function isUniqueCode(string $code, ?string $exceptId = null): bool;

    public function generateSlug(string $code, string $name, int $tryCount): string;

    public function isUniqueSlug(string $slug, ?string $exceptId = null): bool;
}
