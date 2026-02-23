<?php

namespace Controllers;

use Models\ReviewModel;

class ReviewController extends Controller
{
    public function show(array $params)
    {
        $model = new ReviewModel($this->db());
        $review = $model->getReviewById($params['id'] ?? 0);
        $review ? $this->respond($review) : $this->respond(['message' => 'Évaluation introuvable'], 404);
    }

    public function listByArticle(array $params)
    {
        $model = new ReviewModel($this->db());
        $this->respond($model->getReviewsByArticle($params['id'] ?? 0));
    }

    public function assign()
    {
        $data = $this->input();
        $model = new ReviewModel($this->db());
        $model->assignReviewer($data['article_id'], $data['reviewer_id'], $data['deadline_days'] ?? 14);
        $this->respond(['message' => 'Évaluateur assigné']);
    }

    public function submit(array $params)
    {
        $model = new ReviewModel($this->db());
        $model->submitReview($params['id'] ?? 0, $this->input());
        $this->respond(['message' => 'Évaluation soumise']);
    }

    public function accept(array $params)
    {
        $model = new ReviewModel($this->db());
        $model->acceptReviewAssignment($params['id'] ?? 0);
        $this->respond(['message' => 'Invitation acceptée']);
    }

    public function decline(array $params)
    {
        $model = new ReviewModel($this->db());
        $data = $this->input();
        $model->declineReviewAssignment($params['id'] ?? 0, $data['reason'] ?? null);
        $this->respond(['message' => 'Invitation déclinée']);
    }
}
?>

