<?php

namespace Controllers;

use Models\ArticleModel;

class ArticleController extends Controller
{
    public function index()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 20;

        $model = new ArticleModel($this->db());
        $this->respond($model->getAllArticles($page, $limit));
    }

    public function show(array $params)
    {
        $model = new ArticleModel($this->db());
        $article = $model->getArticleById($params['id'] ?? 0);
        $article ? $this->respond($article) : $this->respond(['message' => 'Article introuvable'], 404);
    }

    public function store()
    {
        $data = $this->input();
        $model = new ArticleModel($this->db());
        $id = $model->createArticle($data);
        $this->respond(['id' => $id], 201);
    }

    public function update(array $params)
    {
        $data = $this->input();
        $model = new ArticleModel($this->db());
        $model->updateArticle($params['id'] ?? 0, $data);
        $this->respond(['message' => 'Article mis à jour']);
    }

    public function delete(array $params)
    {
        $model = new ArticleModel($this->db());
        $model->deleteArticle($params['id'] ?? 0);
        $this->respond(['message' => 'Article supprimé']);
    }
}
?>

