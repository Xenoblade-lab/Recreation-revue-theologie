<?php

namespace Controllers;

use Models\JournalModel;
use Models\VolumeModel;

class JournalController extends Controller
{
    public function index()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 20;

        $model = new JournalModel($this->db());
        $this->respond($model->getAllJournals($page, $limit));
    }

    public function show(array $params)
    {
        $model = new JournalModel($this->db());
        $journal = $model->getJournalById($params['id'] ?? 0);
        $journal ? $this->respond($journal) : $this->respond(['message' => 'Revue introuvable'], 404);
    }

    public function store()
    {
        $data = $this->input();
        $model = new VolumeModel($this->db());
        $id = $model->createVolume($data);
        $this->respond(['id' => $id], 201);
    }

    public function update(array $params)
    {
        $model = new JournalModel($this->db());
        $model->updateJournal($params['id'] ?? 0, $this->input());
        $this->respond(['message' => 'Revue mise à jour']);
    }

    public function delete(array $params)
    {
        $model = new JournalModel($this->db());
        $model->deleteJournal($params['id'] ?? 0);
        $this->respond(['message' => 'Revue supprimée']);
    }
}
?>

