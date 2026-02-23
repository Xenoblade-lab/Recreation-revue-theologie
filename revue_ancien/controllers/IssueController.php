<?php

namespace Controllers;

use Models\IssueModel;

class IssueController extends Controller
{
    public function show(array $params)
    {
        $model = new IssueModel($this->db());
        $issue = $model->getIssueById($params['id'] ?? 0);
        $issue ? $this->respond($issue) : $this->respond(['message' => 'Numéro introuvable'], 404);
    }

    public function listByRevue(array $params)
    {
        $model = new IssueModel($this->db());
        $this->respond($model->getIssuesByJournal($params['revueId'] ?? 0));
    }

    public function store(array $params)
    {
        $model = new IssueModel($this->db());
        $id = $model->createIssue($params['revueId'] ?? 0, $this->input());
        $this->respond(['id' => $id], 201);
    }

    public function update(array $params)
    {
        $model = new IssueModel($this->db());
        $model->updateIssue($params['id'] ?? 0, $this->input());
        $this->respond(['message' => 'Numéro mis à jour']);
    }

    public function delete(array $params)
    {
        $model = new IssueModel($this->db());
        $model->deleteIssue($params['id'] ?? 0);
        $this->respond(['message' => 'Numéro supprimé']);
    }
}
?>

