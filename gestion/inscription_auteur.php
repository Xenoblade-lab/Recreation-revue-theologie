<?php
/**
 * Traitement de l'inscription auteur
 * Revue de la Faculté de Théologie - UPC
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

// Base URL pour les redirections
$baseUrl = dirname(dirname($_SERVER['PHP_SELF']));

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . '/pages/inscription-auteur.php?error=invalid_request');
    exit;
}

// Récupérer les données du formulaire
$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$email = trim($_POST['email'] ?? '');
$affiliation = trim($_POST['affiliation'] ?? '');
$orcid = trim($_POST['orcid'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm-password'] ?? '';
$terms = isset($_POST['terms']) && $_POST['terms'] == 'on';

// Validation des champs obligatoires
if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
    header('Location: ' . $baseUrl . '/pages/inscription-auteur.php?error=required&' . http_build_query([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'affiliation' => $affiliation,
        'orcid' => $orcid
    ]));
    exit;
}

// Validation de l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . $baseUrl . '/pages/inscription-auteur.php?error=invalid_email&' . http_build_query([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'affiliation' => $affiliation,
        'orcid' => $orcid
    ]));
    exit;
}

// Validation du mot de passe
if (strlen($password) < 8) {
    header('Location: ' . $baseUrl . '/pages/inscription-auteur.php?error=password_weak&' . http_build_query([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'affiliation' => $affiliation,
        'orcid' => $orcid
    ]));
    exit;
}

// Vérifier que les mots de passe correspondent
if ($password !== $confirmPassword) {
    header('Location: ' . $baseUrl . '/pages/inscription-auteur.php?error=password_mismatch&' . http_build_query([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'affiliation' => $affiliation,
        'orcid' => $orcid
    ]));
    exit;
}

// Vérifier l'acceptation des conditions
if (!$terms) {
    header('Location: ' . $baseUrl . '/pages/inscription-auteur.php?error=terms&' . http_build_query([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'affiliation' => $affiliation,
        'orcid' => $orcid
    ]));
    exit;
}

// Validation ORCID (format: 0000-0000-0000-0000)
if (!empty($orcid) && !preg_match('/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/', $orcid)) {
    $orcid = null; // Ignorer si format invalide
}

try {
    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        header('Location: ' . $baseUrl . '/pages/inscription-auteur.php?error=email_exists&' . http_build_query([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'affiliation' => $affiliation,
            'orcid' => $orcid
        ]));
        exit;
    }

    // Hasher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insérer l'utilisateur dans la base de données
    $stmt = $pdo->prepare("
        INSERT INTO users (nom, prenom, email, password, role, affiliation, orcid, statut, created_at, updated_at) 
        VALUES (?, ?, ?, ?, 'auteur', ?, ?, 'en_attente', NOW(), NOW())
    ");
    
    $stmt->execute([
        $nom,
        $prenom,
        $email,
        $hashedPassword,
        $affiliation ?: null,
        $orcid ?: null
    ]);

    $userId = $pdo->lastInsertId();

    // Logger l'action
    try {
        $logStmt = $pdo->prepare("INSERT INTO logs (user_id, action, model_type, model_id, description, ip_address, user_agent) VALUES (?, 'create', 'User', ?, 'Inscription auteur', ?, ?)");
        $logStmt->execute([
            $userId,
            $userId,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    } catch (Exception $e) {
        // Ignorer les erreurs de log
        error_log("Erreur lors de l'enregistrement du log: " . $e->getMessage());
    }

    // Rediriger vers la page de connexion avec message de succès
    header('Location: ' . $baseUrl . '/pages/login.php?success=registered&email=' . urlencode($email));
    exit;

} catch (PDOException $e) {
    error_log("Erreur lors de l'inscription: " . $e->getMessage());
    $baseUrl = dirname(dirname($_SERVER['PHP_SELF']));
    header('Location: ' . $baseUrl . '/pages/inscription-auteur.php?error=database&' . http_build_query([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'affiliation' => $affiliation,
        'orcid' => $orcid
    ]));
    exit;
}

