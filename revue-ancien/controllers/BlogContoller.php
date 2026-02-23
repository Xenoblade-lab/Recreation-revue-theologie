<?php 

namespace Controllers;

class BlogContoller extends Controller
{
    public function index()
    {
        \Service\AuthService::requireLogin();
        \App\App::view('archives');
    }

    public function show($params)
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->respond(['message' => 'Identifiant requis'], 400);
            return;
        }

        // Placeholder : afficher une page ou JSON
        $this->respond(['id' => $id]);
    }
}
?>